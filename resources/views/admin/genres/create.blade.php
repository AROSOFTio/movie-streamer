@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Create Genre</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.genres.store') }}">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="name" placeholder="Genre name" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" placeholder="Slug (optional)" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Save Genre
        </button>
    </form>
@endsection
