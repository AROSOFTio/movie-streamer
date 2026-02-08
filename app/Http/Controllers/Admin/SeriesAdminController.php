<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesStoreRequest;
use App\Http\Requests\SeriesUpdateRequest;
use App\Models\Language;
use App\Models\Series;
use App\Models\Vj;
use App\Services\Media\MediaService;
use Illuminate\Support\Str;

class SeriesAdminController extends Controller
{
    public function index()
    {
        $series = Series::query()->latest()->paginate(20);

        return view('admin.series.index', [
            'series' => $series,
        ]);
    }

    public function create()
    {
        return view('admin.series.create', [
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function store(SeriesStoreRequest $request, MediaService $media)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['title']);
        $data = $this->hydrateLanguage($data);

        $data['poster_path'] = $media->storePoster($request->file('poster'));
        $data['backdrop_path'] = $media->storeBackdrop($request->file('backdrop'));

        $series = Series::create($data);
        $series->vjs()->sync($data['vjs'] ?? []);

        return redirect()->route('admin.series.index')->with('status', 'Series created.');
    }

    public function edit(Series $series)
    {
        $series->load('vjs', 'language');

        return view('admin.series.edit', [
            'series' => $series,
            'languages' => Language::query()->orderBy('name')->get(),
            'vjs' => Vj::query()->orderBy('name')->get(),
        ]);
    }

    public function update(SeriesUpdateRequest $request, Series $series, MediaService $media)
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

        $series->update($data);
        $series->vjs()->sync($data['vjs'] ?? []);

        return redirect()->route('admin.series.index')->with('status', 'Series updated.');
    }

    public function destroy(Series $series)
    {
        $series->delete();

        return redirect()->route('admin.series.index')->with('status', 'Series deleted.');
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
