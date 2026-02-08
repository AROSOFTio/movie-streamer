<?php

namespace App\Services\Payments;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class PesapalClient
{
    public function initiatePayment(Order $order): array
    {
        if (config('payments.pesapal.mock')) {
            return [
                'reference' => 'MOCK-'.Str::upper(Str::random(10)),
                'redirect_url' => route('payments.return', [
                    'order_id' => $order->id,
                    'mock' => 1,
                ]),
            ];
        }

        $consumerKey = config('payments.pesapal.consumer_key');
        $consumerSecret = config('payments.pesapal.consumer_secret');
        $callbackUrl = config('payments.pesapal.callback_url');

        if (! $consumerKey || ! $consumerSecret || ! $callbackUrl) {
            throw new RuntimeException('Missing PesaPal credentials.');
        }

        $token = $this->getAccessToken($consumerKey, $consumerSecret);
        $description = $order->type === \App\Models\Order::TYPE_DOWNLOAD
            ? 'Download access'
            : 'Subscription for '.$order->plan?->name;

        $payload = [
            'id' => (string) $order->id,
            'currency' => $order->currency,
            'amount' => (float) $order->amount,
            'description' => $description,
            'callback_url' => $callbackUrl,
            'notification_id' => config('payments.pesapal.ipn_id'),
            'billing_address' => [
                'email_address' => $order->user->email,
                'first_name' => $order->user->name,
            ],
        ];

        $response = Http::withToken($token)
            ->post($this->baseUrl().'/Transactions/SubmitOrderRequest', $payload);

        if (! $response->successful()) {
            throw new RuntimeException('PesaPal request failed.');
        }

        $data = $response->json();

        return [
            'reference' => $data['order_tracking_id'] ?? null,
            'redirect_url' => $data['redirect_url'] ?? null,
        ];
    }

    public function getTransactionStatus(string $trackingId): array
    {
        $consumerKey = config('payments.pesapal.consumer_key');
        $consumerSecret = config('payments.pesapal.consumer_secret');

        $token = $this->getAccessToken($consumerKey, $consumerSecret);

        $response = Http::withToken($token)
            ->get($this->baseUrl().'/Transactions/GetTransactionStatus', [
                'orderTrackingId' => $trackingId,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to verify transaction.');
        }

        return $response->json();
    }

    protected function getAccessToken(string $consumerKey, string $consumerSecret): string
    {
        $response = Http::post($this->baseUrl().'/Auth/RequestToken', [
            'consumer_key' => $consumerKey,
            'consumer_secret' => $consumerSecret,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('Unable to authenticate with PesaPal.');
        }

        return $response->json('token');
    }

    protected function baseUrl(): string
    {
        return config('payments.pesapal.env') === 'live'
            ? 'https://pay.pesapal.com/v3/api'
            : 'https://cybqa.pesapal.com/pesapalv3/api';
    }
}
