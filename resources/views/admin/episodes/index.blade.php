@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Episodes</h1>
        <a href="{{ route('admin.episodes.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">Add Episode</a>
    </div>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Series</th>
                    <th class="px-4 py-3">Season</th>
                    <th class="px-4 py-3">Episode</th>
                    <th class="px-4 py-3">Primary Video</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($episodes as $episode)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $episode->title }}</td>
                        <td class="px-4 py-3">{{ $episode->series?->title }}</td>
                        <td class="px-4 py-3">{{ $episode->season_number }}</td>
                        <td class="px-4 py-3">{{ $episode->episode_number }}</td>
                        <td class="px-4 py-3 text-xs text-slate-400">
                            {{ data_get($episode->primaryVideo, 'meta.display_name') ?? (basename($episode->primaryVideo?->path ?? '') ?: 'Not uploaded') }}
                        </td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.episodes.edit', $episode) }}" class="text-brand hover:text-brand-dark">Edit</a>
                            <form method="POST" action="{{ route('admin.episodes.destroy', $episode) }}">
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

    <div>{{ $episodes->links() }}</div>
@endsection
