<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupPhotosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Logica simulada para zipear las fotos del CDN
            Log::info("Iniciando respaldo de fotos para el usuario {$this->user->id}");
            
            // En un caso real: iterar por Storage::disk('bunny')->allFiles() y zipearlos localmente.
            // ...
            
            Log::info("Respaldo de fotos finalizado para el usuario {$this->user->id}");
            
            // Aca idealmente se mandaría una notificación o un email:
            // $this->user->notify(new \App\Notifications\BackupReadyNotification($zipUrl));
        } catch (\Exception $e) {
            Log::error("Error al respaldar fotos: " . $e->getMessage());
        }
    }
}
