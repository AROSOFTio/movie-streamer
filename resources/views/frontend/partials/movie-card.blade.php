@php
    $thumb = $movie->backdrop_url ?? $movie->poster_url;
    $languageName = $movie->language_label;
    $meta = $meta ?? null;
@endphp

<a
    class="preview-card card-hover glass-panel overflow-hidden rounded-2xl"
    href="{{ route('movies.show', $movie->slug) }}"
    data-preview-card
    data-preview-endpoint="{{ $movie->primaryVideo ? route('movies.preview', $movie->slug) : '' }}"
>
    <div class="preview-media aspect-[16/9] bg-surface">
        @if ($thumb)
            <img src="{{ $thumb }}" alt="{{ $movie->title }}" class="preview-image" data-preview-image>
        @endif
        <video class="preview-video" muted loop playsinline preload="none" data-preview-video></video>
    </div>
    <div class="space-y-1 p-3">
        <h3 class="text-lg">{{ $movie->title }}</h3>
        <p class="text-xs text-slate-400">{{ $movie->year }} | {{ $movie->age_rating ?: 'NR' }}</p>
        @if ($languageName)
            <p class="text-xs text-slate-500">{{ $languageName }}</p>
        @endif
        @if (! empty($meta))
            <p class="text-xs font-medium text-brand">{{ $meta }}</p>
        @endif
    </div>
</a>
