@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-xl text-center">
        <div class="glass-panel rounded-3xl p-8">
            <h1 class="text-4xl text-brand">Payment Successful</h1>
            <p class="mt-3 text-sm text-slate-400">
                Your subscription is now active. Enjoy unlimited streaming.
            </p>
            @if ($order)
                <div class="mt-4 text-xs text-slate-500">
                    Order #{{ $order->id }} • {{ strtoupper($order->status) }}
                </div>
            @endif
            <div class="mt-6 flex flex-col gap-3">
                <a href="{{ route('browse') }}" class="rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                    Start Watching
                </a>
                <a href="{{ route('account') }}" class="text-sm text-brand hover:text-brand-dark">
                    View Account
                </a>
            </div>
        </div>
    </div>
@endsection
