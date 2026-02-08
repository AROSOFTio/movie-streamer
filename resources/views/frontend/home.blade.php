@extends('layouts.app')

@section('content')
    <section class="relative overflow-hidden rounded-3xl bg-black/80 p-8 md:p-12">
        @if ($featured)
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-black via-black/80 to-transparent"></div>
                @if ($featured->backdrop_url)
                    <img src="{{ $featured->backdrop_url }}" alt="{{ $featured->title }}" class="h-full w-full object-cover opacity-60">
                @endif
            </div>
            <div class="relative z-10 max-w-xl space-y-4">
                <span class="inline-flex items-center gap-2 rounded-full bg-brand/20 px-3 py-1 text-xs uppercase tracking-[0.3em] text-brand">
                    Featured Translation
                </span>
                <h1 class="text-4xl md:text-6xl">{{ $featured->title }}</h1>
                <p class="text-sm text-slate-300">{{ $featured->description }}</p>
                <p class="text-xs uppercase tracking-[0.3em] text-brand">Free stream: 1 hour daily, even as a guest</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('watch.movie', $featured->slug) }}" class="rounded-full bg-brand px-6 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                        Watch Now
                    </a>
                    <a href="{{ route('movies.show', $featured->slug) }}" class="rounded-full border border-white/20 px-6 py-3 text-sm text-white hover:border-brand hover:text-brand">
                        View Details
                    </a>
                </div>
            </div>
        @else
            <div class="relative z-10 max-w-xl space-y-4">
                <h1 class="text-4xl md:text-6xl">Ateso and Luganda translated cinema.</h1>
                <p class="text-sm text-slate-300">Stream for free up to 1 hour per day, then subscribe when you are ready.</p>
                <a href="{{ route('browse') }}" class="rounded-full bg-brand px-6 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                    Start Browsing
                </a>
            </div>
        @endif
    </section>

    @auth
        @if ($continueWatching->isNotEmpty())
            <section class="mt-10">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-2xl">Continue Watching</h2>
                    <span class="text-xs uppercase tracking-[0.3em] text-brand">Your progress</span>
                </div>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    @foreach ($continueWatching as $history)
                        @php
                            $item = $history->watchable;
                            $thumb = $item?->backdrop_url ?? $item?->poster_url;
                        @endphp
                        @if ($item)
                            <a class="card-hover glass-panel overflow-hidden rounded-2xl" href="{{ $item instanceof \App\Models\Movie ? route('watch.movie', $item->slug) : route('watch.episode', $item->slug) }}">
                                <div class="aspect-[16/9] bg-surface">
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

    @if ($trending->isNotEmpty())
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl">Trending</h2>
                <a href="{{ route('browse') }}" class="text-sm text-brand hover:text-brand-dark">Browse all</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($trending as $movie)
                    @include('frontend.partials.movie-card', ['movie' => $movie, 'meta' => null])
                @endforeach
            </div>
        </section>
    @endif

    @if ($mostStreamed->isNotEmpty())
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl">Most Streamed</h2>
                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Top watched today</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($mostStreamed as $movie)
                    @include('frontend.partials.movie-card', [
                        'movie' => $movie,
                        'meta' => (int) ($movie->stream_count ?? 0) > 0 ? number_format((int) $movie->stream_count).' streams' : null,
                    ])
                @endforeach
            </div>
        </section>
    @endif

    @if ($mostDownloaded->isNotEmpty())
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl">Most Downloaded</h2>
                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Offline favorites</span>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($mostDownloaded as $movie)
                    @include('frontend.partials.movie-card', [
                        'movie' => $movie,
                        'meta' => (int) ($movie->download_count_total ?? 0) > 0 ? number_format((int) $movie->download_count_total).' downloads' : null,
                    ])
                @endforeach
            </div>
        </section>
    @endif

    @foreach ($languageRows as $language)
        <section class="mt-10">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl">{{ $language->name }} Picks</h2>
                <a href="{{ route('browse', ['language' => $language->slug]) }}" class="text-sm text-brand hover:text-brand-dark">Explore</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($language->movies as $movie)
                    @include('frontend.partials.movie-card', ['movie' => $movie, 'meta' => null])
                @endforeach
            </div>
        </section>
    @endforeach
@endsection
