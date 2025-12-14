<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\KategoriDocument;
use App\Models\LendingPage;
use App\Models\Pegawai;
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
    public function getBerita(Request $request)
    {
        $search = $request->keyword ?? '';

        $query = Berita::with('author')
            ->select('id', 'title', 'cover_image', 'slug',  'user_id', 'views_count', 'created_at')
            ->latest();

        // Jika ada pencarian
        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }
        if ($request->has('top')) {
            $query->orderBy('views_count', 'desc')->take($request->top);
        } else {
            $query->latest();
        }

        $berita = $query->limit(20)->get();

        return response()->json([
            'status' => 'success',
            'data' => $berita
        ]);
    }
    public function pegawai()
    {
        $pegawai = Pegawai::with(['bidang', 'golongan'])->get();
        return view('landing_page.about.pegawai', compact('pegawai') +  [
            'meta_title'       => 'Pegawai BAPPERIDA MERAUKE',
            'meta_description' => 'Pegawai Bappperida MERAUKE',
        ]);
    }
    public function sejarah()
    {
        return view('landing_page.about.sejarah', [
            'meta_title'       => 'Sejarah BAPPERIDA MERAUKE',
            'meta_description' => 'Sejarah Bappperida MERAUKE',
        ]);
    }
    public function strukturOrganisasi()
    {
        return view('landing_page.about.struktur_organisasi', [
            'meta_title'       => 'Struktur Organisasi BAPPERIDA MERAUKE',
            'meta_description' => 'Struktur Organisasi Bappperida MERAUKE',
        ]);
    }
    public function tugasFungsi()
    {
        return view('landing_page.about.tugas_fungsi', [
            'meta_title'       => 'Tugas dan Fungsi BAPPERIDA MERAUKE',
            'meta_description' => 'Tugas dan Fungsi Bappperida MERAUKE',
        ]);
    }
    public function visiMisi()
    {
        return view('landing_page.about.visi_misi', [
            'meta_title'       => 'Visi dan Misi BAPPERIDA MERAUKE',
            'meta_description' => 'Visi dan Misi Bappperida MERAUKE',
        ]);
    }
    // riset dan inovasi
    public function riset()
    {
        $beritas = Berita::with('author')
            ->where('status', 'published')
            ->where('page', 'inovasi_riset')
            ->latest()
            ->paginate(6);

        return view('landing_page.inovasi.riset', compact('beritas') + [
            'meta_title'       => 'Riset data BAPPERIDA MERAUKE',
            'meta_description' => 'Riset data Bappperida MERAUKE',
        ]);
    }
    public function inovasi()
    {
        return view('landing_page.inovasi.inovasi', [
            'meta_title'       => 'Inovasi  BAPPERIDA MERAUKE',
            'meta_description' => 'Inovasi  Bappperida MERAUKE',
        ]);
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
