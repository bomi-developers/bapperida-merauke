<?php

namespace App\Http\Controllers;

use App\Models\KategoriDocument;
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
            'dokumen' => KategoriDocument::withCount('documents')->get(),
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
}
