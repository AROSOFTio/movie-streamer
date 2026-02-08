@extends('layouts.admin')

@section('content')
    <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Users</p>
            <p class="mt-2 text-3xl">{{ $stats['users'] }}</p>
        </div>
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Active Subs</p>
            <p class="mt-2 text-3xl">{{ $stats['active_subs'] }}</p>
        </div>
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Movies</p>
            <p class="mt-2 text-3xl">{{ $stats['movies'] }}</p>
        </div>
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Series</p>
            <p class="mt-2 text-3xl">{{ $stats['series'] }}</p>
        </div>
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">SOP</p>
            <p class="mt-2 text-3xl">{{ $stats['episodes'] }}</p>
        </div>
        <div class="glass-panel rounded-2xl p-4">
            <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Revenue</p>
            <p class="mt-2 text-3xl">UGX {{ number_format($stats['revenue'], 0) }}</p>
        </div>
    </div>
@endsection
