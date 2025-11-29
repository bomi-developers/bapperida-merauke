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
            $table->string('file_dokumen_verifikasi')->nullable()->after('catatan_dokumen');
        });

        // Tambahkan juga ke history agar tercatat
        Schema::table('renja_histories', function (Blueprint $table) {
            $table->string('file_dokumen_verifikasi')->nullable()->after('catatan_verifikasi');
        });
    }

    public function down(): void
    {
        Schema::table('renja_documents', function (Blueprint $table) {
            $table->dropColumn('file_dokumen_verifikasi');
        });
        Schema::table('renja_histories', function (Blueprint $table) {
            $table->dropColumn('file_dokumen_verifikasi');
        });
    }
};
