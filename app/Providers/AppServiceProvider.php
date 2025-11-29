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
            $websiteSettings = WebsiteSetting::first();
            $ProfileDinas = ProfileDinas::first();
            $pageView = PageView::count();
            $pageViewToday = PageView::whereDate('viewed_at', Carbon::today())
                ->count();
            $pageViewUrl = PageView::distinct('url')
                ->count('url');
            $view->with(['websiteSettings' => $websiteSettings, 'ProfileDinas' => $ProfileDinas, 'pageView' => $pageView, 'pageViewToday' => $pageViewToday, 'pageViewUrl' => $pageViewUrl]);
        });
        View::composer('components.landing.navbar', function ($view) {
            $view->with('kategoriDocuments', KategoriDocument::orderBy('nama_kategori')->orderBy('id', 'desc')->get());
        });
    }
}