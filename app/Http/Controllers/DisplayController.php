<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DisplayController extends Controller
{
    /**
     * Menampilkan tampilan layar TV monitor lobby / kiosk mode.
     */
    public function index(): View
    {
        $today = today();

        // 1. Kegiatan Sedang Berlangsung (LIVE)
        $kegiatanLive = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();

        // 2. Agenda Hari Ini
        $kegiatanHariIni = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->approved()
            ->whereDate('tanggal_kegiatan', $today)
            ->orderBy('waktu_mulai')
            ->get();

        // 3. Status Seluruh Ruangan Rapat
        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view('display.index', compact(
            'kegiatanLive',
            'kegiatanHariIni',
            'ruangans'
        ));
    }

    /**
     * API JSON endpoint untuk update data otomatis di layar TV tanpa reload.
     */
    public function apiData(): JsonResponse
    {
        $today = today();

        $kegiatanLive = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_pemesanan,
                    'ruangan' => $item->ruangan?->nama_ruangan ?? '-',
                    'judul' => $item->judul_kegiatan,
                    'unit' => $item->user?->nama_unit ?? $item->user?->name ?? 'Unit Internal',
                    'pic' => $item->pic_kegiatan,
                    'waktu' => $item->waktu_mulai . ' - ' . $item->waktu_selesai . ' WITA',
                    'end_time' => $item->tanggal_kegiatan->format('Y-m-d') . 'T' . $item->waktu_selesai,
                ];
            });

        $kegiatanHariIni = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->approved()
            ->whereDate('tanggal_kegiatan', $today)
            ->orderBy('waktu_mulai')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'kode' => $item->kode_pemesanan,
                    'ruangan' => $item->ruangan?->nama_ruangan ?? '-',
                    'judul' => $item->judul_kegiatan,
                    'unit' => $item->user?->nama_unit ?? $item->user?->name ?? 'Unit Internal',
                    'pic' => $item->pic_kegiatan,
                    'waktu' => $item->waktu_mulai . ' - ' . $item->waktu_selesai . ' WITA',
                    'status' => is_object($item->status) ? $item->status->value : $item->status,
                ];
            });

        return response()->json([
            'status' => 'success',
            'timestamp' => now()->translatedFormat('l, d F Y H:i:s'),
            'live_count' => $kegiatanLive->count(),
            'live' => $kegiatanLive,
            'today' => $kegiatanHariIni,
        ]);
    }
}
