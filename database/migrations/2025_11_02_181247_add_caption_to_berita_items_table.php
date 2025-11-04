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
        Schema::table('berita_items', function (Blueprint $table) {
            // Tambahkan kolom caption setelah kolom 'content'
            $table->string('caption')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita_items', function (Blueprint $table) {
            $table->dropColumn('caption');
        });
    }
};
