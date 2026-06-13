<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateType;
use Illuminate\Http\Request;

class CertificateTypeController extends Controller
{
    public function index()
    {
        $types = CertificateType::where('school_id', auth()->user()->school_id)->get();
        return view('admin.certificate_types.index', compact('types'));
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
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'is_active' => true,
        ]);

        return back()->with('success', 'Trámite / Certificado creado exitosamente.');
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
            'requires_clearance' => $request->has('requires_clearance'),
            'requires_no_sanctions' => $request->has('requires_no_sanctions'),
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Trámite / Certificado actualizado.');
    }

    public function destroy(CertificateType $certificate_type)
    {
        if ($certificate_type->school_id !== auth()->user()->school_id) abort(403);
        $certificate_type->delete();
        return back()->with('success', 'Eliminado correctamente.');
    }
}
