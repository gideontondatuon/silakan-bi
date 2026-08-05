<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Fasilitas extends Model
{

    protected $table = 'fasilitas';


    protected $fillable = [
        'nama_fasilitas',
    ];



    public function ruangan()
    {

        return $this->belongsToMany(
            Ruangan::class,
            'ruangan_fasilitas'
        );

    }

}