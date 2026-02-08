<?php

return [
    'provider' => 'pesapal',
    'currency' => env('PAYMENTS_CURRENCY', 'UGX'),
    'pesapal' => [
        'consumer_key' => env('PESAPAL_CONSUMER_KEY'),
        'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
        'env' => env('PESAPAL_ENV', 'sandbox'),
        'ipn_id' => env('PESAPAL_IPN_ID'),
        'callback_url' => env('PESAPAL_CALLBACK_URL'),
        'mock' => env('PESAPAL_MOCK', true),
    ],
];
