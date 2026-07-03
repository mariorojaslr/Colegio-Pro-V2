<?php
/**
 * Script para exportar la base de datos completa de produccion desde la consola SSH.
 */

// 1. Cargar bootstrap de Laravel para acceder al entorno y base de datos
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 2. Obtener credenciales de base de datos del archivo .env actual de produccion
$databaseName = env('DB_DATABASE');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$host = env('DB_HOST', '127.0.0.1');

if (!$databaseName) {
    die("Error: No se pudo cargar la configuracion de la base de datos desde el .env.\n");
}

$filename = 'remoto.sql';
$filePath = __DIR__ . '/' . $filename;

echo "Iniciando exportacion de la base de datos '{$databaseName}' en el servidor...\n";

try {
    // 3. Generar el dump usando la libreria de ifsnop instalada en composer
    $dump = new \Ifsnop\Mysqldump\Mysqldump("mysql:host={$host};dbname={$databaseName}", $username, $password);
    $dump->start($filePath);

    if (file_exists($filePath)) {
        echo "¡Exito! La base de datos ha sido exportada correctamente en:\n";
        echo "=> {$filePath}\n";
        echo "Ahora puedes descargar el archivo 'remoto.sql' a tu PC local usando FileZilla o tu cliente FTP preferido.\n";
    } else {
        echo "Error: El archivo de backup no pudo ser creado en el disco.\n";
    }
} catch (\Exception $e) {
    echo "Error al generar el backup: " . $e->getMessage() . "\n";
}
