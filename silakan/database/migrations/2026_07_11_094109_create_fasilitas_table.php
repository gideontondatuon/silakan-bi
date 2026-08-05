<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fasilitas', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Master Fasilitas Ruangan
            |--------------------------------------------------------------------------
            */

            $table->string('nama_fasilitas');


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};