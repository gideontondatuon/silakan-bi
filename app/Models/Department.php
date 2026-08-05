<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Department extends Model
{

    use HasFactory;


    protected $fillable=[

        'nama_unit',

        'kode_unit',

        'status',

    ];



    public function users()
    {

        return $this->hasMany(
            User::class
        );

    }

}