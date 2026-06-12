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

        return view('student.payments.index', compact('collegiate', 'pendingDues', 'paidDues'));
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
