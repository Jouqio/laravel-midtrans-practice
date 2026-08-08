<?php

// Tambahkan blok array 'midtrans' ini ke dalam file config/services.php
// yang sudah ada di project Laravel Anda (jangan timpa seluruh file,
// cukup gabungkan bagian ini ke dalam array yang sudah ada).

return [

    // ... isi services.php yang sudah ada (mailgun, postmark, dll) tetap dibiarkan ...

    'midtrans' => [
        'merchant_id'    => env('MIDTRANS_MERCHANT_ID'),
        'client_key'     => env('MIDTRANS_CLIENT_KEY'),
        'server_key'     => env('MIDTRANS_SERVER_KEY'),
        'is_production'  => env('MIDTRANS_IS_PRODUCTION', false),
        'is_sanitized'   => env('MIDTRANS_IS_SANITIZED', true),
        'is_3ds'         => env('MIDTRANS_IS_3DS', true),
    ],

];
