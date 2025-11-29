<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('renja_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renja_document_id')->constrained('renja_documents')->onDelete('cascade');

            // File Versi Lama
            $table->string('file_dokumen_renja');
            $table->string('file_matriks_renja');

            // Catatan saat itu
            $table->string('status_snapshot');
            $table->text('catatan_verifikasi')->nullable();
            $table->string('file_matriks_verifikasi')->nullable(); // File balasan admin lama

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('renja_histories');
    }
};
