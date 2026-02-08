@extends('layouts.app')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
        <aside class="glass-panel rounded-2xl p-6">
            <h1 class="text-2xl">Browse</h1>
            <p class="mt-1 text-xs text-slate-500">Filter and search titles.</p>
            <form class="mt-4 space-y-4" method="GET" action="{{ route('browse') }}">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search movies..."
                    class="w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm"
                />
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Genres</label>
                    <select name="genre" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm">
                        <option value="">All Genres</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre->slug }}" @selected(request('genre') === $genre->slug)>{{ $genre->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Languages</label>
                    <select name="language" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm">
                        <option value="">All Languages</option>
                        @foreach ($languages as $language)
                            <option value="{{ $language->slug }}" @selected(request('language') === $language->slug)>{{ $language->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs / Translators</label>
                    <select name="vj" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-2 text-sm">
                        <option value="">All VJs</option>
                        @foreach ($vjs as $vj)
                            <option value="{{ $vj->slug }}" @selected(request('vj') === $vj->slug)>{{ $vj->name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-300">
                    <input type="checkbox" name="featured" value="1" @checked(request('featured')) class="rounded border-white/20 bg-white/5">
                    Featured only
                </label>
                <button class="w-full rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">Apply Filters</button>
            </form>

            <div class="mt-8 space-y-2 text-sm">
                <div class="text-xs uppercase tracking-[0.3em] text-slate-500">Quick Access</div>
                <a href="#movies" class="block rounded-lg px-3 py-2 hover:bg-white/5">Movies</a>
                <a href="#series" class="block rounded-lg px-3 py-2 hover:bg-white/5">Series</a>
                <a href="#sop" class="block rounded-lg px-3 py-2 hover:bg-white/5">SOP</a>
            </div>
        </aside>

        <div class="space-y-10">
            <section id="movies">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl">Movies</h2>
                    <span class="text-xs uppercase tracking-[0.3em] text-slate-500">{{ $movies->total() }} results</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($movies as $movie)
                        <a class="card-hover glass-panel overflow-hidden rounded-2xl" href="{{ route('movies.show', $movie->slug) }}">
                            <div class="aspect-[16/9] bg-surface">
                                @php
                                    $thumb = $movie->backdrop_url ?? $movie->poster_url;
                                @endphp
                                @if ($thumb)
                                    <img src="{{ $thumb }}" alt="{{ $movie->title }}" class="h-full w-full object-cover">
                                @endif
                            </div>
                            <div class="space-y-1 p-3">
                                <h3 class="text-lg">{{ $movie->title }}</h3>
                                <p class="text-xs text-slate-400">{{ $movie->year }} • {{ $movie->age_rating }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $movies->links() }}
                </div>
            </section>

            @if ($series->count())
                <section id="series">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-2xl">Series Spotlight</h2>
                        <span class="text-xs uppercase tracking-[0.3em] text-brand">Fresh seasons</span>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        @foreach ($series as $show)
                            <div class="card-hover glass-panel overflow-hidden rounded-2xl">
                                <div class="aspect-[16/9] bg-surface">
                                    @php
                                        $thumb = $show->backdrop_url ?? $show->poster_url;
                                    @endphp
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $show->title }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="space-y-1 p-3">
                                    <h3 class="text-lg">{{ $show->title }}</h3>
                                    <p class="text-xs text-slate-400">{{ $show->year }} • {{ $show->age_rating }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            @if ($episodes->count())
                <section id="sop">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-2xl">SOP — Short Original Programs</h2>
                        <span class="text-xs uppercase tracking-[0.3em] text-brand">New drops</span>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                        @foreach ($episodes as $episode)
                            <a class="card-hover glass-panel overflow-hidden rounded-2xl" href="{{ route('watch.episode', $episode->slug) }}">
                                <div class="aspect-[16/9] bg-surface">
                                    @php
                                        $thumb = $episode->backdrop_url ?? $episode->poster_url;
                                    @endphp
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $episode->title }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="space-y-1 p-3">
                                    <h3 class="text-lg">{{ $episode->title }}</h3>
                                    <p class="text-xs text-slate-400">S{{ $episode->season_number }} • E{{ $episode->episode_number }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </div>
@endsection
