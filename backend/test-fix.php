<?php
// Test script to verify our fixes for the OrderController 500 error

require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Models\Address;
use App\Models\Product;

echo "Testing Model Fixes\n";
echo "==================\n";

// Set up Laravel container
$container = new Container();
$app = new \Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

// Test Address model creation
echo "1. Testing Address model creation...\n";
try {
    $address = Address::create([
        'user_id' => 1,
        'name' => 'Test User',
        'phone' => '1234567890',
        'address' => '123 Test St',
        'city' => 'Test City',
        'state' => 'TS',
        'zip_code' => '12345',
        'country' => 'Test Country',
        'is_default' => false,
    ]);
    
    echo "   ✓ Address created successfully\n";
    echo "   Address ID: " . $address->id . "\n";
    
    // Clean up
    $address->delete();
} catch (\Exception $e) {
    echo "   ✗ Address creation failed: " . $e->getMessage() . "\n";
}

// Test Product model access
echo "2. Testing Product model access...\n";
try {
    $product = Product::find(1);
    
    if ($product) {
        echo "   ✓ Product found successfully\n";
        echo "   Product name: " . $product->name . "\n";
    } else {
        echo "   ✗ Product not found\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Product access failed: " . $e->getMessage() . "\n";
}

echo "==================\n";
echo "Test completed. If both tests passed (✓), the fixes should resolve the 500 error.\n";