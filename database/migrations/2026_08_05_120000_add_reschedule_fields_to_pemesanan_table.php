<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->enum('reschedule_status', ['None', 'Pending', 'Disetujui', 'Ditolak'])
                ->default('None')
                ->after('status');
            $table->date('reschedule_tanggal')->nullable()->after('reschedule_status');
            $table->time('reschedule_waktu_mulai')->nullable()->after('reschedule_tanggal');
            $table->time('reschedule_waktu_selesai')->nullable()->after('reschedule_waktu_mulai');
            $table->text('reschedule_alasan')->nullable()->after('reschedule_waktu_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('pemesanan', function (Blueprint $table) {
            $table->dropColumn([
                'reschedule_status',
                'reschedule_tanggal',
                'reschedule_waktu_mulai',
                'reschedule_waktu_selesai',
                'reschedule_alasan',
            ]);
        });
    }
};
