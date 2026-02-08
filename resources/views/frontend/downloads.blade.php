@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl">Download Petitions</h1>
            <a href="{{ route('browse') }}" class="text-sm text-brand hover:text-brand-dark">Browse titles</a>
        </div>

        <div class="glass-panel overflow-hidden rounded-2xl">
            <table class="w-full text-sm">
                <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Title</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Payment</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        @php
                            $item = $request->downloadable;
                        @endphp
                        <tr class="border-t border-white/5">
                            <td class="px-4 py-3">{{ $item?->title ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                {{ class_basename($request->downloadable_type) }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-white/5 px-3 py-1 text-xs">
                                    {{ strtoupper($request->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-400">
                                @if (auth()->user()->hasActiveSubscription())
                                    Subscription
                                @else
                                    {{ $request->isPaid() ? 'Paid' : 'Unpaid' }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($request->isApproved())
                                    @if (auth()->user()->hasActiveSubscription() || $request->isPaid())
                                        <form method="POST" action="{{ route('downloads.token', $request->id) }}">
                                            @csrf
                                            <button class="rounded-full bg-brand px-4 py-2 text-xs font-semibold text-black hover:bg-brand-dark">
                                                Download
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('downloads.pay', $request->id) }}">
                                            @csrf
                                            <button class="rounded-full border border-white/10 px-4 py-2 text-xs hover:border-brand hover:text-brand">
                                                Pay UGX {{ number_format(config('downloads.price', 500), 0) }}
                                            </button>
                                        </form>
                                    @endif
                                @elseif (! $request->isPaid() && ! auth()->user()->hasActiveSubscription())
                                    <form method="POST" action="{{ route('downloads.pay', $request->id) }}">
                                        @csrf
                                        <button class="rounded-full border border-white/10 px-4 py-2 text-xs hover:border-brand hover:text-brand">
                                            Pay UGX {{ number_format(config('downloads.price', 500), 0) }}
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-500">Awaiting approval</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500">No download petitions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
