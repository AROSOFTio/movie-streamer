<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DownloadRequest;
use App\Models\Movie;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    public function show(string $slug)
    {
        $movie = Movie::query()
            ->with(['genres', 'castMembers', 'primaryVideo', 'vjs', 'language'])
            ->where('slug', $slug)
            ->firstOrFail();

        $downloadRequest = null;
        $watchHistory = null;

        if (Auth::check()) {
            $downloadRequest = DownloadRequest::query()
                ->where('user_id', Auth::id())
                ->where('downloadable_type', Movie::class)
                ->where('downloadable_id', $movie->id)
                ->latest()
                ->first();

            $watchHistory = WatchHistory::query()
                ->where('user_id', Auth::id())
                ->where('watchable_type', Movie::class)
                ->where('watchable_id', $movie->id)
                ->first();
        }

        return view('frontend.movie', [
            'movie' => $movie,
            'downloadRequest' => $downloadRequest,
            'watchHistory' => $watchHistory,
        ]);
    }
}
