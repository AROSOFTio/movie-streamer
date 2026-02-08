<?php

namespace App\Services\Streaming;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FreeAccessService
{
    public function remainingSeconds(?User $user, string $sessionId): int
    {
        $used = $this->usedSeconds($user, $sessionId);
        $limit = (int) config('streaming.free_daily_seconds', 3600);

        return max(0, $limit - $used);
    }

    public function usedSeconds(?User $user, string $sessionId): int
    {
        $key = $this->key($user, $sessionId);

        return (int) Cache::get($key, 0);
    }

    public function addSeconds(?User $user, string $sessionId, int $seconds): void
    {
        if ($seconds <= 0) {
            return;
        }

        $key = $this->key($user, $sessionId);
        $limit = (int) config('streaming.free_daily_seconds', 3600);
        $ttl = now()->endOfDay()->diffInSeconds(now());

        $current = (int) Cache::get($key, 0);
        $next = min($limit, $current + $seconds);

        Cache::put($key, $next, $ttl);
    }

    protected function key(?User $user, string $sessionId): string
    {
        $date = now()->toDateString();
        $identifier = $user ? 'user_'.$user->id : 'guest_'.$sessionId;

        return 'free_watch_'.$identifier.'_'.$date;
    }
}
