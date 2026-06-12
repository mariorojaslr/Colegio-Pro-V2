<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collegiate;
use Illuminate\Support\Facades\Auth;

class CollegiateController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);

        $baseQuery = $user->isOwner() ? Collegiate::query() : $user->school->collegiates();

        // 📊 Calculamos Métricas para las Tarjetas Superiores
        $stats = [
            'total' => (clone $baseQuery)->count(),
            'debt_fees' => (clone $baseQuery)->where('is_fees_compliant', false)->count(),
            'debt_docs' => (clone $baseQuery)->where('is_fully_documented', false)->count(),
            'enabled' => (clone $baseQuery)->where('is_fees_compliant', true)
                                          ->where('is_fully_documented', true)
                                          ->where('is_ethics_compliant', true)
                                          ->count(),
        ];

        $query = clone $baseQuery;

        // 🔍 Búsqueda General por Texto (Nombre, Apellido, Matrícula, DNI, Email, Teléfono)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('registration_number', 'like', "%$search%")
                  ->orWhere('dni', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

        // 🎯 Filtros Específicos de Gestión
        if ($request->filter === 'morosos') {
            $query->where('is_fees_compliant', false);
        } elseif ($request->filter === 'sin_papeles') {
            $query->where('is_fully_documented', false);
        } elseif ($request->filter === 'habilitados') {
            $query->where('is_fees_compliant', true)
                  ->where('is_fully_documented', true)
                  ->where('is_ethics_compliant', true);
        }

        // 📏 Densidad de Datos (Paginación Personalizada)
        $perPage = (int) $request->get('per_page', 15);
        if (!in_array($perPage, [5, 10, 15, 20, 50, 100])) $perPage = 15;

        $collegiates = $query->orderBy('last_name')->paginate($perPage);
        
        return view('collegiates.index', compact('collegiates', 'stats', 'perPage'));
    }

    public function show(Collegiate $collegiate)
    {
        $user = Auth::user();
        
        // Seguridad: Solo admin del mismo colegio puede ver
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        // Cargamos los documentos entregados y las cuotas de este colegio
        $collegiate->load(['documents.requirement', 'dues' => function($q) {
            $q->orderBy('due_date', 'desc');
        }]);
        
        $requirements = \App\Models\ComplianceRequirement::where('school_id', $collegiate->school_id)
            ->get();

        return view('collegiates.show', compact('collegiate', 'requirements'));
    }

    public function update(Request $request, Collegiate $collegiate)
    {
        $user = Auth::user();
        
        // Seguridad: Solo admin del mismo colegio puede editar
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'dni' => 'required|string|max:20',
            'registration_number' => 'required|string|max:50',
            'professional_situation' => 'required|string|max:100',
            'financial_situation_note' => 'nullable|string'
        ]);

        $collegiate->update($request->all());

        return back()->with('success', 'Ficha del colegiado actualizada correctamente.');
    }

    /**
     * Genera el Certificado de Habilitación Profesional.
     */
    public function certificate(Collegiate $collegiate)
    {
        $user = Auth::user();

        // 1. Verificación de permisos
        if (!$user->isOwner() && $collegiate->school_id !== $user->school_id) abort(403);

        // 2. Verificación de Habilitación Real
        if (!$collegiate->isEnabledForCertificates()) {
            return redirect()->route('collegiates.show', $collegiate)
                ->with('error', 'El colegiado no cumple con los requisitos para emitir el certificado.');
        }

        // 3. Retornamos la vista del certificado (diseñada para imprimir/PDF)
        return view('collegiates.certificate', compact('collegiate'));
    }

    public function import()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);
        return view('collegiates.import');
    }

    public function storeImport(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\CollegiatesImport($user->school_id), $request->file('file'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "Error en importación masiva: " . $e->getMessage());
        }

        return redirect()->route('collegiates.index')->with('success', "¡Proceso terminado! Se ha importado/actualizado el padrón exitosamente.");
    }

    /**
     * Exporta el padrón completo a Excel.
     */
    public function export()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO' && !$user->isOwner()) abort(403);

        $fileName = 'padron_profesional_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CollegiatesExport($user->school_id), $fileName);
    }
}
