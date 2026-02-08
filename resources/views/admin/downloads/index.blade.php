@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Download Requests</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Payment</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($requests as $request)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $request->user?->email }}</td>
                        <td class="px-4 py-3">{{ $request->downloadable?->title }}</td>
                        <td class="px-4 py-3">{{ strtoupper($request->status) }}</td>
                        <td class="px-4 py-3">
                            {{ $request->isPaid() ? 'PAID' : 'UNPAID' }}
                        </td>
                        <td class="px-4 py-3">
                            @if ($request->status === \App\Models\DownloadRequest::STATUS_PENDING)
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('admin.downloads.approve', $request) }}">
                                        @csrf
                                        <button class="rounded-lg bg-brand px-3 py-1 text-xs font-semibold text-black hover:bg-brand-dark">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.downloads.reject', $request) }}">
                                        @csrf
                                        <button class="rounded-lg border border-white/10 px-3 py-1 text-xs hover:border-red-400">Reject</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-slate-500">No actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $requests->links() }}</div>
@endsection
