<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionCheckoutRequest;
use App\Models\Plan;
use App\Services\Payments\PaymentService;

class SubscriptionApiController extends Controller
{
    public function checkout(SubscriptionCheckoutRequest $request, PaymentService $paymentService)
    {
        $plan = Plan::findOrFail($request->validated('plan_id'));
        $order = $paymentService->createOrder($request->user(), $plan);

        return response()->json([
            'order_id' => $order->id,
            'redirect_url' => $order->checkout_url,
        ]);
    }
}
