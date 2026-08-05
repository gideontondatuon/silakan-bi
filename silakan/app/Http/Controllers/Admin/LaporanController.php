<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Pemesanan;
use App\Models\Ruangan;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanPemesananExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class LaporanController extends Controller
{

    public function index(
        Request $request
    ): View {


        $ruangan =
            Ruangan::orderBy(
                'nama_ruangan'
            )
            ->get();



        $query =
            Pemesanan::with([

                'ruangan',

                'user'

            ])
            ->approved();



        if(
            $request->tanggal_mulai
        ){

            $query->whereDate(

                'tanggal_kegiatan',

                '>=',

                $request->tanggal_mulai

            );

        }



        if(
            $request->tanggal_selesai
        ){

            $query->whereDate(

                'tanggal_kegiatan',

                '<=',

                $request->tanggal_selesai

            );

        }



        if(
            $request->ruangan_id
        ){

            $query->where(

                'ruangan_id',

                $request->ruangan_id

            );

        }



        $pemesanan =

            $query

            ->latest()

            ->paginate(10);



        $totalKegiatan =

            $query->count();



        $totalJam =

            $query
            ->get()
            ->sum('durasi');



        $statRuangan =

            Pemesanan::approved()

            ->with('ruangan')

            ->select(
                'ruangan_id',
                DB::raw(
                    'count(*) as total'
                )
            )

            ->groupBy(
                'ruangan_id'
            )

            ->orderByDesc(
                'total'
            )

            ->get();



        return view(

            'admin.laporan.index',

            compact(

                'pemesanan',

                'ruangan',

                'totalKegiatan',

                'totalJam',

                'statRuangan'

            )

        );

    }


    public function exportExcel()
    {

        return Excel::download(

            new LaporanPemesananExport,

            'laporan-penggunaan-ruangan.xlsx'

        );

    }

    public function exportPdf(
        Request $request
    )
    {

        $pemesanan = Pemesanan::with([

            'user',

            'ruangan'

        ])

        ->approved();



        if(
            $request->tanggal_mulai
        ){

            $pemesanan->whereDate(

                'tanggal_kegiatan',

                '>=',

                $request->tanggal_mulai

            );

        }



        if(
            $request->tanggal_selesai
        ){

            $pemesanan->whereDate(

                'tanggal_kegiatan',

                '<=',

                $request->tanggal_selesai

            );

        }



        if(
            $request->ruangan_id
        ){

            $pemesanan->where(

                'ruangan_id',

                $request->ruangan_id

            );

        }



        $data = [

            'pemesanan' =>
                $pemesanan->get(),


            'totalKegiatan' =>
                $pemesanan->count(),


            'totalJam' =>
                $pemesanan
                ->get()
                ->sum('durasi')

        ];



        $pdf = Pdf::loadView(

            'admin.laporan.pdf.laporan',

            $data

        );



        return $pdf->download(

            'laporan-penggunaan-ruangan.pdf'

        );

    }

}