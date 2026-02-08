@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit VJ</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.vjs.update', $vj) }}">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="name" value="{{ old('name', $vj->name) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $vj->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <div>
            <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Language</label>
            <select name="language_id" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="">Select Language</option>
                @foreach ($languages as $language)
                    <option value="{{ $language->id }}" @selected(old('language_id', $vj->language_id) == $language->id)>{{ $language->name }}</option>
                @endforeach
            </select>
        </div>
        <textarea name="bio" rows="4" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('bio', $vj->bio) }}</textarea>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" @checked($vj->is_active) class="rounded border-white/10 bg-white/5">
            Active
        </label>
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update VJ
        </button>
    </form>
@endsection
