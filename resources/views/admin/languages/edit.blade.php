@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit Language</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.languages.update', $language) }}">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="name" value="{{ old('name', $language->name) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $language->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" @checked($language->is_active) class="rounded border-white/10 bg-white/5">
            Active
        </label>
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update Language
        </button>
    </form>
@endsection
