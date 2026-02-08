@extends('layouts.app')

@section('content')
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <section class="glass-panel rounded-2xl p-6">
            <h1 class="text-3xl">Account</h1>
            <p class="mt-2 text-sm text-slate-400">Manage your plan and access.</p>

            <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 p-5">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Subscription Status</p>
                @if ($user && $subscription)
                    <p class="mt-2 text-2xl text-brand">Active</p>
                    <p class="text-sm text-slate-400">
                        {{ $subscription->plan->name }} • ends {{ $subscription->ends_at->toFormattedDateString() }}
                    </p>
                @elseif ($user)
                    <p class="mt-2 text-2xl text-red-300">Inactive</p>
                    <p class="text-sm text-slate-400">No active subscription found.</p>
                @else
                    <p class="mt-2 text-2xl text-slate-200">Guest</p>
                    <p class="text-sm text-slate-400">Free streaming available 1 hour per day. Login to subscribe.</p>
                @endif
            </div>
        </section>

        <aside class="space-y-4">
            <h2 class="text-2xl">Upgrade Plan</h2>
            @foreach ($plans as $plan)
                <div class="glass-panel rounded-2xl p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl">{{ $plan->name }}</h3>
                        <span class="text-lg text-brand">UGX {{ number_format($plan->price, 0) }}</span>
                    </div>
                    <p class="mt-2 text-sm text-slate-400">{{ $plan->description }}</p>
                    @if ($user)
                        <form class="mt-4" method="POST" action="{{ route('checkout.store') }}">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            <button class="w-full rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
                                Select {{ $plan->name }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="mt-4 block rounded-xl border border-white/10 px-4 py-2 text-center text-sm hover:border-brand hover:text-brand">
                            Login to Subscribe
                        </a>
                    @endif
                </div>
            @endforeach
        </aside>
    </div>
@endsection
