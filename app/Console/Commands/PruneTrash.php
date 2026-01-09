<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Berita;
use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PruneTrash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trash:prune';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete items from Recycle Bin that are older than 1 month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subMonth();
        $this->info("Pruning trash older than: " . $cutoffDate->toDateTimeString());

        // 1. Prune Berita
        $beritas = Berita::onlyTrashed()->where('deleted_at', '<', $cutoffDate)->get();
        $beritaCount = $beritas->count();

        foreach ($beritas as $berita) {
            DB::transaction(function () use ($berita) {
                if ($berita->cover_image) {
                    Storage::disk('public')->delete($berita->cover_image);
                }
                foreach ($berita->items as $item) {
                    if ($item->type === 'image' && $item->content) {
                        Storage::disk('public')->delete($item->content);
                    }
                }
                $berita->forceDelete();
            });
        }
        $this->info("Deleted {$beritaCount} Berita items.");

        // 2. Prune Galeri
        $galeris = Galeri::onlyTrashed()->where('deleted_at', '<', $cutoffDate)->get();
        $galeriCount = $galeris->count();

        foreach ($galeris as $galeri) {
            DB::transaction(function () use ($galeri) {
                foreach ($galeri->items as $item) {
                    if ($item->tipe_file !== 'video_url' && $item->file_path) {
                        Storage::disk('public')->delete($item->file_path);
                    }
                }
                $galeri->items()->forceDelete();
                $galeri->forceDelete();
            });
        }
        $this->info("Deleted {$galeriCount} Galeri albums.");

        return Command::SUCCESS;
    }
}
