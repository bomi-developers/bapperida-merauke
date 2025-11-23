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
        Schema::create('triwulan_periods', function (Blueprint $table) {
            $table->id();
            $table->year('tahun'); 
            $table->enum('triwulan', ['1', '2', '3', '4']);
            $table->date('start_date');
            $table->date('end_date'); 
            $table->boolean('is_open')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('triwulan_periods');
    }
};
