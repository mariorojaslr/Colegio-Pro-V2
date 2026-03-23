<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collegiate;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Procesa el pago de cuotas societarias (Simulación / Placeholder Pasarela).
     */
    public function payDues()
    {
        $user = Auth::user();
        $collegiate = Collegiate::where('user_id', $user->id)->first();

        if (!$collegiate) {
            return back()->with('error', 'Su perfil no está vinculado a una matrícula activa.');
        }

        // LÓGICA DE PASARELA (Aquí iría la redirección a MercadoPago/Stripe)
        // Por ahora simulamos el éxito inmediato del pago:
        
        $collegiate->update([
            'is_fees_compliant' => true
        ]);

        return redirect()->route('home')->with('success', '¡Pago recibido exitosamente! Su estado de habilitación ha sido actualizado.');
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
