<?php

// Simple API test script
// Run this with: php test_costume_api.php

$baseUrl = 'http://127.0.0.1:8000/api';

echo "Testing Costume API...\n\n";

// Test 1: Get all costumes
echo "TEST 1: GET /api/costumes\n";
echo "================================\n";
$response = file_get_contents("$baseUrl/costumes");
$data = json_decode($response, true);
if (isset($data['data']) && count($data['data']) > 0) {
    echo "✅ SUCCESS: Found " . count($data['data']) . " costumes\n";
    echo "First costume: " . $data['data'][0]['name'] . "\n";
} else {
    echo "❌ FAILED: No costumes found\n";
}
echo "\n";

// Test 2: Get single costume
echo "TEST 2: GET /api/costumes/1\n";
echo "================================\n";
$response = file_get_contents("$baseUrl/costumes/1");
$costume = json_decode($response, true);

if (isset($costume['id'])) {
    echo "✅ SUCCESS: Got costume details\n";
    echo "ID: " . $costume['id'] . "\n";
    echo "Name: " . ($costume['name'] ?? 'NULL') . "\n";
    echo "Brand: " . ($costume['brand'] ?? 'NULL') . "\n";
    echo "Price: " . ($costume['price_per_day'] ?? 'NULL') . "\n";
    echo "Available: " . ($costume['available'] ? 'Yes' : 'No') . "\n";
    echo "Sizes: " . (isset($costume['sizes']) ? count($costume['sizes']) : '0') . "\n";
    echo "Images: " . (isset($costume['images']) ? count($costume['images']) : '0') . "\n";
} else {
    echo "❌ FAILED: Could not get costume details\n";
    echo "Response: " . print_r($costume, true) . "\n";
}
echo "\n";

// Test 3: Check if data is complete
if (isset($costume['brand']) && $costume['brand'] &&
    isset($costume['price_per_day']) && $costume['price_per_day'] > 0 &&
    isset($costume['sizes']) && count($costume['sizes']) > 0) {
    echo "✅ ALL TESTS PASSED!\n";
    echo "The backend API is working correctly.\n";
    echo "If your Android app still shows empty data, rebuild the app.\n";
} else {
    echo "❌ DATA INCOMPLETE!\n";
    echo "Run: php artisan db:seed --class=CostumeSeeder\n";
}

