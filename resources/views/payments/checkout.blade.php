@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl">Choose Your Plan</h1>
            <p class="text-sm text-slate-400">Unlock streaming with flexible billing.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            @foreach ($plans as $plan)
                <div class="glass-panel rounded-2xl p-6">
                    <h2 class="text-2xl">{{ $plan->name }}</h2>
                    <p class="mt-2 text-sm text-slate-400">{{ $plan->description }}</p>
                    <p class="mt-4 text-3xl text-brand">UGX {{ number_format($plan->price, 0) }}</p>
                    <ul class="mt-4 space-y-2 text-sm text-slate-300">
                        @foreach ($plan->features ?? [] as $feature)
                            <li>• {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <form class="mt-6" method="POST" action="{{ route('checkout.store') }}">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button class="w-full rounded-xl bg-brand px-4 py-3 text-sm font-semibold text-black hover:bg-brand-dark">
                            Continue to Pay
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
