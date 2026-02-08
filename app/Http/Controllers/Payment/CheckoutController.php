<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Plan;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $plans = Plan::query()->where('is_active', true)->orderBy('price')->get();

        return view('payments.checkout', [
            'plans' => $plans,
        ]);
    }

    public function store(Request $request, PaymentService $paymentService)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($data['plan_id']);
        $order = $paymentService->createOrder(Auth::user(), $plan);

        return redirect()->away($order->checkout_url);
    }

    public function success(Request $request)
    {
        $order = null;
        if ($request->filled('order_id')) {
            $order = Order::find($request->get('order_id'));
        }

        return view('payments.success', [
            'order' => $order,
        ]);
    }
}
