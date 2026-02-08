<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GenreAdminController extends Controller
{
    public function index()
    {
        $genres = Genre::query()->orderBy('name')->paginate(20);

        return view('admin.genres.index', [
            'genres' => $genres,
        ]);
    }

    public function create()
    {
        return view('admin.genres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:genres,slug'],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        Genre::create($data);

        return redirect()->route('admin.genres.index')->with('status', 'Genre created.');
    }

    public function edit(Genre $genre)
    {
        return view('admin.genres.edit', [
            'genre' => $genre,
        ]);
    }

    public function update(Request $request, Genre $genre)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'unique:genres,slug,'.$genre->id],
        ]);

        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);

        $genre->update($data);

        return redirect()->route('admin.genres.index')->with('status', 'Genre updated.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return redirect()->route('admin.genres.index')->with('status', 'Genre deleted.');
    }
}
