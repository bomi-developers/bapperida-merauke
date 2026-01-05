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
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jabatan')->constrained('jabatans')->onDelete('cascade');
            $table->foreignId('id_golongan')->constrained('golongans')->onDelete('cascade');
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('nik')->nullable();
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};