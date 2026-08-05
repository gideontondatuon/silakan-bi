<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi layanan WhatsApp Gateway untuk notifikasi SILAKAN.
    | Mendukung Fonnte, Whacenter, Wablas, atau API gateway HTTP standar lainnya.
    |
    */

    'enabled' => env('WA_GATEWAY_ENABLED', true),

    'provider' => env('WA_GATEWAY_PROVIDER', 'fonnte'), // fonnte, whacenter, wablas, generic

    'api_token' => env('WA_GATEWAY_TOKEN', ''),

    'api_url' => env('WA_GATEWAY_URL', 'https://api.fonnte.com/send'),

    'admin_number' => env('ADMIN_WA_NUMBER', '081234567890'),
];
