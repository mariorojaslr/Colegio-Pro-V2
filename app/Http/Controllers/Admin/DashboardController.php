<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use App\Models\PaymentRecord;
use Illuminate\Http\Request;

/**
 * Controlador de Administración Global (Panel OWNER)
 * Gestiona las métricas de todo el ecosistema SaaS y la funcionalidad de suplantación.
 */
class DashboardController extends Controller
{
    /**
     * Muestra la vista general con métricas agregadas de todos los colegios (tenants).
     * Incluye datos de almacenamiento, tráfico, archivos y uso de streaming.
     */
    public function index()
    {
        // Se calculan las estadísticas globales sumando los registros de todos los colegios
        $stats = [
            'total_schools' => School::count(),
            'total_users' => User::count(),
            'storage_used' => School::sum('storage_used'),
            'traffic_used' => School::sum('traffic_used'),
            'total_files' => School::sum('total_files'),
            'total_images' => School::sum('total_images'),
            'streaming_usage' => School::sum('streaming_usage'), // Minutos de streaming consumidos
            'total_revenue' => PaymentRecord::where('status', 'paid')->sum('amount'), // Ingresos totales confirmados
            'mrr' => \App\Models\Subscription::where('status', 'active')->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')->sum('subscription_plans.price'),
        ];

        // Se obtienen los colegios con el conteo de sus usuarios y colegiados
        $schools = School::withCount(['users', 'collegiates'])->with('activeSubscription.plan')->latest()->get();
        
        // Obtenemos los logs recientes para el panel lateral de monitoreo
        $recentLogs = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'schools', 'recentLogs'));
    }

    /**
     * Lógica de "Visión Omnisciente": Permite al OWNER entrar en la cuenta de cualquier colegio.
     * Cambia la sesión actual a la del administrador del colegio seleccionado.
     */
    public function impersonate($schoolId, $role = 'ADMIN_COLEGIO')
    {
        $school = School::findOrFail($schoolId);
        
        // Buscamos un usuario con el rol solicitado dentro de ese colegio específico
        $user = User::where('school_id', $schoolId)->where('role', $role)->first();

        if (!$user) {
            // Si no existe un administrador asignado, tomamos el primer usuario disponible del colegio
            $user = User::where('school_id', $schoolId)->first();
        }

        if ($user) {
            // Guardamos el ID original del OWNER en la sesión para poder permitir el retorno
            session(['impersonator_id' => auth()->id()]);
            
            // Iniciamos sesión como el usuario del colegio (suplantación)
            auth()->login($user);
            
            return redirect()->route('home')->with('status', "Sesión iniciada como {$user->name} ({$school->name})");
        }

        return redirect()->back()->with('error', 'No se encontró un usuario para suplantar en este colegio.');
    }

    /**
     * Finaliza la suplantación de identidad y devuelve la sesión al OWNER original.
     */
    public function leaveImpersonation()
    {
        // Recuperamos el ID del OWNER guardado previamente en la sesión
        $ownerId = session('impersonator_id');

        if ($ownerId) {
            $owner = User::find($ownerId);
            
            // Limpiamos los rastros de la suplantación en la sesión
            session()->forget('impersonator_id');
            
            // Volvemos a loguear como OWNER real
            auth()->login($owner);
            
            return redirect()->route('admin.dashboard')->with('status', 'Has regresado a tu sesión de OWNER.');
        }

        return redirect()->route('home');
    }
}
