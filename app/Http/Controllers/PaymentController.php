<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collegiate;
use App\Models\CollegiatePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

        $school = $collegiate->school;

        if (!$school->mp_access_token) {
            return back()->with('error', 'El colegio aún no ha configurado Mercado Pago. Por favor intente más tarde.');
        }

        // Crear Comprobante Interno
        $externalReference = Str::uuid()->toString();

        $payment = CollegiatePayment::create([
            'school_id' => $school->id,
            'collegiate_id' => $collegiate->id,
            'amount' => $totalAmount,
            'gateway' => 'mercadopago',
            'external_reference' => $externalReference,
            'status' => 'pending',
        ]);

        // Vincular cuotas al comprobante
        foreach ($dues as $due) {
            $due->update(['collegiate_payment_id' => $payment->id]);
        }

        // Generar items para la preferencia
        $items = [];
        foreach ($dues as $due) {
            $items[] = [
                'title' => $due->concept ?? 'Cuota Societaria',
                'quantity' => 1,
                'unit_price' => (float) $due->amount,
                'currency_id' => 'ARS'
            ];
        }

        // Crear la preferencia en Mercado Pago
        $webhookUrl = route('mercadopago.webhook', ['school_id' => $school->id]);

        $response = Http::withToken($school->mp_access_token)
            ->post('https://api.mercadopago.com/checkout/preferences', [
                'items' => $items,
                'external_reference' => $externalReference,
                'notification_url' => $webhookUrl,
                'back_urls' => [
                    'success' => route('payment.success'),
                    'pending' => route('payment.pending'),
                    'failure' => route('payment.failure')
                ],
                'auto_return' => 'approved',
            ]);

        if ($response->successful()) {
            $preference = $response->json();
            $initPoint = $school->mp_sandbox_mode ? $preference['sandbox_init_point'] : $preference['init_point'];
            return redirect()->away($initPoint);
        }

        return back()->with('error', 'Ocurrió un error al generar la orden de pago en Mercado Pago.');
    }

    public function success(Request $request)
    {
        return redirect()->route('payment.index')->with('success', '¡Pago procesado exitosamente! El sistema actualizará su cuenta automáticamente en breve.');
    }

    public function pending(Request $request)
    {
        return redirect()->route('payment.index')->with('warning', 'Su pago está pendiente. El estado de cuenta se actualizará una vez que se confirme el cobro.');
    }

    public function failure(Request $request)
    {
        return redirect()->route('payment.index')->with('error', 'El pago ha sido rechazado o cancelado.');
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
