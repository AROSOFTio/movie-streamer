<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EpisodeStoreRequest;
use App\Http\Requests\EpisodeUpdateRequest;
use App\Models\Cast;
use App\Models\Episode;
use App\Models\Genre;
use App\Models\Language;
use App\Models\Series;
use App\Models\Vj;
use App\Models\VideoFile;
use App\Services\Media\MediaService;
use Illuminate\Support\Str;

class EpisodeAdminController extends Controller
{
    public function index()
    {
        $episodes = Episode::query()->with('series')->latest()->paginate(20);

        return view('admin.episodes.index', [
            'episodes' => $episodes,
        ]);
    }

    public function create()
    {
        return view('admin.episodes.create', [
            'series' => Series::all(),
            'genres' => Genre::all(),
            'casts' => Cast::all(),
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function store(EpisodeStoreRequest $request, MediaService $media)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title'].'-'.$data['season_number'].'-'.$data['episode_number']);
        $data = $this->hydrateLanguage($data);

        $data['poster_path'] = $media->storePoster($request->file('poster'));
        $data['backdrop_path'] = $media->storeBackdrop($request->file('backdrop'));

        $episode = Episode::create($data);
        $episode->genres()->sync($data['genres'] ?? []);
        $episode->castMembers()->sync($data['casts'] ?? []);
        $episode->vjs()->sync($data['vjs'] ?? []);

        if ($request->hasFile('video')) {
            $path = $media->storeVideo($request->file('video'));
            $episode->videoFiles()->update(['is_primary' => false]);
            VideoFile::create([
                'owner_type' => Episode::class,
                'owner_id' => $episode->id,
                'disk' => 'local',
                'path' => $path,
                'type' => 'mp4',
                'quality' => $this->normalizeQuality($request->input('video_quality')),
                'size_bytes' => $request->file('video')->getSize(),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.episodes.index')->with('status', 'Episode created.');
    }

    public function edit(Episode $episode)
    {
        $episode->load('genres', 'castMembers', 'vjs', 'language');

        return view('admin.episodes.edit', [
            'episode' => $episode,
            'series' => Series::all(),
            'genres' => Genre::all(),
            'casts' => Cast::all(),
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function update(EpisodeUpdateRequest $request, Episode $episode, MediaService $media)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title'].'-'.$data['season_number'].'-'.$data['episode_number']);
        $data = $this->hydrateLanguage($data);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $media->storePoster($request->file('poster'));
        }

        if ($request->hasFile('backdrop')) {
            $data['backdrop_path'] = $media->storeBackdrop($request->file('backdrop'));
        }

        $episode->update($data);
        $episode->genres()->sync($data['genres'] ?? []);
        $episode->castMembers()->sync($data['casts'] ?? []);
        $episode->vjs()->sync($data['vjs'] ?? []);

        if ($request->hasFile('video')) {
            $path = $media->storeVideo($request->file('video'));
            $episode->videoFiles()->update(['is_primary' => false]);
            VideoFile::create([
                'owner_type' => Episode::class,
                'owner_id' => $episode->id,
                'disk' => 'local',
                'path' => $path,
                'type' => 'mp4',
                'quality' => $this->normalizeQuality($request->input('video_quality')),
                'size_bytes' => $request->file('video')->getSize(),
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.episodes.index')->with('status', 'Episode updated.');
    }

    public function destroy(Episode $episode)
    {
        $episode->delete();

        return redirect()->route('admin.episodes.index')->with('status', 'Episode deleted.');
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
