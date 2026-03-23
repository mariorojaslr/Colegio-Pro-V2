<?php

namespace App\Http\Controllers;

use App\Models\ComplianceRequirement;
use App\Models\CollegiateDocument;
use App\Models\Collegiate;
use App\Services\BunnyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ComplianceController extends Controller
{
    protected $bunny;

    public function __construct(BunnyService $bunny)
    {
        $this->bunny = $bunny;
    }

    /**
     * Muestra el estado del legajo digital al colegiado.
     */
    public function index()
    {
        $user = Auth::user();
        $school = $user->school;
        $collegiate = Collegiate::where('user_id', $user->id)->first();

        if (!$collegiate) abort(404, 'No se encontró el perfil de colegiado.');

        // Obtener requisitos del colegio y documentos ya subidos por este usuario
        $requirements = ComplianceRequirement::where('school_id', $school->id)->get();
        $myDocuments = CollegiateDocument::where('collegiate_id', $collegiate->id)->get()->keyBy('requirement_id');

        return view('compliance.index', compact('requirements', 'myDocuments', 'collegiate'));
    }

    /**
     * Sube un documento a Bunny.net y lo registra para aprobación.
     */
    public function upload(Request $request, ComplianceRequirement $requirement)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Máx 5MB
        ]);

        $user = Auth::user();
        $collegiate = Collegiate::where('user_id', $user->id)->first();

        // Estructura de Bunny: colegio-pro/docs/{school_slug}/{registration_number}/{req_name}_{timestamp}.ext
        $file = $request->file('document');
        $extension = $file->getClientOriginalExtension();
        $remoteName = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_" . time() . ".{$extension}";

        $url = $this->bunny->uploadFile($file->getPathname(), $remoteName);

        if (!$url) {
            return back()->with('error', 'Error al subir el archivo a la nube. Intente nuevamente.');
        }

        // Registrar o actualizar el documento del colegiado
        CollegiateDocument::updateOrCreate(
            ['collegiate_id' => $collegiate->id, 'requirement_id' => $requirement->id],
            [
                'file_url' => $url,
                'status' => 'pending', // Siempre vuelve a pendiente tras una resubida
                'admin_notes' => null,   // Limpiar notas anteriores si es una corrección
                'uploaded_at' => now(),
            ]
        );

        return back()->with('success', "¡El documento '{$requirement->name}' ha sido enviado para revisión!");
    }
}
