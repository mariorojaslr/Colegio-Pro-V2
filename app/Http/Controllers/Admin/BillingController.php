<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collegiate;
use App\Models\MembershipFee;
use App\Models\CollegiateDue;
use App\Services\BillingService;
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
        $billingConcepts = \App\Models\BillingConcept::where('school_id', $schoolId)->get();

        return view('admin.billing.index', compact('stats', 'collegiates', 'activeFee', 'search', 'statusFilter', 'billingConcepts'));
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
            'tarjeta_financiada' => 'Tarjeta (Financiada por tarjeta)',
            'tarjeta_cuota' => 'Tarjeta (Valor de la cuota)'
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
    public function generateMonthlyDues(BillingService $billingService)
    {
        $schoolId = auth()->user()->school_id;
        $school = \App\Models\School::findOrFail($schoolId);
        
        $activeFee = MembershipFee::where('school_id', $schoolId)->where('is_active', true)->first();

        if (!$activeFee) {
            return back()->with('error', 'Debe definir un valor de cuota activo primero.');
        }

        $result = $billingService->generateMonthlyDuesForSchool($school);

        if (isset($result['status']) && $result['status'] === 'error') {
            return back()->with('error', $result['message']);
        }

        $message = "<strong>Informe de Liquidación:</strong><br>";
        $message .= "<ul>";
        $message .= "<li>Total de colegiados activos: <strong>{$result['total_active']}</strong></li>";
        $message .= "<li>Cuotas generadas exitosamente: <strong>{$result['generated']}</strong></li>";
        
        if ($result['updated'] > 0) {
            $message .= "<li>Cuotas pendientes actualizadas al nuevo monto: <strong>{$result['updated']}</strong></li>";
        }
        
        if ($result['already_generated'] > 0) {
            $message .= "<li>Cuotas ya generadas previamente: <strong>{$result['already_generated']}</strong></li>";
        }

        if ($result['already_paid'] > 0) {
            $message .= "<li>Excluidos (ya la pagaron por adelantado): <strong>{$result['already_paid']}</strong></li>";
        }
        
        if ($result['excepted'] > 0) {
            $message .= "<li>Exceptuados (perfil anual u otras reglas): <strong>{$result['excepted']}</strong></li>";
        }
        $message .= "</ul>";

        if ($result['total_processed'] > 0) {
            return back()->with('success', $message);
        } else {
            return back()->with('info', "<strong>La liquidación para el período que estás tratando ya fue hecha.</strong><br><br>" . $message);
        }
    }

    /**
     * Generar un cargo personalizado / deuda histórica para un colegiado.
     */
    public function storeCustom(Request $request)
    {
        $request->validate([
            'collegiate_id' => 'required|exists:collegiates,id',
            'amount' => 'required|numeric|min:0',
            'billing_concept_id' => 'nullable|exists:billing_concepts,id',
            'concept' => 'required_without:billing_concept_id|string|max:255',
            'due_date' => 'required|date'
        ]);

        $collegiate = Collegiate::findOrFail($request->collegiate_id);
        
        if (!auth()->user()->isOwner() && $collegiate->school_id !== auth()->user()->school_id) {
            abort(403);
        }

        $conceptName = $request->concept;
        $dueType = 'extraordinary';

        if ($request->filled('billing_concept_id')) {
            $billingConcept = \App\Models\BillingConcept::find($request->billing_concept_id);
            if ($billingConcept) {
                $conceptName = $billingConcept->name;
                $dueType = $billingConcept->type;
            }
        }

        CollegiateDue::create([
            'collegiate_id' => $collegiate->id,
            'billing_concept_id' => $request->billing_concept_id,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'concept' => $conceptName,
            'due_type' => $dueType,
            'status' => 'pending'
        ]);

        $collegiate->update(['is_fees_compliant' => false]);

        return back()->with('success', 'La novedad financiera fue generada correctamente y ya figura en el estado de cuenta.');
    }

    // --- CRUD Conceptos Facturables --- //

    public function storeConcept(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        \App\Models\BillingConcept::create([
            'school_id' => auth()->user()->school_id,
            'name' => $request->name,
            'default_amount' => $request->default_amount,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return back()->with('success', 'Concepto facturable creado exitosamente.');
    }

    public function updateConcept(Request $request, \App\Models\BillingConcept $concept)
    {
        if (!auth()->user()->isOwner() && $concept->school_id !== auth()->user()->school_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'default_amount' => 'required|numeric|min:0',
            'type' => 'required|string',
        ]);

        $concept->update([
            'name' => $request->name,
            'default_amount' => $request->default_amount,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Concepto facturable actualizado.');
    }

    public function toggleConcept(\App\Models\BillingConcept $concept)
    {
        if (!auth()->user()->isOwner() && $concept->school_id !== auth()->user()->school_id) abort(403);
        
        $concept->update(['is_active' => !$concept->is_active]);
        
        $status = $concept->is_active ? 'activado' : 'suspendido';
        return back()->with('success', "El concepto ha sido {$status}.");
    }

    public function destroyConcept(\App\Models\BillingConcept $concept)
    {
        if (!auth()->user()->isOwner() && $concept->school_id !== auth()->user()->school_id) abort(403);

        if ($concept->collegiateDues()->exists()) {
            return back()->with('error', 'No se puede eliminar este concepto porque ya ha sido utilizado en cobros. Puede suspenderlo en su lugar.');
        }

        $concept->delete();
        return back()->with('success', 'Concepto eliminado exitosamente.');
    }
}
