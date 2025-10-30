<?php

namespace App\Jobs;

use App\Models\GaleriItem; // Gunakan model GaleriItem
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;

class CompressGaleriItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $galeriItem;

    /**
     * Create a new job instance.
     *
     * @param GaleriItem $galeriItem Model item yang akan diproses
     */
    public function __construct(GaleriItem $galeriItem)
    {
        $this->galeriItem = $galeriItem;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Cek apakah ini benar-benar gambar (bukan video atau URL)
        if ($this->galeriItem->tipe_file !== 'image') {
            Log::info("Skipping compression for non-image item: {$this->galeriItem->id}");
            return;
        }

        $filePath = $this->galeriItem->file_path;
        $fullPath = storage_path('app/public/' . $filePath);

        try {
            if (!Storage::disk('public')->exists($filePath)) {
                Log::warning("File not found, skipping compression: {$filePath}");
                return;
            }

            // 2. Buka gambar, kompres, dan timpa file aslinya
            Image::make($fullPath)
                ->resize(1920, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($fullPath, 80); // Simpan DENGAN NAMA YANG SAMA (kualitas 80%)

            Log::info("Successfully compressed galeri image: {$filePath}");

        } catch (\Exception $e) {
            Log::error("Failed to compress galeri image {$filePath}: " . $e->getMessage());
        }
    }
}
