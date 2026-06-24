<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$s = App\Models\School::where('slug', 'abogados')->first();
if ($s) {
    $s->logo = 'images/tenants/logo-abogados-redondo.png';
    $s->save();
    echo "Logo actualizado para Abogados.\n";
} else {
    echo "No se encontro la escuela abogados.\n";
}

// Check other schools
foreach(App\Models\School::all() as $school) {
    echo "ID: {$school->id} | Name: {$school->name} | Logo: {$school->logo}\n";
}
