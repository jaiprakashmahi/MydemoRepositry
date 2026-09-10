<?php
/**
 * Atom Payment Gateway Configuration
 * Store this file outside the web root in production
 */
return [
    'atom' => [
        'url' => 'https://payment.atomtech.in/paynetz/epi/fts',
        'login_id' => 'YOUR_LOGIN_ID',
        'password' => 'YOUR_PASSWORD',
        'product_id' => 'YOUR_PRODUCT_ID',
        'client_code' => 'YOUR_CLIENT_CODE',
        'hash_key' => 'YOUR_HASH_KEY_SECRET',
        'request_hash_key' => 'YOUR_REQUEST_HASH_KEY',
        'response_hash_key' => 'YOUR_RESPONSE_HASH_KEY',
        'merchant_name' => 'YOUR_MERCHANT_NAME',
        'return_url' => 'https://yourdomain.com/wallet/callback.php',
        'test_mode' => true, // Set to false for production
        'test_url' => 'https://payment.atomtech.in/paynetz/epi/fts',
        'prod_url' => 'https://payment.atomtech.in/paynetz/epi/fts'
    ]
];