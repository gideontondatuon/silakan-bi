<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Identitas User / Unit Kerja
            |--------------------------------------------------------------------------
            */

            // Tetap nullable sesuai SDD
            $table->string('name')
                ->nullable();

            // Login alternatif selain email
            $table->string('username')
                ->nullable()
                ->unique();

            // Login alternatif
            $table->string('email')
                ->nullable()
                ->unique();


            /*
            |--------------------------------------------------------------------------
            | Authentication
            |--------------------------------------------------------------------------
            */

            $table->string('password');
            $table->string('password_plain')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Role
            |--------------------------------------------------------------------------
            */

            $table->enum('role', [
                'admin',
                'user'
            ])
            ->default('user');


            /*
            |--------------------------------------------------------------------------
            | Data Unit Kerja
            |--------------------------------------------------------------------------
            */

            $table->string('nama_unit');

            $table->string('kode_unit');


            /*
            |--------------------------------------------------------------------------
            | Laravel Default
            |--------------------------------------------------------------------------
            */

            $table->rememberToken();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};