<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\WebsiteSetting;
use App\Models\KategoriDocument;

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
        View::composer('*', function ($view) {
            $websiteSettings = WebsiteSetting::first(); // ambil data pertama
            $view->with('websiteSettings', $websiteSettings);
        });
        View::composer('components.landing.navbar', function ($view) {
            $view->with('kategoriDocuments', KategoriDocument::orderBy('nama_kategori')->orderBy('id', 'desc')->get());
        });
    }
}
