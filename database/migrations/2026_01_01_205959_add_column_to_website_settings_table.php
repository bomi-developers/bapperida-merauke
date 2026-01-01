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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('judul_hero')->default('Badan Perkembangan, Riset dan Inovasi Daerah Kabupaten Merauke')->after('youtube');
            $table->string('deskripsi_hero')->default('Bappeda mempunyai tugas menyelenggarakan fungsi penunjang urusan pemerintahan bidang perencanaan dan menunjang urusan pemerintahan bidang penelitian dan pengembangan.')->after('judul_hero');
            $table->string('file_hero')->nullable()->after('deskripsi_hero');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            //
        });
    }
};
