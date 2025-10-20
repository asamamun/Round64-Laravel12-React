<?php
// Simple test script to verify order creation

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;

echo "Testing Order Creation\n";
echo "====================\n\n";

// Use the token from previous tests
$token = '4|04P6G4xj2wJeh6dMPW01knMfmu4YYVb0jR6pGeDfd05c1a6b';

echo "Using token: " . $token . "\n\n";

// Test creating an order
echo "1. Creating an order:\n";
$client = new Client([
    'base_uri' => 'http://localhost:8000/',
    'timeout' => 10,
]);

try {
    $response = $client->post('api/orders', [
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ],
        'json' => [
            'items' => [
                [
                    'product_id' => 1,
                    'quantity' => 2,
                    'price' => 299.99
                ]
            ],
            'total_amount' => 599.98,
            'shipping_address' => [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001'
            ]
        ]
    ]);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    echo "Response:\n" . $response->getBody() . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    // Check if it's a Guzzle HTTP exception
    if (method_exists($e, 'getResponse') && $e->getResponse()) {
        echo "Status Code: " . $e->getResponse()->getStatusCode() . "\n";
        echo "Response Body:\n" . $e->getResponse()->getBody() . "\n";
    }
}

echo "\n====================\n";