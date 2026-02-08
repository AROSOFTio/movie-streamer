@extends('layouts.app')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="space-y-6">
            <div class="relative overflow-hidden rounded-3xl bg-black/60">
                @if ($movie->backdrop_url)
                    <img src="{{ $movie->backdrop_url }}" alt="{{ $movie->title }}" class="h-72 w-full object-cover opacity-70">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>
                <div class="absolute bottom-6 left-6 space-y-3">
                    <h1 class="text-4xl md:text-5xl">{{ $movie->title }}</h1>
                    <div class="flex flex-wrap gap-3 text-xs text-slate-400">
                        <span>{{ $movie->year }}</span>
                        <span>{{ $movie->age_rating }}</span>
                        <span>{{ $movie->duration }} min</span>
                        <span>{{ number_format($movie->rating, 1) }} ★</span>
                    </div>
                </div>
            </div>

            <p class="text-sm text-slate-300">{{ $movie->description }}</p>

            <div class="flex flex-wrap gap-2">
                @foreach ($movie->genres as $genre)
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs">{{ $genre->name }}</span>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-4 text-sm text-slate-300">
                @if ($movie->language_label)
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Language</span>
                        <span>{{ $movie->language_label }}</span>
                    </div>
                @endif
                @if ($movie->vjs->count())
                    <div class="flex items-center gap-2">
                        <span class="text-xs uppercase tracking-[0.3em] text-slate-500">VJs</span>
                        <span>{{ $movie->vjs->pluck('name')->join(', ') }}</span>
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-xl">Cast</h3>
                <div class="mt-3 flex flex-wrap gap-2 text-sm text-slate-300">
                    @foreach ($movie->castMembers as $cast)
                        <span class="rounded-full bg-white/5 px-3 py-1">{{ $cast->name }}</span>
                    @endforeach
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <div class="glass-panel rounded-2xl p-6">
                <h2 class="text-2xl">Watch Options</h2>
                <p class="mt-2 text-sm text-slate-400">Enjoy free streaming up to 1 hour per day, even without an account. Subscribe for unlimited access.</p>
                <div class="mt-4 flex flex-col gap-3">
                    <a href="{{ route('watch.movie', $movie->slug) }}" class="rounded-xl bg-brand px-4 py-3 text-center text-sm font-semibold text-black hover:bg-brand-dark">
                        Watch Now
                    </a>
                    <a href="{{ route('account') }}" class="rounded-xl border border-white/10 px-4 py-3 text-center text-sm hover:border-brand hover:text-brand">
                        View Plans
                    </a>
                </div>
            </div>

            <div class="glass-panel rounded-2xl p-6">
                <h2 class="text-2xl">Download Petition</h2>
                <p class="mt-2 text-sm text-slate-400">Request offline access. Admin approval required.</p>

                @auth
                    @if ($downloadRequest)
                        <div class="mt-4 rounded-xl border border-white/10 bg-white/5 p-4 text-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full bg-brand/20 px-3 py-1 text-xs text-brand">
                                    {{ strtoupper($downloadRequest->status) }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ $downloadRequest->isPaid() ? 'Payment received' : 'Payment required' }}
                                </span>
                            </div>
                            @if (! auth()->user()->hasActiveSubscription() && ! $downloadRequest->isPaid())
                                <form class="mt-4" method="POST" action="{{ route('downloads.pay', $downloadRequest->id) }}">
                                    @csrf
                                    <button class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                                        Pay UGX {{ number_format(config('downloads.price', 500), 0) }} to Download
                                    </button>
                                </form>
                            @endif
                        </div>
                    @else
                        <form class="mt-4 space-y-3" method="POST" action="{{ route('downloads.request') }}">
                            @csrf
                            <input type="hidden" name="type" value="movie">
                            <input type="hidden" name="id" value="{{ $movie->id }}">
                            <textarea name="reason" rows="3" class="w-full rounded-xl border border-white/10 bg-white/5 p-3 text-sm" placeholder="Why do you need offline access?"></textarea>
                            <button class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                                Request Download
                            </button>
                        </form>
                    @endif
                @else
                    <p class="mt-4 text-sm text-slate-400">Login to request downloads.</p>
                @endauth
            </div>
        </aside>
    </div>
@endsection
