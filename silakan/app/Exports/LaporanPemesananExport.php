<?php

namespace App\Exports;

use App\Models\Pemesanan;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class LaporanPemesananExport implements
    FromCollection,
    WithHeadings,
    WithMapping
{

    public function collection()
    {

        return Pemesanan::with([

            'user',

            'ruangan'

        ])

        ->approved()

        ->latest()

        ->get();

    }



    public function headings(): array
    {

        return [

            'Kode Pemesanan',

            'Kegiatan',

            'Pemohon',

            'Ruangan',

            'Tanggal',

            'Waktu',

            'Durasi',

            'Status'

        ];

    }



    public function map($item): array
    {

        return [

            $item->kode_pemesanan,

            $item->judul_kegiatan,

            $item->user->name,

            $item->ruangan->nama_ruangan,

            $item->tanggal_kegiatan
                ->format('d-m-Y'),

            $item->waktu_mulai
            .' - '.
            $item->waktu_selesai,

            $item->durasi_format,

            $item->display_status

        ];

    }

}