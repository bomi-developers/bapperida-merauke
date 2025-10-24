<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LoginLog;
use Spatie\Activitylog\Models\Activity;
use App\Models\PageView;

class PagesController extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'beritaCount' => number_format(Berita::where('status', 'published')->where('page', 'berita')->count()),
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
        $data = [
            'title' => 'Webbsite Settings',
            'settings' => WebsiteSetting::first(),
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
        $settings = WebsiteSetting::first();

        if (!$settings) {
            $settings = new WebsiteSetting();
        }

        // Validasi minimal (boleh diperluas sesuai kebutuhan)
        $request->validate([
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png|max:1024',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::exists($settings->logo)) {
                Storage::delete($settings->logo); // hapus file lama
            }
            $settings->logo = $request->file('logo')->store('public/logo');
        }

        // Upload favicon
        if ($request->hasFile('favicon')) {
            if ($settings->favicon && Storage::exists($settings->favicon)) {
                Storage::delete($settings->favicon);
            }
            $settings->favicon = $request->file('favicon')->store('public/favicon');
        }

        // Update field lainnya
        $settings->nama_kantor     = $request->nama_kantor;
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

        return redirect()->back()->with('success', 'Website settings updated successfully.');
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
