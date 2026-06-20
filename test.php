<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s1 = \App\Models\School::find(1);
$s2 = \App\Models\School::find(2);

echo "School 1: " . ($s1->canUploadFile(0) ? 'true' : 'false') . "\n";
echo "School 1 storage used: " . $s1->storage_used . "\n";
echo "School 1 max storage: " . $s1->activeSubscription?->plan?->max_storage . "\n";

echo "School 2: " . ($s2->canUploadFile(0) ? 'true' : 'false') . "\n";
echo "School 2 storage used: " . $s2->storage_used . "\n";
echo "School 2 max storage: " . $s2->activeSubscription?->plan?->max_storage . "\n";
