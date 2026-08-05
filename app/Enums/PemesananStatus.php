<?php

namespace App\Enums;

enum PemesananStatus: string
{
    case PENDING = 'Pending';

    case DISETUJUI = 'Disetujui';

    case DITOLAK = 'Ditolak';

    case CANCEL = 'Cancel';


    public function label(): string
    {
        return match ($this) {

            self::PENDING => 'Pending',

            self::DISETUJUI => 'Disetujui',

            self::DITOLAK => 'Ditolak',

            self::CANCEL => 'Cancel',

        };
    }


    public function color(): string
    {
        return match ($this) {

            self::PENDING => 'yellow',

            self::DISETUJUI => 'green',

            self::DITOLAK => 'red',

            self::CANCEL => 'gray',

        };
    }
}