<?php
// Simple API test using cURL

echo "API Test Script\n";
echo "==============\n";

// Test registration
echo "1. Testing registration...\n";

$registerData = json_encode([
    'name' => 'Test User',
    'email' => 'test' . time() . '@example.com',
    'password' => 'password',
    'password_confirmation' => 'password'
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/register');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $registerData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($httpCode == 200 || $httpCode == 201) {
    $responseData = json_decode($response, true);
    $token = $responseData['access_token'] ?? null;
    
    if ($token) {
        echo "Registration successful! Token: " . $token . "\n";
        
        // Test order placement
        echo "\n2. Testing order placement...\n";
        
        $orderData = json_encode([
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
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/orders');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $orderData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $token
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $orderResponse = curl_exec($ch);
        $orderHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "Order HTTP Code: " . $orderHttpCode . "\n";
        echo "Order Response: " . $orderResponse . "\n";
    } else {
        echo "Registration succeeded but no token returned\n";
    }
} else {
    echo "Registration failed\n";
}

echo "==============\n";