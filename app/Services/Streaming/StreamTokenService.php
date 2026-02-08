<?php

namespace App\Services\Streaming;

use App\Models\StreamToken;
use App\Models\VideoFile;
use Illuminate\Support\Str;

class StreamTokenService
{
    public function create(?\App\Models\User $user, VideoFile $videoFile, string $sessionId): StreamToken
    {
        $ttl = (int) config('streaming.token_ttl_seconds', 300);
        $uses = config('streaming.single_use') ? 1 : (int) config('streaming.max_uses', 3);

        return StreamToken::create([
            'user_id' => $user?->id,
            'session_id' => $user ? null : $sessionId,
            'video_file_id' => $videoFile->id,
            'token' => Str::random(64),
            'expires_at' => now()->addSeconds($ttl),
            'uses_remaining' => $uses,
        ]);
    }

    public function validate(string $token, ?\App\Models\User $user, string $sessionId): ?StreamToken
    {
        $streamToken = StreamToken::query()
            ->with('videoFile')
            ->where('token', $token)
            ->first();

        if (! $streamToken || ! $streamToken->canBeUsed()) {
            return null;
        }

        if ($user) {
            if ($streamToken->user_id !== $user->id) {
                return null;
            }
        } else {
            if ($streamToken->session_id !== $sessionId) {
                return null;
            }
        }

        return $streamToken;
    }

    public function consume(StreamToken $streamToken): void
    {
        if ($streamToken->uses_remaining > 0) {
            $streamToken->uses_remaining -= 1;
            $streamToken->used_at = now();
            $streamToken->save();
        }
    }
}
