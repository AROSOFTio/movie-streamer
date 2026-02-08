<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadRequest;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\WatchHistory;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function payments()
    {
        $payments = Payment::query()
            ->with(['user', 'order'])
            ->latest()
            ->paginate(30);

        return view('admin.reports.payments', [
            'payments' => $payments,
        ]);
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::query()
            ->with(['user', 'plan'])
            ->latest()
            ->paginate(30);

        return view('admin.reports.subscriptions', [
            'subscriptions' => $subscriptions,
        ]);
    }

    public function topWatched()
    {
        $rows = WatchHistory::query()
            ->select('watchable_type', 'watchable_id', DB::raw('count(*) as views'))
            ->groupBy('watchable_type', 'watchable_id')
            ->orderByDesc('views')
            ->take(20)
            ->get();

        $items = $rows->map(function ($row) {
            $title = $row->watchable_type === Movie::class
                ? Movie::find($row->watchable_id)?->title
                : Episode::find($row->watchable_id)?->title;

            return [
                'title' => $title ?? 'Unknown',
                'views' => $row->views,
            ];
        });

        return view('admin.reports.top-watched', [
            'items' => $items,
        ]);
    }

    public function topDownloads()
    {
        $requests = DownloadRequest::query()
            ->with('downloadable')
            ->orderByDesc('download_count')
            ->take(20)
            ->get();

        return view('admin.reports.top-downloads', [
            'requests' => $requests,
        ]);
    }
}
