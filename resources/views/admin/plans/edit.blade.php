@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Edit Plan</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @csrf
        @method('PUT')
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="name" value="{{ old('name', $plan->name) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" value="{{ old('slug', $plan->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="1" name="price" value="{{ old('price', $plan->price) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="currency" value="{{ old('currency', $plan->currency) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="interval" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="daily" @selected($plan->interval === 'daily')>Daily</option>
                <option value="weekly" @selected($plan->interval === 'weekly')>Weekly</option>
                <option value="bi-weekly" @selected($plan->interval === 'bi-weekly')>Bi-Weekly</option>
                <option value="monthly" @selected($plan->interval === 'monthly')>Monthly</option>
                <option value="quarterly" @selected($plan->interval === 'quarterly')>Quarterly</option>
                <option value="yearly" @selected($plan->interval === 'yearly')>Yearly</option>
            </select>
            <input type="number" name="interval_count" value="{{ old('interval_count', $plan->interval_count) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="3" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('description', $plan->description) }}</textarea>
        <textarea name="features" rows="3" class="w-full rounded-xl border border-white/10 bg-white/5 p-3">{{ old('features', json_encode($plan->features)) }}</textarea>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" @checked($plan->is_active) class="rounded border-white/10 bg-white/5">
            Active
        </label>
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Update Plan
        </button>
    </form>
@endsection
