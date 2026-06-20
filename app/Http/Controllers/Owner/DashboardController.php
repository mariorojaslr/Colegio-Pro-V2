<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    /**
     * Muestra el resumen global de la plataforma SaaS para el Owner.
     */
    public function index()
    {
        // Al ser OWNER, evitamos el TenantScope si necesitamos ver todo.
        // Asumiendo que el TenantScope ignora al OWNER o se usa withoutGlobalScopes()
        $totalTenants = School::count();
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $totalUsers = User::count();
        $totalStorageUsed = School::sum('storage_used');

        return view('owner.dashboard.index', compact(
            'totalTenants',
            'activeSubscriptions',
            'totalUsers',
            'totalStorageUsed'
        ));
    }

    /**
     * Permite al Owner "Entrar como..." administrador de una empresa específica.
     */
    public function impersonateTenant(Request $request, $schoolId)
    {
        $school = School::findOrFail($schoolId);
        
        // Guardar en sesión el ID de la empresa que estamos suplantando
        Session::put('impersonated_tenant_id', $school->id);
        
        return redirect()->route('dashboard')->with('success', "Has entrado a la empresa: {$school->name}");
    }

    /**
     * Sale del modo "Entrar como..." y vuelve a la visión global del Owner.
     */
    public function leaveImpersonation()
    {
        Session::forget('impersonated_tenant_id');
        
        return redirect()->route('owner.dashboard')->with('success', 'Has vuelto a la vista global de OWNER.');
    }
}
