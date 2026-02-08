<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Series;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Movie::query()
            ->with('genres')
            ->where('featured', true)
            ->latest()
            ->first();

        $trending = Movie::query()
            ->with('genres')
            ->latest()
            ->take(12)
            ->get();

        $genres = Genre::query()
            ->with(['movies' => function ($query) {
                $query->latest()->take(12);
            }])
            ->get();

        $series = Series::query()->latest()->take(10)->get();
        $episodes = Episode::query()->latest()->take(10)->get();

        $languageTrending = Language::query()
            ->where('is_active', true)
            ->with(['movies' => function ($query) {
                $query->latest()->take(10);
            }])
            ->orderBy('name')
            ->get()
            ->filter(function ($language) {
                return $language->movies->count() > 0;
            });

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
            'genres' => $genres,
            'continueWatching' => $continueWatching,
            'series' => $series,
            'episodes' => $episodes,
            'languageTrending' => $languageTrending,
        ]);
    }
}
