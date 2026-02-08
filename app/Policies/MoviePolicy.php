<?php

namespace App\Policies;

use App\Models\Movie;
use App\Models\User;

class MoviePolicy
{
    public function view(?User $user, Movie $movie): bool
    {
        return true;
    }

    public function watch(User $user, Movie $movie): bool
    {
        return $user->hasActiveSubscription();
    }

    public function manage(User $user): bool
    {
        return $user->isAdmin();
    }
}
