<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\AuditLog;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department;


class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $table = 'users';


    protected $fillable = [
        'name',
        'username',
        'email',
        'no_wa',
        'password',
        'password_plain',
        'role',
        'nama_unit',
        'kode_unit',
        'department_id',
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

    public function department()
    {

        return $this->belongsTo(
            Department::class
        );

    }

    /**
     * Get avatar initials / unit code based on kode_unit or unit/user name.
     */
    public function getInitialsAttribute(): string
    {
        if (!empty($this->kode_unit)) {
            return strtoupper($this->kode_unit);
        }

        $sourceName = $this->nama_unit ?: $this->name ?: $this->username ?: '';
        $words = preg_split('/\s+/', trim($sourceName));

        if (count($words) >= 2) {
            $initials = '';
            foreach ($words as $w) {
                if (!empty($w)) {
                    $initials .= mb_substr($w, 0, 1);
                }
            }
            return strtoupper(substr($initials, 0, 5));
        }

        return strtoupper(substr($sourceName, 0, 2));
    }

    /**
     * Dynamic font size style for avatar based on initials length.
     */
    public function getAvatarStyleAttribute(): string
    {
        $len = mb_strlen($this->initials);
        if ($len >= 6) {
            return 'font-size: 8px; letter-spacing: -0.5px; padding: 0 2px;';
        }
        if ($len == 5) {
            return 'font-size: 9px; letter-spacing: -0.5px; padding: 0 2px;';
        }
        if ($len == 4) {
            return 'font-size: 10.5px; letter-spacing: -0.3px; padding: 0 2px;';
        }
        if ($len == 3) {
            return 'font-size: 12px; letter-spacing: 0; padding: 0 2px;';
        }
        return 'font-size: 14px; letter-spacing: 0; padding: 0 2px;';
    }
}