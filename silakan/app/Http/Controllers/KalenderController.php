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

                'id' =>
                    $item->id,


                'title' =>
                    $item->judul_kegiatan
                    . ' - '
                    . $item->ruangan->nama_ruangan,


                'start' =>
                    $item->tanggal_kegiatan->format('Y-m-d')
                    . 'T'
                    . $item->waktu_mulai,


                'end' =>
                    $item->tanggal_kegiatan->format('Y-m-d')
                    . 'T'
                    . $item->waktu_selesai,


                /*
                |--------------------------------------------------------------------------
                | Warna Event Kalender
                |--------------------------------------------------------------------------
                |
                | Hijau = Ruangan sudah disetujui
                |
                */

                'backgroundColor' =>
                    '#16a34a',


                'borderColor' =>
                    '#16a34a',


                'textColor' =>
                    '#ffffff',



                'extendedProps' => [


                    'ruangan' =>
                        $item->ruangan->nama_ruangan,


                    'layout' =>
                        $item->layout->nama_layout,


                    'pic' =>
                        $item->pic_kegiatan,


                    'pemohon' =>
                        $item->user->name,


                    'waktu' =>
                        $item->waktu_mulai
                        .
                        ' - '
                        .
                        $item->waktu_selesai,


                ]

            ];


        });



        return response()
            ->json($events);

    }

}