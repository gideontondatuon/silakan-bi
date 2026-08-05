<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class LayoutRuangan extends Model
{

    protected $table = 'layout_ruangan';


    protected $fillable = [

        'ruangan_id',

        'nama_layout',

        'kapasitas_layout',

    ];



    protected $casts = [

        'kapasitas_layout' => 'integer',

    ];



    public function ruangan()
    {

        return $this->belongsTo(
            Ruangan::class,
            'ruangan_id', 'id'
        );

    }

    public function pemesanan()
    {
        return $this->hasMany(
            Pemesanan::class,
            'layout_ruangan_id'
        );
    }

}