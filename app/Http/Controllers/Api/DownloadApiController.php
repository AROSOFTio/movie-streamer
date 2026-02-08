<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadRequestStoreRequest;
use App\Models\DownloadRequest;
use App\Models\Episode;
use App\Models\Movie;

class DownloadApiController extends Controller
{
    public function request(DownloadRequestStoreRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();

        $downloadable = $data['type'] === 'episode'
            ? Episode::query()->findOrFail($data['id'])
            : Movie::query()->findOrFail($data['id']);

        $downloadRequest = DownloadRequest::query()
            ->where('user_id', $user->id)
            ->where('downloadable_type', get_class($downloadable))
            ->where('downloadable_id', $downloadable->id)
            ->latest()
            ->first();

        if (! $downloadRequest || $downloadRequest->status === DownloadRequest::STATUS_REJECTED) {
            $downloadRequest = DownloadRequest::create([
                'user_id' => $user->id,
                'downloadable_type' => get_class($downloadable),
                'downloadable_id' => $downloadable->id,
                'status' => DownloadRequest::STATUS_PENDING,
                'reason' => $data['reason'] ?? null,
            ]);
        }

        return response()->json($downloadRequest, 201);
    }
}
