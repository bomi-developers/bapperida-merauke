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
            $table->enum('status', [0, 1, 2])->after('email_verified')->default(0)->comment('0: pending, 1: accepted, 2: rejected');
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
