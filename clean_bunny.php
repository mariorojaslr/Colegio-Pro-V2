<?php

use App\Models\CollegiateDocument;
use Illuminate\Support\Facades\Http;

echo "Iniciando depuracion de BunnyCDN...\n";

$storageConfig = config('services.bunny.storage');
$baseUri = "https://{$storageConfig['region']}.bunnycdn.com/{$storageConfig['zone']}/";
$apiKey = $storageConfig['api_key'];
$pullZoneUrl = $storageConfig['pull_zone_url'];

// 1. Obtener todos los documentos válidos de la base de datos
$validDocs = CollegiateDocument::all();
$validPaths = [];
foreach ($validDocs as $doc) {
    if ($doc->file_url) {
        $validPaths[] = str_replace($pullZoneUrl . '/', '', $doc->file_url);
    }
    if ($doc->file_url_back) {
        $validPaths[] = str_replace($pullZoneUrl . '/', '', $doc->file_url_back);
    }
}

// 2. Carpetas a revisar (99 y 195)
$foldersToCheck = ['colegio-pro/docs/cotolar/99/', 'colegio-pro/docs/cotolar/195/'];

$deletedCount = 0;
foreach ($foldersToCheck as $folder) {
    echo "Revisando carpeta: $folder\n";
    $response = Http::withHeaders(['AccessKey' => $apiKey, 'Accept' => 'application/json'])
                    ->get($baseUri . $folder);
    
    if ($response->successful()) {
        $files = $response->json();
        foreach ($files as $file) {
            if (!$file['IsDirectory']) {
                $filePath = $folder . $file['ObjectName'];
                // Si el archivo no está en la base de datos, es basura vieja
                if (!in_array($filePath, $validPaths)) {
                    Http::withHeaders(['AccessKey' => $apiKey])->delete($baseUri . $filePath);
                    echo "[-] Borrado archivo basura: " . $file['ObjectName'] . "\n";
                    $deletedCount++;
                }
            }
        }
    } else {
        echo "No se pudo leer la carpeta $folder o esta vacia.\n";
    }
}

echo "====================================\n";
echo "Limpieza terminada. Archivos basura eliminados: $deletedCount\n";
echo "====================================\n";
