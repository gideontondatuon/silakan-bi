<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HariLiburController extends Controller
{
    public function index(Request $request): View
    {
        $query = HariLibur::query();

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $hariLibur = $query->orderBy('tanggal', 'asc')->paginate(15);
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        $yearExpr = $driver === 'sqlite' ? "strftime('%Y', tanggal)" : "YEAR(tanggal)";
        $tahunList = HariLibur::selectRaw("{$yearExpr} as tahun")
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('admin.hari-libur.index', compact('hariLibur', 'tahunList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'tanggal' => 'required|date|unique:hari_libur,tanggal',
            'keterangan' => 'required|string|max:255',
            'kategori' => 'required|in:libur_nasional,cuti_bersama,internal',
        ]);

        HariLibur::create([
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'kategori' => $request->kategori,
            'is_nasional' => $request->kategori !== 'internal',
        ]);

        return back()->with('success', 'Hari libur / cuti bersama berhasil ditambahkan.');
    }

    public function destroy(HariLibur $hariLibur): RedirectResponse
    {
        $hariLibur->delete();

        return back()->with('success', 'Data hari libur berhasil dihapus.');
    }

    public function syncApi(Request $request): RedirectResponse
    {
        $year = $request->input('tahun', date('Y'));
        $count = 0;

        try {
            // Try fetching from public API
            $response = Http::timeout(5)->get("https://dayoffapi.vercel.app/api?year={$year}");

            if ($response->successful()) {
                $holidays = $response->json();
                foreach ($holidays as $item) {
                    if (isset($item['is_holiday']) && $item['is_holiday']) {
                        $isCuti = str_contains(strtolower($item['holiday_name'] ?? ''), 'cuti bersama');
                        HariLibur::updateOrCreate(
                            ['tanggal' => $item['holiday_date']],
                            [
                                'keterangan' => $item['holiday_name'],
                                'kategori' => $isCuti ? 'cuti_bersama' : 'libur_nasional',
                                'is_nasional' => true,
                            ]
                        );
                        $count++;
                    }
                }
            } else {
                $count = $this->seedDefaultHolidays($year);
            }
        } catch (\Exception $e) {
            $count = $this->seedDefaultHolidays($year);
        }

        return back()->with('success', "Berhasil sinkronisasi {$count} hari libur nasional & cuti bersama untuk tahun {$year}.");
    }

    private function seedDefaultHolidays(int $year): int
    {
        // Default 2026 Holidays & Cuti Bersama
        $defaults = [
            // Hari Libur Nasional 2026
            '2026-01-01' => ['Tahun Baru 2026 Masehi', 'libur_nasional'],
            '2026-01-16' => ['Isra Mikraj Nabi Muhammad SAW', 'libur_nasional'],
            '2026-02-17' => ['Tahun Baru Imlek 2577 Kongzili', 'libur_nasional'],
            '2026-03-19' => ['Hari Suci Nyepi Tahun Baru Saka 1948', 'libur_nasional'],
            '2026-03-20' => ['Hari Raya Idul Fitri 1447 Hijriah', 'libur_nasional'],
            '2026-03-21' => ['Hari Raya Idul Fitri 1447 Hijriah', 'libur_nasional'],
            '2026-04-03' => ['Wafat Yesus Kristus', 'libur_nasional'],
            '2026-04-05' => ['Hari Paskah', 'libur_nasional'],
            '2026-05-01' => ['Hari Buruh Internasional', 'libur_nasional'],
            '2026-05-14' => ['Kenaikan Yesus Kristus', 'libur_nasional'],
            '2026-05-27' => ['Hari Raya Idul Adha 1447 Hijriah', 'libur_nasional'],
            '2026-05-31' => ['Hari Raya Waisak 2570 BE', 'libur_nasional'],
            '2026-06-01' => ['Hari Lahir Pancasila', 'libur_nasional'],
            '2026-06-16' => ['Tahun Baru Islam 1448 Hijriah', 'libur_nasional'],
            '2026-08-17' => ['Hari Kemerdekaan Republik Indonesia', 'libur_nasional'],
            '2026-08-25' => ['Maulid Nabi Muhammad SAW', 'libur_nasional'],
            '2026-12-25' => ['Hari Raya Natal', 'libur_nasional'],

            // Cuti Bersama 2026
            '2026-02-16' => ['Cuti Bersama Tahun Baru Imlek', 'cuti_bersama'],
            '2026-03-18' => ['Cuti Bersama Hari Suci Nyepi', 'cuti_bersama'],
            '2026-03-23' => ['Cuti Bersama Hari Raya Idul Fitri 1447 H', 'cuti_bersama'],
            '2026-03-24' => ['Cuti Bersama Hari Raya Idul Fitri 1447 H', 'cuti_bersama'],
            '2026-05-15' => ['Cuti Bersama Kenaikan Yesus Kristus', 'cuti_bersama'],
            '2026-05-28' => ['Cuti Bersama Hari Raya Idul Adha 1447 H', 'cuti_bersama'],
            '2026-12-26' => ['Cuti Bersama Hari Raya Natal', 'cuti_bersama'],
        ];

        $count = 0;
        foreach ($defaults as $date => $info) {
            if (substr($date, 0, 4) == $year) {
                HariLibur::updateOrCreate(
                    ['tanggal' => $date],
                    [
                        'keterangan' => $info[0],
                        'kategori' => $info[1],
                        'is_nasional' => true,
                    ]
                );
                $count++;
            }
        }

        return $count;
    }
}
