<?php

namespace App\Http\Middleware;

use App\Services\Streaming\FreeAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStreamingAccess
{
    public function __construct(private FreeAccessService $freeAccessService)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->hasActiveSubscription()) {
            $request->session()->forget('free_time_expired');
            return $next($request);
        }

        $sessionId = $request->session()->getId();
        $remaining = $this->freeAccessService->remainingSeconds($user, $sessionId);

        if ($remaining <= 0) {
            $request->session()->put('free_time_expired', true);

            return redirect()
                ->route('account')
                ->with('error', 'Free streaming time used. Please subscribe to continue.');
        }

        $request->session()->forget('free_time_expired');

        return $next($request);
    }
}
