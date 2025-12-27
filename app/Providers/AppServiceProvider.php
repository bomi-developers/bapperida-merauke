<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;
use App\Models\KategoriDocument;
use App\Models\PageView;
use App\Models\ProfileDinas;
use Carbon\Carbon;
use Pest\Plugins\Profile;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();
        View::composer('*', function ($view) {
            // Cache Website Settings (1 jam)
            $websiteSettings = Cache::remember('website_settings', 60 * 60, function () {
                return WebsiteSetting::first();
            });

            // Cache Profile Dinas (1 jam)
            $ProfileDinas = Cache::remember('profile_dinas', 60 * 60, function () {
                return ProfileDinas::first();
            });

            // Cache Page Views (Update tiap 5 menit agar tidak berat)
            // Menggunakan cache tags atau simple key
            $stats = Cache::remember('global_stats', 5 * 60, function () {
                $start = Carbon::now()->subHour(2);
                $end = Carbon::now();
                
                return [
                    'pageView' => PageView::count(),
                    'pageViewOnline' => PageView::whereBetween('viewed_at', [$start, $end])->count(),
                    'pageViewToday' => PageView::whereDate('viewed_at', Carbon::today())->count(),
                    'pageViewUrl' => PageView::distinct('url')->count('url')
                ];
            });

            $view->with([
                'websiteSettings' => $websiteSettings,
                'ProfileDinas' => $ProfileDinas,
                'pageView' => $stats['pageView'],
                'pageViewToday' => $stats['pageViewToday'],
                'pageViewUrl' => $stats['pageViewUrl'],
                'pageViewOnline' => $stats['pageViewOnline']
            ]);
        });
        View::composer('components.landing.navbar', function ($view) {
            $view->with('kategoriDocuments', KategoriDocument::orderBy('nama_kategori')->orderBy('id', 'desc')->get());
        });
    }
}