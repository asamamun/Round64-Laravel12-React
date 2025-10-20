<?php
// Test script to verify role-based order functionality

require_once 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

echo "Testing Role-Based Order Functionality\n";
echo "=====================================\n";

// Create a Guzzle HTTP client
$client = new Client([
    'base_uri' => 'http://localhost:8000/',
    'timeout' => 10,
]);

// Test data
$adminCredentials = [
    'email' => 'admin@example.com',
    'password' => 'password'
];

$customerCredentials = [
    'email' => 'customer@example.com',
    'password' => 'password'
];

try {
    // Register and login as admin
    echo "1. Registering admin user...\n";
    $response = $client->post('api/register', [
        'json' => [
            'name' => 'Admin User',
            'email' => $adminCredentials['email'],
            'password' => $adminCredentials['password'],
            'password_confirmation' => $adminCredentials['password']
        ]
    ]);
    
    $adminData = json_decode($response->getBody(), true);
    $adminToken = $adminData['token'];
    echo "   Admin registered successfully\n";
    
    // Update admin user to have admin role (you would normally do this in a seeder)
    // For testing purposes, we'll manually update the database
    echo "   Note: Remember to set the user role to 'admin' in the database\n";
    
    // Register and login as customer
    echo "2. Registering customer user...\n";
    $response = $client->post('api/register', [
        'json' => [
            'name' => 'Customer User',
            'email' => $customerCredentials['email'],
            'password' => $customerCredentials['password'],
            'password_confirmation' => $customerCredentials['password']
        ]
    ]);
    
    $customerData = json_decode($response->getBody(), true);
    $customerToken = $customerData['token'];
    echo "   Customer registered successfully\n";
    
    echo "=====================================\n";
    echo "Setup complete. To fully test the role-based functionality:\n";
    echo "1. Set the first user's role to 'admin' in the database\n";
    echo "2. Place an order as the customer\n";
    echo "3. As admin, fetch all orders (should see the customer's order)\n";
    echo "4. As admin, update the order status\n";
    echo "5. As customer, fetch their orders (should see the updated status)\n";
    
} catch (RequestException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    if ($e->hasResponse()) {
        echo "Status Code: " . $e->getResponse()->getStatusCode() . "\n";
        echo "Response Body:\n" . $e->getResponse()->getBody() . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}