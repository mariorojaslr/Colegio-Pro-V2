<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$due = \App\Models\CollegiateDue::find(92);
echo "Before: " . $due->status . "\n";
$due->update([
    'status' => 'paid',
    'paid_at' => now(),
    'payment_method' => 'Tarjeta de Crédito',
    'notes' => 'Pagado vía Financiación Externa / Tarjeta'
]);
$due->refresh();
echo "After: " . $due->status . "\n";
