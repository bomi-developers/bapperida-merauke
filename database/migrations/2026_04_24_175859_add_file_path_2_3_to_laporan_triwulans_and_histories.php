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
        Schema::table('laporan_triwulans', function (Blueprint $table) {
            $table->string('file_path_2')->nullable()->after('file_path');
            $table->string('file_path_3')->nullable()->after('file_path_2');
        });

        Schema::table('laporan_histories', function (Blueprint $table) {
            $table->string('file_path_2')->nullable()->after('file_path');
            $table->string('file_path_3')->nullable()->after('file_path_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_triwulans', function (Blueprint $table) {
            $table->dropColumn(['file_path_2', 'file_path_3']);
        });

        Schema::table('laporan_histories', function (Blueprint $table) {
            $table->dropColumn(['file_path_2', 'file_path_3']);
        });
    }
};
