<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pegawai;
use App\Models\Document;
use App\Models\LoginLog;
use App\Models\PageView;
use App\Models\ProfileDinas;
use Illuminate\Http\Request;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class PagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super_admin')->only(['websiteSetting', 'websiteSettingUpdate', 'loginLogs', 'activityLogs', 'viewLogs']);
    }

    public function dashboard()
    {
        $dailyViewsRaw = DB::table('page_views')
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as total')
            ->whereNull('user_id')
            ->where('viewed_at', '>=', Carbon::now()->subDays(6)->startOfDay())
            ->groupBy(DB::raw('DATE(viewed_at)'))
            ->orderBy('date', 'ASC')
            ->pluck('total', 'date');
        $dailyViews = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyViews->push([
                'date' => $date,
                'total' => $dailyViewsRaw[$date] ?? 0, // isi 0 kalau tidak ada data
            ]);
        }

        $monthlyViewsRaw = DB::table('page_views')
            ->selectRaw('DATE_FORMAT(viewed_at, "%Y-%m") as month, COUNT(*) as total')
            ->whereNull('user_id')
            ->where('viewed_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy(DB::raw('DATE_FORMAT(viewed_at, "%Y-%m")'))
            ->orderBy('month', 'ASC')
            ->pluck('total', 'month');
        $monthlyViews = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthlyViews->push([
                'month' => $month,
                'total' => $monthlyViewsRaw[$month] ?? 0,
            ]);
        }


        $data = [
            'title' => 'Dashboard',
            'dailyViews' => $dailyViews,
            'monthlyViews' => $monthlyViews,
            'galeriCount' => number_format(Galeri::count()),
            'documentCount' => number_format(Document::count()),
            'beritaCount' => number_format(Berita::where('status', 'published')->where('page', 'berita')->count()),
            'beritaDraftCount' => number_format(Berita::where('status', '!=', 'published')->where('page', 'berita')->count()),
            'beritaCountView' => number_format(Berita::where('status', 'published')->where('page', 'berita')->sum('views_count')),
        ];
        return view('pages.dashboard', $data);
    }
    public function calendar()
    {
        $data = [
            'title' => 'Calendar',
        ];
        return view('pages.calendar', $data);
    }
    public function alerts()
    {
        $data = [
            'title' => 'Alerts',
        ];
        return view('pages.alerts', $data);
    }
    public function buttons()
    {
        $data = [
            'title' => 'Buttons',
        ];
        return view('pages.buttons', $data);
    }
    public function chart()
    {
        $data = [
            'title' => 'charts',
        ];
        return view('pages.chart', $data);
    }
    public function form_elements()
    {
        $data = [
            'title' => 'form elements',
        ];
        return view('pages.form-elements', $data);
    }
    public function form_layout()
    {
        $data = [
            'title' => 'form layout',
        ];
        return view('pages.form-layout', $data);
    }
    public function profile()
    {
        $data = [
            'title' => 'profile',
            'pegawai' => Pegawai::find(Auth::user()->id_pegawai),
            'logs' => LoginLog::where('id_user', Auth::id())->latest()->limit(10)->get(),
            'pages' => PageView::where('user_id', Auth::id())->orderBy('viewed_at', 'DESC')->limit(5)->get(),
        ];
        return view('pages.profile', $data);
    }
    public function settings()
    {
        $data = [
            'title' => 'Settings',
        ];
        return view('pages.settings', $data);
    }
    public function websiteSetting()
    {
        $pageHeroes = \App\Models\PageHero::pluck('hero_bg', 'route_name')->toArray();

        $data = [
            'title' => 'Website & Profil Settings',
            'settings' => WebsiteSetting::first(),
            'profile' => ProfileDinas::first(),
            'pageHeroes' => $pageHeroes,
        ];
        return view('pages.setting.index', $data);
    }
    public function tables()
    {
        $data = [
            'title' => 'Tables',
        ];
        return view('pages.tables', $data);
    }
    public function websiteSettingUpdate(Request $request)
    {
        // 1. UPDATE WEBSITE SETTINGS (Umum)
        $settings = WebsiteSetting::first();
        if (!$settings) {
            $settings = new WebsiteSetting();
        }

        $request->validate([
            // Validasi Website
            'logo'      => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'logo_dark' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'favicon'   => 'nullable|image|mimes:ico,png|max:2048',
            'hero_bg' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',

            // Validasi Profil Dinas
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'tugas_fungsi' => 'nullable|string',
            'struktur_organisasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
        ]);

        // -- Simpan Gambar Website --
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) Storage::disk('public')->delete($settings->logo);
            $settings->logo = $request->file('logo')->store('logo', 'public');
        }
        if ($request->hasFile('logo_dark')) {
            if ($settings->logo_dark && Storage::disk('public')->exists($settings->logo_dark)) Storage::disk('public')->delete($settings->logo_dark);
            $settings->logo_dark = $request->file('logo_dark')->store('logo', 'public');
        }
        if ($request->hasFile('favicon')) {
            if ($settings->favicon && Storage::disk('public')->exists($settings->favicon)) Storage::disk('public')->delete($settings->favicon);
            $settings->favicon = $request->file('favicon')->store('favicon', 'public');
        }
        if ($request->hasFile('hero_bg')) {
            if ($settings->hero_bg && Storage::disk('public')->exists($settings->hero_bg)) {
                Storage::disk('public')->delete($settings->hero_bg);
            }
            $settings->hero_bg = $request->file('hero_bg')->store('hero', 'public');
        }
        if ($request->has('page_heroes')) {
            foreach ($request->file('page_heroes') as $route => $file) {
                // Upload file baru
                $path = $file->store('hero_pages', 'public');

                // Cek database
                $hero = \App\Models\PageHero::where('route_name', $route)->first();

                if ($hero) {
                    // Hapus file lama
                    if (\Illuminate\Support\Facades\Storage::disk('public')->exists($hero->hero_bg)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($hero->hero_bg);
                    }
                    // Update record
                    $hero->update(['hero_bg' => $path]);
                } else {
                    // Buat baru
                    \App\Models\PageHero::create([
                        'route_name' => $route,
                        'hero_bg' => $path
                    ]);
                }
            }
        }

        // -- Update Data Website --
        $settings->nama_kantor      = $request->nama_kantor;
        $settings->alamat           = $request->alamat;
        $settings->telepon          = $request->telepon;
        $settings->email            = $request->email;
        $settings->website          = $request->website;
        $settings->maps_iframe      = $request->maps_iframe;
        $settings->facebook         = $request->facebook;
        $settings->instagram        = $request->instagram;
        $settings->twitter          = $request->twitter;
        $settings->linkedin         = $request->linkedin;
        $settings->youtube          = $request->youtube;
        $settings->meta_title       = $request->meta_title;
        $settings->meta_description = $request->meta_description;
        $settings->meta_keywords    = $request->meta_keywords;
        $settings->is_maintenance   = $request->has('is_maintenance') ? true : false;
        $settings->save();

        // 2. UPDATE PROFILE DINAS
        $profile = ProfileDinas::first();
        if (!$profile) {
            $profile = new ProfileDinas();
        }

        $profile->visi = $request->visi;
        $profile->misi = $request->misi;
        $profile->sejarah = $request->sejarah;
        $profile->tugas_fungsi = $request->tugas_fungsi;

        // -- Upload Struktur Organisasi --
        if ($request->hasFile('struktur_organisasi')) {
            if ($profile->struktur_organisasi && Storage::disk('public')->exists($profile->struktur_organisasi)) {
                Storage::disk('public')->delete($profile->struktur_organisasi);
            }
            $profile->struktur_organisasi = $request->file('struktur_organisasi')->store('struktur', 'public');
        }
        $profile->save();

        return redirect()->back()->with('success', 'Pengaturan dan Profil Dinas berhasil diperbarui.');
    }
    public function loginLogs()
    {
        $data = [
            'title' => 'Login Logs',
            'logs' => LoginLog::latest()->paginate(15),
        ];
        return view('pages.logs.index', $data);
    }
    public function activityLogs()
    {
        $data = [
            'title' => 'Activity Logs',
            'logs' => Activity::with('causer')->latest()->paginate(20),
        ];
        return view('pages.logs.activity', $data);
    }
    public function viewLogs(Request $request)
    {
        $filter = $request->get('filter');
        $search = $request->get('search');

        $logs = PageView::with('user')
            ->when($filter === 'guest', function ($query) {
                $query->whereNull('user_id');
            })
            ->when($filter === 'user', function ($query) {
                $query->whereNotNull('user_id');
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('url', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%")
                        ->orWhereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"));
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(20);
        $todayGuest = PageView::whereNull('user_id')
            ->whereDate('viewed_at', today())->count();

        $todayUser = PageView::whereNotNull('user_id')
            ->whereDate('viewed_at', today())->count();

        // Statistik total
        $totalGuest = PageView::whereNull('user_id')->count();
        $totalUser  = PageView::whereNotNull('user_id')->count();

        $data = [
            'title' => 'Activity Logs',
            'logs' => $logs,
            'todayGuest' => $todayGuest,
            'todayUser' => $todayUser,
            'totalGuest' => $totalGuest,
            'totalUser' => $totalUser,
            'search' => $search,
        ];
        return view('pages.logs.view', $data);
    }
}
