<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('ruangan_fasilitas');
        Schema::dropIfExists('fasilitas');
    }

    public function down(): void
    {
    }
};
