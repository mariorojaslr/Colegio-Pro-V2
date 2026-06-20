<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach(\App\Models\User::all() as $u) { 
    echo $u->email . ' - School ID: ' . $u->school_id . ' - canUpload: ' . ($u->school ? ($u->school->canUploadFile(0)?'true':'false') : 'NO SCHOOL') . "\n"; 
}
