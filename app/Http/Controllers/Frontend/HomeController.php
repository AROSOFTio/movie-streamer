<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Movie;
use App\Models\WatchHistory;
use App\Services\Streaming\StreamTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Movie::query()
            ->with(['genres', 'primaryVideo', 'language'])
            ->where('featured', true)
            ->latest()
            ->first();

        $trending = Movie::query()
            ->with(['primaryVideo', 'language'])
            ->latest()
            ->take(12)
            ->get();

        $mostStreamed = Movie::query()
            ->with(['primaryVideo', 'language'])
            ->withCount('watchHistories as stream_count')
            ->orderByDesc('stream_count')
            ->latest()
            ->take(12)
            ->get()
            ->filter(fn (Movie $movie) => (int) ($movie->stream_count ?? 0) > 0)
            ->values();

        if ($mostStreamed->isEmpty()) {
            $mostStreamed = $trending->take(10)->values();
        }

        $mostDownloaded = Movie::query()
            ->with(['primaryVideo', 'language'])
            ->withSum('downloadRequests as download_count_total', 'download_count')
            ->orderByDesc('download_count_total')
            ->latest()
            ->take(12)
            ->get()
            ->filter(fn (Movie $movie) => (int) ($movie->download_count_total ?? 0) > 0)
            ->values();

        if ($mostDownloaded->isEmpty()) {
            $mostDownloaded = $trending->take(10)->values();
        }

        $languageRows = Language::query()
            ->where('is_active', true)
            ->with(['movies' => function ($query) {
                $query->with(['primaryVideo', 'language'])->latest()->take(10);
            }])
            ->orderByRaw("case when lower(name) = 'ateso' then 0 when lower(name) = 'luganda' then 1 else 2 end")
            ->orderBy('name')
            ->get()
            ->filter(fn (Language $language) => $language->movies->isNotEmpty())
            ->values();

        $continueWatching = collect();
        if (Auth::check()) {
            $continueWatching = WatchHistory::query()
                ->with('watchable')
                ->where('user_id', Auth::id())
                ->latest('last_watched_at')
                ->take(10)
                ->get();
        }

        return view('frontend.home', [
            'featured' => $featured,
            'trending' => $trending,
            'mostStreamed' => $mostStreamed,
            'mostDownloaded' => $mostDownloaded,
            'languageRows' => $languageRows,
            'continueWatching' => $continueWatching,
        ]);
    }

    public function preview(Request $request, Movie $movie, StreamTokenService $streamTokenService)
    {
        $movie->loadMissing('primaryVideo');

        if (! $movie->primaryVideo) {
            return response()->json([
                'message' => 'Preview is not available for this title.',
            ], 404);
        }

        $streamToken = $streamTokenService->create(
            $request->user(),
            $movie->primaryVideo,
            $request->session()->getId()
        );

        return response()->json([
            'url' => route('stream', $streamToken->token),
        ]);
    }
}
