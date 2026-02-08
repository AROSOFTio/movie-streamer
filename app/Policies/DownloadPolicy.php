<?php

namespace App\Policies;

use App\Models\DownloadRequest;
use App\Models\User;

class DownloadPolicy
{
    public function request(User $user): bool
    {
        return true;
    }

    public function download(User $user, DownloadRequest $request): bool
    {
        return $request->user_id === $user->id
            && $request->isApproved()
            && ($user->hasActiveSubscription() || $request->isPaid());
    }

    public function approve(User $user): bool
    {
        return $user->isAdmin();
    }
}
