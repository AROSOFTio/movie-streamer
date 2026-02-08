<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin | {{ config('app.name', 'MovieStreaming') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-slate-100">
    <div class="min-h-screen bg-gradient-to-b from-black via-surface to-black">
        <header class="border-b border-white/10 bg-black/70 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="text-xl font-display tracking-[0.2em]">
                        ADMIN<span class="text-brand">PANEL</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-400">{{ auth()->user()->name }}</span>
                    <a href="{{ route('home') }}" class="text-sm text-brand hover:text-brand-dark">Go to Site</a>
                </div>
            </div>
        </header>

        <div class="mx-auto flex max-w-7xl gap-6 px-6 py-8">
            <aside class="w-64 shrink-0 space-y-2">
                <nav class="space-y-1 text-sm">
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <div class="pt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Content</div>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.movies.index') }}">Movies</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.series.index') }}">Series</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.episodes.index') }}">Episodes</a>
                    <div class="pt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Taxonomy</div>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.languages.index') }}">Languages</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.vjs.index') }}">VJs</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.genres.index') }}">Genres</a>
                    <div class="pt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Commerce</div>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.plans.index') }}">Plans</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.users.index') }}">Users</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.downloads.index') }}">Download Requests</a>
                    <div class="pt-4 text-xs uppercase tracking-[0.3em] text-slate-500">Reports</div>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.reports.payments') }}">Payments</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.reports.subscriptions') }}">Subscriptions</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.reports.top-watched') }}">Top Watched</a>
                    <a class="block rounded-lg px-4 py-2 hover:bg-white/5" href="{{ route('admin.reports.top-downloads') }}">Top Downloads</a>
                </nav>
            </aside>

            <main class="flex-1 space-y-6">
                @if (session('status'))
                    <div class="rounded-lg border border-brand/40 bg-brand/10 px-4 py-3 text-sm text-brand">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
