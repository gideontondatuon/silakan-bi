<?php

namespace Database\Seeders;

use App\Models\LayoutRuangan;
use Illuminate\Database\Seeder;

class LayoutRuanganSeeder extends Seeder
{
    public function run(): void
    {
        $layouts = [
            'U-Shape',
            'Classroom',
            'Teater',
            'Interview Set',
            'Transit Room',
            'Round Table',
        ];

        foreach ($layouts as $nama) {
            LayoutRuangan::firstOrCreate([
                'nama_layout' => $nama,
            ]);
        }
    }
}
