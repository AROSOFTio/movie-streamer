<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AccountApiController extends Controller
{
    public function subscription()
    {
        $user = request()->user();
        $subscription = $user->activeSubscription()->with('plan')->first();

        return response()->json([
            'active' => (bool) $subscription,
            'subscription' => $subscription,
        ]);
    }
}
