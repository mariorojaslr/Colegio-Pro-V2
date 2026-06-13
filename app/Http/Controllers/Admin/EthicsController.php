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

        // Miembros de la comisión de ética (Simulados: Admnistradores de la misma escuela)
        $commissionMembers = \App\Models\User::where('school_id', $schoolId)
            ->where('role', 'ADMIN_COLEGIO')
            ->get();
            
        // Reglas parametrizadas
        $rules = \App\Models\EthicsRule::where('school_id', $schoolId)->get();

        return view('admin.ethics.index', compact('activeSanctions', 'history', 'commissionMembers', 'rules'));
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
}
