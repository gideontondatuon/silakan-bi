<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AuditLog;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'users';


    protected $fillable = [

        'name',

        'username',

        'email',

        'password',

        'role',

        'nama_unit',

        'kode_unit',

    ];


    protected $hidden = [

        'password',

        'remember_token',

    ];


    protected function casts(): array
    {
        return [

            'password' =>
                'hashed',

            'role' =>
                Role::class,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Relationship
    |--------------------------------------------------------------------------
    */


    public function pemesanan()
    {
        return $this->hasMany(
            Pemesanan::class
        );
    }


    public function approvedPemesanan()
    {
        return $this->hasMany(
            Pemesanan::class,
            'approved_by'
        );
    }


    public function auditLogs()
    {
        return $this->hasMany(
            AuditLog::class
        );
    }
}