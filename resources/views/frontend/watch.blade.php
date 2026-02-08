@extends('layouts.app')

@section('content')
    @php
        $watchType = $watchable instanceof \App\Models\Episode ? 'episode' : 'movie';
    @endphp

    @php
        $streamSources = $streamSources ?? [];
        $defaultStreamSource = $defaultStreamSource ?? null;
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl">{{ $title }}</h1>
                <p class="text-sm text-slate-400">Enjoy the stream. Progress saves automatically.</p>
            </div>
            <a href="{{ route('browse') }}" class="text-sm text-brand hover:text-brand-dark">Back to Browse</a>
        </div>

        @if (count($streamSources) > 1)
            <div class="glass-panel flex flex-wrap items-center gap-4 rounded-2xl px-4 py-3 text-sm">
                <span class="text-xs uppercase tracking-[0.3em] text-slate-500">Quality</span>
                <select
                    data-quality-select
                    class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-sm text-white"
                >
                    @foreach ($streamSources as $source)
                        <option value="{{ $source['id'] }}" @selected($defaultStreamSource && $source['id'] === $defaultStreamSource['id'])>
                            {{ $source['label'] }}
                        </option>
                    @endforeach
                </select>
                <span class="text-xs text-slate-400">Switch quality if multiple files are available.</span>
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-white/10 bg-black">
            <video
                class="w-full"
                controls
                playsinline
                preload="metadata"
                data-watch-player
                data-watchable-type="{{ $watchType }}"
                data-watchable-id="{{ $watchable->id }}"
                data-stream-sources='@json($streamSources)'
                data-stream-default="{{ $defaultStreamSource['id'] ?? '' }}"
                data-remaining-seconds="{{ $remainingSeconds ?? '' }}"
                data-account-url="{{ route('account') }}"
            >
                <source src="{{ $defaultStreamSource['url'] ?? route('stream', $streamToken) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="glass-panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Current Progress</p>
                <p class="mt-2 text-2xl">
                    {{ $watchHistory?->progress_percent ?? 0 }}%
                </p>
            </div>
            <div class="glass-panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Free Time Left</p>
                <p class="mt-2 text-2xl" data-free-time-left>
                    {{ $remainingSeconds !== null ? ceil($remainingSeconds / 60).' min' : 'Unlimited' }}
                </p>
            </div>
            <div class="glass-panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Language</p>
                <p class="mt-2 text-2xl">{{ $watchable->language_label ?? 'English' }}</p>
            </div>
            <div class="glass-panel rounded-2xl p-4">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Rating</p>
                <p class="mt-2 text-2xl">{{ $watchable->age_rating ?? 'PG-13' }}</p>
            </div>
        </div>
    </div>
@endsection
