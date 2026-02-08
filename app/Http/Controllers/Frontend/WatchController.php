<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WatchProgressRequest;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\WatchHistory;
use App\Services\Streaming\FreeAccessService;
use App\Services\Streaming\StreamTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WatchController extends Controller
{
    public function movie(Movie $movie, StreamTokenService $streamTokenService, FreeAccessService $freeAccessService, Request $request)
    {
        $movie->load('videoFiles', 'primaryVideo', 'genres', 'castMembers');

        if ($movie->videoFiles->isEmpty()) {
            abort(404, 'Video not available.');
        }

        $user = $request->user();
        $sessionId = $request->session()->getId();
        [$streamSources, $defaultSource] = $this->buildStreamSources($movie->videoFiles, $streamTokenService, $user, $sessionId);

        $watchHistory = WatchHistory::query()
            ->where('user_id', Auth::id())
            ->where('watchable_type', Movie::class)
            ->where('watchable_id', $movie->id)
            ->first();

        $remainingSeconds = null;
        if (! $user || ! $user->hasActiveSubscription()) {
            $remainingSeconds = $freeAccessService->remainingSeconds($user, $sessionId);
        }

        return view('frontend.watch', [
            'title' => $movie->title,
            'watchable' => $movie,
            'streamToken' => $defaultSource['token'],
            'streamSources' => $streamSources,
            'defaultStreamSource' => $defaultSource,
            'watchHistory' => $watchHistory,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function episode(Episode $episode, StreamTokenService $streamTokenService, FreeAccessService $freeAccessService, Request $request)
    {
        $episode->load('videoFiles', 'primaryVideo', 'series', 'genres', 'castMembers');

        if ($episode->videoFiles->isEmpty()) {
            abort(404, 'Video not available.');
        }

        $user = $request->user();
        $sessionId = $request->session()->getId();
        [$streamSources, $defaultSource] = $this->buildStreamSources($episode->videoFiles, $streamTokenService, $user, $sessionId);

        $watchHistory = WatchHistory::query()
            ->where('user_id', Auth::id())
            ->where('watchable_type', Episode::class)
            ->where('watchable_id', $episode->id)
            ->first();

        $remainingSeconds = null;
        if (! $user || ! $user->hasActiveSubscription()) {
            $remainingSeconds = $freeAccessService->remainingSeconds($user, $sessionId);
        }

        return view('frontend.watch', [
            'title' => $episode->title,
            'watchable' => $episode,
            'streamToken' => $defaultSource['token'],
            'streamSources' => $streamSources,
            'defaultStreamSource' => $defaultSource,
            'watchHistory' => $watchHistory,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function progress(WatchProgressRequest $request, FreeAccessService $freeAccessService)
    {
        $data = $request->validated();
        $user = $request->user();
        $sessionId = $request->session()->getId();
        $hasUnlimitedAccess = (bool) ($user && $user->hasActiveSubscription());

        $watchableClass = $data['watchable_type'] === 'episode' ? Episode::class : Movie::class;

        $history = null;
        $lastPosition = 0;

        if ($user) {
            $history = WatchHistory::query()->firstOrNew([
                'user_id' => $user->id,
                'watchable_type' => $watchableClass,
                'watchable_id' => $data['watchable_id'],
            ]);
            $lastPosition = (int) $history->last_position_seconds;
        } else {
            $key = 'free_watch_last_'.$data['watchable_type'].'_'.$data['watchable_id'];
            $lastPosition = (int) $request->session()->get($key, 0);
            $request->session()->put($key, $data['last_position_seconds']);
        }

        $delta = max(0, (int) $data['last_position_seconds'] - $lastPosition);
        $delta = min($delta, 120);

        $remainingSeconds = null;
        if (! $hasUnlimitedAccess) {
            $freeAccessService->addSeconds($user, $sessionId, $delta);
            $remainingSeconds = $freeAccessService->remainingSeconds($user, $sessionId);
        } else {
            $request->session()->forget('free_time_expired');
        }

        if ($remainingSeconds !== null && $remainingSeconds <= 0) {
            $request->session()->put('free_time_expired', true);

            return response()->json([
                'status' => 'blocked',
                'message' => 'Free streaming time used. Please subscribe to continue.',
                'redirect_url' => route('account'),
                'remaining_seconds' => 0,
            ], 403);
        }

        if ($history) {
            $history->last_position_seconds = $data['last_position_seconds'];
            $history->progress_percent = min(100, max(0, $data['progress_percent']));
            $history->last_watched_at = now();

            if ($data['completed']) {
                $history->completed_at = now();
            }

            $history->save();
        }

        return response()->json([
            'status' => 'ok',
            'remaining_seconds' => $remainingSeconds,
        ]);
    }

    protected function buildStreamSources($videoFiles, StreamTokenService $streamTokenService, ?\App\Models\User $user, string $sessionId): array
    {
        $sorted = $videoFiles->sortByDesc(function ($video) {
            return $this->qualityRank($video->quality);
        })->values();

        $primary = $sorted->firstWhere('is_primary', true) ?? $sorted->first();

        $sources = $sorted->map(function ($video) use ($streamTokenService, $user, $sessionId, $primary) {
            $token = $streamTokenService->create($user, $video, $sessionId);

            return [
                'id' => $video->id,
                'quality' => $video->quality,
                'label' => $this->qualityLabel($video->quality),
                'url' => route('stream', $token->token),
                'token' => $token->token,
                'is_primary' => (bool) $video->is_primary,
                'is_default' => $primary && $video->id === $primary->id,
            ];
        })->values();

        $defaultSource = $sources->firstWhere('is_default', true) ?? $sources->first();

        return [$sources, $defaultSource];
    }

    protected function qualityRank(?string $quality): int
    {
        if (! $quality) {
            return 0;
        }

        $quality = strtolower($quality);
        if ($quality === '4k') {
            $quality = '2160p';
        }

        $map = [
            '360p' => 360,
            '480p' => 480,
            '720p' => 720,
            '1080p' => 1080,
            '1440p' => 1440,
            '2160p' => 2160,
        ];

        return $map[$quality] ?? 0;
    }

    protected function qualityLabel(?string $quality): string
    {
        if (! $quality) {
            return 'Auto';
        }

        $quality = strtolower($quality);

        if ($quality === '4k') {
            return '4K';
        }

        if ($quality === '2160p') {
            return '4K (2160p)';
        }

        return strtoupper($quality);
    }
}
