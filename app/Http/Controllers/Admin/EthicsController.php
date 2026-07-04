<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collegiate;
use App\Models\EthicsSanction;
use App\Models\EthicsCommissionVote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EthicsController extends Controller
{
    /**
     * Gestión de sanciones y comisión de ética.
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        
        $activeSanctions = EthicsSanction::with('collegiate')
            ->whereHas('collegiate', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('status', 'active')
            ->latest()
            ->get();

        $history = EthicsSanction::with('collegiate')
            ->whereHas('collegiate', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->where('status', '!=', 'active')
            ->latest()
            ->paginate(15);

        // Miembros reales del Tribunal de Ética (desde la tabla board_members)
        $commissionMembers = \App\Models\BoardMember::where('school_id', $schoolId)
            ->where('department', 'Tribunal de Ética')
            ->with('collegiate')
            ->orderBy('order')
            ->get();
            
        // Autogenerar veedores iniciales si la tabla de la escuela está vacía
        if ($commissionMembers->isEmpty()) {
            $colls = Collegiate::where('school_id', $schoolId)->limit(3)->get();
            $roles = ['Presidente', 'Vocal', 'Suplente'];
            foreach ($colls as $idx => $coll) {
                \App\Models\BoardMember::create([
                    'school_id' => $schoolId,
                    'collegiate_id' => $coll->id,
                    'department' => 'Tribunal de Ética',
                    'role' => $roles[$idx] ?? 'Vocal',
                    'name' => $coll->first_name . ' ' . $coll->last_name,
                    'is_substitute' => ($roles[$idx] ?? 'Vocal') === 'Suplente',
                    'order' => $idx
                ]);
            }
            // Recargar miembros
            $commissionMembers = \App\Models\BoardMember::where('school_id', $schoolId)
                ->where('department', 'Tribunal de Ética')
                ->with('collegiate')
                ->orderBy('order')
                ->get();
        }

        // Cargar todos los colegiados para la administración de la comisión
        $collegiates = Collegiate::where('school_id', $schoolId)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
            
        // Reglas parametrizadas
        $rules = \App\Models\EthicsRule::where('school_id', $schoolId)->get();

        if ($rules->isEmpty()) {
            \App\Models\EthicsRule::create([
                'school_id' => $schoolId,
                'name' => 'Violación grave del deber de confidencialidad y secreto profesional',
                'description' => 'Revelación injustificada de datos personales, familiares o informes sociales de sujetos de intervención o sectores vulnerables.',
                'penalty_type' => 'temporary',
                'penalty_days' => 180,
            ]);

            \App\Models\EthicsRule::create([
                'school_id' => $schoolId,
                'name' => 'Desvío de recursos asistenciales y falsedad en informes socioeconómicos',
                'description' => 'Adjudicación fraudulenta de subsidios, manipulación de diagnósticos sociales para beneficio propio o de terceros, y desvío de recursos del sector de asistencia social.',
                'penalty_type' => 'permanent',
                'penalty_days' => null,
            ]);

            \App\Models\EthicsRule::create([
                'school_id' => $schoolId,
                'name' => 'Abuso de poder y hostigamiento contra destinatarios de servicios sociales',
                'description' => 'Trato denigrante, coacción o uso inapropiado del rol profesional para ejercer presión sobre personas vulnerables en programas de asistencia.',
                'penalty_type' => 'temporary',
                'penalty_days' => 90,
            ]);

            // Recargar reglas
            $rules = \App\Models\EthicsRule::where('school_id', $schoolId)->get();
        }

        return view('admin.ethics.index', compact('activeSanctions', 'history', 'commissionMembers', 'rules', 'collegiates'));
    }

    /**
     * Guardar una nueva regla de ética.
     */
    public function storeRule(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'penalty_type' => 'required|string',
            'penalty_days' => 'nullable|integer|min:1',
        ]);

        \App\Models\EthicsRule::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
            'penalty_type' => $request->penalty_type,
            'penalty_days' => $request->penalty_days,
        ]);

        return back()->with('success', 'Regla de ética creada correctamente.');
    }

    /**
     * Eliminar una regla de ética.
     */
    public function destroyRule(\App\Models\EthicsRule $rule)
    {
        if ($rule->school_id !== auth()->user()->school_id) abort(403);
        $rule->delete();
        return back()->with('success', 'Regla eliminada.');
    }

    /**
     * Registrar una nueva sanción basada en una regla.
     */
    public function createSanction(Request $request)
    {
        $request->validate([
            'collegiate_id' => 'required',
            'rule_id' => 'required|exists:ethics_rules,id',
            'start_date' => 'required|date',
        ]);

        $rule = \App\Models\EthicsRule::findOrFail($request->rule_id);
        
        $endDate = null;
        if ($rule->penalty_days) {
            $endDate = \Carbon\Carbon::parse($request->start_date)->addDays($rule->penalty_days);
        }

        EthicsSanction::create([
            'collegiate_id' => $request->collegiate_id,
            'type' => $rule->penalty_type,
            'reason' => $rule->name . ($request->notes ? ' - ' . $request->notes : ''),
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'status' => 'active',
        ]);

        return back()->with('success', 'Sanción ética registrada correctamente.');
    }

    /**
     * Levantar una sanción.
     */
    public function liftSanction(Request $request, EthicsSanction $sanction)
    {
        $request->validate(['lifted_reason' => 'required']);

        $sanction->update([
            'status' => 'lifted',
            'lifted_at' => now(),
            'lifted_reason' => $request->lifted_reason,
            'lifted_by' => auth()->id(),
            'approved_by_president' => true
        ]);

        return back()->with('success', 'Sanción levantada y registrada en el historial.');
    }

    /**
     * Añadir un miembro al Tribunal de Ética.
     */
    public function addCommissionMember(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $request->validate([
            'collegiate_id' => 'required|exists:collegiates,id',
            'role' => 'required|string|in:Presidente,Vocal,Suplente',
        ]);

        // Validar pertenencia del colegiado
        $collegiate = Collegiate::where('id', $request->collegiate_id)
            ->where('school_id', $schoolId)
            ->firstOrFail();

        // Validar si ya es miembro del Tribunal de Ética
        $exists = \App\Models\BoardMember::where('school_id', $schoolId)
            ->where('department', 'Tribunal de Ética')
            ->where('collegiate_id', $collegiate->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'El colegiado ya forma parte del Tribunal de Ética.');
        }

        // Crear miembro en board_members
        \App\Models\BoardMember::create([
            'school_id' => $schoolId,
            'collegiate_id' => $collegiate->id,
            'department' => 'Tribunal de Ética',
            'role' => $request->role,
            'name' => $collegiate->first_name . ' ' . $collegiate->last_name,
            'is_substitute' => $request->role === 'Suplente',
            'order' => $request->role === 'Presidente' ? 0 : ($request->role === 'Vocal' ? 1 : 2)
        ]);

        return back()->with('success', 'Miembro añadido al Tribunal de Ética correctamente.');
    }

    /**
     * Remover un miembro del Tribunal de Ética.
     */
    public function removeCommissionMember(\App\Models\BoardMember $member)
    {
        $schoolId = auth()->user()->school_id;

        if ($member->school_id !== $schoolId || $member->department !== 'Tribunal de Ética') {
            abort(403);
        }

        $member->delete();

        return back()->with('success', 'Miembro removido del Tribunal de Ética.');
    }

    /**
     * Descargar Libro de Actas completo en PDF.
     */
    public function downloadActBookPdf()
    {
        $schoolId = auth()->user()->school_id;
        $school = \App\Models\School::findOrFail($schoolId);
        
        $sanctions = EthicsSanction::with('collegiate')
            ->whereHas('collegiate', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $commissionMembers = \App\Models\BoardMember::where('school_id', $schoolId)
            ->where('department', 'Tribunal de Ética')
            ->with('collegiate')
            ->orderBy('order')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.ethics.pdf.act_book', compact('sanctions', 'school', 'commissionMembers'));
        
        return $pdf->download('libro-de-actas-' . strtolower(str_replace(' ', '-', $school->name)) . '.pdf');
    }

    /**
     * Descargar acta de sanción individual en PDF.
     */
    public function downloadSanctionActPdf(EthicsSanction $sanction)
    {
        $schoolId = auth()->user()->school_id;
        
        if ($sanction->collegiate->school_id !== $schoolId) {
            abort(403);
        }

        $school = \App\Models\School::findOrFail($schoolId);
        
        $commissionMembers = \App\Models\BoardMember::where('school_id', $schoolId)
            ->where('department', 'Tribunal de Ética')
            ->with('collegiate')
            ->orderBy('order')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.ethics.pdf.sanction_act', compact('sanction', 'school', 'commissionMembers'));
        
        return $pdf->download('acta-resolucion-' . $sanction->id . '-' . strtolower(str_replace(' ', '-', $sanction->collegiate->last_name)) . '.pdf');
    }
}
