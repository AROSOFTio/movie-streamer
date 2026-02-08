@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit Episode</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.episodes.update', $episode) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <select name="series_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                @foreach ($series as $show)
                    <option value="{{ $show->id }}" @selected($episode->series_id === $show->id)>{{ $show->title }}</option>
                @endforeach
            </select>
            <input type="text" name="title" value="{{ old('title', $episode->title) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $episode->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="season_number" value="{{ old('season_number', $episode->season_number) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="episode_number" value="{{ old('episode_number', $episode->episode_number) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="year" value="{{ old('year', $episode->year) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="0.1" name="rating" value="{{ old('rating', $episode->rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="duration" value="{{ old('duration', $episode->duration) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="language_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="">Select Language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" @selected(old('language_id', $episode->language_id) == $language->id)>{{ $language->name }}</option>
                @endforeach
            </select>
            <input type="text" name="country" value="{{ old('country', $episode->country) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="age_rating" value="{{ old('age_rating', $episode->age_rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="4" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('description', $episode->description) }}</textarea>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Genres</label>
                <select name="genres[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" @selected($episode->genres->pluck('id')->contains($genre->id))>{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Cast</label>
                <select name="casts[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($casts as $cast)
                        <option value="{{ $cast->id }}" @selected($episode->castMembers->pluck('id')->contains($cast->id))>{{ $cast->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
                <select name="vjs[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($vjs as $vj)
                        <option value="{{ $vj->id }}" @selected($episode->vjs->pluck('id')->contains($vj->id))>{{ $vj->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <input type="file" name="poster" class="rounded-xl border border-white/10 bg-white/5 p-2">
            <input type="file" name="backdrop" class="rounded-xl border border-white/10 bg-white/5 p-2">
            <input type="file" name="video" class="rounded-xl border border-white/10 bg-white/5 p-2">
        </div>
        <div>
            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Video Quality</label>
            <select name="video_quality" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2 text-sm">
                @php
                    $selectedQuality = old('video_quality', $episode->primaryVideo?->quality ?? '1080p');
                @endphp
                <option value="360p" @selected($selectedQuality === '360p')>360p</option>
                <option value="480p" @selected($selectedQuality === '480p')>480p</option>
                <option value="720p" @selected($selectedQuality === '720p')>720p</option>
                <option value="1080p" @selected($selectedQuality === '1080p')>1080p</option>
                <option value="1440p" @selected($selectedQuality === '1440p')>1440p</option>
                <option value="2160p" @selected($selectedQuality === '2160p' || $selectedQuality === '4k')>4K (2160p)</option>
            </select>
        </div>

        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update Episode
        </button>
    </form>
@endsection
