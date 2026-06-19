<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProfessionalCertificate;

class CertificateController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        $certificates = ProfessionalCertificate::whereHas('type', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->where('status', 'pending')->latest()->get();

        return view('admin.certificates.index', compact('certificates'));
    }

    public function approve(ProfessionalCertificate $certificate)
    {
        if ($certificate->collegiate->school_id !== auth()->user()->school_id) abort(403);

        $certificate->update(['status' => 'valid']);
        return back()->with('success', 'El certificado ha sido aprobado como excepción y ya está disponible para el colegiado.');
    }

    public function reject(ProfessionalCertificate $certificate)
    {
        if ($certificate->collegiate->school_id !== auth()->user()->school_id) abort(403);

        $certificate->update(['status' => 'revoked', 'revoked_reason' => 'Solicitud rechazada por administración.']);
        return back()->with('success', 'La solicitud ha sido rechazada.');
    }
}
