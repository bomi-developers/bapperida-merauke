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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();

            // Informasi dasar kantor
            $table->string('nama_kantor')->nullable();
            $table->string('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('maps_iframe')->nullable();

            // Logo dan favicon
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // Media sosial
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();

            // Metadata SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();

            // Status dan waktu
            $table->boolean('is_maintenance')->default(false);
            $table->timestamps();
        });
        DB::table('website_settings')->insert([
            'nama_kantor' => 'Dinas Kabupaten',
            'alamat' => 'Jl. Contoh No.1, Kota Contoh',
            'telepon' => '021-1234567',
            'email' => 'info@dinascontoh.go.id',
            'website' => 'https://dinascontoh.go.id',
            'maps_iframe' => '<iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            'logo' => 'default',
            'favicon' => 'default',
            'facebook' => 'https://facebook.com/dinascontoh',
            'instagram' => 'https://instagram.com/dinascontoh',
            'twitter' => 'https://twitter.com/dinascontoh',
            'linkedin' => 'https://linkedin.com/company/dinascontoh',
            'youtube' => 'https://youtube.com/dinascontoh',
            'meta_title' => 'Website Resmi Dinas Contoh Kabupaten',
            'meta_description' => 'Website resmi Dinas Contoh Kabupaten untuk informasi layanan dan berita terkini.',
            'meta_keywords' => 'dinas, kabupaten, layanan, berita, informasi',
            'is_maintenance' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};