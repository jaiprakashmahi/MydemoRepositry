<?php

declare(strict_types=1);

// Load configuration
$config = require __DIR__ . '/../config/config.php';

// Load database connection
require_once __DIR__ . '/../config/database.php';

// Accept only POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// Read POST data
$response = $_POST;

// Log the callback
file_put_contents(
    __DIR__ . '/../logs/payment_callback.log',
    date('Y-m-d H:i:s') . PHP_EOL .
        print_r($response, true) . PHP_EOL,
    FILE_APPEND
);

// TODO: Verify the response hash/signature exactly as documented by Atom.
// if (!verifyAtomSignature($response, $config['response_hash_key'])) {
//     http_response_code(400);
//     exit('Invalid signature');
// }

// TODO: Look up the transaction by merchant transaction ID.

// TODO: Prevent duplicate processing (idempotency).

// TODO: Update payment status in the database.

// TODO: Update the admission/student record if payment succeeded.

// Respond as required by Atom's documentation.
echo 'OK';
