<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('ruangan_fasilitas', function (Blueprint $table) {


            $table->id();


            $table->foreignId('ruangan_id')
                ->constrained('ruangan')
                ->cascadeOnDelete();


            $table->foreignId('fasilitas_id')
                ->constrained('fasilitas')
                ->cascadeOnDelete();



            $table->timestamps();



            $table->unique([
                'ruangan_id',
                'fasilitas_id'
            ]);

        });

    }



    public function down(): void
    {

        Schema::dropIfExists(
            'ruangan_fasilitas'
        );

    }

};