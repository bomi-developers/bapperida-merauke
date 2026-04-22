<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop unique index on nip if it exists
        $indexes = collect(DB::select("SHOW INDEX FROM opd WHERE Column_name = 'nip'"))
            ->pluck('Key_name')
            ->unique();

        Schema::table('opd', function (Blueprint $table) use ($indexes) {
            foreach ($indexes as $indexName) {
                if ($indexName !== 'PRIMARY') {
                    $table->dropIndex($indexName);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('opd', function (Blueprint $table) {
            $table->unique('nip');
        });
    }
};
