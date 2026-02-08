@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit Series</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.series.update', $series) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="title" value="{{ old('title', $series->title) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $series->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="year" value="{{ old('year', $series->year) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="0.1" name="rating" value="{{ old('rating', $series->rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="language_id" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="">Select Language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" @selected(old('language_id', $series->language_id) == $language->id)>{{ $language->name }}</option>
                @endforeach
            </select>
            <input type="text" name="country" value="{{ old('country', $series->country) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="age_rating" value="{{ old('age_rating', $series->age_rating) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="4" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('description', $series->description) }}</textarea>

        <div>
            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
            <select name="vjs[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                @foreach ($vjs as $vj)
                    <option value="{{ $vj->id }}" @selected($series->vjs->pluck('id')->contains($vj->id))>{{ $vj->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <input type="file" name="poster" class="rounded-xl border border-white/10 bg-white/5 p-2">
            <input type="file" name="backdrop" class="rounded-xl border border-white/10 bg-white/5 p-2">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="featured" value="1" @checked($series->featured) class="rounded border-white/10 bg-white/5">
            Featured
        </label>

        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update Series
        </button>
    </form>
@endsection
