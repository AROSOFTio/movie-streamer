<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $subscription = null;

        if ($user) {
            $subscription = $user->activeSubscription()->with('plan')->first();
        }
        $plans = Plan::query()->where('is_active', true)->orderBy('price')->get();

        return view('frontend.account', [
            'user' => $user,
            'subscription' => $subscription,
            'plans' => $plans,
        ]);
    }
}
