<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Streaming\StreamTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StreamController extends Controller
{
    public function stream(Request $request, string $token, StreamTokenService $service)
    {
        $user = $request->user();
        $streamToken = $service->validate($token, $user, $request->session()->getId());

        if (! $streamToken) {
            abort(403, 'Invalid or expired stream token.');
        }

        $videoFile = $streamToken->videoFile;
        $path = Storage::disk($videoFile->disk)->path($videoFile->path);

        if (! file_exists($path)) {
            abort(404, 'Video file not found.');
        }

        $service->consume($streamToken);

        return response()->file($path, [
            'Content-Type' => 'video/mp4',
        ]);
    }
}
