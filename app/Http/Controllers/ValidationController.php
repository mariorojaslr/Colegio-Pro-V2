<?php

namespace App\Http\Controllers;

use App\Models\CollegiateDocument;
use App\Models\Collegiate;
use Illuminate\Http\Request;

class ValidationController extends Controller
{
    /**
     * Valida públicamente un documento, certificado o recibo institucional.
     * Esta es la vista que ven terceros al escanear el código QR.
     */
    public function show($uuid)
    {
        // En una implementación real, buscaríamos en una tabla de 'verifications'
        // Para la demo, usamos el ID del documento como 'token' si es numérico
        // o un UUID si lo configuramos. 
        
        $document = CollegiateDocument::with(['collegiate', 'requirement'])->find($uuid);

        if (!$document || $document->status !== 'approved') {
            return view('validation.error', [
                'message' => 'El código de validación es inválido o el documento ha sido revocado.'
            ]);
        }

        return view('validation.success', compact('document'));
    }
}
