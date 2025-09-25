<?php


use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

// cache control
Route::get('img/{path}', function ($path) {
  $fullPath = public_path('img/' . $path);

  if (!File::exists($fullPath)) abort(404);

  return Response::file($fullPath, [
    'Cache-Control' => 'max-age=31536000, public',
  ]);
})->where('path', '.*');

Route::get('tailadmin/{path}', function ($path) {
  $fullPath = public_path('tailadmin/' . $path);

  if (!File::exists($fullPath)) abort(404);

  return Response::file($fullPath, [
    'Cache-Control' => 'max-age=31536000, public',
  ]);
})->where('path', '.*');
// end cache control

Route::get('/', function () {
  return redirect()->route('login');
})->name('welcome');

Auth::routes(['register' => false, 'verify' => false, 'reset' => false]);
Route::middleware(['auth'])->group(function () {
  // dashboard page
  Route::get('/home', [PagesController::class, 'dashboard'])->name('home');
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