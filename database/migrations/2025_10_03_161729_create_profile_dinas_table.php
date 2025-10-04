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
        Schema::create('profile_dinas', function (Blueprint $table) {
            $table->id();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('tugas_fungsi')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_dinas');
    }
};