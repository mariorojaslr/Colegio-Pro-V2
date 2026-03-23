<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collegiate;
use App\Models\MembershipFee;
use App\Models\CollegiateDue;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Vista general de cobros y estados de cuenta de los colegiados.
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;
        
        // Métricas rápidas
        $stats = [
            'total_to_collect' => CollegiateDue::where('status', 'pending')->sum('amount'),
            'total_overdue' => CollegiateDue::where('status', 'overdue')->sum('amount'),
            'total_collected' => CollegiateDue::where('status', 'paid')->sum('amount'),
            'count_active' => Collegiate::where('school_id', $schoolId)->where('status', 'active')->count(),
            'count_overdue_users' => CollegiateDue::where('status', 'overdue')->distinct('collegiate_id')->count('collegiate_id'),
        ];

        // Listado de colegiados con su estado de deuda más reciente
        $collegiates = Collegiate::where('school_id', $schoolId)
            ->with(['dues' => function($q) {
                $q->orderBy('due_date', 'desc');
            }])
            ->paginate(20);

        $activeFee = MembershipFee::where('school_id', $schoolId)->where('is_active', true)->latest()->first();

        return view('admin.billing.index', compact('stats', 'collegiates', 'activeFee'));
    }

    /**
     * Muestra el historial completo de un colegiado.
     */
    public function collegiateHistory(Collegiate $collegiate)
    {
        $dues = $collegiate->dues()->orderBy('due_date', 'desc')->get();
        return view('admin.billing.history', compact('collegiate', 'dues'));
    }

    /**
     * Marcar una cuota como pagada manualmente.
     */
    public function markAsPaid(CollegiateDue $due)
    {
        $due->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_reference' => 'MANUAL-' . auth()->id() . '-' . time()
        ]);

        return back()->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Ajustar el monto de la cuota societaria para el colegio.
     */
    public function updateFee(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:0']);
        
        $schoolId = auth()->user()->school_id;

        // Desactivamos la anterior
        MembershipFee::where('school_id', $schoolId)->update(['is_active' => false]);

        // Creamos la nueva
        MembershipFee::create([
            'school_id' => $schoolId,
            'amount' => $request->amount,
            'effective_date' => Carbon::now()->startOfMonth(),
            'is_active' => true
        ]);

        return back()->with('success', 'Monto de cuota societaria actualizado.');
    }
}
