<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use App\Models\LayoutRuangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruangans = [
            [
                'nama_ruangan' => 'Balai Kerapuan',
                'kapasitas' => 200,
                'lokasi' => 'Lantai 1, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
            [
                'nama_ruangan' => 'Ruangan Bunaken',
                'kapasitas' => 50,
                'lokasi' => 'Lantai 2, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
            [
                'nama_ruangan' => 'Ruangan Siladen',
                'kapasitas' => 30,
                'lokasi' => 'Lantai 2, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
            [
                'nama_ruangan' => 'Ruangan Manado',
                'kapasitas' => 20,
                'lokasi' => 'Lantai 3, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
            [
                'nama_ruangan' => 'Ruangan Minahasa',
                'kapasitas' => 15,
                'lokasi' => 'Lantai 3, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
            [
                'nama_ruangan' => 'Ruangan Lokon',
                'kapasitas' => 10,
                'lokasi' => 'Lantai 3, Gedung Utama KPwBI Sulut',
                'status' => 'aktif',
            ],
        ];

        $allLayoutIds = LayoutRuangan::pluck('id')->toArray();

        foreach ($ruangans as $data) {
            $ruangan = Ruangan::updateOrCreate(
                ['nama_ruangan' => $data['nama_ruangan']],
                $data
            );

            // Connect all layouts to each room with standard capacity
            if (!empty($allLayoutIds)) {
                $pivotData = [];
                foreach ($allLayoutIds as $layoutId) {
                    $pivotData[$layoutId] = ['kapasitas_layout' => $ruangan->kapasitas];
                }
                $ruangan->layouts()->sync($pivotData);
            }
        }
    }
}
