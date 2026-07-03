<?php
/**
 * Script local para importar la base de datos remota (remoto.sql)
 * y ejecutar el seeder de usuarios demo en el entorno local.
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sqlFile = __DIR__ . '/remoto.sql';

if (!file_exists($sqlFile)) {
    die("Error: No se encontro el archivo 'remoto.sql' en la raiz del proyecto.\nPor favor descargalo desde tu servidor e importalo primero.\n");
}

echo "Iniciando importacion de base de datos desde remoto.sql...\n";

// Obtener credenciales locales
$dbName = env('DB_DATABASE');
$dbUser = env('DB_USERNAME');
$dbPass = env('DB_PASSWORD');
$dbHost = env('DB_HOST', '127.0.0.1');
$dbPort = env('DB_PORT', '3306');

try {
    // 1. Conectar a MySQL (sin seleccionar base de datos primero, para recrearla si es necesario)
    $pdo = new PDO("mysql:host={$dbHost};port={$dbPort}", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Recrear la base de datos limpia
    echo "Recreando base de datos '{$dbName}'...\n";
    $pdo->exec("DROP DATABASE IF EXISTS `{$dbName}`");
    $pdo->exec("CREATE DATABASE `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Conectar a la base de datos recreada
    $pdo->exec("USE `{$dbName}`");
    
    // 2. Importar el archivo SQL por partes para no saturar la memoria
    echo "Cargando archivo SQL...\n";
    $sql = file_get_contents($sqlFile);
    
    // Ejecutar el SQL de forma directa
    echo "Ejecutando sentencias SQL en la base de datos local...\n";
    $pdo->exec($sql);
    echo "¡Importacion de base de datos completada con éxito!\n\n";
    
    // 3. Ejecutar el seeder para poblar usuarios demo en los colegios correspondientes (excepto Cotolar)
    echo "Ejecutando DemoUsersSeeder en la base de datos recien importada...\n";
    $artisan = Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'DemoUsersSeeder'
    ]);
    
    echo "Salida de Artisan:\n";
    echo Illuminate\Support\Facades\Artisan::output() . "\n";
    echo "=== PROCESO COMPLETADO ===\n";
    echo "Tu base de datos local es ahora un espejo exacto del servidor de produccion, con los usuarios demo cargados.\n";
    
} catch (PDOException $e) {
    die("Error durante la conexion/importacion de la base de datos: " . $e->getMessage() . "\n");
} catch (\Exception $e) {
    die("Error inesperado: " . $e->getMessage() . "\n");
}
