@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-black/80 p-8 md:p-12">
        @if ($featured)
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/70 to-transparent"></div>
                @if ($featured->backdrop_url)
                    <img src="{{ $featured->backdrop_url }}" alt="{{ $featured->title }}" class="h-full w-full object-cover opacity-60">
                @endif
            </div>
            <div class="relative z-10 max-w-xl space-y-4">
                <span class="inline-flex items-center gap-2 rounded-full bg-brand/20 px-3 py-1 text-xs uppercase tracking-[0.3em] text-brand">
                    Featured
                </span>
                <h1 class="text-4xl md:text-6xl">{{ $featured->title }}</h1>
                <p class="text-sm text-slate-300">{{ $featured->description }}</p>
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-400">
                    <span>{{ $featured->year }}</span>
                    <span>{{ $featured->age_rating }}</span>
                    <span>{{ $featured->duration }} min</span>
                    <span>{{ number_format($featured->rating, 1) }} ★</span>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('movies.show', $featured->slug) }}" class="rounded-full bg-brand px-6 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                        View Details
                    </a>
                    <a href="{{ route('watch.movie', $featured->slug) }}" class="rounded-full border border-white/20 px-6 py-3 text-sm text-white hover:border-brand hover:text-brand">
                        Watch Now
                    </a>
                </div>
                <p class="text-xs uppercase tracking-[0.3em] text-brand">Free 1 hour streaming daily</p>
            </div>
        @else
            <div class="relative z-10 space-y-4">
                <h1 class="text-4xl md:text-6xl">Cinematic stories, delivered.</h1>
                <p class="text-sm text-slate-300">Browse curated collections and pick up right where you left off.</p>
                <a href="{{ route('browse') }}" class="rounded-full bg-brand px-6 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                    Start Browsing
                </a>
            </div>
        @endif
    </section>

    <section class="mt-10 grid gap-4 md:grid-cols-3">
        <a href="{{ route('browse') }}#movies" class="glass-panel card-hover rounded-2xl p-6">
            <h2 class="text-2xl">Movies</h2>
            <p class="mt-2 text-sm text-slate-400">Blockbusters and indie gems.</p>
        </a>
        <a href="{{ route('browse') }}#series" class="glass-panel card-hover rounded-2xl p-6">
            <h2 class="text-2xl">Series</h2>
            <p class="mt-2 text-sm text-slate-400">Binge-ready collections.</p>
        </a>
        <a href="{{ route('browse') }}#sop" class="glass-panel card-hover rounded-2xl p-6">
            <h2 class="text-2xl">SOP</h2>
            <p class="mt-2 text-sm text-slate-400">Short original programs.</p>
        </a>
    </section>

    @auth
        @if ($continueWatching->count())
            <section class="mt-10">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl">Continue Watching</h2>
                    <span class="text-xs uppercase tracking-[0.3em] text-brand">Because you started</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($continueWatching as $history)
                        @php
                            $item = $history->watchable;
                        @endphp
                        @if ($item)
                            <a class="card-hover glass-panel overflow-hidden rounded-2xl" href="{{ $item instanceof \App\Models\Movie ? route('movies.show', $item->slug) : route('watch.episode', $item->slug) }}">
                                <div class="aspect-[16/9] bg-surface">
                                    @php
                                        $thumb = $item->backdrop_url ?? $item->poster_url;
                                    @endphp
                                    @if ($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    @endif
                                </div>
                                <div class="space-y-2 p-3">
                                    <h3 class="text-lg">{{ $item->title }}</h3>
                                    <div class="h-1 w-full rounded-full bg-white/10">
                                        <div class="h-1 rounded-full bg-brand" style="width: {{ $history->progress_percent }}%"></div>
                                    </div>
                                    <p class="text-xs text-slate-400">{{ $history->progress_percent }}% watched</p>
                                </div>
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    @endauth

    <section class="mt-10">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-2xl">Trending Now</h2>
            <a href="{{ route('browse') }}" class="text-sm text-brand hover:text-brand-dark">See all</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ($trending as $movie)
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
    </section>

    @if ($languageTrending->count())
        @foreach ($languageTrending as $language)
            <section class="mt-10">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl">Trending in {{ $language->name }}</h2>
                    <a href="{{ route('browse', ['language' => $language->slug]) }}" class="text-sm text-brand hover:text-brand-dark">Explore</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($language->movies as $movie)
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
                                <p class="text-xs text-slate-400">{{ $movie->year }} - {{ $movie->age_rating }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endforeach
    @endif

    @if ($series->count())
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl">Series Spotlight</h2>
                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Fresh seasons</span>
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
        <section class="mt-10">
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

    @foreach ($genres as $genre)
        @if ($genre->movies->count())
            <section class="mt-10">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl">{{ $genre->name }}</h2>
                    <a href="{{ route('browse', ['genre' => $genre->slug]) }}" class="text-sm text-brand hover:text-brand-dark">Explore</a>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($genre->movies as $movie)
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
            </section>
        @endif
    @endforeach
@endsection
