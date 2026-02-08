<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DownloadRequest;
use Illuminate\Support\Facades\Auth;

class DownloadRequestAdminController extends Controller
{
    public function index()
    {
        $requests = DownloadRequest::query()
            ->with(['user', 'downloadable'])
            ->latest()
            ->paginate(30);

        return view('admin.downloads.index', [
            'requests' => $requests,
        ]);
    }

    public function approve(DownloadRequest $downloadRequest)
    {
        $downloadRequest->status = DownloadRequest::STATUS_APPROVED;
        $downloadRequest->approved_by = Auth::id();
        $downloadRequest->approved_at = now();
        $downloadRequest->save();

        return redirect()->route('admin.downloads.index')->with('status', 'Download approved.');
    }

    public function reject(DownloadRequest $downloadRequest)
    {
        $downloadRequest->status = DownloadRequest::STATUS_REJECTED;
        $downloadRequest->approved_by = Auth::id();
        $downloadRequest->approved_at = now();
        $downloadRequest->save();

        return redirect()->route('admin.downloads.index')->with('status', 'Download rejected.');
    }
}
