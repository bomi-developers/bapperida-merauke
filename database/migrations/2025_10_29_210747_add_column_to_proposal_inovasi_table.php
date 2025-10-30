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
        Schema::table('proposal_inovasi', function (Blueprint $table) {
            $table->text('ide_inovasi')->nullable()->after('judul');
            $table->text('tujuan_inovasi')->nullable()->after('ide_inovasi');
            $table->text('target_perubahan')->nullable()->after('tujuan_inovasi');
            $table->text('stakeholder')->nullable()->after('target_perubahan');
            $table->text('sdm')->nullable()->after('stakeholder');
            $table->text('penerima_manfaat')->nullable()->after('sdm');
            $table->text('kebaruan')->nullable()->after('penerima_manfaat');
            $table->text('deskripsi_ide')->nullable()->after('kebaruan');
            $table->text('keterangan')->nullable()->after('deskripsi_ide');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposal_inovasi', function (Blueprint $table) {
            //
        });
    }
};
