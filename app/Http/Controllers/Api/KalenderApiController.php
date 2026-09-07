<?php

namespace App\Http\Controllers\Api;

use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\HariLibur;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KalenderApiController extends Controller
{
    /**
     * Get active rooms and calendar events.
     */
    public function index(): JsonResponse
    {
        $ruangans = Ruangan::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get(['id', 'nama_ruangan', 'kapasitas', 'lokasi']);

        $totalRuangan = Ruangan::count();
        $jadwalAktif = Pemesanan::approved()->count();
        $akanDatang = Pemesanan::approved()->upcoming()->count();

        $upcomingSchedule = Pemesanan::with(['ruangan', 'layout', 'user'])
            ->approved()
            ->upcoming()
            ->orderBy('tanggal_kegiatan', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->take(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'kode_pemesanan' => $item->kode_pemesanan,
                    'judul_kegiatan' => $item->judul_kegiatan,
                    'tanggal_kegiatan' => $item->tanggal_kegiatan ? $item->tanggal_kegiatan->translatedFormat('d M Y') : '-',
                    'waktu_mulai' => substr($item->waktu_mulai, 0, 5),
                    'waktu_selesai' => substr($item->waktu_selesai, 0, 5),
                    'pic_kegiatan' => $item->pic_kegiatan,
                    'nama_ruangan' => $item->ruangan?->nama_ruangan ?? '-',
                    'nama_unit' => $item->user?->nama_unit ?? 'Unit',
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => [
                'ruangan' => $ruangans,
                'stats' => [
                    'total_ruangan' => $totalRuangan,
                    'jadwal_aktif' => $jadwalAktif,
                    'akan_datang' => $akanDatang,
                ],
                'upcoming' => $upcomingSchedule,
            ],
        ]);
    }

    /**
     * Get events for calendar view (bookings + holidays).
     */
    public function events(Request $request): JsonResponse
    {
        $query = Pemesanan::with([
            'ruangan',
            'layout',
            'user',
        ])
        ->where('status', PemesananStatus::DISETUJUI->value);

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $pemesanan = $query->get();

        $events = $pemesanan->map(function ($item) {
            $wMulai = substr($item->waktu_mulai, 0, 5);
            $wSelesai = substr($item->waktu_selesai, 0, 5);

            return [
                'id' => 'booking_' . $item->id,
                'title' => $item->judul_kegiatan . ' (' . ($item->ruangan?->nama_ruangan ?? 'Ruangan') . ')',
                'start' => $item->tanggal_kegiatan->format('Y-m-d') . 'T' . $item->waktu_mulai,
                'end' => $item->tanggal_kegiatan->format('Y-m-d') . 'T' . $item->waktu_selesai,
                'backgroundColor' => '#005baa',
                'borderColor' => '#003b73',
                'textColor' => '#ffffff',
                'type' => 'booking',
                'extendedProps' => [
                    'booking_id' => $item->id,
                    'kode_pemesanan' => $item->kode_pemesanan,
                    'judul' => $item->judul_kegiatan,
                    'ruangan' => $item->ruangan?->nama_ruangan ?? 'Ruangan',
                    'lokasi' => $item->ruangan?->lokasi ?? '-',
                    'layout' => $item->layout?->nama_layout ?? '-',
                    'pic' => $item->pic_kegiatan,
                    'no_wa_pic' => $item->no_wa_pic ?? '-',
                    'jenis_pic' => $item->jenis_pic,
                    'tamu' => $item->jumlah_tamu,
                    'pemohon' => $item->user?->name ?? 'User',
                    'unit' => $item->user?->nama_unit ?? '-',
                    'waktu' => $wMulai . ' - ' . $wSelesai . ' WITA',
                    'tanggal' => $item->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY'),
                ],
            ];
        })->toArray();

        // Hari Libur
        $hariLibur = HariLibur::all();
        foreach ($hariLibur as $libur) {
            $isCuti = $libur->kategori === 'cuti_bersama';
            $isInternal = $libur->kategori === 'internal';

            $bgColor = $isCuti ? '#f59e0b' : ($isInternal ? '#0ea5e9' : '#ef4444');
            $borderColor = $isCuti ? '#d97706' : ($isInternal ? '#0284c7' : '#dc2626');
            $prefix = $isCuti ? '🏖️ Cuti Bersama: ' : ($isInternal ? '🏛️ Libur Internal: ' : '🚩 Libur: ');

            $events[] = [
                'id' => 'holiday_' . $libur->id,
                'title' => $prefix . $libur->keterangan,
                'start' => $libur->tanggal->format('Y-m-d'),
                'allDay' => true,
                'backgroundColor' => $bgColor,
                'borderColor' => $borderColor,
                'textColor' => '#ffffff',
                'display' => 'block',
                'type' => 'holiday',
                'extendedProps' => [
                    'keterangan' => $libur->keterangan,
                    'kategori' => $libur->kategori,
                    'kategori_label' => $libur->kategori_label,
                    'is_nasional' => $libur->is_nasional,
                    'tanggal' => $libur->tanggal->isoFormat('dddd, D MMMM YYYY'),
                ],
            ];
        }

        return response()->json($events);
    }
}
