<?php

namespace App\Actions;

use App\Enums\PemesananStatus;
use App\Models\Pemesanan;

use Illuminate\Support\Facades\DB;

use Exception;


class RejectPemesananAction
{

    public function execute(
        Pemesanan $pemesanan,
        string $alasan
    ): Pemesanan {


        return DB::transaction(function () use (
            $pemesanan,
            $alasan
        ) {


            $pemesanan = Pemesanan::lockForUpdate()
                ->findOrFail(
                    $pemesanan->id
                );



            if (
                $pemesanan->status->value
                !==
                PemesananStatus::PENDING->value
            ) {

                throw new Exception(
                    'Pemesanan sudah diproses sebelumnya.'
                );

            }



            if (
                empty(trim($alasan))
            ) {

                throw new Exception(
                    'Alasan penolakan wajib diisi.'
                );

            }



            $pemesanan->update([

                'status'
                    =>
                    PemesananStatus::DITOLAK->value,


                'rejected_by'
                    =>
                    auth()->id(),


                'rejected_at'
                    =>
                    now(),


                'alasan_penolakan'
                    =>
                    $alasan,

            ]);



            return $pemesanan;


        });


    }

}