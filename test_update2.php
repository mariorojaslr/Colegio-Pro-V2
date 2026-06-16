<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$c = \App\Models\Collegiate::where('registration_number', 'MAT-2065')->first();
$dues = $c->dues;
foreach($dues as $d) {
    echo "ID: $d->id, Status: $d->status, Date: " . $d->due_date->format('Y-m-d') . "\n";
}
