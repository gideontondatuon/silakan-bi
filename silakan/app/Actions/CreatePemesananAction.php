<?php

namespace App\Actions;

use App\Enums\PemesananStatus;
use App\Enums\Role;

use App\Models\LayoutRuangan;
use App\Models\Pemesanan;
use App\Models\User;

use App\Notifications\PemesananNotification;

use Exception;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\AuditLogService;


class CreatePemesananAction
{

    public function execute(array $data): Pemesanan
    {

        $pemesanan = DB::transaction(function () use ($data) {


            $layout = LayoutRuangan::lockForUpdate()
                ->findOrFail(
                    $data['layout_ruangan_id']
                );


            if (
                $data['jumlah_tamu']
                >
                $layout->kapasitas_layout
            ) {

                throw new Exception(
                    'Jumlah tamu melebihi kapasitas layout ruangan.'
                );

            }


            $bentrok = Pemesanan::where(
                    'ruangan_id',
                    $data['ruangan_id']
                )
                ->where(
                    'tanggal_kegiatan',
                    $data['tanggal_kegiatan']
                )
                ->where(
                    'status',
                    PemesananStatus::DISETUJUI
                )
                ->where(function ($query) use ($data) {


                    $query
                        ->where(
                            'waktu_mulai',
                            '<',
                            $data['waktu_selesai']
                        )
                        ->where(
                            'waktu_selesai',
                            '>',
                            $data['waktu_mulai']
                        );


                })
                ->lockForUpdate()
                ->exists();


            if ($bentrok) {

                throw new Exception(
                    'Ruangan sudah digunakan pada waktu tersebut.'
                );

            }


            return Pemesanan::create([

                'kode_pemesanan' =>
                    'SIL-'
                    . now()->format('Ymd')
                    . '-'
                    . strtoupper(
                        Str::random(5)
                    ),


                'user_id' =>
                    auth()->id(),


                'ruangan_id' =>
                    $data['ruangan_id'],


                'layout_ruangan_id' =>
                    $data['layout_ruangan_id'],


                'tanggal_kegiatan' =>
                    $data['tanggal_kegiatan'],


                'waktu_mulai' =>
                    $data['waktu_mulai'],


                'waktu_selesai' =>
                    $data['waktu_selesai'],


                'judul_kegiatan' =>
                    $data['judul_kegiatan'],


                'pic_kegiatan' =>
                    $data['pic_kegiatan'],


                'jenis_pic' =>
                    $data['jenis_pic'],


                'jumlah_tamu' =>
                    $data['jumlah_tamu'],


                'keterangan_layout' =>
                    $data['keterangan_layout'] ?? null,


                'catatan_user' =>
                    $data['catatan_user'] ?? null,


                'status' =>
                    PemesananStatus::PENDING->value,

            ]);

        });

        AuditLogService::create(
            'Membuat Pemesanan',

            'Pemesanan',

            'Membuat pengajuan pemesanan '
            . $pemesanan->kode_pemesanan

        );



        /*
        |--------------------------------------------------------------------------
        | Notification Admin
        |--------------------------------------------------------------------------
        */

        try {


            User::where(
                'role',
                Role::ADMIN->value
            )
            ->get()
            ->each(function ($admin) use ($pemesanan) {


                $admin->notify(

                new PemesananNotification(

                    'Pengajuan Pemesanan Baru',

                    'Pemesanan '
                    . $pemesanan->kode_pemesanan
                    . ' membutuhkan approval admin.',


                    $pemesanan->id

                )

                );


            });


        } catch (\Exception $e) {


            report($e);


        }



        return $pemesanan;

    }

}