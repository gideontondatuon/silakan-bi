<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Enums\PemesananStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


use App\Models\Ruangan;
use Illuminate\Http\Request;

class KalenderController extends Controller
{
    public function index(): View
    {
        $ruangans = Ruangan::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get();

        return view('kalender.index', compact('ruangans'));
    }

    public function events(Request $request): JsonResponse
    {
        $query = Pemesanan::with([
            'ruangan',
            'layout',
            'user'
        ])
        ->where('status', PemesananStatus::DISETUJUI->value);

        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        $pemesanan = $query->get();

        $events = $pemesanan->map(function ($item) {
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
                    'no_wa_pic' => $item->no_wa_pic,
                    'jenis_pic' => $item->jenis_pic,
                    'tamu' => $item->jumlah_tamu,
                    'pemohon' => $item->user?->name ?? 'User',
                    'unit' => $item->user?->nama_unit ?? '-',
                    'waktu' => $item->waktu_mulai . ' - ' . $item->waktu_selesai . ' WITA',
                    'tanggal' => $item->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY'),
                ]
            ];
        })->toArray();

        // Hari Libur
        $hariLibur = \App\Models\HariLibur::all();
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
                ]
            ];
        }

        return response()->json($events);
    }

}