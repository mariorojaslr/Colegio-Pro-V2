<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * Genera y descarga el PDF del certificado.
     */
    public function download(Certificate $certificate)
    {
        // Seguridad: El certificado debe pertenecer al usuario logueado
        if ($certificate->user_id !== Auth::id()) {
            abort(403, 'No tiene permiso para descargar este certificado.');
        }

        $certificate->load(['user', 'lesson']);

        $pdf = Pdf::loadView('certificates.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape'); // Formato horizontal premium

        return $pdf->download("Certificado_{$certificate->code}.pdf");
    }
}
