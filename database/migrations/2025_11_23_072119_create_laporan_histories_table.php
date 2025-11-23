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
        Schema::create('laporan_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_id')->constrained('laporan_triwulans')->onDelete('cascade');
            $table->string('file_path'); 
            $table->text('catatan_admin')->nullable();
            $table->string('status_snapshot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_histories');
    }
};
