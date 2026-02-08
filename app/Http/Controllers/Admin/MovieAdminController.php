<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieStoreRequest;
use App\Http\Requests\MovieUpdateRequest;
use App\Models\Cast;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Movie;
use App\Models\Vj;
use App\Models\VideoFile;
use App\Services\Media\MediaService;
use Illuminate\Support\Str;

class MovieAdminController extends Controller
{
    public function index()
    {
        $movies = Movie::query()->latest()->paginate(20);

        return view('admin.movies.index', [
            'movies' => $movies,
        ]);
    }

    public function create()
    {
        return view('admin.movies.create', [
            'genres' => Genre::all(),
            'casts' => Cast::all(),
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function store(MovieStoreRequest $request, MediaService $media)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data = $this->hydrateLanguage($data);

        $data['poster_path'] = $media->storePoster($request->file('poster'));
        $data['backdrop_path'] = $media->storeBackdrop($request->file('backdrop'));

        $movie = Movie::create($data);

        $movie->genres()->sync($data['genres'] ?? []);
        $movie->castMembers()->sync($data['casts'] ?? []);
        $movie->vjs()->sync($data['vjs'] ?? []);

        if ($request->hasFile('video')) {
            $path = $media->storeVideo($request->file('video'));
            $movie->videoFiles()->update(['is_primary' => false]);
            VideoFile::create([
                'owner_type' => Movie::class,
                'owner_id' => $movie->id,
                'disk' => 'local',
                'path' => $path,
                'type' => 'mp4',
                'quality' => $this->normalizeQuality($request->input('video_quality')),
                'size_bytes' => $request->file('video')->getSize(),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.movies.index')->with('status', 'Movie created.');
    }

    public function edit(Movie $movie)
    {
        $movie->load('genres', 'castMembers', 'vjs', 'language');

        return view('admin.movies.edit', [
            'movie' => $movie,
            'genres' => Genre::all(),
            'casts' => Cast::all(),
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function update(MovieUpdateRequest $request, Movie $movie, MediaService $media)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data = $this->hydrateLanguage($data);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $media->storePoster($request->file('poster'));
        }

        if ($request->hasFile('backdrop')) {
            $data['backdrop_path'] = $media->storeBackdrop($request->file('backdrop'));
        }

        $movie->update($data);
        $movie->genres()->sync($data['genres'] ?? []);
        $movie->castMembers()->sync($data['casts'] ?? []);
        $movie->vjs()->sync($data['vjs'] ?? []);

        if ($request->hasFile('video')) {
            $path = $media->storeVideo($request->file('video'));
            $movie->videoFiles()->update(['is_primary' => false]);
            VideoFile::create([
                'owner_type' => Movie::class,
                'owner_id' => $movie->id,
                'disk' => 'local',
                'path' => $path,
                'type' => 'mp4',
                'quality' => $this->normalizeQuality($request->input('video_quality')),
                'size_bytes' => $request->file('video')->getSize(),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.movies.index')->with('status', 'Movie updated.');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect()->route('admin.movies.index')->with('status', 'Movie deleted.');
    }

    protected function normalizeQuality(?string $quality): ?string
    {
        if (! $quality) {
            return '1080p';
        }

        $quality = strtolower($quality);

        return $quality === '4k' ? '2160p' : $quality;
    }

    protected function hydrateLanguage(array $data): array
    {
        if (! empty($data['language_id'])) {
            $language = Language::find($data['language_id']);
            if ($language) {
                $data['language'] = $language->name;
            }
        }

        return $data;
    }
}
