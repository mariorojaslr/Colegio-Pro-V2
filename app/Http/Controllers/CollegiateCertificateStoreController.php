<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\ProfessionalCertificate;
use App\Models\EthicsSanction;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CollegiateCertificateStoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $collegiate = $user->collegiate;
        
        if (!$collegiate) {
            return redirect()->route('home')->with('error', 'Debe estar registrado como colegiado para acceder a esta sección.');
        }

        $types = CertificateType::where('school_id', $user->school_id)
                                ->where('is_active', true)
                                ->get();

        // Check rules globally for this collegiate
        $hasSanctions = EthicsSanction::where('collegiate_id', $collegiate->id)
                                      ->where('status', 'active')
                                      ->where(function ($q) {
                                          $q->whereNull('end_date')
                                            ->orWhere('end_date', '>', now());
                                      })->exists();

        $hasDebt = false; // Simplified for now, should check membership fees

        $myCertificates = ProfessionalCertificate::where('collegiate_id', $collegiate->id)->latest()->get();

        return view('collegiates.certificates.store', compact('types', 'hasSanctions', 'hasDebt', 'myCertificates'));
    }

    public function purchase(Request $request, CertificateType $type)
    {
        $user = auth()->user();
        $collegiate = $user->collegiate;

        if ($type->school_id !== $user->school_id) abort(403);

        $exceptionRequested = $request->input('request_exception') == '1';
        $blockedReason = null;

        // Check rules
        if ($type->requires_no_sanctions) {
            $hasSanctions = EthicsSanction::where('collegiate_id', $collegiate->id)
                                      ->where('is_lifted', false)
                                      ->where(function ($q) {
                                          $q->whereNull('end_date')
                                            ->orWhere('end_date', '>', now());
                                      })->exists();
            if ($hasSanctions) {
                $blockedReason = 'Sanciones éticas activas';
            }
        }

        if (!$blockedReason && $type->requires_clearance) {
            // Check debt using our existing compliance system
            $collegiate->updateComplianceStatus();
            if (!$collegiate->is_fees_compliant) {
                $blockedReason = 'Deuda activa';
            }
        }

        if ($blockedReason) {
            if ($exceptionRequested) {
                ProfessionalCertificate::create([
                    'collegiate_id' => $collegiate->id,
                    'certificate_type_id' => $type->id,
                    'issued_at' => now(),
                    'expires_at' => $type->validity_days ? now()->addDays($type->validity_days) : null,
                    'status' => 'pending',
                ]);
                return back()->with('success', "Se ha generado una solicitud de excepción debido a: {$blockedReason}. Un administrador evaluará su caso para autorizar el certificado.");
            } else {
                return back()->with('error', "No puede solicitar este trámite porque posee {$blockedReason}.");
            }
        }

        // Logic for Payment (skip if free)
        if ($type->price > 0) {
            // Here you would redirect to payment gateway
            // For now, we simulate success
            $status = 'active';
        } else {
            $status = 'active';
        }

        ProfessionalCertificate::create([
            'collegiate_id' => $collegiate->id,
            'certificate_type_id' => $type->id,
            'issued_at' => now(),
            'expires_at' => $type->validity_days ? now()->addDays($type->validity_days) : null,
            'status' => $status,
        ]);

        return back()->with('success', 'Trámite generado exitosamente. Puede descargarlo a continuación.');
    }

    public function download(ProfessionalCertificate $certificate)
    {
        $user = auth()->user();
        if ($certificate->collegiate->user_id !== $user->id && !$user->hasPermission('manage_users') && !$user->isOwner()) {
            abort(403);
        }

        $school = $certificate->collegiate->school;
        $collegiate = $certificate->collegiate;

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate', 'school', 'collegiate'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Certificado_' . $certificate->code . '.pdf');
    }
}
