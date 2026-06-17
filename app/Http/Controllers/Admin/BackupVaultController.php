<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BackupVaultService;
use App\Jobs\BackupPhotosJob;

class BackupVaultController extends Controller
{
    protected $backupService;

    public function __construct(BackupVaultService $backupService)
    {
        $this->backupService = $backupService;
    }

    public function index()
    {
        return view('admin.settings.vault');
    }

    public function downloadData()
    {
        try {
            $filePath = $this->backupService->generateDatabaseDump();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el respaldo de datos: ' . $e->getMessage());
        }
    }

    public function downloadPhotos()
    {
        // Despachamos un Job en segundo plano para no bloquear el servidor
        BackupPhotosJob::dispatch(auth()->user());

        return redirect()->back()->with('success', 'La descarga de fotos se ha iniciado en segundo plano. Recibirá una notificación cuando el archivo ZIP esté listo para descargar.');
    }

    public function downloadCertificates()
    {
        try {
            $filePath = $this->backupService->generateCertificatesZip();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al empaquetar los certificados: ' . $e->getMessage());
        }
    }

    public function downloadMaster()
    {
        try {
            $filePath = $this->backupService->generateMasterZip();
            return response()->download($filePath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el resguardo maestro: ' . $e->getMessage());
        }
    }
}
