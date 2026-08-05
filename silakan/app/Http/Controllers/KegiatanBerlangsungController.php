<?php

namespace App\Http\Controllers;


use App\Models\Pemesanan;

use Illuminate\View\View;



class KegiatanBerlangsungController extends Controller
{


    public function index(): View
    {


        $kegiatan =
            Pemesanan::with([

                'ruangan',

                'layout',

                'user'

            ])

            ->isLive()

            ->get();



        return view(

            'kegiatan-berlangsung.index',

            compact(
                'kegiatan'
            )

        );


    }


}