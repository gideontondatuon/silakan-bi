<?php

namespace App\Services;


use App\Models\AuditLog;


class AuditLogService
{


    public static function create(

        string $aksi,

        string $modul,

        string $keterangan

    )
    {


        AuditLog::create([


            'user_id' =>
                auth()->id(),


            'aksi' =>
                $aksi,


            'modul' =>
                $modul,


            'keterangan' =>
                $keterangan,


        ]);


    }


}