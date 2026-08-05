<?php

namespace App\Actions;

use App\Enums\PemesananStatus;
use App\Models\Pemesanan;

use Illuminate\Support\Facades\DB;

use Exception;


class ApprovePemesananAction
{

    public function execute(
        Pemesanan $pemesanan
    ): Pemesanan {


        return DB::transaction(function () use ($pemesanan) {


            /*
            |--------------------------------------------------------------------------
            | Lock data pemesanan
            |--------------------------------------------------------------------------
            */

            $pemesanan = Pemesanan::lockForUpdate()
                ->findOrFail(
                    $pemesanan->id
                );



            /*
            |--------------------------------------------------------------------------
            | Pastikan masih Pending
            |--------------------------------------------------------------------------
            */

            if (
                $pemesanan->status->value
                !==
                PemesananStatus::PENDING->value
            ) {

                throw new Exception(
                    'Pemesanan sudah diproses sebelumnya.'
                );

            }



            /*
            |--------------------------------------------------------------------------
            | BR-23
            |
            | Cek bentrok ulang saat approval
            |
            |--------------------------------------------------------------------------
            */


            $bentrok = Pemesanan::where(
                    'ruangan_id',
                    $pemesanan->ruangan_id
                )

                ->where(
                    'tanggal_kegiatan',
                    $pemesanan->tanggal_kegiatan
                )

                ->where(
                    'id',
                    '!=',
                    $pemesanan->id
                )

                ->where(
                    'status',
                    PemesananStatus::DISETUJUI->value
                )

                ->where(function ($query) use ($pemesanan) {


                    $query->where(
                        'waktu_mulai',
                        '<',
                        $pemesanan->waktu_selesai
                    )

                    ->where(
                        'waktu_selesai',
                        '>',
                        $pemesanan->waktu_mulai
                    );


                })

                ->lockForUpdate()

                ->exists();




            if ($bentrok) {


                throw new Exception(
                    'Tidak dapat menyetujui. Jadwal ruangan sudah digunakan.'
                );


            }





            /*
            |--------------------------------------------------------------------------
            | Update approval
            |--------------------------------------------------------------------------
            */


            $pemesanan->update([

                'status'
                    =>
                    PemesananStatus::DISETUJUI->value,


                'approved_by'
                    =>
                    auth()->id(),


                'approved_at'
                    =>
                    now(),

            ]);



            return $pemesanan;


        });


    }

}