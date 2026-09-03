<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use App\Models\LayoutRuangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus ruangan dummy bawaan lama jika ada
        Ruangan::whereIn('nama_ruangan', [
            'Balai Kerapuan', 'Ruangan Bunaken', 'Ruangan Siladen',
            'Ruangan Manado', 'Ruangan Minahasa', 'Ruangan Lokon'
        ])->delete();

        $ruangans = [
            [
                'id' => 1,
                'nama_ruangan' => 'Tondano',
                'kapasitas' => 300,
                'lokasi' => 'Lantai 3',
                'status' => 'aktif',
            ],
            [
                'id' => 8,
                'nama_ruangan' => 'Klabat',
                'kapasitas' => 70,
                'lokasi' => 'Lantai 4',
                'status' => 'aktif',
            ],
            [
                'id' => 9,
                'nama_ruangan' => 'Bunaken',
                'kapasitas' => 23,
                'lokasi' => 'Lantai 2',
                'status' => 'aktif',
            ],
            [
                'id' => 10,
                'nama_ruangan' => 'Tomohon',
                'kapasitas' => 23,
                'lokasi' => 'Lantai 3',
                'status' => 'aktif',
            ],
            [
                'id' => 11,
                'nama_ruangan' => 'Karangetang Besar',
                'kapasitas' => 53,
                'lokasi' => 'Lantai 4',
                'status' => 'aktif',
            ],
            [
                'id' => 12,
                'nama_ruangan' => 'Karangetang Kecil',
                'kapasitas' => 25,
                'lokasi' => 'Lantai 4',
                'status' => 'aktif',
            ],
            [
                'id' => 13,
                'nama_ruangan' => 'Linow 1',
                'kapasitas' => 7,
                'lokasi' => 'Lantai 1',
                'status' => 'aktif',
            ],
            [
                'id' => 14,
                'nama_ruangan' => 'Linow 2',
                'kapasitas' => 8,
                'lokasi' => 'Lantai 1',
                'status' => 'aktif',
            ],
            [
                'id' => 15,
                'nama_ruangan' => 'Lokon',
                'kapasitas' => 20,
                'lokasi' => 'Lantai 1',
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
