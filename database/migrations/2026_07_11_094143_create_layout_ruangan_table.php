<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layout_ruangan', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | Relasi ke Ruangan
            |--------------------------------------------------------------------------
            */

            $table->foreignId('ruangan_id')
                ->constrained('ruangan')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Informasi Layout
            |--------------------------------------------------------------------------
            */

            $table->string('nama_layout');

            $table->integer('kapasitas_layout');


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('layout_ruangan');
    }
};