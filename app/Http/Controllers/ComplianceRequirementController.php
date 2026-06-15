<?php

namespace App\Http\Controllers;

use App\Models\ComplianceRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplianceRequirementController extends Controller
{
    /**
     * Muestra la tabla de configuración de requisitos del colegio.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $requirements = ComplianceRequirement::where('school_id', $schoolId)->get();
        
        return view('compliance_requirements.index', compact('requirements'));
    }

    /**
     * Crea un nuevo requisito de documentación (DNI, Título, etc.).
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:permanent,perentory,special',
            'expiry_frequency' => 'required|in:none,semester,year,fixed',
            'expiration_months' => 'nullable|integer|min:1',
            'is_mandatory' => 'boolean',
        ]);

        ComplianceRequirement::create([
            'school_id' => $user->school_id,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'expiry_frequency' => $request->expiry_frequency,
            'expiration_months' => $request->expiration_months,
            'is_mandatory' => $request->has('is_mandatory'),
            'delivery_method' => $request->delivery_method ?? 'digital',
        ]);

        return redirect()->route('compliance_requirements.index')->with('success', "¡Requisito '{$request->name}' creado correctamente!");
    }

    /**
     * Actualiza un requisito existente.
     */
    public function update(Request $request, ComplianceRequirement $requirement)
    {
        if (Auth::user()->school_id !== $requirement->school_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:permanent,perentory,special',
            'expiry_frequency' => 'required|in:none,semester,year,fixed',
            'expiration_months' => 'nullable|integer|min:1',
        ]);

        $requirement->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'expiry_frequency' => $request->expiry_frequency,
            'expiration_months' => $request->expiration_months,
            'is_mandatory' => $request->has('is_mandatory'),
            'delivery_method' => $request->delivery_method ?? 'digital',
        ]);

        return redirect()->route('compliance_requirements.index')->with('success', "Actualización de '{$requirement->name}' exitosa.");
    }

    /**
     * Elimina un requisito (ten en cuenta el impacto en trámites existentes).
     */
    public function destroy(ComplianceRequirement $requirement)
    {
        if (Auth::user()->school_id !== $requirement->school_id) abort(403);
        
        $requirement->delete();
        return redirect()->route('compliance_requirements.index')->with('success', "Requisito eliminado.");
    }
}
