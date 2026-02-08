@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="glass-panel rounded-3xl p-8">
            <h1 class="text-3xl">Create Account</h1>
            <p class="mt-2 text-sm text-slate-400">Join the stream in seconds.</p>

            <form class="mt-6 space-y-4" method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm">
                    @error('name')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>
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
                <div>
                    <label class="text-xs uppercase tracking-[0.3em] text-slate-500">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="mt-2 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm">
                </div>
                <button class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                    Create Account
                </button>
            </form>

            <p class="mt-4 text-center text-xs text-slate-500">
                Already have an account? <a href="{{ route('login') }}" class="text-brand hover:text-brand-dark">Sign in</a>
            </p>
        </div>
    </div>
@endsection
