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
        Schema::table('laporan_histories', function (Blueprint $table) {
            // Menambahkan kolom keterangan_opd setelah file_path
            $table->text('keterangan_opd')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_histories', function (Blueprint $table) {
            $table->dropColumn('keterangan_opd');
        });
    }
};
