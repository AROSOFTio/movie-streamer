@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Top Watched</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Views</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $item['title'] }}</td>
                        <td class="px-4 py-3">{{ $item['views'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
