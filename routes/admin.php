<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DownloadRequestAdminController;
use App\Http\Controllers\Admin\EpisodeAdminController;
use App\Http\Controllers\Admin\GenreAdminController;
use App\Http\Controllers\Admin\LanguageAdminController;
use App\Http\Controllers\Admin\MovieAdminController;
use App\Http\Controllers\Admin\PlanAdminController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SeriesAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\VjAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('movies', MovieAdminController::class);
        Route::resource('series', SeriesAdminController::class);
        Route::resource('episodes', EpisodeAdminController::class);
        Route::resource('languages', LanguageAdminController::class)->except(['show']);
        Route::resource('vjs', VjAdminController::class)->except(['show']);
        Route::resource('genres', GenreAdminController::class)->except(['show']);
        Route::resource('plans', PlanAdminController::class)->except(['show']);

        Route::get('users', [UserAdminController::class, 'index'])->name('users.index');
        Route::post('users/{user}/role', [UserAdminController::class, 'updateRole'])->name('users.role');

        Route::get('downloads', [DownloadRequestAdminController::class, 'index'])->name('downloads.index');
        Route::post('downloads/{downloadRequest}/approve', [DownloadRequestAdminController::class, 'approve'])
            ->name('downloads.approve');
        Route::post('downloads/{downloadRequest}/reject', [DownloadRequestAdminController::class, 'reject'])
            ->name('downloads.reject');

        Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
        Route::get('reports/subscriptions', [ReportController::class, 'subscriptions'])->name('reports.subscriptions');
        Route::get('reports/top-watched', [ReportController::class, 'topWatched'])->name('reports.top-watched');
        Route::get('reports/top-downloads', [ReportController::class, 'topDownloads'])->name('reports.top-downloads');
    });
