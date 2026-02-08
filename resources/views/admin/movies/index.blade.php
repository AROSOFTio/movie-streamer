@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl">Movies</h1>
        <a href="{{ route('admin.movies.create') }}" class="rounded-xl bg-brand px-4 py-2 text-sm font-semibold text-black hover:bg-brand-dark">Add Movie</a>
    </div>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Year</th>
                    <th class="px-4 py-3">Featured</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movies as $movie)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $movie->title }}</td>
                        <td class="px-4 py-3">{{ $movie->year }}</td>
                        <td class="px-4 py-3">{{ $movie->featured ? 'Yes' : 'No' }}</td>
                        <td class="px-4 py-3 flex gap-2">
                            <a href="{{ route('admin.movies.edit', $movie) }}" class="text-brand hover:text-brand-dark">Edit</a>
                            <form method="POST" action="{{ route('admin.movies.destroy', $movie) }}">
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

    <div>{{ $movies->links() }}</div>
@endsection
