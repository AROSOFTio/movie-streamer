<?php

return [
    'token_ttl_seconds' => env('STREAM_TOKEN_TTL', 300),
    'single_use' => env('STREAM_TOKEN_SINGLE_USE', true),
    'max_uses' => env('STREAM_TOKEN_MAX_USES', 1),
    'free_daily_seconds' => env('STREAM_FREE_DAILY_SECONDS', 3600),
];
