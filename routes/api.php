<?php

use App\Http\Controllers\Api\AccountApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DownloadApiController;
use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use Illuminate\Support\Facades\Route;

Route::get('/movies', [MovieApiController::class, 'index']);
Route::get('/movies/{slug}', [MovieApiController::class, 'show']);

Route::post('/auth/login', [AuthApiController::class, 'login'])->middleware('throttle:auth');
Route::post('/auth/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/subscriptions/checkout', [SubscriptionApiController::class, 'checkout']);
    Route::post('/downloads/request', [DownloadApiController::class, 'request']);
    Route::get('/account/subscription', [AccountApiController::class, 'subscription']);
});
