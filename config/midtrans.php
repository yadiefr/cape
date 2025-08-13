<?php

return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID', 'your-merchant-id'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'your-client-key'),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'your-server-key'),
    
    // Set to true for production, false for sandbox
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    
    // Set sanitization to true (default)
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    
    // Set 3DS to true
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
    
    // Snap settings
    'snap' => [
        'enabled_payments' => [
            'credit_card',
            'gopay',
            'shopeepay', 
            'other_qris',
            'bank_transfer',
            'echannel',
            'alfamart',
            'indomaret'
        ]
    ]
];
