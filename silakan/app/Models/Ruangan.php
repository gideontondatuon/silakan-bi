<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{

    protected $table = 'ruangan';


    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'status',
        'lokasi',
    ];


    public function fasilitas()
    {
        return $this->belongsToMany(
            Fasilitas::class,
            'ruangan_fasilitas'
        );
    }

    public function layouts()
    {

        return $this->hasMany(
            LayoutRuangan::class,
            'ruangan_id', 'id'
        );

    }

    public function pemesanan()
    {
        return $this->hasMany(
            Pemesanan::class
        );
    }

}