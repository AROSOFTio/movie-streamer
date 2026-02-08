@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Subscriptions</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Plan</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Ends At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscriptions as $subscription)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $subscription->user?->email }}</td>
                        <td class="px-4 py-3">{{ $subscription->plan?->name }}</td>
                        <td class="px-4 py-3">{{ strtoupper($subscription->status) }}</td>
                        <td class="px-4 py-3">{{ optional($subscription->ends_at)->toDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $subscriptions->links() }}</div>
@endsection
