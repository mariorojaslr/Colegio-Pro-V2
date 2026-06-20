<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use File;

class BackupVaultService
{
    /**
     * Generate an SQL dump of the entire database using mysqldump
     */
    public function generateDatabaseDump(): string
    {
        $databaseName = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');

        $filename = 'backup_datos_' . date('Y_m_d_His') . '.sql';
        $storagePath = storage_path('app/backups');
        
        if (!File::exists($storagePath)) {
            File::makeDirectory($storagePath, 0755, true);
        }

        $filePath = $storagePath . '/' . $filename;

        try {
            $dump = new \Ifsnop\Mysqldump\Mysqldump("mysql:host={$host};dbname={$databaseName}", $username, $password);
            $dump->start($filePath);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Backup failed: ' . $e->getMessage());
            throw $e;
        }

        return $filePath;
    }

    /**
     * Zip all digital certificates
     */
    public function generateCertificatesZip(): string
    {
        $certsPath = storage_path('app/certificates');
        $zipFilename = 'backup_certificados_' . date('Y_m_d_His') . '.zip';
        $zipPath = storage_path('app/backups/' . $zipFilename);

        if (!File::exists(storage_path('app/backups'))) {
            File::makeDirectory(storage_path('app/backups'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            if (File::exists($certsPath)) {
                $files = File::allFiles($certsPath);
                foreach ($files as $file) {
                    $zip->addFile($file->getRealPath(), $file->getRelativePathname());
                }
            } else {
                // Add a dummy file so zip is not empty
                $zip->addFromString('info.txt', 'No se encontraron certificados de AFIP en la plataforma.');
            }
            $zip->close();
        }

        return $zipPath;
    }

    /**
     * Generate Master Backup (Contains DB and Certificates, photos are heavy so maybe exclude or include a note)
     */
    public function generateMasterZip(): string
    {
        $dbFile = $this->generateDatabaseDump();
        $certsZip = $this->generateCertificatesZip();

        $masterFilename = 'resguardo_maestro_' . date('Y_m_d_His') . '.zip';
        $masterPath = storage_path('app/backups/' . $masterFilename);

        $zip = new ZipArchive();
        if ($zip->open($masterPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFile($dbFile, basename($dbFile));
            $zip->addFile($certsZip, basename($certsZip));
            $zip->addFromString('instrucciones.txt', 'Este es un resguardo maestro. Incluye la base de datos completa y los certificados. Las fotos del CDN deben descargarse por separado debido a su tamaño o configuracion asincrona.');
            $zip->close();
        }

        return $masterPath;
    }
}
