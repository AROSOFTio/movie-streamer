<?php

namespace App\Http\Middleware;

use App\Models\DownloadToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ThrottleDownloads
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $limit = (int) config('downloads.max_per_day', 3);
        $usedToday = DownloadToken::query()
            ->where('user_id', $user->id)
            ->whereNotNull('used_at')
            ->whereDate('used_at', today())
            ->count();

        if ($usedToday >= $limit) {
            abort(429, 'Daily download limit reached.');
        }

        return $next($request);
    }
}
