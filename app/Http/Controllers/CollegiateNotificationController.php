<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collegiate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CollegiateNotificationController extends Controller
{
    /**
     * Envía una notificación individual de advertencia por morosidad.
     */
    public function sendWarning(Collegiate $collegiate)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);
        if ($collegiate->school_id !== $user->school_id) abort(403);

        // Lógica de envío simulada (Email/Push/WhatsApp)
        // Mail::to($collegiate->email)->send(new MorosityWarning($collegiate));
        
        Log::info("Aviso de morosidad enviado de forma manual a: {$collegiate->email} desde el panel de control.");

        return back()->with('success', "Notificación enviada exitosamente a {$collegiate->first_name} {$collegiate->last_name}.");
    }

    /**
     * Ejecuta una campaña masiva de notificaciones según la intensidad.
     */
    public function bulkNotify(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') abort(403);

        $request->validate(['intensity' => 'required|integer|min:1|max:4']);
        $intensity = $request->intensity;

        $school = $user->school;
        $morosos = $school->collegiates()->where('is_fees_compliant', false)->get();
        
        $count = $morosos->count();
        if ($count == 0) return back()->with('info', "No se encontraron colegiados morosos para esta campaña.");

        foreach ($morosos as $moroso) {
            // Lógica según intensidad
            // if ($intensity == 4) { ... alerta invasiva ... }
        }

        Log::info("Campaña masiva de intensidad {$intensity} lanzada para {$count} colegiados.");

        return back()->with('success', "¡Campaña Lanzada! Se están procesando {$count} notificaciones de nivel {$intensity}.");
    }
}
