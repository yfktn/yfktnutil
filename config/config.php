<?php

return [
    'recaptcha' => [
        'enabled' => true,
        'secretKey' => env('RECAPTCHA_SECRET_KEY', ''),
        'siteKey' => env('RECAPTCHA_SITE_KEY', '')
    ],
];