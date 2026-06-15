<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Servicio integral para la gestión de medios en Bunny.net.
 * Cubre almacenamiento de archivos (Storage) y streaming de video (Stream).
 */
class BunnyService
{
    protected $storageConfig;
    protected $streamConfig;

    public function __construct()
    {
        $this->storageConfig = config('services.bunny.storage');
        $this->streamConfig = config('services.bunny.stream');
    }

    /**
     * SUBIDA A STORAGE (Archivos e Imágenes)
     * 
     * @param string $localFilePath Ruta temporal del archivo
     * @param string $remoteFileName Nombre destino (sin el prefijo de la app)
     * @return string|bool URL pública del archivo o false
     */
    public function uploadFile($localFilePath, $remoteFileName)
    {
        $baseFolder = 'colegio-pro';
        $fullPath = "{$baseFolder}/{$remoteFileName}";
        
        // Fallback for local development / testing without Bunny keys
        if (empty($this->storageConfig['api_key']) || $this->storageConfig['api_key'] === 'tu_bunny_api_key_aqui') {
            $contents = file_get_contents($localFilePath);
            \Illuminate\Support\Facades\Storage::disk('public')->put($fullPath, $contents);
            return asset('storage/' . $fullPath);
        }

        $baseUri = "https://{$this->storageConfig['region']}.bunnycdn.com/{$this->storageConfig['zone']}/";

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'AccessKey' => $this->storageConfig['api_key'],
                'Content-Type' => 'application/octet-stream',
            ])->withBody(file_get_contents($localFilePath), 'application/octet-stream')
              ->put($baseUri . $fullPath);

            if ($response->successful()) {
                return $this->storageConfig['pull_zone_url'] . '/' . $fullPath;
            }

            Log::error('Bunny Storage Upload Fail: ' . $response->body());
            return false;
        } catch (\Exception $e) {
            Log::error('Bunny Storage Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * STREAMING DE VIDEO (Bunny Stream)
     * Crea un registro de video y sube el contenido.
     */
    public function uploadVideo($localFilePath, $title)
    {
        $libraryId = $this->streamConfig['library_id'];
        $apiKey = $this->streamConfig['api_key'];
        
        try {
            // 1. Crear el objeto de video en Bunny Stream
            $createResponse = Http::withHeaders([
                'AccessKey' => $apiKey,
                'Content-Type' => 'application/json',
            ])->post("https://video.bunnycdn.com/library/{$libraryId}/videos", [
                'title' => $title,
            ]);

            /** @var \Illuminate\Http\Client\Response $createResponse */
            if (!$createResponse->successful()) return false;

            $videoId = $createResponse->json()['guid'];

            // 2. Subir el archivo de video binario
            $uploadResponse = Http::withHeaders([
                'AccessKey' => $apiKey,
            ])->withBody(file_get_contents($localFilePath), 'application/octet-stream')
              ->put("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}");

            return $uploadResponse->successful() ? $videoId : false;

        } catch (\Exception $e) {
            Log::error('Bunny Stream Exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener el estado de procesamiento de un video
     */
    public function getVideo($videoId)
    {
        $libraryId = $this->streamConfig['library_id'];
        $response = Http::withHeaders([
            'AccessKey' => $this->streamConfig['api_key'],
        ])->get("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}");

        return $response->json();
    }

    /**
     * Eliminar contenido de Storage o Stream
     */
    public function deleteFile($remotePath)
    {
        $baseUri = "https://{$this->storageConfig['region']}.bunnycdn.com/{$this->storageConfig['zone']}/";
        $response = Http::withHeaders(['AccessKey' => $this->storageConfig['api_key']])
                        ->delete($baseUri . $remotePath);
        return $response->successful();
    }

    public function deleteVideo($videoId)
    {
        $libraryId = $this->streamConfig['library_id'];
        $response = Http::withHeaders(['AccessKey' => $this->streamConfig['api_key']])
                        ->delete("https://video.bunnycdn.com/library/{$libraryId}/videos/{$videoId}");
        return $response->successful();
    }
}
