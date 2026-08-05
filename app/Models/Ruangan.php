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




    public function layouts()
    {
        return $this->belongsToMany(
            LayoutRuangan::class,
            'ruangan_layout',
            'ruangan_id',
            'layout_ruangan_id'
        )->withPivot('kapasitas_layout')->withTimestamps();
    }

    public function pemesanan()
    {
        return $this->hasMany(
            Pemesanan::class
        );
    }

}