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
        Schema::create('master_tahapan_rkpds', function (Blueprint $table) {
            $table->id();
            $table->year('tahun'); // Misal: 2025

            // Jenis Tahapan (Ranwal, Rancangan, Ranhir)
            $table->enum('nama_tahapan', ['RANWAL', 'RANCANGAN', 'RANHIR']);

            // File Template dari Admin (Misal: Template Bab atau Matriks kosong)
            $table->string('file_template_rkpd')->nullable();

            // Status Buka/Tutup Upload
            $table->boolean('is_active')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_tahapan_rkpds');
    }
};
