<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layout_ruangan', function (Blueprint $table) {
            $table->foreignId('ruangan_id')->nullable()->change();
        });

        if (!Schema::hasTable('ruangan_layout')) {
            Schema::create('ruangan_layout', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
                $table->foreignId('layout_ruangan_id')->constrained('layout_ruangan')->cascadeOnDelete();
                $table->integer('kapasitas_layout')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ruangan_layout');

        Schema::table('layout_ruangan', function (Blueprint $table) {
            $table->foreignId('ruangan_id')->nullable(false)->change();
        });
    }
};
