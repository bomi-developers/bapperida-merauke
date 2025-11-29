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
        Schema::create('renja_documents', function (Blueprint $table) {
            $table->id();

            // Relasi
            $table->foreignId('tahapan_id')->constrained('master_tahapan_rkpds')->onDelete('cascade');
            $table->foreignId('opd_id')->constrained('users')->onDelete('cascade');

            // File Upload OPD
            $table->string('file_dokumen_renja'); // PDF/Word (Bab-bab)
            $table->string('file_matriks_renja'); // Excel (Program Kegiatan)

            // Status & Verifikasi
            $table->enum('status', ['MENUNGGU', 'REVISI', 'DISETUJUI'])->default('MENUNGGU');

            // FEEDBACK DARI ADMIN
            $table->text('catatan_verifikasi')->nullable(); // Komentar Teks
            $table->string('file_matriks_verifikasi')->nullable(); // FILE BALASAN ADMIN (Excel Coretan)

            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renja_documents');
    }
};
