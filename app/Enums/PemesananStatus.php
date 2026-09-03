<?php

namespace App\Enums;

enum PemesananStatus: string
{
    case PENDING = 'Pending';
    case DISETUJUI = 'Disetujui';
    case DITOLAK = 'Ditolak';
    case CANCEL = 'Cancel';
    case SELESAI = 'Selesai';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::CANCEL => 'Cancel',
            self::SELESAI => 'Selesai',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::DISETUJUI => 'green',
            self::DITOLAK => 'red',
            self::CANCEL => 'gray',
            self::SELESAI => 'blue',
        };
    }
}