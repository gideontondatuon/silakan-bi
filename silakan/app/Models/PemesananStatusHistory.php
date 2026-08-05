<?php

namespace App\Models;

use App\Enums\PemesananStatus;
use Illuminate\Database\Eloquent\Model;


class PemesananStatusHistory extends Model
{

    protected $table = 'pemesanan_status_history';


    protected $fillable = [

        'pemesanan_id',

        'status_lama',

        'status_baru',

        'changed_by',

        'changed_at',

    ];



    protected function casts(): array
    {
        return [

            'status_lama' => PemesananStatus::class,

            'status_baru' => PemesananStatus::class,

            'changed_at' => 'datetime',

        ];
    }



    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */


    public function pemesanan()
    {
        return $this->belongsTo(
            Pemesanan::class
        );
    }



    public function changer()
    {
        return $this->belongsTo(
            User::class,
            'changed_by'
        );
    }

}