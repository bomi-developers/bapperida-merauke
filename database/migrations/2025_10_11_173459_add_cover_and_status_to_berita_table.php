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
        Schema::table('berita', function (Blueprint $table) {
            $table->string('cover_image')->nullable()->after('slug');
            $table->text('excerpt')->nullable()->after('cover_image');
            $table->enum('status', ['published', 'draft'])->default('draft')->after('excerpt');
            $table->foreignId('user_id')->nullable()->constrained('users')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['cover_image', 'excerpt', 'status', 'user_id']);
        });
    }
};
