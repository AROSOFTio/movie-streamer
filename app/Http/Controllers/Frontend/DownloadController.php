<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadRequestStoreRequest;
use App\Models\DownloadRequest;
use App\Models\Episode;
use App\Models\Movie;
use App\Services\Payments\PaymentService;
use App\Services\Downloads\DownloadTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index()
    {
        $requests = DownloadRequest::query()
            ->with('downloadable')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('frontend.downloads', [
            'requests' => $requests,
        ]);
    }

    public function store(DownloadRequestStoreRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $downloadable = $data['type'] === 'episode'
            ? Episode::query()->findOrFail($data['id'])
            : Movie::query()->findOrFail($data['id']);

        $existing = DownloadRequest::query()
            ->where('user_id', $user->id)
            ->where('downloadable_type', get_class($downloadable))
            ->where('downloadable_id', $downloadable->id)
            ->latest()
            ->first();

        if (! $existing || $existing->status === DownloadRequest::STATUS_REJECTED) {
            $existing = DownloadRequest::create([
                'user_id' => $user->id,
                'downloadable_type' => get_class($downloadable),
                'downloadable_id' => $downloadable->id,
                'status' => DownloadRequest::STATUS_PENDING,
                'reason' => $data['reason'] ?? null,
            ]);
        }

        return redirect()->route('downloads.index')->with('status', 'Download request submitted.');
    }

    public function token(DownloadRequest $downloadRequest, DownloadTokenService $service)
    {
        $this->authorize('download', $downloadRequest);

        $token = $service->create($downloadRequest->user, $downloadRequest);

        return redirect()->route('downloads.download', ['token' => $token->token]);
    }

    public function pay(DownloadRequest $downloadRequest, PaymentService $paymentService)
    {
        if ($downloadRequest->user_id !== Auth::id()) {
            abort(403);
        }

        if ($downloadRequest->status === DownloadRequest::STATUS_REJECTED) {
            return redirect()->route('downloads.index')->with('error', 'Download request was rejected.');
        }

        if ($downloadRequest->isPaid()) {
            return redirect()->route('downloads.index')->with('status', 'Download already paid.');
        }

        $order = $paymentService->createDownloadOrder(Auth::user(), $downloadRequest);

        return redirect()->away($order->checkout_url);
    }

    public function download(Request $request, string $token, DownloadTokenService $service)
    {
        $user = $request->user();
        $downloadToken = $service->validate($token, $user);

        if (! $downloadToken) {
            abort(403, 'Invalid or expired download token.');
        }

        $downloadRequest = $downloadToken->downloadRequest;
        $this->authorize('download', $downloadRequest);
        if (! $downloadRequest->isApproved()) {
            abort(403, 'Download request not approved.');
        }

        if (! $user->hasActiveSubscription() && ! $downloadRequest->isPaid()) {
            abort(403, 'Payment required for download.');
        }

        $downloadable = $downloadRequest->downloadable;
        $videoFile = $downloadable?->primaryVideo;

        if (! $videoFile) {
            abort(404, 'Video file not found.');
        }

        $path = Storage::disk($videoFile->disk)->path($videoFile->path);

        if (! file_exists($path)) {
            abort(404, 'Video file not found.');
        }

        $service->consume($downloadToken);
        $downloadRequest->increment('download_count');
        $downloadRequest->downloaded_at = now();
        $downloadRequest->save();

        return response()->download($path, basename($path), [
            'Content-Type' => 'video/mp4',
        ]);
    }
}
