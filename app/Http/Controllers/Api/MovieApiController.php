<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query()->with('genres');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->string('search').'%');
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

        return response()->json($query->latest()->paginate(20));
    }

    public function show(string $slug)
    {
        $movie = Movie::query()
            ->with(['genres', 'castMembers', 'primaryVideo'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($movie);
    }
}
