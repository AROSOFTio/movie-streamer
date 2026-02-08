<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MovieStreaming') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-black text-slate-100">
    <div class="min-h-screen bg-gradient-to-b from-black via-surface to-black">
        <nav class="sticky top-0 z-50 border-b border-white/10 bg-black/60 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-2xl font-display text-white tracking-[0.2em]">
                        MOVIE<span class="text-brand">STREAM</span>
                    </a>
                    <div class="hidden md:flex items-center gap-4 text-sm">
                        <a href="{{ route('home') }}" class="hover:text-brand">Home</a>
                        <a href="{{ route('browse') }}" class="hover:text-brand">Browse</a>
                        @auth
                            <a href="{{ route('downloads.index') }}" class="hover:text-brand">Downloads</a>
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand">Admin</a>
                            @endif
                        @endauth
                        <a href="{{ route('account') }}" class="hover:text-brand">Plans</a>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <form action="{{ route('browse') }}" method="GET" class="hidden md:block">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search..."
                            class="w-56 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm text-white placeholder:text-slate-400 focus:border-brand focus:outline-none"
                        />
                    </form>
                    @auth
                        <span class="hidden sm:block text-sm text-slate-300">Hi, {{ auth()->user()->name }}</span>
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-brand/40 px-4 py-2 text-xs uppercase tracking-[0.3em] text-brand hover:border-brand hover:text-brand">
                                Admin
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="rounded-full border border-white/10 px-4 py-2 text-sm hover:border-brand hover:text-brand">
                                Logout
                            </button>
                        </form>
                    @else
                        @if (session('free_time_expired'))
                            <a href="{{ route('login') }}" class="rounded-full border border-white/10 px-4 py-2 text-sm hover:border-brand hover:text-brand">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="rounded-full bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
                                Join Now
                            </a>
                        @else
                            <span class="rounded-full border border-brand/40 bg-brand/10 px-4 py-2 text-xs uppercase tracking-[0.3em] text-brand">
                                Free 1hr/day
                            </span>
                        @endif
                    @endauth
                </div>
            </div>
        </nav>

        @if (session('status'))
            <div class="mx-auto mt-4 max-w-5xl rounded-lg border border-brand/40 bg-brand/10 px-4 py-3 text-sm text-brand">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mx-auto mt-4 max-w-5xl rounded-lg border border-red-500/40 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <main class="mx-auto max-w-7xl px-4 pb-16 pt-8 lg:px-8">
            @yield('content')
        </main>

        <footer class="border-t border-white/10 bg-black/60 py-8 text-center text-xs text-slate-500">
            Stream smarter. © {{ date('Y') }} MovieStream.
        </footer>
    </div>
</body>
</html>
