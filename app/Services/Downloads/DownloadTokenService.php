<?php

namespace App\Services\Downloads;

use App\Models\DownloadRequest;
use App\Models\DownloadToken;
use App\Models\User;
use Illuminate\Support\Str;

class DownloadTokenService
{
    public function create(User $user, DownloadRequest $downloadRequest): DownloadToken
    {
        $ttl = (int) config('downloads.token_ttl_seconds', 300);
        $uses = config('downloads.single_use') ? 1 : (int) config('downloads.max_uses', 3);

        return DownloadToken::create([
            'user_id' => $user->id,
            'download_request_id' => $downloadRequest->id,
            'token' => Str::random(64),
            'expires_at' => now()->addSeconds($ttl),
            'uses_remaining' => $uses,
        ]);
    }

    public function validate(string $token, User $user): ?DownloadToken
    {
        $downloadToken = DownloadToken::query()
            ->with('downloadRequest.downloadable')
            ->where('token', $token)
            ->where('user_id', $user->id)
            ->first();

        if (! $downloadToken || ! $downloadToken->canBeUsed()) {
            return null;
        }

        return $downloadToken;
    }

    public function consume(DownloadToken $downloadToken): void
    {
        if ($downloadToken->uses_remaining > 0) {
            $downloadToken->uses_remaining -= 1;
            $downloadToken->used_at = now();
            $downloadToken->save();
        }
    }
}
