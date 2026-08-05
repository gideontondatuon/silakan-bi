<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | User Pelaku Aktivitas
            |--------------------------------------------------------------------------
            */

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            /*
            |--------------------------------------------------------------------------
            | Detail Aktivitas
            |--------------------------------------------------------------------------
            */

            $table->string('aksi');

            $table->string('modul');

            $table->text('keterangan')
                ->nullable();


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }

};