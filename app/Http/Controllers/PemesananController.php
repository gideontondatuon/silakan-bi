<?php

namespace App\Http\Controllers;

use App\Actions\CancelPemesananAction;
use App\Actions\CreatePemesananAction;
use App\Http\Requests\StorePemesananRequest;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PemesananController extends Controller
{
    public function create(): View
    {
        $ruangan = Ruangan::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get();

        return view('pemesanan.create', compact('ruangan'));
    }

    public function store(
        StorePemesananRequest $request,
        CreatePemesananAction $action
    ): RedirectResponse {
        try {
            $data = $request->validated();
            if ($request->hasFile('file_disposisi')) {
                $data['file_disposisi'] = $request->file('file_disposisi');
            }

            $action->execute(
                $data,
                auth()->user()
            );

            return redirect()
                ->route('pemesanan.index')
                ->with('success', 'Pemesanan berhasil dibuat dan menunggu persetujuan admin.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function index(): View
    {
        Pemesanan::markFinishedAgendas();

        $pemesanan = Pemesanan::with(['ruangan', 'layout'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('pemesanan.index', compact('pemesanan'));
    }

    public function show(Pemesanan $pemesanan): View
    {
        abort_if($pemesanan->user_id !== auth()->id(), 403);

        $pemesanan->load(['ruangan', 'layout']);

        return view('pemesanan.show', compact('pemesanan'));
    }

    public function cancel(
        Pemesanan $pemesanan,
        CancelPemesananAction $action
    ): RedirectResponse {
        try {
            $action->execute($pemesanan, auth()->user());

            return redirect()
                ->route('pemesanan.index')
                ->with('success', 'Pemesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function selesaiAwal(Pemesanan $pemesanan): RedirectResponse
    {
        abort_if($pemesanan->user_id !== auth()->id(), 403);

        $statusVal = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
        if ($statusVal !== \App\Enums\PemesananStatus::DISETUJUI->value) {
            return back()->with('error', 'Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.');
        }

        if (!$pemesanan->tanggal_kegiatan || !$pemesanan->tanggal_kegiatan->isToday()) {
            return back()->with('error', 'Hanya kegiatan yang berlangsung hari ini yang dapat diselesaikan lebih awal.');
        }

        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');

        if ($currentTime >= $pemesanan->waktu_selesai) {
            return back()->with('error', 'Kegiatan ini sudah selesai sesuai jadwal dan tidak dapat diselesaikan lebih awal.');
        }

        if ($currentTime < $pemesanan->waktu_mulai) {
            return back()->with('error', 'Kegiatan ini belum dimulai sesuai jadwal.');
        }

        $pemesanan->update([
            'waktu_selesai' => $currentTime,
            'status' => \App\Enums\PemesananStatus::SELESAI,
        ]);

        \App\Services\AuditLogService::create(
            'Menyelesaikan Kegiatan Lebih Awal',
            'Pemesanan',
            "Pengguna menyelesaikan kegiatan {$pemesanan->kode_pemesanan} lebih awal pada pukul {$currentTime} WITA."
        );

        return back()->with('success', "Kegiatan pada ruangan {$pemesanan->ruangan->nama_ruangan} berhasil diselesaikan lebih awal pada pukul {$currentTime} WITA. Ruangan kini berstatus kosong dan tersedia.");
    }

    public function checkConflict(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
        ]);

        $nowMakassar = \Carbon\Carbon::now('Asia/Makassar');
        $today = $nowMakassar->toDateString();
        $currentTime = $nowMakassar->format('H:i');

        if ($request->tanggal_kegiatan < $today) {
            return response()->json([
                'conflict' => true,
                'is_past' => true,
                'message' => 'Tanggal kegiatan sudah lewat! Tidak dapat memesan untuk tanggal yang telah berlalu.',
            ]);
        }

        if ($request->tanggal_kegiatan === $today && $request->waktu_mulai < $currentTime) {
            return response()->json([
                'conflict' => true,
                'is_past' => true,
                'message' => "Waktu mulai ({$request->waktu_mulai} WITA) sudah terlewat! Waktu saat ini adalah {$currentTime} WITA.",
            ]);
        }

        $date = \Carbon\Carbon::parse($request->tanggal_kegiatan);
        $isWeekend = $date->isWeekend();
        $dayName = $date->isoFormat('dddd');

        $holiday = \App\Models\HariLibur::where('tanggal', $request->tanggal_kegiatan)->first();

        $conflicting = Pemesanan::where('ruangan_id', $request->ruangan_id)
            ->whereDate('tanggal_kegiatan', $request->tanggal_kegiatan)
            ->whereIn('status', [\App\Enums\PemesananStatus::DISETUJUI->value, \App\Enums\PemesananStatus::SELESAI->value])
            ->where(function ($query) use ($request) {
                $query->where('waktu_mulai', '<', $request->waktu_selesai)
                    ->where('waktu_selesai', '>', $request->waktu_mulai);
            })
            ->first();

        if ($conflicting) {
            return response()->json([
                'conflict' => true,
                'message' => 'Jadwal bentrok! Ruangan sudah di-booking untuk: "' . $conflicting->judul_kegiatan . '" (' . $conflicting->waktu_mulai . ' - ' . $conflicting->waktu_selesai . ')',
                'is_weekend' => $isWeekend,
                'is_holiday' => (bool) $holiday,
                'holiday_name' => $holiday?->keterangan,
            ]);
        }

        $extraNote = '';
        if ($holiday) {
            $katLabel = $holiday->kategori_label;
            $extraNote = " (Catatan: Tanggal ini adalah {$katLabel} — {$holiday->keterangan})";
        } elseif ($isWeekend) {
            $extraNote = " (Catatan: Tanggal ini jatuh pada hari {$dayName} / Akhir Pekan)";
        }

        return response()->json([
            'conflict' => false,
            'is_weekend' => $isWeekend,
            'is_holiday' => (bool) $holiday,
            'holiday_name' => $holiday?->keterangan,
            'day_name' => $dayName,
            'message' => 'Jadwal ruangan tersedia!' . $extraNote
        ]);
    }
}