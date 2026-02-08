<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Payments\PaymentService;
use Illuminate\Http\Request;

class PesapalController extends Controller
{
    public function handleReturn(Request $request, PaymentService $paymentService)
    {
        $orderId = $request->get('order_id');
        $reference = $request->get('tracking_id') ?? $request->get('OrderTrackingId');

        $order = null;
        if ($orderId) {
            $order = Order::findOrFail($orderId);
        } elseif ($reference) {
            $order = Order::where('provider_reference', $reference)->firstOrFail();
        } else {
            abort(404);
        }

        $paymentService->handleReturn($order, $request->all());

        return redirect()->route('payments.success', ['order_id' => $order->id]);
    }

    public function handleIpn(Request $request, PaymentService $paymentService)
    {
        $reference = $request->get('tracking_id') ?? $request->get('OrderTrackingId');
        if (! $reference) {
            return response()->json(['status' => 'missing reference'], 422);
        }

        $order = Order::where('provider_reference', $reference)->first();
        if (! $order) {
            return response()->json(['status' => 'order not found'], 404);
        }

        $paymentService->handleIpn($order, $request->all());

        return response()->json(['status' => 'ok']);
    }
}
