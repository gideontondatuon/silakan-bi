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



class ApprovePemesananAction
{


    public function execute(
        Pemesanan $pemesanan,
        User $admin,
        ?string $catatanAdmin = null
    ): Pemesanan {

        return DB::transaction(function () use (
            $pemesanan,
            $admin,
            $catatanAdmin
        ) {
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
            | Pastikan masih pending
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
            | Cek bentrok saat approval
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
                    $query
                        ->where(
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
            | Simpan status lama
            |--------------------------------------------------------------------------
            */

            $statusLama = $pemesanan->status;

            /*
            |--------------------------------------------------------------------------
            | Update approval
            |--------------------------------------------------------------------------
            */

            $pemesanan->update([
                'status' => PemesananStatus::DISETUJUI->value,
                'approved_by' => $admin->id,
                'approved_at' => now(),
                'catatan_admin' => $catatanAdmin,
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
                    PemesananStatus::DISETUJUI,


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


                'Menyetujui Pemesanan',


                'Approval',


                'Menyetujui pemesanan '
                . $pemesanan->kode_pemesanan


            );





            /*
            |--------------------------------------------------------------------------
            | Notification User
            |--------------------------------------------------------------------------
            */

            try {
                $pemesanan->user->notify(
                    new StatusPemesananNotification(
                        'Pemesanan Disetujui',
                        'Pemesanan ' . $pemesanan->kode_pemesanan . ' telah disetujui admin.',
                        $pemesanan->id
                    )
                );

                // Trigger WhatsApp Notification
                (new \App\Services\WhatsAppService())->notifyUserBookingApproved($pemesanan);
            } catch (\Exception $e) {
                report($e);
            }




            return $pemesanan;



        });


    }


}