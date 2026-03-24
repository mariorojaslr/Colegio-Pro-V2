<?php

namespace App\Http\Controllers;

use App\Models\CollegiateDocument;
use App\Models\Collegiate;
use App\Models\ProfessionalCertificate;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * Valida públicamente un documento, certificado o recibo institucional.
     * Esta es la vista que ven terceros (bancos, juzgados, etc) al escanear el código QR.
     */
    public function show($uuid)
    {
        // 1. Buscamos primero en Certificados Institucionales (Emitidos por el Colegio)
        $certificate = ProfessionalCertificate::with(['collegiate', 'type'])
            ->where('uuid', $uuid)
            ->first();

        if ($certificate) {
            return view('validation.success', compact('certificate'));
        }

        // 2. Buscamos en Documentos del Legajo (Subidos por el Colegiado)
        $document = CollegiateDocument::with(['collegiate', 'requirement'])
            ->where('id', $uuid) // O usa UUID si lo configuraste
            ->first();

        if ($document && $document->status === 'approved') {
            return view('validation.success_document', compact('document'));
        }

        return view('validation.error', [
            'message' => 'El código de validación es inválido o el documento no se encuentra habilitado.'
        ]);
    }

    /**
     * Inactiva un certificado de un solo uso vinculándolo a un expediente/trámite específico.
     * Esto evita que el mismo certificado se use en múltiples trámites.
     */
    public function burn(Request $request, $uuid)
    {
        $certificate = ProfessionalCertificate::where('uuid', $uuid)->firstOrFail();

        // Verificamos si ya fue usado
        if ($certificate->used_at) {
            return back()->with('error', 'Este certificado ya fue utilizado anteriormente.');
        }

        // Verificamos si el tipo admite un solo uso
        if (!$certificate->type->is_single_use) {
            return back()->with('error', 'Este tipo de certificado no requiere validación de uso único.');
        }

        $request->validate([
            'expedient_number' => 'required|string|max:100',
            'notary_info' => 'nullable|string|max:255'
        ]);

        $certificate->update([
            'used_at' => now(),
            'used_for_expedient' => $request->expedient_number,
            'used_by_info' => $request->notary_info,
            'status' => 'used'
        ]);

        return back()->with('success', 'Certificado validado y vinculado al expediente correctamente.');
    }
}
