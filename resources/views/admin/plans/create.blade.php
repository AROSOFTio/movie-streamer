@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Create Plan</h1>
    <form class="space-y-4" method="POST" action="{{ route('admin.plans.store') }}">
        @csrf
        <div class="grid gap-4 md:grid-cols-2">
            <input type="text" name="name" placeholder="Name" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="slug" placeholder="Slug (optional)" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="number" step="1" name="price" placeholder="Price" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <input type="text" name="currency" value="UGX" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
            <select name="interval" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="bi-weekly">Bi-Weekly</option>
                <option value="monthly">Monthly</option>
                <option value="quarterly">Quarterly</option>
                <option value="yearly">Yearly</option>
            </select>
            <input type="number" name="interval_count" value="1" class="rounded-xl border border-white/10 bg-white/5 px-4 py-2">
        </div>
        <textarea name="description" rows="3" placeholder="Description" class="w-full rounded-xl border border-white/10 bg-white/5 p-3"></textarea>
        <textarea name="features" rows="3" placeholder='Features (JSON array: ["HD","Multi-device"])' class="w-full rounded-xl border border-white/10 bg-white/5 p-3"></textarea>
        <label class="flex items-center gap-2 text-sm text-slate-300">
            <input type="checkbox" name="is_active" value="1" checked class="rounded border-white/10 bg-white/5">
            Active
        </label>
        <button class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">
            Save Plan
        </button>
    </form>
@endsection
