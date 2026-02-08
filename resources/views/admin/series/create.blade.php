@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Create Series</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.series.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="title" placeholder="Title" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" placeholder="Slug (optional)" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" name="year" placeholder="Year" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="0.1" name="rating" placeholder="Rating" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
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

        <div>
            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
            <select name="vjs[]" multiple class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 p-2">
                @foreach ($vjs as $vj)
                    <option value="{{ $vj->id }}">{{ $vj->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <input type="file" name="poster" class="rounded-xl border border-white/10 bg-white/5 p-2">
            <input type="file" name="backdrop" class="rounded-xl border border-white/10 bg-white/5 p-2">
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="featured" value="1" class="rounded border-white/10 bg-white/5">
            Featured
        </label>

        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Save Series
        </button>
    </form>
@endsection
