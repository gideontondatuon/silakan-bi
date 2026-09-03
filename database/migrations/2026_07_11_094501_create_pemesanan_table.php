<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Identitas Pemesanan
            |--------------------------------------------------------------------------
            */

            $table->string('kode_pemesanan')
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Relasi Utama
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();


            $table->foreignId('ruangan_id')
                ->constrained('ruangan')
                ->cascadeOnDelete();


            $table->foreignId('layout_ruangan_id')
                ->nullable()
                ->constrained('layout_ruangan')
                ->nullOnDelete();



            /*
            |--------------------------------------------------------------------------
            | Waktu Kegiatan
            |--------------------------------------------------------------------------
            */

            $table->date('tanggal_kegiatan');


            $table->time('waktu_mulai');


            $table->time('waktu_selesai');



            /*
            |--------------------------------------------------------------------------
            | Detail Kegiatan
            |--------------------------------------------------------------------------
            */

            $table->string('judul_kegiatan', 150);


            $table->string('pic_kegiatan');


            $table->enum('jenis_pic', [
                'Organik',
                'Non Organik'
            ]);


            $table->integer('jumlah_tamu');



            /*
            |--------------------------------------------------------------------------
            | Detail Layout
            |--------------------------------------------------------------------------
            */

            $table->text('keterangan_layout')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Catatan Tambahan User
            |--------------------------------------------------------------------------
            */

            $table->text('catatan_user')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Status Pemesanan
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'Pending',
                'Disetujui',
                'Ditolak',
                'Cancel',
                'Selesai'
            ])
            ->default('Pending');



            /*
            |--------------------------------------------------------------------------
            | Approval
            |--------------------------------------------------------------------------
            */

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('approved_at')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Reject
            |--------------------------------------------------------------------------
            */

            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('rejected_at')
                ->nullable();


            $table->text('alasan_penolakan')
                ->nullable();



            /*
            |--------------------------------------------------------------------------
            | Cancel
            |--------------------------------------------------------------------------
            */

            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->timestamp('cancelled_at')
                ->nullable();


            $table->text('alasan_pembatalan')
                ->nullable();



            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Index untuk validasi bentrok jadwal
            |--------------------------------------------------------------------------
            */

            $table->index([
                'ruangan_id',
                'tanggal_kegiatan',
                'status'
            ]);

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};