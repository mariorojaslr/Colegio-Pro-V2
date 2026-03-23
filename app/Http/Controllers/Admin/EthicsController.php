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

        return view('admin.ethics.index', compact('activeSanctions', 'history', 'commissionMembers'));
    }

    /**
     * Registrar una nueva sanción.
     */
    public function createSanction(Request $request)
    {
        $request->validate([
            'collegiate_id' => 'required',
            'type' => 'required', // temporary, permanent
            'reason' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        EthicsSanction::create($request->all());

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
