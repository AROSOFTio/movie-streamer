@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">VJs / Translators</h1>
        <a href="{{ route('admin.vjs.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">Add VJ</a>
    </div>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Language</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vjs as $vj)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $vj->name }}</td>
                        <td class="px-4 py-3">{{ $vj->language?->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $vj->is_active ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.vjs.edit', $vj) }}" class="text-brand hover:text-brand-dark">Edit</a>
                            <form method="POST" action="{{ route('admin.vjs.destroy', $vj) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-300 hover:text-red-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $vjs->links() }}</div>
@endsection
