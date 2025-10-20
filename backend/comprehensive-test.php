<?php
// Comprehensive test to authenticate and place an order

echo "Comprehensive Order Test\n";
echo "=======================\n";

// First, let's try to authenticate
$loginData = [
    'email' => 'user@example.com',
    'password' => 'password'
];

$loginOptions = [
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        'content' => json_encode($loginData)
    ]
];

$loginContext = stream_context_create($loginOptions);
echo "1. Attempting to login...\n";

$loginResult = @file_get_contents('http://localhost:8000/api/login', false, $loginContext);

if ($loginResult === false) {
    echo "Login failed - could not connect to API\n";
    exit(1);
}

$loginResponse = json_decode($loginResult, true);
if (!isset($loginResponse['access_token'])) {
    echo "Login failed - no access token in response\n";
    echo "Response: " . $loginResult . "\n";
    
    // Let's try with a different user or check if we need to register first
    echo "Trying to register a new user...\n";
    
    $registerData = [
        'name' => 'Test User',
        'email' => 'test' . time() . '@example.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ];
    
    $registerOptions = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            'content' => json_encode($registerData)
        ]
    ];
    
    $registerContext = stream_context_create($registerOptions);
    $registerResult = @file_get_contents('http://localhost:8000/api/register', false, $registerContext);
    
    if ($registerResult !== false) {
        echo "Registration successful!\n";
        echo "Response: " . $registerResult . "\n";
        
        $registerResponse = json_decode($registerResult, true);
        if (isset($registerResponse['access_token'])) {
            $token = $registerResponse['access_token'];
            echo "Got token: " . $token . "\n";
        } else {
            echo "Registration succeeded but no token returned\n";
            exit(1);
        }
    } else {
        echo "Registration also failed\n";
        exit(1);
    }
} else {
    $token = $loginResponse['access_token'];
    echo "Login successful!\n";
    echo "Got token: " . $token . "\n";
}

// Now let's try to place an order
echo "\n2. Placing an order...\n";

$orderData = [
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
];

$orderOptions = [
    'http' => [
        'method' => 'POST',
        'header' => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ],
        'content' => json_encode($orderData)
    ]
];

$orderContext = stream_context_create($orderOptions);
$orderResult = @file_get_contents('http://localhost:8000/api/orders', false, $orderContext);

if ($orderResult === false) {
    echo "Order placement failed - could not connect to API\n";
    $error = error_get_last();
    if ($error) {
        echo "Error: " . $error['message'] . "\n";
    }
} else {
    echo "Order placement response:\n";
    echo $orderResult . "\n";
}

echo "=======================\n";