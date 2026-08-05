<?php

namespace App\Actions;


use App\Enums\PemesananStatus;

use App\Models\Pemesanan;
use App\Models\PemesananStatusHistory;
use App\Models\User;

use App\Services\AuditLogService;

use App\Notifications\StatusPemesananNotification;

use Illuminate\Support\Facades\DB;

use Exception;



class RejectPemesananAction
{


    public function execute(
        Pemesanan $pemesanan,
        string $alasan,
        User $admin
    ): Pemesanan {


        return DB::transaction(function () use (
            $pemesanan,
            $alasan,
            $admin
        ) {



            $pemesanan = Pemesanan::lockForUpdate()
                ->findOrFail(
                    $pemesanan->id
                );




            /*
            |--------------------------------------------------------------------------
            | Validasi status
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




            if (
                empty(trim($alasan))
            ) {

                throw new Exception(
                    'Alasan penolakan wajib diisi.'
                );

            }





            $statusLama =
                $pemesanan->status;





            /*
            |--------------------------------------------------------------------------
            | Update Reject
            |--------------------------------------------------------------------------
            */


            $pemesanan->update([


                'status' =>
                    PemesananStatus::DITOLAK->value,


                'rejected_by' =>
                    $admin->id,


                'rejected_at' =>
                    now(),


                'alasan_penolakan' =>
                    $alasan,


            ]);






            /*
            |--------------------------------------------------------------------------
            | Status History
            |--------------------------------------------------------------------------
            */


            PemesananStatusHistory::create([


                'pemesanan_id' =>
                    $pemesanan->id,


                'status_lama' =>
                    $statusLama,


                'status_baru' =>
                    PemesananStatus::DITOLAK,


                'changed_by' =>
                    $admin->id,


                'changed_at' =>
                    now(),


            ]);






            /*
            |--------------------------------------------------------------------------
            | Audit Log
            |--------------------------------------------------------------------------
            */


            AuditLogService::create(


                'Menolak Pemesanan',


                'Approval',


                'Menolak pemesanan '
                . $pemesanan->kode_pemesanan
                . '. Alasan: '
                . $alasan


            );






            /*
            |--------------------------------------------------------------------------
            | Notification User
            |--------------------------------------------------------------------------
            */


            try {
                $pemesanan->user->notify(
                    new StatusPemesananNotification(
                        'Pemesanan Ditolak',
                        'Pemesanan ' . $pemesanan->kode_pemesanan . ' ditolak admin. Alasan: ' . $alasan,
                        $pemesanan->id
                    )
                );

                // Trigger WhatsApp Notification
                (new \App\Services\WhatsAppService())->notifyUserBookingRejected($pemesanan, $alasan);
            } catch (\Exception $e) {
                report($e);
            }





            return $pemesanan;


        });


    }


}