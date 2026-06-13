<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateType;
use App\Models\ProfessionalCertificate;
use App\Models\EthicsSanction;
use Illuminate\Support\Str;

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
                                      ->where('is_lifted', false)
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

        // Check rules
        if ($type->requires_no_sanctions) {
            $hasSanctions = EthicsSanction::where('collegiate_id', $collegiate->id)
                                      ->where('is_lifted', false)
                                      ->where(function ($q) {
                                          $q->whereNull('end_date')
                                            ->orWhere('end_date', '>', now());
                                      })->exists();
            if ($hasSanctions) {
                return back()->with('error', 'No puede solicitar este trámite porque posee sanciones éticas activas.');
            }
        }

        if ($type->requires_clearance) {
            // Check debt
            $hasDebt = false; // TODO: Implement real debt check
            if ($hasDebt) {
                return back()->with('error', 'No puede solicitar este trámite porque posee deuda activa.');
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
            'issue_date' => now(),
            'expiration_date' => $type->validity_days ? now()->addDays($type->validity_days) : null,
            'validation_code' => strtoupper(Str::random(10)),
            'status' => $status,
        ]);

        return back()->with('success', 'Trámite generado exitosamente. Puede descargarlo a continuación.');
    }
}
