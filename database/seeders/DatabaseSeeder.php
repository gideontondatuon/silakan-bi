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

        // Hapus akun demo lama jika ada
        User::whereIn('username', ['user', 'sdm', 'keuangan', 'humas'])->delete();

        $users = [
            [
                'name' => 'Fungsi Implementasi Kebijakan Sistem Pembayaran',
                'username' => 'fiksp_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Fungsi Implementasi Kebijakan Sistem Pembayaran',
                'kode_unit' => 'FIKSP',
            ],
            [
                'name' => 'Pengelolaan Uang Rupiah',
                'username' => 'pur_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Pengelolaan Uang Rupiah',
                'kode_unit' => 'PUR',
            ],
            [
                'name' => 'Tim Manajemen Internal',
                'username' => 'tmi_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Tim Manajemen Internal',
                'kode_unit' => 'TMI',
            ],
            [
                'name' => 'Unit Kehumasan',
                'username' => 'uk_kpwbisulut',
                'email' => null,
                'password' => Hash::make('humassulut'),
                'password_plain' => 'humassulut',
                'role' => 'user',
                'nama_unit' => 'Unit Kehumasan',
                'kode_unit' => 'UK',
            ],
            [
                'name' => 'Fungsi Data dan Statistik Ekonomi & Keuangan',
                'username' => 'fdsek_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Fungsi Data dan Statistik Ekonomi & Keuangan',
                'kode_unit' => 'FDSEK',
            ],
            [
                'name' => 'Fasilitator Pengendalian Inflasi dan Kebijakan Publik',
                'username' => 'fpkp_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Fasilitator Pengendalian Inflasi dan Kebijakan Publik',
                'kode_unit' => 'FPKP',
            ],
            [
                'name' => 'Fungsi Pelaksana Pengembangan UMKM',
                'username' => 'fppu_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Fungsi Pelaksana Pengembangan UMKM',
                'kode_unit' => 'FPPU',
            ],
            [
                'name' => 'Persatuan Istri Pegawai Bank Indonesia',
                'username' => 'pipebi_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Persatuan Istri Pegawai Bank Indonesia',
                'kode_unit' => 'PIPEBI',
            ],
            [
                'name' => 'Persatuan Pegawai Bank Indonesia',
                'username' => 'ppbi_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Persatuan Pegawai Bank Indonesia',
                'kode_unit' => 'PPBI',
            ],
            [
                'name' => 'Change Agent',
                'username' => 'ca_kpwbisulut',
                'email' => null,
                'password' => Hash::make('kpwbisulut'),
                'password_plain' => 'kpwbisulut',
                'role' => 'user',
                'nama_unit' => 'Change Agent',
                'kode_unit' => 'CA',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['username' => $u['username']], $u);
        }

        // Pastikan tidak ada akun yang password_plain bernilai NULL
        User::where('role', 'user')->whereNull('password_plain')->update(['password_plain' => 'kpwbisulut']);
        User::where('role', 'admin')->whereNull('password_plain')->update(['password_plain' => 'password']);
    }
}
