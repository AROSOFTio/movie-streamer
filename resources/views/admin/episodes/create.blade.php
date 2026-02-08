@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Create Episode</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.episodes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <select name="series_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                @foreach ($series as $show)
                    <option value="{{ $show->id }}">{{ $show->title }}</option>
                @endforeach
            </select>
            <input type="text" name="title" placeholder="Title" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" placeholder="Slug (optional)" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="season_number" placeholder="Season" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="episode_number" placeholder="Episode" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="year" placeholder="Year" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="0.1" name="rating" placeholder="Rating" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="duration" placeholder="Duration (min)" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="language_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="">Select Language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}">{{ $language->name }}</option>
                @endforeach
            </select>
            <input type="text" name="country" placeholder="Country" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="age_rating" placeholder="Age Rating" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="4" placeholder="Description" class="w-full rounded-xl border border-white/10 bg-white/5 p-3"></textarea>

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Genres</label>
                <select name="genres[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Cast</label>
                <select name="casts[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($casts as $cast)
                        <option value="{{ $cast->id }}">{{ $cast->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
                <select name="vjs[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                    @foreach ($vjs as $vj)
                        <option value="{{ $vj->id }}">{{ $vj->name }}</option>
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
                <option value="360p">360p</option>
                <option value="480p">480p</option>
                <option value="720p">720p</option>
                <option value="1080p" selected>1080p</option>
                <option value="1440p">1440p</option>
                <option value="2160p">4K (2160p)</option>
            </select>
        </div>

        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Save Episode
        </button>
    </form>
@endsection
