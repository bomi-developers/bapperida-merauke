<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('golongans', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->string('golongan');
            $table->timestamps();
        });
        $now = date('Y-m-d H:i:s');

        $golongans = [
            ['kode' => 'I/a', 'nama' => 'Juru Muda'],
            ['kode' => 'I/b', 'nama' => 'Juru Muda Tingkat I'],
            ['kode' => 'I/c', 'nama' => 'Juru'],
            ['kode' => 'I/d', 'nama' => 'Juru Tingkat I'],
            ['kode' => 'II/a', 'nama' => 'Pengatur Muda'],
            ['kode' => 'II/b', 'nama' => 'Pengatur Muda Tingkat I'],
            ['kode' => 'II/c', 'nama' => 'Pengatur'],
            ['kode' => 'II/d', 'nama' => 'Pengatur Tingkat I'],
            ['kode' => 'III/a', 'nama' => 'Penata Muda'],
            ['kode' => 'III/b', 'nama' => 'Penata Muda Tingkat I'],
            ['kode' => 'III/c', 'nama' => 'Penata'],
            ['kode' => 'III/d', 'nama' => 'Penata Tingkat I'],
            ['kode' => 'IV/a', 'nama' => 'Pembina'],
            ['kode' => 'IV/b', 'nama' => 'Pembina Tingkat I'],
            ['kode' => 'IV/c', 'nama' => 'Pembina Utama Muda'],
            ['kode' => 'IV/d', 'nama' => 'Pembina Utama Madya'],
            ['kode' => 'IV/e', 'nama' => 'Pembina Utama'],
        ];

        foreach ($golongans as $g) {
            DB::table('golongans')->insert([
                'kode' => $g['kode'],
                'golongan' => $g['nama'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('golongans');
    }
};
