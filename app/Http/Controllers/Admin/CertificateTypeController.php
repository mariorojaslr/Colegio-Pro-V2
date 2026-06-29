<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
class CertificateTypeController extends Controller
{
    public function index()
    {
        $types = CertificateType::where('school_id', auth()->user()->school_id)->get();
        return view('admin.certificate_types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.certificate_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        CertificateType::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'validity_days' => $request->validity_days,
            'is_single_use' => $request->has('is_single_use'),
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'template_content' => $request->template_content,
            'has_qr' => $request->has('has_qr'),
            'is_active' => true,
        ]);

        return redirect()->route('admin.certificate_types.index')->with('success', 'Trámite / Certificado creado exitosamente.');
    }

    public function edit(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);
        return view('admin.certificate_types.edit', compact('certificate_type'));
    }

    public function update(Request $request, CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        $certificate_type->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'validity_days' => $request->validity_days,
            'is_single_use' => $request->has('is_single_use'),
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'template_content' => $request->template_content,
            'has_qr' => $request->has('has_qr'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.certificate_types.index')->with('success', 'Trámite / Certificado actualizado.');
    }

    public function destroy(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);
        $certificate_type->delete();
        return back()->with('success', 'Eliminado correctamente.');
    }

    public function preview(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);

        $school = auth()->user()->school;

        // Create a mock collegiate
        $collegiate = new \App\Models\Collegiate([
            'first_name' => 'Karina',
            'last_name' => 'Arias',
            'dni' => '12345678',
            'registration_number' => 'MAT-0001',
            'status' => 'active'
        ]);

        // Create a mock certificate
        $certificate = new \App\Models\Certificate([
            'uuid' => Str::uuid(),
            'code' => 'DEMO-123456',
            'issued_at' => now(),
            'expires_at' => $certificate_type->validity_days ? now()->addDays($certificate_type->validity_days) : null,
        ]);
        
        $certificate->setRelation('type', $certificate_type);

        $pdf = Pdf::loadView('pdf.certificate', compact('certificate', 'school', 'collegiate'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Vista_Previa_Certificado.pdf');
    }
}
