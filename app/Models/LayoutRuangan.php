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
        'ruangan_id',
    ];



    protected $casts = [

        'kapasitas_layout' => 'integer',

    ];



    public function ruangans()
    {
        return $this->belongsToMany(
            Ruangan::class,
            'ruangan_layout',
            'layout_ruangan_id',
            'ruangan_id'
        )->withPivot('kapasitas_layout')->withTimestamps();
    }

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