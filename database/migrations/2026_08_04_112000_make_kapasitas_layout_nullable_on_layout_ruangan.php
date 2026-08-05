<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layout_ruangan', function (Blueprint $table) {
            $table->integer('kapasitas_layout')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('layout_ruangan', function (Blueprint $table) {
            $table->integer('kapasitas_layout')->nullable(false)->change();
        });
    }
};
