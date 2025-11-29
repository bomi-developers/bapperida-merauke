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
        Schema::table('renja_documents', function (Blueprint $table) {
            // Status & Catatan Dokumen (Naskah)
            $table->enum('status_dokumen', ['MENUNGGU', 'REVISI', 'DISETUJUI'])->default('MENUNGGU')->after('file_matriks_renja');
            $table->text('catatan_dokumen')->nullable()->after('status_dokumen');

            // Status & Catatan Matriks (Excel)
            $table->enum('status_matriks', ['MENUNGGU', 'REVISI', 'DISETUJUI'])->default('MENUNGGU')->after('catatan_dokumen');
            $table->text('catatan_matriks')->nullable()->after('status_matriks');
        });
    }

    public function down(): void
    {
        Schema::table('renja_documents', function (Blueprint $table) {
            $table->dropColumn(['status_dokumen', 'catatan_dokumen', 'status_matriks', 'catatan_matriks']);
        });
    }
};
