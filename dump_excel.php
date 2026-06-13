<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Maatwebsite\Excel\Facades\Excel;

$filePath = __DIR__ . '/ACUERDO DE TRABAJO/Terapistas Ocupacionales/COLEGIO DE TERAPISTAS OCUPACIONALES DE LA RIOJA (respuestas).ods';

if (!file_exists($filePath)) {
    echo "File not found: " . $filePath;
    exit;
}

try {
    $data = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToArray {
        public function array(array $array) {}
    }, $filePath);

    if (!empty($data) && !empty($data[0])) {
        $headers = array_filter($data[0][0], function($v) { return !empty(trim($v)); });
        echo "Headers:\n";
        print_r($headers);
        
        $row1 = array_filter($data[0][1], function($v) { return !empty(trim($v)); });
        echo "\nFirst Row Data:\n";
        print_r($row1);
    } else {
        echo "No data found.";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
