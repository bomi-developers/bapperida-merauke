<?php

namespace App\Http\Controllers;

use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PagesController extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
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
}