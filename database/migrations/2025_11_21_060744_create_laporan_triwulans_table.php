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
        Schema::create('laporan_triwulans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('period_id')->constrained('triwulan_periods')->onDelete('cascade');
            $table->string('file_path');
            $table->text('keterangan_opd')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->enum('status', ['MENUNGGU', 'REVISI', 'DISETUJUI'])->default('MENUNGGU');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_triwulans');
    }
};
