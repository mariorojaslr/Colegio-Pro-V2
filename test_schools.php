<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$schools = \App\Models\School::all();
foreach($schools as $school) {
    echo "ID: " . $school->id . " | Name: " . $school->name . " | Colors: " . $school->primary_color . ", " . $school->secondary_color . " | Logo: " . $school->logo . " | News: " . $school->news()->count() . "\n";
}
