<?php

// Simple test to verify the API endpoints are working
echo "Testing Student Performance Tracker API...\n";

// Test login
$loginData = [
    'email' => 'admin@gmail.com',
    'password' => 'password'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost:8000/api/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Login Test:\n";
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

if ($httpCode === 200) {
    $loginResponse = json_decode($response, true);
    $token = $loginResponse['access_token'] ?? null;
    
    if ($token) {
        echo "Login successful! Token received.\n";
        echo "API is ready for use.\n";
    } else {
        echo "Login failed - no token received.\n";
    }
} else {
    echo "Login failed with HTTP code: $httpCode\n";
}
