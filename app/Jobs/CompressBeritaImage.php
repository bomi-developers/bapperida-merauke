<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CompressBeritaImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath Path file relatif terhadap disk 'public'
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     * Logika kompresi akan berjalan di sini (di latar belakang).
     */
    public function handle(): void
    {
        try {
            // Dapatkan path lengkap di storage
            $fullPath = storage_path('app/public/' . $this->filePath);

            if (!Storage::disk('public')->exists($this->filePath)) {
                Log::warning("File not found, skipping compression: {$this->filePath}");
                return;
            }

            // Buka gambar, kompres, dan TIMPA file aslinya
            Image::make($fullPath)
                ->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio(); // Jaga rasio
                    $constraint->upsize();      // Jangan perbesar gambar kecil
                })
                ->save($fullPath, 80); // Simpan DENGAN NAMA YANG SAMA (menimpa)

            Log::info("Successfully compressed image: {$this->filePath}");
        } catch (\Exception $e) {
            // Catat error jika kompresi gagal
            Log::error("Failed to compress image {$this->filePath}: " . $e->getMessage());
        }
    }
}
