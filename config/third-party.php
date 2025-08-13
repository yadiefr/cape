<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Scripts Configuration
    |--------------------------------------------------------------------------
    |
    | Configure third-party scripts and analytics services
    |
    */

    'cloudflare' => [
        'web_analytics' => [
            'enabled' => env('CLOUDFLARE_ANALYTICS_ENABLED', true),
            'token' => env('CLOUDFLARE_ANALYTICS_TOKEN', null),
            'suppress_errors' => env('CLOUDFLARE_SUPPRESS_ERRORS', true),
        ],
    ],

    'google_analytics' => [
        'enabled' => env('GOOGLE_ANALYTICS_ENABLED', true),
        'tracking_id' => env('GOOGLE_ANALYTICS_ID', 'G-6KZ23B7ECV'),
        'suppress_errors' => env('GA_SUPPRESS_ERRORS', true),
    ],

    'error_handling' => [
        'suppress_blocked_scripts' => env('SUPPRESS_BLOCKED_SCRIPTS', true),
        'log_blocked_scripts' => env('LOG_BLOCKED_SCRIPTS', false),
        'development_debug' => env('ANALYTICS_DEBUG', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocked Scripts Whitelist
    |--------------------------------------------------------------------------
    |
    | Scripts that are commonly blocked by ad blockers but are safe to ignore
    |
    */
    'safe_to_block' => [
        'beacon.min.js',
        'cloudflareinsights',
        'googletagmanager.com',
        'google-analytics.com',
        'gtag',
        'analytics.js',
        'facebook.net',
        'doubleclick.net',
    ],
];
