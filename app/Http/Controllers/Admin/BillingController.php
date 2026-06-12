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
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        // Query base para colegiados de esta escuela
        $query = Collegiate::where('school_id', $schoolId)
                    ->with(['dues' => function($q) {
                        $q->orderBy('due_date', 'desc');
                    }]);

        // Filtro por Búsqueda (Criterio: Nombre, DNI, Matrícula)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhere('registration_number', 'like', "%{$search}%");
            });
        }

        // Filtro por Estado (Moroso / Al Día) - Sincronizado con Padrón
        if ($statusFilter === 'overdue' || $statusFilter === 'morosos') {
            $query->where('is_fees_compliant', false);
        } elseif ($statusFilter === 'compliant' || $statusFilter === 'habilitados') {
            $query->where('is_fees_compliant', true);
        }

        $collegiates = $query->paginate(30)->withQueryString();
        
        // Métricas calculadas para Dashboard (Solo de esta escuela)
        $stats = [
            'total_to_collect' => CollegiateDue::whereHas('collegiate', fn($q) => $q->where('school_id', $schoolId))
                ->where('status', 'pending')->sum('amount'),
            'total_overdue' => CollegiateDue::whereHas('collegiate', fn($q) => $q->where('school_id', $schoolId))
                ->where('status', 'overdue')->sum('amount'),
            'total_collected' => CollegiateDue::whereHas('collegiate', fn($q) => $q->where('school_id', $schoolId))
                ->where('status', 'paid')->sum('amount'),
            'count_active' => Collegiate::where('school_id', $schoolId)->where('status', 'active')->count(),
            'count_overdue_users' => Collegiate::where('school_id', $schoolId)->where('is_fees_compliant', false)->count(),
        ];

        $activeFee = MembershipFee::where('school_id', $schoolId)->where('is_active', true)->latest()->first();

        return view('admin.billing.index', compact('stats', 'collegiates', 'activeFee', 'search', 'statusFilter'));
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
     * Registrar un pago múltiple de forma presencial.
     */
    public function payInPerson(Request $request)
    {
        $request->validate([
            'collegiate_id' => 'required|exists:collegiates,id',
            'dues_ids' => 'required|array',
            'dues_ids.*' => 'exists:collegiate_dues,id',
            'payment_method' => 'required|in:efectivo,transferencia,debito,credito',
            'notes' => 'nullable|string'
        ]);

        $collegiate = Collegiate::findOrFail($request->collegiate_id);
        
        // Validar seguridad de la escuela
        if (!auth()->user()->isOwner() && $collegiate->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $dues = CollegiateDue::whereIn('id', $request->dues_ids)
                             ->where('collegiate_id', $collegiate->id)
                             ->get();

        $methodNames = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'debito' => 'Tarjeta de Débito',
            'credito' => 'Tarjeta de Crédito'
        ];
        
        $methodLabel = $methodNames[$request->payment_method] ?? 'Otro';
        $reference = "Presencial - {$methodLabel}";
        if ($request->notes) {
            $reference .= " | Obs: " . $request->notes;
        }

        foreach ($dues as $due) {
            $due->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_reference' => substr($reference, 0, 255)
            ]);
        }

        // Revisar si ya no le quedan cuotas vencidas y marcarlo al día
        if ($collegiate->pendingDues()->where('status', 'overdue')->count() === 0) {
            $collegiate->update(['is_fees_compliant' => true]);
        }

        return back()->with('success', 'Pago presencial de ' . count($dues) . ' cuota(s) registrado correctamente.');
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

    /**
     * Generar cuota del mes para todos los colegiados activos.
     */
    public function generateMonthlyDues()
    {
        $schoolId = auth()->user()->school_id;
        $activeFee = MembershipFee::where('school_id', $schoolId)->where('is_active', true)->first();

        if (!$activeFee) {
            return back()->with('error', 'Debe definir un valor de cuota activo primero.');
        }

        $collegiates = Collegiate::where('school_id', $schoolId)->where('status', 'active')->get();
        $dueDate = Carbon::now()->endOfMonth(); // Vencimiento a fin de mes
        $count = 0;

        foreach ($collegiates as $collegiate) {
            // Evitar duplicados en el mismo mes
            $exists = CollegiateDue::where('collegiate_id', $collegiate->id)
                ->whereYear('due_date', $dueDate->year)
                ->whereMonth('due_date', $dueDate->month)
                ->exists();

            if (!$exists) {
                CollegiateDue::create([
                    'collegiate_id' => $collegiate->id,
                    'amount' => $activeFee->amount,
                    'due_date' => clone $dueDate,
                    'status' => 'pending'
                ]);
                
                // Si se generó deuda, el usuario pasa a no estar al día
                $collegiate->update(['is_fees_compliant' => false]);
                $count++;
            }
        }

        return back()->with('success', "Se generaron $count cuotas para el mes actual.");
    }
}
