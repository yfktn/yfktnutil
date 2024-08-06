<?php

return [
    'recaptcha' => [
        'enabled' => true,
        'secretKey' => env('RECAPTCHA_SECRET_KEY', ''),
        'siteKey' => env('RECAPTCHA_SITE_KEY', '')
    ],
    'fonnte' => [
        'base_url' => env('FONNTE_BASE_URL', 'https://api.fonnte.com'),
        'token' => env('FONNTE_TOKEN'),
        'fallback_recipient' => env('FONNTE_FALLBACK_RECIPIENT'),
    ]
];