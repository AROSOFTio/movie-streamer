@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Top Downloads</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Downloads</th>
                    <th class="px-4 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $request->downloadable?->title }}</td>
                        <td class="px-4 py-3">{{ $request->download_count }}</td>
                        <td class="px-4 py-3">{{ strtoupper($request->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
