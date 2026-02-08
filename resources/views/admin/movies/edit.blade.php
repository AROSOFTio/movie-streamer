@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit Movie</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="title" value="{{ old('title', $movie->title) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $movie->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="year" value="{{ old('year', $movie->year) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="0.1" name="rating" value="{{ old('rating', $movie->rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="duration" value="{{ old('duration', $movie->duration) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="language_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="">Select Language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" @selected(old('language_id', $movie->language_id) == $language->id)>{{ $language->name }}</option>
                @endforeach
            </select>
            <input type="text" name="country" value="{{ old('country', $movie->country) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="age_rating" value="{{ old('age_rating', $movie->age_rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="4" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('description', $movie->description) }}</textarea>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Genres</label>
                <select name="genres[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" @selected($movie->genres->pluck('id')->contains($genre->id))>{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Cast</label>
                <select name="casts[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($casts as $cast)
                        <option value="{{ $cast->id }}" @selected($movie->castMembers->pluck('id')->contains($cast->id))>{{ $cast->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
                <select name="vjs[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($vjs as $vj)
                        <option value="{{ $vj->id }}" @selected($movie->vjs->pluck('id')->contains($vj->id))>{{ $vj->name }}</option>
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
                    $selectedQuality = old('video_quality', $movie->primaryVideo?->quality ?? '1080p');
                @endphp
                <option value="360p" @selected($selectedQuality === '360p')>360p</option>
                <option value="480p" @selected($selectedQuality === '480p')>480p</option>
                <option value="720p" @selected($selectedQuality === '720p')>720p</option>
                <option value="1080p" @selected($selectedQuality === '1080p')>1080p</option>
                <option value="1440p" @selected($selectedQuality === '1440p')>1440p</option>
                <option value="2160p" @selected($selectedQuality === '2160p' || $selectedQuality === '4k')>4K (2160p)</option>
            </select>
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="featured" value="1" @checked($movie->featured) class="rounded border-white/10 bg-white/5">
            Featured
        </label>

        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update Movie
        </button>
    </form>
@endsection
