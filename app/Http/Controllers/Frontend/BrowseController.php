<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Series;
use App\Models\Vj;
use Illuminate\Http\Request;

class BrowseController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query()->with('genres');
        $languageSlug = $request->string('language');
        $vjSlug = $request->string('vj');
        $language = null;
        if ($languageSlug->isNotEmpty()) {
            $language = Language::query()->where('slug', $languageSlug)->first();
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('genre')) {
            $genre = $request->string('genre');
            $query->whereHas('genres', function ($q) use ($genre) {
                $q->where('slug', $genre);
            });
        }

        if ($request->boolean('featured')) {
            $query->where('featured', true);
        }

        if ($language) {
            $query->where(function ($q) use ($language) {
                $q->where('language_id', $language->id)
                    ->orWhere('language', $language->name);
            });
        }

        if ($vjSlug->isNotEmpty()) {
            $query->whereHas('vjs', function ($q) use ($vjSlug) {
                $q->where('slug', $vjSlug);
            });
        }

        $movies = $query->latest()->paginate(18)->withQueryString();
        $genres = Genre::query()->orderBy('name')->get();
        $languages = Language::query()->where('is_active', true)->orderBy('name')->get();
        $vjs = Vj::query()->where('is_active', true)->orderBy('name')->get();

        $seriesQuery = Series::query()->latest();
        if ($language) {
            $seriesQuery->where(function ($q) use ($language) {
                $q->where('language_id', $language->id)
                    ->orWhere('language', $language->name);
            });
        }
        if ($vjSlug->isNotEmpty()) {
            $seriesQuery->whereHas('vjs', function ($q) use ($vjSlug) {
                $q->where('slug', $vjSlug);
            });
        }
        $series = $seriesQuery->take(10)->get();

        $episodeQuery = Episode::query()->latest();
        if ($language) {
            $episodeQuery->where(function ($q) use ($language) {
                $q->where('language_id', $language->id)
                    ->orWhere('language', $language->name);
            });
        }
        if ($vjSlug->isNotEmpty()) {
            $episodeQuery->whereHas('vjs', function ($q) use ($vjSlug) {
                $q->where('slug', $vjSlug);
            });
        }
        $episodes = $episodeQuery->take(10)->get();

        return view('frontend.browse', [
            'movies' => $movies,
            'genres' => $genres,
            'series' => $series,
            'episodes' => $episodes,
            'languages' => $languages,
            'vjs' => $vjs,
        ]);
    }
}
