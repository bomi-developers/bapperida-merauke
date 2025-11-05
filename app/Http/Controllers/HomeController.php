<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriDocument;
use App\Models\LendingPage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = [
            'dokumen' => KategoriDocument::all(),
            'Str' => new \Illuminate\Support\Str(),
            'LendingPage' => LendingPage::with('template')->get()->sortBy('order'),
        ];
        return view('landing_page.index', $data);
    }
    public function pegawai()
    {
        return view('landing_page.about.pegawai');
    }
    public function sejarah()
    {
        return view('landing_page.about.sejarah');
    }
    public function strukturOrganisasi()
    {
        return view('landing_page.about.struktur_organisasi');
    }
    public function tugasFungsi()
    {
        return view('landing_page.about.tugas_fungsi');
    }
    public function visiMisi()
    {
        return view('landing_page.about.visi_misi');
    }
    // riset dan inovasi
    public function riset()
    {
        $beritas = Berita::with('author')
            ->where('status', 'published')
            ->where('page', 'inovasi_riset')
            ->latest()
            ->paginate(6);

        return view('landing_page.inovasi.riset', compact('beritas'));
    }
    public function inovasi()
    {
        return view('landing_page.inovasi.inovasi');
    }
    public function data()
    {
        $beritas = Berita::with('author')
            ->where('status', 'published')
            ->where('page', 'inovasi_data')
            ->latest()
            ->paginate(6);

        return view('landing_page.inovasi.data', compact('beritas'));
    }
    public function kekayaanIntelektual()
    {
        $beritas = Berita::with('author')
            ->where('status', 'published')
            ->where('page', 'inovasi_kekayaan_intelektual')
            ->latest()
            ->paginate(6);

        return view('landing_page.inovasi.kekayaan_intelektual', compact('beritas'));
    }
}