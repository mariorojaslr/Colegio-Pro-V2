<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = env('BUNNY_STORAGE_API_KEY') ?: env('BUNNY_API_KEY') ?: env('BUNNY_PASSWORD');
$zone = env('BUNNY_STORAGE_ZONE') ?: env('BUNNY_USERNAME');
$host = env('BUNNY_HOSTNAME');
$region = env('BUNNY_STORAGE_REGION') ?: ($host ? str_replace('.bunnycdn.com', '', $host) : 'storage');

$baseUri = "https://{$region}.bunnycdn.com/{$zone}/";
echo "Testing Bunny Upload...\n";
echo "Base URI: $baseUri\n";
echo "API Key length: " . strlen($key) . "\n";
echo "API Key starts with: " . substr($key, 0, 5) . "...\n";

// test upload a dummy file
$fileContent = "test file content";
$ch = curl_init($baseUri . "test_upload.txt");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "AccessKey: {$key}",
    "Content-Type: application/octet-stream"
));
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
