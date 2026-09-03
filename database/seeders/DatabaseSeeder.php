<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            LayoutRuanganSeeder::class,
            RuanganSeeder::class,
        ]);

        $users = [
            [
                'name' => 'Unit Kehumasan',
                'username' => 'humas',
                'email' => 'humas@bi.go.id',
                'password' => Hash::make('kpwbisulut'),
                'role' => 'user',
                'nama_unit' => 'Unit Kehumasan',
                'kode_unit' => 'HUMAS',
            ],
            [
                'name' => 'Unit Keuangan',
                'username' => 'keuangan',
                'email' => 'keuangan@bi.go.id',
                'password' => Hash::make('kpwbisulut'),
                'role' => 'user',
                'nama_unit' => 'Unit Keuangan',
                'kode_unit' => 'KEU',
            ],
            [
                'name' => 'Unit SDM & Logistik',
                'username' => 'sdm',
                'email' => 'sdm@bi.go.id',
                'password' => Hash::make('kpwbisulut'),
                'role' => 'user',
                'nama_unit' => 'Unit SDM',
                'kode_unit' => 'SDM',
            ],
            [
                'name' => 'Pengguna Umum',
                'username' => 'user',
                'email' => 'user@bi.go.id',
                'password' => Hash::make('kpwbisulut'),
                'role' => 'user',
                'nama_unit' => 'Unit Operasional',
                'kode_unit' => 'OPS',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['username' => $u['username']], $u);
        }
    }
}
