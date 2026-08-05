<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLibur extends Model
{
    use HasFactory;

    protected $table = 'hari_libur';

    protected $fillable = [
        'tanggal',
        'keterangan',
        'kategori', // libur_nasional, cuti_bersama, internal
        'is_nasional',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'is_nasional' => 'boolean',
        ];
    }

    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'cuti_bersama' => 'Cuti Bersama',
            'internal' => 'Libur Internal BI',
            default => 'Hari Libur Nasional',
        };
    }

    public function getKategoriBadgeClassAttribute(): string
    {
        return match ($this->kategori) {
            'cuti_bersama' => 'badge-warning',
            'internal' => 'badge-info',
            default => 'badge-danger',
        };
    }

    public static function isHoliday($date): bool
    {
        return static::where('tanggal', $date)->exists();
    }

    public static function getHolidayByDate($date)
    {
        return static::where('tanggal', $date)->first();
    }
}
