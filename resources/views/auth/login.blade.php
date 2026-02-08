@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="glass-panel rounded-3xl p-8">
            <h1 class="text-3xl">Welcome Back</h1>
            <p class="mt-2 text-sm text-slate-400">Sign in to continue streaming.</p>

            <form class="mt-6 space-y-4" method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm">
                    @error('email')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Password</label>
                    <input type="password" name="password" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm">
                    @error('password')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
                <label class="flex items-center gap-2 text-xs text-slate-400">
                    <input type="checkbox" name="remember" class="rounded border-white/10 bg-white/5">
                    Remember me
                </label>
                <button class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                    Sign In
                </button>
            </form>

            <p class="mt-4 text-center text-xs text-slate-500">
                New here? <a href="{{ route('register') }}" class="text-brand hover:text-brand-dark">Create an account</a>
            </p>
        </div>
    </div>
@endsection
