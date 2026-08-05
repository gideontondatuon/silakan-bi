<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan_status_history', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Relasi Pemesanan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('pemesanan_id')
                ->constrained('pemesanan')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Perubahan Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status_lama', [
                'Pending',
                'Disetujui',
                'Ditolak',
                'Cancel'
            ])
            ->nullable();


            $table->enum('status_baru', [
                'Pending',
                'Disetujui',
                'Ditolak',
                'Cancel'
            ]);


            /*
            |--------------------------------------------------------------------------
            | User yang melakukan perubahan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('changed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('changed_at');


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pemesanan_status_history');
    }
};