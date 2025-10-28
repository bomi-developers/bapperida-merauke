<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileDinasController;
use App\Http\Controllers\KategoriDocumentController;
use App\Http\Controllers\ProposalController;
use App\Models\Notifikasi;
use Carbon\Carbon;

// cache control



Route::get('img/{path}', function ($path) {
  $fullPath = public_path('img/' . $path);

  if (!File::exists($fullPath)) abort(404);

  $mime = File::mimeType($fullPath);
  $contents = File::get($fullPath);

  return Response::make($contents, 200, [
    'Content-Type' => $mime,
    'Cache-Control' => 'max-age=2592000, public',
    'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($fullPath)) . ' GMT',
  ]);
})->where('path', '.*');

// end cache control

// Route::get('/', function () {
//   return redirect()->route('login');
// })->name('welcome');



Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::prefix('about')->name('about.')->group(function () {
  Route::get('/pegawai', [HomeController::class, 'pegawai'])->name('pegawai');
  Route::get('/sejarah', [HomeController::class, 'sejarah'])->name('sejarah');
  Route::get('/tugas-fungsi', [HomeController::class, 'tugasFungsi'])->name('tugas-fungsi');
  Route::get('/struktur-organisasi', [HomeController::class, 'strukturOrganisasi'])->name('struktur-organisasi');
  Route::get('/visi-misi', [HomeController::class, 'visiMisi'])->name('visi-misi');
});
Route::prefix('riset-inovasi')->name('riset-inovasi.')->group(function () {
  Route::get('/data', [HomeController::class, 'data'])->name('data');
  Route::get('/riset', [HomeController::class, 'riset'])->name('riset');
  Route::get('/inovasi', [HomeController::class, 'inovasi'])->name('inovasi');
  Route::get('/kekayaan-intelektual', [HomeController::class, 'kekayaanIntelektual'])->name('kekayaan-intelektual');
  // proposal
  // routes/web.php
  Route::post('/proposal/store', [ProposalController::class, 'store'])->name('proposal.store');
  // API untuk cek email
  Route::get('/api/check-email', [ProposalController::class, 'checkEmail']);
});

Route::get('/berita', [BeritaController::class, 'home'])->name('berita.public.home');
Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.public.show');
Route::get('/dokumen/kategori/{kategori}/{slug}', [DocumentController::class, 'showByCategory'])
  ->name('documents.by_category');
Route::get('/dokumen/search', [DocumentController::class, 'searchPublic'])->name('documents.search_public');
// download 
Route::get('/documents/{document}/download', [DocumentController::class, 'downloadFile'])->name('documents.download');

Auth::routes(['register' => false, 'verify' => false, 'reset' => false]);
Route::middleware(['auth'])->group(function () {
  // notifikasi
  Route::get('/notifikasi/json', function () {
    $notifs = Notifikasi::whereNull('read_at')->orderBy('created_at', 'desc')->limit(10)->get();
    $count = Notifikasi::whereNull('read_at')->count();
    return response()->json([
      'count' => $count,
      'notifikasi' => $notifs
    ]);
  });
  Route::put('/notifikasi/{id}', function ($id) {
    $notif = Notifikasi::find($id);

    if (!$notif) {
      return response()->json([
        'success' => false,
        'message' => 'Notifikasi tidak ditemukan'
      ], 404);
    }

    // Update read_at menjadi timestamp hari ini
    $notif->read_at = Carbon::now();
    $notif->save();

    return response()->json(['success' => true]);
  });
  // dashboard page
  Route::get('/home', [PagesController::class, 'dashboard'])->name('home');
  // bidang
  Route::prefix('admin')->name('admin.')->group(function () {
    // bidang
    Route::get('bidang', [\App\Http\Controllers\BidangController::class, 'index'])->name('bidang.index');
    Route::get('bidang/data', [\App\Http\Controllers\BidangController::class, 'getData'])->name('bidang.data');
    Route::post('bidang', [\App\Http\Controllers\BidangController::class, 'store'])->name('bidang.store');
    Route::put('bidang/{bidang}', [\App\Http\Controllers\BidangController::class, 'update'])->name('bidang.update');
    Route::delete('bidang/{bidang}', [\App\Http\Controllers\BidangController::class, 'destroy'])->name('bidang.destroy');
    // golongan
    Route::get('golongan', [\App\Http\Controllers\GolonganController::class, 'index'])->name('golongan');
    Route::get('golongan/data', [\App\Http\Controllers\GolonganController::class, 'getData'])->name('golongan.data');
    Route::post('golongan', [\App\Http\Controllers\GolonganController::class, 'store'])->name('golongan.store');
    Route::put('golongan/{golongan}', [\App\Http\Controllers\GolonganController::class, 'update'])->name('golongan.update');
    Route::delete('golongan/{golongan}', [\App\Http\Controllers\GolonganController::class, 'destroy'])->name('golongan.destroy');
    // jabatan
    Route::get('jabatan', [\App\Http\Controllers\JabatanController::class, 'index'])->name('jabatan');
    Route::get('jabatan/data', [\App\Http\Controllers\JabatanController::class, 'getData'])->name('jabatan.data');
    Route::post('jabatan', [\App\Http\Controllers\JabatanController::class, 'store'])->name('jabatan.store');
    Route::put('jabatan/{jabatan}', [\App\Http\Controllers\JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('jabatan/{jabatan}', [\App\Http\Controllers\JabatanController::class, 'destroy'])->name('jabatan.destroy');
    // pegawai
    Route::get('pegawai', [\App\Http\Controllers\PegawaiController::class, 'index'])->name('pegawai');
    Route::get('pegawai/data', [\App\Http\Controllers\PegawaiController::class, 'getData'])->name('pegawai.data');
    Route::post('pegawai', [\App\Http\Controllers\PegawaiController::class, 'store'])->name('pegawai.store');
    Route::put('pegawai/{pegawai}', [\App\Http\Controllers\PegawaiController::class, 'update'])->name('pegawai.update');
    Route::delete('pegawai/{pegawai}', [\App\Http\Controllers\PegawaiController::class, 'destroy'])->name('pegawai.destroy');
    // akun pegawai
    Route::get('pegawai/akun', [\App\Http\Controllers\UserController::class, 'pegawai'])->name('pegawai.akun');
    // profile dinas
    Route::get('/profile-dinas', [ProfileDinasController::class, 'index'])->name('profile-dinas');
    Route::post('/profile-dinas', [ProfileDinasController::class, 'store'])->name('profile-dinas.store');
    // berita
    Route::get('berita', [\App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
    Route::get('berita/create', [\App\Http\Controllers\BeritaController::class, 'create'])->name('berita.create');
    Route::post('berita', [\App\Http\Controllers\BeritaController::class, 'store'])->name('berita.store');
    Route::get('berita/{berita}/edit', [\App\Http\Controllers\BeritaController::class, 'edit'])->name('berita.edit');
    Route::put('berita/{berita}', [\App\Http\Controllers\BeritaController::class, 'update'])->name('berita.update');
    Route::delete('berita/{berita}', [\App\Http\Controllers\BeritaController::class, 'destroy'])->name('berita.destroy');
    Route::get('berita/search', [\App\Http\Controllers\BeritaController::class, 'search'])->name('berita.search');
    // document
    Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::post('documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    Route::put('documents/{document}', [DocumentController::class, 'update'])->name('documents.update');
    Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    Route::get('/documents/{document}/download', [DocumentController::class, 'downloadFile'])->name('documents.download');
    // kategori document
    Route::get('/document-kategori', [KategoriDocumentController::class, 'index'])->name('doctkategori.index');
    Route::post('/document-kategori', [KategoriDocumentController::class, 'store'])->name('doctkategori.store');
    Route::get('/document-kategori/{kategori}', [KategoriDocumentController::class, 'show'])->name('doctkategori.show');
    Route::put('/document-kategori/{kategori}', [KategoriDocumentController::class, 'update'])->name('doctkategori.update');
    Route::delete('/document-kategori/{kategori}', [KategoriDocumentController::class, 'destroy'])->name('doctkategori.destroy');
    // galeri
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{galeri}', [GaleriController::class, 'show'])->name('galeri.show'); // Untuk AJAX edit
    Route::put('/galeri/{galeri}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
    // logs login
    Route::get('login-logs', [PagesController::class, 'loginLogs'])->name('login-logs');
    Route::get('activity-logs', [PagesController::class, 'activityLogs'])->name('activity-logs');
    Route::get('view-logs', [PagesController::class, 'viewLogs'])->name('view-logs');
  });
  // setting page
  Route::get('/website-setting', [PagesController::class, 'websiteSetting'])->name('website-setting');
  Route::put('/website-setting', [PagesController::class, 'websiteSettingUpdate'])->name('website-setting.update');
  // account
  Route::get('/profile', [PagesController::class, 'profile'])->name('profile');

  // pages examples
  Route::get('/calendar', [PagesController::class, 'calendar'])->name('calendar');
  Route::get('/alerts', [PagesController::class, 'alerts'])->name('alerts');
  Route::get('/buttons', [PagesController::class, 'buttons'])->name('buttons');
  Route::get('/chart', [PagesController::class, 'chart'])->name('chart');
  Route::get('/form-elements', [PagesController::class, 'form_elements'])->name('form-elements');
  Route::get('/form-layout', [PagesController::class, 'form_layout'])->name('form-layout');
  Route::get('/settings', [PagesController::class, 'settings'])->name('settings');
  Route::get('/tables', [PagesController::class, 'tables'])->name('tables');
});
