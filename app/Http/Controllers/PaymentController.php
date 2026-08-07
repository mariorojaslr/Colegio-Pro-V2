<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collegiate;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Portal del socio: Estado de cuenta y selección de cuotas a pagar.
     */
    public function index()
    {
        $user = Auth::user();
        $collegiate = Collegiate::where('user_id', $user->id)->first();

        if (!$collegiate) {
            return back()->with('error', 'No tiene un perfil de colegiado asociado.');
        }

        $pendingDues = $collegiate->pendingDues;
        $paidDues = $collegiate->dues()->where('status', 'paid')->orderBy('paid_at', 'desc')->get();

        $annualConcept = null;
        $currentMonth = now()->month;
        if ($currentMonth >= 1 && $currentMonth <= 3 && $collegiate->billing_profile !== 'anual') {
            $annualConcept = \App\Models\BillingConcept::where('school_id', $collegiate->school_id)
                                                       ->where('type', 'annual')
                                                       ->where('is_active', true)
                                                       ->first();
        }

        return view('student.payments.index', compact('collegiate', 'pendingDues', 'paidDues', 'annualConcept'));
    }

    /**
     * Procesa el pago de cuotas societarias (Simulación Sandbox Mercado Pago).
     */
    public function payDues(Request $request)
    {
        $user = Auth::user();
        $collegiate = Collegiate::where('user_id', $user->id)->first();

        if (!$collegiate) {
            return back()->with('error', 'Su perfil no está vinculado a una matrícula activa.');
        }

        $duesIds = $request->input('dues', []);
        
        if (empty($duesIds)) {
            return back()->with('error', 'Debe seleccionar al menos una cuota para pagar.');
        }

        $dues = \App\Models\CollegiateDue::whereIn('id', $duesIds)->where('collegiate_id', $collegiate->id)->get();
        $totalAmount = $dues->sum('amount');

        /* 
         * INTEGRACIÓN MERCADO PAGO (PREPARADA)
         * Cuando el cliente provea su ACCESS_TOKEN, se habilita este bloque:
         * 
         * \MercadoPago\SDK::setAccessToken(env('MP_ACCESS_TOKEN'));
         * $preference = new \MercadoPago\Preference();
         * 
         * $item = new \MercadoPago\Item();
         * $item->title = 'Cuotas Societarias - ' . $collegiate->registration_number;
         * $item->quantity = 1;
         * $item->unit_price = $totalAmount;
         * $preference->items = array($item);
         * 
         * $preference->back_urls = array(
         *    "success" => route('payment.success'),
         *    "failure" => route('payment.failure'),
         *    "pending" => route('payment.pending')
         * );
         * $preference->auto_return = "approved";
         * $preference->save();
         * 
         * return redirect($preference->init_point);
         */

        // LÓGICA DE SIMULACIÓN DE PASARELA (Fallback temporal hasta cargar credenciales)
        foreach ($dues as $due) {
            $due->update([
                'status' => 'paid',
                'paid_at' => now(),
                'payment_reference' => 'MP-SIMULADO-' . uniqid()
            ]);
        }

        if ($collegiate->pendingDues->where('status', 'overdue')->count() === 0) {
            $collegiate->update(['is_fees_compliant' => true]);
        }

        return redirect()->route('payment.index')->with('success', '¡Pago procesado exitosamente (Modo Sandbox MP)! Su cuenta ha sido actualizada.');
    }

    public function generateAnnualPayment(Request $request)
    {
        $user = Auth::user();
        $collegiate = Collegiate::where('user_id', $user->id)->first();
        if (!$collegiate) return back()->with('error', 'Sin perfil asociado.');

        $currentMonth = now()->month;
        if ($currentMonth < 1 || $currentMonth > 3) {
            return back()->with('error', 'El Pago Anual Anticipado solo está disponible entre Enero y Marzo.');
        }

        if ($collegiate->billing_profile === 'anual') {
            return back()->with('error', 'Ya posee el perfil de Pago Anual.');
        }

        $concept = \App\Models\BillingConcept::where('school_id', $collegiate->school_id)
                                             ->where('type', 'annual')
                                             ->where('is_active', true)
                                             ->first();
                                             
        if (!$concept) {
            return back()->with('error', 'El pago anual no está configurado por el colegio.');
        }

        $request->validate([
            'date_1' => 'required|date|after_or_equal:today',
            'date_2' => 'required|date|after_or_equal:date_1',
        ]);

        $amountPerInstallment = $concept->default_amount / 2;

        // Generar Cuota 1
        \App\Models\CollegiateDue::create([
            'collegiate_id' => $collegiate->id,
            'billing_concept_id' => $concept->id,
            'amount' => $amountPerInstallment,
            'due_date' => $request->date_1,
            'concept' => $concept->name . ' (Cuota 1/2)',
            'due_type' => 'extraordinary',
            'status' => 'pending'
        ]);

        // Generar Cuota 2
        \App\Models\CollegiateDue::create([
            'collegiate_id' => $collegiate->id,
            'billing_concept_id' => $concept->id,
            'amount' => $amountPerInstallment,
            'due_date' => $request->date_2,
            'concept' => $concept->name . ' (Cuota 2/2)',
            'due_type' => 'extraordinary',
            'status' => 'pending'
        ]);

        // Cambiar perfil
        $collegiate->update(['billing_profile' => 'anual']);

        return back()->with('success', 'Acuerdo de Pago Anual generado exitosamente. Podrá pagar las cuotas desde su estado de cuenta.');
    }

    /**
     * Procesa el pago de una reserva de amenidad específica.
     */
    public function payBooking(\App\Models\AmenityBooking $booking)
    {
        if (Auth::user()->id !== $booking->collegiate->user_id) abort(403);
        
        $booking->update(['status' => 'confirmed']);
        
        return back()->with('success', 'Pago de reserva confirmado.');
    }
}
