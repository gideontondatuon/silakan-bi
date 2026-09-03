<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel', 'Selesai') NOT NULL DEFAULT 'Pending'");
            if (Schema::hasTable('pemesanan_status_history')) {
                DB::statement("ALTER TABLE pemesanan_status_history MODIFY COLUMN status_lama ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel', 'Selesai') NOT NULL");
                DB::statement("ALTER TABLE pemesanan_status_history MODIFY COLUMN status_baru ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel', 'Selesai') NOT NULL");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE pemesanan MODIFY COLUMN status ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel') NOT NULL DEFAULT 'Pending'");
            if (Schema::hasTable('pemesanan_status_history')) {
                DB::statement("ALTER TABLE pemesanan_status_history MODIFY COLUMN status_lama ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel') NOT NULL");
                DB::statement("ALTER TABLE pemesanan_status_history MODIFY COLUMN status_baru ENUM('Pending', 'Disetujui', 'Ditolak', 'Cancel') NOT NULL");
            }
        }
    }
};
