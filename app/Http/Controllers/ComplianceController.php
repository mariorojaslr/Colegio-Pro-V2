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

        $mandatoryReqsCount = $requirements->where('is_mandatory', true)->count();
        
        $validMandatoryDocsCount = 0;
        foreach ($requirements as $req) {
            if ($req->is_mandatory && isset($myDocuments[$req->id])) {
                $doc = $myDocuments[$req->id];
                if ($doc->status === 'approved') {
                    if (!$doc->expires_at || $doc->expires_at > now()) {
                        $validMandatoryDocsCount++;
                    }
                }
            }
        }

        return view('compliance.index', compact('requirements', 'myDocuments', 'collegiate', 'mandatoryReqsCount', 'validMandatoryDocsCount'));
    }

    /**
     * Sube un documento a Bunny.net y lo registra para aprobación.
     */
    public function upload(Request $request, ComplianceRequirement $requirement)
    {
        $request->validate([
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx|max:10240', // Máx 10MB
        ]);

        $user = Auth::user();
        
        if ($request->has('collegiate_id') && ($user->role === 'ADMIN_COLEGIO' || $user->isOwner())) {
            $collegiate = Collegiate::findOrFail($request->collegiate_id);
        } else {
            $collegiate = Collegiate::where('user_id', $user->id)->first();
        }

        if (!$collegiate) {
            return back()->with('error', 'Colegiado no encontrado.');
        }

        // Estructura de Bunny: colegio-pro/docs/{school_slug}/{registration_number}/{req_name}_{timestamp}.ext
        $file = $request->file('document');
        $extension = strtolower($file->getClientOriginalExtension());
        $remoteName = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_" . time() . ".{$extension}";

        $uploadPath = $file->getPathname();

        // COMPRESIÓN DE IMÁGENES
        // Si el archivo es una imagen, lo comprimimos antes de enviarlo a Bunny.net
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            try {
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $image = $manager->read($uploadPath);
                
                // Redimensionar proporcionalmente para que no exceda 1200px de ancho/alto
                $image->scaleDown(width: 1200, height: 1200);
                
                // Guardar en temp
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.' . $extension;
                
                if ($extension === 'png') {
                    $image->toPng()->save($tempPath);
                } else {
                    $image->toJpeg(quality: 75)->save($tempPath);
                }
                
                $uploadPath = $tempPath;
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Error comprimiendo imagen: " . $e->getMessage());
                // Si falla la compresión, intentamos subir el original
            }
        }

        $result = $this->bunny->uploadFile($uploadPath, $remoteName);

        if (!$result['success']) {
            return back()->with('error', 'Error al subir el archivo a la nube. Detalle: ' . $result['error']);
        }

        // Limpiar archivo temporal si fue creado
        if ($uploadPath !== $file->getPathname() && file_exists($uploadPath)) {
            @unlink($uploadPath);
        }

        // Registrar o actualizar el documento del colegiado
        CollegiateDocument::updateOrCreate(
            ['collegiate_id' => $collegiate->id, 'compliance_requirement_id' => $requirement->id],
            [
                'file_url' => $result['url'],
                'status' => 'pending', // Siempre vuelve a pendiente tras una resubida
                'admin_notes' => null,   // Limpiar notas anteriores si es una corrección
            ]
        );

        return back()->with('success', "¡El documento '{$requirement->name}' ha sido enviado para revisión!");
    }
}
