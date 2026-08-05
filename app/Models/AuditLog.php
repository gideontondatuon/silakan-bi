<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AuditLog extends Model
{

    use HasFactory;


    protected $table = 'audit_log';



    protected $fillable = [

        'user_id',

        'aksi',

        'modul',

        'keterangan',

    ];



    public function user()
    {

        return $this->belongsTo(
            User::class
        );

    }


}