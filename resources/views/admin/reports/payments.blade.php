@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl">Payments</h1>

    <div class="glass-panel overflow-hidden rounded-2xl">
        <table class="w-full text-sm">
            <thead class="bg-white/5 text-left text-xs uppercase tracking-[0.3em] text-slate-500">
                <tr>
                    <th class="px-4 py-3">User</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Amount</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $payment)
                    <tr class="border-t border-white/5">
                        <td class="px-4 py-3">{{ $payment->user?->email }}</td>
                        <td class="px-4 py-3">#{{ $payment->order_id }}</td>
                        <td class="px-4 py-3">UGX {{ number_format($payment->amount, 0) }}</td>
                        <td class="px-4 py-3">{{ strtoupper($payment->status) }}</td>
                        <td class="px-4 py-3">{{ $payment->created_at->toDateString() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>{{ $payments->links() }}</div>
@endsection
