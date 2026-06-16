<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$response = $kernel->handle($request);

echo "API KEY from config: " . config('services.bunny.storage.api_key') . "\n";
echo "REGION from config: " . config('services.bunny.storage.region') . "\n";
