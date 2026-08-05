<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('ruangan', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Master Data Ruangan
            |--------------------------------------------------------------------------
            */

            $table->string('nama_ruangan');

            $table->integer('kapasitas');


            /*
            |--------------------------------------------------------------------------
            | Status Operasional Ruangan
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [
                'aktif',
                'nonaktif',
                'perawatan'
            ])
            ->default('aktif');


            $table->string('lokasi');


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }

};