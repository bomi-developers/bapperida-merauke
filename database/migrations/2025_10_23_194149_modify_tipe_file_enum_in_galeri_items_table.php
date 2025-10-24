<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galeri_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE galeri_items MODIFY COLUMN tipe_file ENUM('image', 'video', 'video_url') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeri_items', function (Blueprint $table) {
            DB::statement("ALTER TABLE galeri_items MODIFY COLUMN tipe_file ENUM('image', 'video') NOT NULL");
        });
    }
};
