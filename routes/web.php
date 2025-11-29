<?php

use Carbon\Carbon;
use App\Models\Template;
use App\Models\Notifikasi;
use App\Models\LendingPage;
use App\Models\KategoriDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\RenjaController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TriwulanController;
use App\Http\Controllers\LendingPageController;
use App\Http\Controllers\ProfileDinasController;
use App\Http\Controllers\UpdatePasswordController;
use App\Http\Controllers\KategoriDocumentController;

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
Route::get('/berita/data', [HomeController::class, 'getBerita'])->name('berita.data');
Route::get('/berita/search', [BeritaController::class, 'searchPublic'])->name('berita.public.search');
Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.public.show');

Route::get('/galeri', [GaleriController::class, 'indexPublic'])->name('galeri.public.index');
Route::get('/galeri/search', [GaleriController::class, 'searchPublic'])->name('galeri.public.search');
Route::get('/galeri/{galeri}', [GaleriController::class, 'showPublic'])->name('galeri.public.show');
Route::get('/galeri/{galeri}/filter', [GaleriController::class, 'filterItems'])->name('galeri.public.filter_items');

Route::get('/dokumen/kategori/{kategori}/{slug}', [DocumentController::class, 'showByCategory'])
  ->name('documents.by_category');
Route::get('/dokumen/data', [DocumentController::class, 'getDokumen'])->name('dokumen.data');
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
    // template
    Route::get('/template/{id}', function ($id) {
      $template = Template::find($id);
      $dokumen = KategoriDocument::all();

      if (!$template) {
        abort(404, 'Template tidak ditemukan');
      }
      // Render isi Blade dinamis dengan variabel $dokumen
      $renderedContent = Blade::render($template->content, [
        'dokumen' => $dokumen,
        'Str' => new \Illuminate\Support\Str(),
      ]);

      $html = "
          <!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Template Preview</title>
              <script src='https://cdn.tailwindcss.com'></script>
              <style>
                  body { background-color: #f9fafb; padding: 2rem; }
              </style>
          </head>
          <body >
            <div >
              {$renderedContent}
              </div>
          </body>
          </html>
          ";

      return response($html)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('X-Frame-Options', 'SAMEORIGIN')
        ->header('Referrer-Policy', 'no-referrer')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    });
    Route::get('/template-preview/all', function () {
      $pages = LendingPage::with('template')->where('is_active', true)->orderBy('order')->get();
      $dokumen = KategoriDocument::all();

      $htmlContent = '';
      foreach ($pages as $page) {
        if ($page->template) {
          $htmlContent .= Blade::render($page->template->content, [
            'dokumen' => $dokumen,
            'Str' => new \Illuminate\Support\Str(),
          ]);
        }
      }
      $html = "
          <!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>Template Preview</title>
              <script src='https://cdn.tailwindcss.com'></script>
              <style>
                  body { background-color: #f9fafb; padding: 2rem; }
              </style>
          </head>
          <body>
              {$htmlContent}
          </body>
          </html>
          ";

      return response($html)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('X-Frame-Options', 'SAMEORIGIN')
        ->header('Referrer-Policy', 'no-referrer')
        ->header('X-Content-Type-Options', 'nosniff')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    });
    // lending page
    Route::get('/lending-page', [LendingPageController::class, 'index'])->name('lending.index');
    Route::get('/lending-page/template', [LendingPageController::class, 'template'])->name('lending.template');
    Route::post('/lending-page', [LendingPageController::class, 'store'])->name('lending.store');
    Route::post('/lending-page/{id}', [LendingPageController::class, 'update'])->name('lending.update');
    Route::delete('/lending-page/{id}', [LendingPageController::class, 'destroy'])->name('lending.destroy');
    // update password
    Route::post('/ganti-password', [UpdatePasswordController::class, 'update'])->name('password.update');

    Route::middleware('role:super_admin')->group(function () {
      Route::get('bidang', [\App\Http\Controllers\BidangController::class, 'index'])->name('bidang.index');
      Route::get('bidang/data', [\App\Http\Controllers\BidangController::class, 'getData'])->name('bidang.data');
      Route::post('bidang', [\App\Http\Controllers\BidangController::class, 'store'])->name('bidang.store');
      Route::put('bidang/{bidang}', [\App\Http\Controllers\BidangController::class, 'update'])->name('bidang.update');
      Route::delete('bidang/{bidang}', [\App\Http\Controllers\BidangController::class, 'destroy'])->name('bidang.destroy');
      // golongan
      Route::get('golongan', [\App\Http\Controllers\GolonganController::class, 'index'])->name('golongan');
      Route::get('golongan/data', [\App\Http\Controllers\GolonganController::class, 'getData'])->name('golongan.data');
      Route::post('golongan/store', [\App\Http\Controllers\GolonganController::class, 'store'])->name('golongan.store');
      Route::put('golongan/update/{golongan}', [\App\Http\Controllers\GolonganController::class, 'update'])->name('golongan.update');
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
      // kategori document
      Route::get('/document-kategori', [KategoriDocumentController::class, 'index'])->name('doctkategori.index');
      Route::post('/document-kategori', [KategoriDocumentController::class, 'store'])->name('doctkategori.store');
      Route::get('document-kategori/data', [\App\Http\Controllers\KategoriDocumentController::class, 'getData'])->name('doctkategori.data');
      Route::get('/document-kategori/{kategori}', [KategoriDocumentController::class, 'show'])->name('doctkategori.show');
      Route::put('/document-kategori/{kategori}', [KategoriDocumentController::class, 'update'])->name('doctkategori.update');
      Route::delete('/document-kategori/{kategori}', [KategoriDocumentController::class, 'destroy'])->name('doctkategori.destroy');
      // logs login
      Route::get('login-logs', [PagesController::class, 'loginLogs'])->name('login-logs');
      Route::get('activity-logs', [PagesController::class, 'activityLogs'])->name('activity-logs');
      Route::get('view-logs', [PagesController::class, 'viewLogs'])->name('view-logs');
      // user pegawai
      Route::get('akun/{id}', [\App\Http\Controllers\UserController::class, 'cekAkun'])->name('akun.cek');
      Route::post('akun/store', [\App\Http\Controllers\UserController::class, 'store'])->name('akun.store');
      // user admin
      Route::get('user/admin', [\App\Http\Controllers\UserController::class, 'admin'])->name('user.admin');
      Route::get('user/admin-data', [\App\Http\Controllers\UserController::class, 'admin_data'])->name('user.admin-data');
    });
    // berita
    Route::get('berita', [\App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
    Route::get('berita/create', [\App\Http\Controllers\BeritaController::class, 'create'])->name('berita.create');
    Route::get('berita/data', [\App\Http\Controllers\BeritaController::class, 'getData'])->name('berita.data');
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
    // galeri
    Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
    Route::get('/galeri/data', [GaleriController::class, 'getData'])->name('galeri.data');
    Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
    Route::get('/galeri/{galeri}', [GaleriController::class, 'show'])->name('galeri.show');
    Route::put('/galeri/{galeri}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::delete('/galeri/{galeri}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
  });

  Route::get('/triwulan', [TriwulanController::class, 'index'])->name('triwulan.index');
  Route::post('/triwulan', [TriwulanController::class, 'store'])->name('triwulan.store');
  Route::put('/triwulan/{id}/verify', [TriwulanController::class, 'verify'])->name('triwulan.verify');
  Route::get('/triwulan/template', [TriwulanController::class, 'downloadTemplate'])->name('triwulan.template');
  Route::post('/triwulan/periode', [TriwulanController::class, 'updatePeriod'])->name('triwulan.period.update');
  Route::post('/triwulan/periode/{id}/toggle', [TriwulanController::class, 'togglePeriod'])->name('triwulan.period.toggle');
  Route::get('/triwulan/{id}/history', [TriwulanController::class, 'getHistory'])->name('triwulan.history');
  Route::post('/triwulan/template/upload', [TriwulanController::class, 'uploadTemplate'])->name('triwulan.template.upload');

  // Index Renja (OPD & Admin)
  Route::get('/renja', [RenjaController::class, 'index'])->name('renja.index');
  Route::post('/renja/upload', [RenjaController::class, 'store'])->name('renja.store');
  Route::post('/renja/tahapan', [RenjaController::class, 'updateTahapan'])->name('renja.tahapan.update');
  Route::put('/renja/{id}/verify', [RenjaController::class, 'verify'])->name('renja.verify');
  Route::post('/renja/tahapan/{id}/toggle', [RenjaController::class, 'toggleTahapan'])->name('renja.tahapan.toggle');
  Route::get('/renja/{id}/history', [RenjaController::class, 'getHistory'])->name('renja.history');

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
  // Route::get('/settings', [PagesController::class, 'settings'])->name('settings');
  Route::get('/tables', [PagesController::class, 'tables'])->name('tables');
});