<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique(); // ID Halaman (misal: welcome, berita.index)
            $table->string('hero_bg'); // Path Gambar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_heroes');
    }
};
