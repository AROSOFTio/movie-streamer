<?php

namespace App\Providers;

use App\Models\DownloadRequest;
use App\Models\Movie;
use App\Policies\DownloadPolicy;
use App\Policies\MoviePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureAssetUrlForProjectRoot();

        Gate::policy(Movie::class, MoviePolicy::class);
        Gate::policy(DownloadRequest::class, DownloadPolicy::class);

        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }

    protected function configureAssetUrlForProjectRoot(): void
    {
        if (config('app.asset_url')) {
            return;
        }

        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? null;
        if (! $documentRoot) {
            return;
        }

        $documentRoot = realpath($documentRoot);
        $basePath = realpath(base_path());
        $publicPath = realpath(public_path());

        if (! $documentRoot || ! $basePath || ! $publicPath) {
            return;
        }

        if ($documentRoot !== $basePath) {
            return;
        }

        if ($publicPath === $documentRoot) {
            return;
        }

        config(['app.asset_url' => '/public']);
    }
}
