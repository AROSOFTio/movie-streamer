<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Models\DownloadRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(private PesapalClient $pesapalClient)
    {
    }

    public function createOrder(User $user, Plan $plan): Order
    {
        return DB::transaction(function () use ($user, $plan) {
            $order = Order::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => $plan->currency,
                'status' => Order::STATUS_PENDING,
                'provider' => 'pesapal',
                'type' => Order::TYPE_SUBSCRIPTION,
            ]);

            $response = $this->pesapalClient->initiatePayment($order);

            $order->provider_reference = $response['reference'] ?? Str::upper(Str::random(10));
            $order->checkout_url = $response['redirect_url'] ?? route('payments.return', [
                'order_id' => $order->id,
                'mock' => 1,
            ]);
            $order->save();

            return $order;
        });
    }

    public function createDownloadOrder(User $user, DownloadRequest $downloadRequest): Order
    {
        $amount = (int) config('downloads.price', 500);

        return DB::transaction(function () use ($user, $downloadRequest, $amount) {
            $order = Order::create([
                'user_id' => $user->id,
                'plan_id' => null,
                'amount' => $amount,
                'currency' => config('payments.currency', 'UGX'),
                'status' => Order::STATUS_PENDING,
                'provider' => 'pesapal',
                'type' => Order::TYPE_DOWNLOAD,
                'meta' => [
                    'download_request_id' => $downloadRequest->id,
                ],
            ]);

            $response = $this->pesapalClient->initiatePayment($order);

            $order->provider_reference = $response['reference'] ?? Str::upper(Str::random(10));
            $order->checkout_url = $response['redirect_url'] ?? route('payments.return', [
                'order_id' => $order->id,
                'mock' => 1,
            ]);
            $order->save();

            return $order;
        });
    }

    public function handleReturn(Order $order, array $payload = []): void
    {
        if ($order->status === Order::STATUS_PAID) {
            return;
        }

        $this->markPaid($order, $payload);
    }

    public function handleIpn(Order $order, array $payload = []): void
    {
        if ($order->status === Order::STATUS_PAID) {
            return;
        }

        $status = $payload['status'] ?? 'success';
        if (strtolower($status) !== 'success') {
            $order->status = Order::STATUS_FAILED;
            $order->save();

            return;
        }

        $this->markPaid($order, $payload);
    }

    protected function markPaid(Order $order, array $payload = []): void
    {
        DB::transaction(function () use ($order, $payload) {
            $order->status = Order::STATUS_PAID;
            $order->save();

            $payment = Payment::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'status' => Payment::STATUS_SUCCESS,
                'provider' => $order->provider,
                'provider_reference' => $order->provider_reference,
                'payload' => $this->safePayload($payload),
                'paid_at' => now(),
            ]);

            if ($order->type === Order::TYPE_SUBSCRIPTION) {
                $plan = $order->plan;
                $startsAt = now();
                $endsAt = $plan->calculateEndsAt($startsAt);

                Subscription::create([
                    'user_id' => $order->user_id,
                    'plan_id' => $plan->id,
                    'status' => Subscription::STATUS_ACTIVE,
                    'starts_at' => $startsAt,
                    'ends_at' => $endsAt,
                    'meta' => ['order_id' => $order->id],
                ]);
            }

            if ($order->type === Order::TYPE_DOWNLOAD) {
                $downloadRequestId = $order->meta['download_request_id'] ?? null;
                if ($downloadRequestId) {
                    DownloadRequest::where('id', $downloadRequestId)
                        ->update([
                            'payment_id' => $payment->id,
                            'paid_at' => now(),
                        ]);
                }
            }
        });
    }

    protected function safePayload(array $payload): array
    {
        return collect($payload)->only([
            'status',
            'order_id',
            'tracking_id',
            'amount',
            'currency',
            'payment_method',
        ])->toArray();
    }
}
