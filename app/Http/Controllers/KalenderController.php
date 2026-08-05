<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Enums\PemesananStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


class KalenderController extends Controller
{

    public function index(): View
    {
        return view(
            'kalender.index'
        );
    }


    public function events(): JsonResponse
    {

        $pemesanan = Pemesanan::with([
                'ruangan',
                'layout',
                'user'
            ])
            ->where(
                'status',
                PemesananStatus::DISETUJUI
            )
            ->get();



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
                    'ruangan' => $item->ruangan?->nama_ruangan ?? 'Ruangan',
                    'layout' => $item->layout?->nama_layout ?? '-',
                    'pic' => $item->pic_kegiatan,
                    'pemohon' => $item->user?->name ?? 'User',
                    'waktu' => $item->waktu_mulai . ' - ' . $item->waktu_selesai,
                ]
            ];
        })->toArray();

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
                ]
            ];
        }

        return response()->json($events);
    }

}