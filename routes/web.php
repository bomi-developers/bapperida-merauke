<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileDinasController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

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
Route::get('/berita', [BeritaController::class, 'home'])->name('berita.public.home');
Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.public.show');

Auth::routes(['register' => false, 'verify' => false, 'reset' => false]);
Route::middleware(['auth'])->group(function () {
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
