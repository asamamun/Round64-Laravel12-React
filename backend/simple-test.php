<?php
// Simple test to check if Laravel API is responding

echo "Testing Laravel API Connection\n";
echo "=============================\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Accept: application/json'
    ]
]);

// Test the API endpoint
$url = 'http://localhost:8000/api/user';
echo "Testing: " . $url . "\n";

$result = @file_get_contents($url, false, $context);

if ($result === false) {
    echo "Error: Could not connect to the API\n";
    $error = error_get_last();
    if ($error) {
        echo "Details: " . $error['message'] . "\n";
    }
} else {
    echo "Response received:\n";
    echo $result . "\n";
}

echo "=============================\n";