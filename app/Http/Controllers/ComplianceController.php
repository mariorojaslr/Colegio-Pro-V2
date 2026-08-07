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
        // Validar normal y base64
        $request->validate([
            'document' => 'nullable|file|mimes:jpg,jpeg,png,pdf,xls,xlsx,doc,docx|max:10240',
            'cropped_image' => 'nullable|string',
            'cropped_image_back' => 'nullable|string',
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

        $url = null;
        $url_back = null;

        // Buscar documento existente para limpiar archivos viejos de Bunny
        $existingDoc = CollegiateDocument::where('collegiate_id', $collegiate->id)
            ->where('compliance_requirement_id', $requirement->id)
            ->first();

        // --- PROCESAR FRENTE ---
        if ($request->filled('cropped_image')) {
            $base64 = $request->input('cropped_image');
            $data = explode(',', $base64);
            if (count($data) > 1) {
                $imageData = base64_decode($data[1]);
                $tempPath = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                file_put_contents($tempPath, $imageData);
                $remoteName = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_front_" . time() . ".jpg";
                $result = $this->bunny->uploadFile($tempPath, $remoteName);
                if ($result['success']) $url = $result['url'];
            }
        } elseif ($request->hasFile('document')) {
            $file = $request->file('document');
            $extension = strtolower($file->getClientOriginalExtension());
            $remoteName = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_" . time() . ".{$extension}";
            $result = $this->bunny->uploadFile($file->getPathname(), $remoteName);
            if ($result['success']) $url = $result['url'];
        }

        if (!$url) {
            return back()->with('error', 'Error al procesar el archivo principal.');
        }

        // --- PROCESAR DORSO (Si existe) ---
        if ($request->filled('cropped_image_back')) {
            $base64_back = $request->input('cropped_image_back');
            $data_back = explode(',', $base64_back);
            if (count($data_back) > 1) {
                $imageDataBack = base64_decode($data_back[1]);
                $tempPathBack = sys_get_temp_dir() . '/' . uniqid() . '.jpg';
                file_put_contents($tempPathBack, $imageDataBack);
                $remoteNameBack = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_back_" . time() . ".jpg";
                $result = $this->bunny->uploadFile($tempPathBack, $remoteNameBack);
                if ($result['success']) $url_back = $result['url'];
            }
        } elseif ($request->hasFile('document_back')) {
            $file_back = $request->file('document_back');
            $extension_back = strtolower($file_back->getClientOriginalExtension());
            $remoteNameBack = "docs/{$user->school->slug}/{$collegiate->registration_number}/" . Str::slug($requirement->name) . "_back_" . time() . ".{$extension_back}";
            $result = $this->bunny->uploadFile($file_back->getPathname(), $remoteNameBack);
            if ($result['success']) $url_back = $result['url'];
        }

        // Borrar archivos viejos si fueron reemplazados exitosamente
        if ($existingDoc) {
            $pullZoneUrl = config('services.bunny.storage.pull_zone_url');
            if ($url && $existingDoc->file_url) {
                $oldPath = str_replace($pullZoneUrl . '/', '', $existingDoc->file_url);
                $this->bunny->deleteFile($oldPath);
            }
            if ($url_back && $existingDoc->file_url_back) {
                $oldPathBack = str_replace($pullZoneUrl . '/', '', $existingDoc->file_url_back);
                $this->bunny->deleteFile($oldPathBack);
            }
        }

        // Buscar o crear el registro
        CollegiateDocument::updateOrCreate(
            [
                'collegiate_id' => $collegiate->id,
                'compliance_requirement_id' => $requirement->id,
            ],
            [
                'file_url' => $url ?: ($existingDoc ? $existingDoc->file_url : null),
                'file_url_back' => $url_back ?: ($existingDoc ? $existingDoc->file_url_back : null),
                'status' => 'pending',
                'admin_notes' => null, // Limpiar notas previas si re-sube
            ]
        );

        return back()->with('success', 'Documento enviado para revisión.');
    }
}
