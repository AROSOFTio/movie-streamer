<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\BrowseController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MovieController;
use App\Http\Controllers\Frontend\StreamController;
use App\Http\Controllers\Frontend\WatchController;
use App\Http\Controllers\Payment\CheckoutController;
use App\Http\Controllers\Payment\PesapalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/browse', [BrowseController::class, 'index'])->name('browse');
Route::get('/movies/{slug}', [MovieController::class, 'show'])->name('movies.show');
Route::get('/movies/{movie:slug}/preview', [HomeController::class, 'preview'])
    ->name('movies.preview')
    ->middleware('throttle:60,1');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:auth');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth');
Route::get('/payments/return', [PesapalController::class, 'handleReturn'])->name('payments.return');
Route::post('/payments/ipn', [PesapalController::class, 'handleIpn'])->name('payments.ipn');
Route::get('/payments/success', [CheckoutController::class, 'success'])->name('payments.success');

Route::middleware(['streaming.access'])->group(function () {
    Route::get('/watch/movies/{movie:slug}', [WatchController::class, 'movie'])->name('watch.movie');
    Route::get('/watch/episodes/{episode:slug}', [WatchController::class, 'episode'])->name('watch.episode');
    Route::post('/watch/progress', [WatchController::class, 'progress'])->name('watch.progress');
    Route::get('/stream/{token}', [StreamController::class, 'stream'])->name('stream');
});

Route::get('/account', [AccountController::class, 'index'])->name('account');

Route::middleware('auth')->group(function () {
    Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
    Route::post('/downloads/request', [DownloadController::class, 'store'])->name('downloads.request');
    Route::post('/downloads/{downloadRequest}/token', [DownloadController::class, 'token'])
        ->name('downloads.token');
    Route::post('/downloads/{downloadRequest}/pay', [DownloadController::class, 'pay'])
        ->name('downloads.pay');
    Route::get('/download/{token}', [DownloadController::class, 'download'])
        ->name('downloads.download')
        ->middleware(['throttle.downloads']);
});

require __DIR__.'/admin.php';
