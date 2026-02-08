<?php

return [
    'token_ttl_seconds' => env('DOWNLOAD_TOKEN_TTL', 300),
    'single_use' => env('DOWNLOAD_TOKEN_SINGLE_USE', true),
    'max_per_day' => env('DOWNLOADS_MAX_PER_DAY', 3),
    'price' => env('DOWNLOAD_PRICE', 500),
];
