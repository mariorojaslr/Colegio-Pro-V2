<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\School;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Controlador para la gestión de Colegios (Tenants) en el sistema global.
 * Permite al OWNER dar de alta nuevas instituciones y asignar sus administradores.
 */
class SchoolController extends Controller
{
    /**
     * Lista todos los colegios registrados en el sistema global.
     */
    public function index()
    {
        $schools = School::withCount('users')->latest()->paginate(request('per_page', 10));
        return view('admin.schools.index', compact('schools'));
    }

    /**
     * Muestra el formulario para registrar un nuevo colegio y su administrador.
     */
    public function create()
    {
        $plans = SubscriptionPlan::all();
        return view('admin.schools.create', compact('plans'));
    }

    /**
     * Almacena un nuevo colegio y crea la cuenta del administrador principal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:schools,slug|max:255',
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8',
        ]);

        try {
            DB::beginTransaction();

            // 1. Crear la instancia del Colegio (SaaS Tenant)
            $plan = SubscriptionPlan::find($request->subscription_plan_id);
            
            $school = School::create([
                'name' => $request->name,
                'slug' => Str::slug($request->slug),
                'plan_category' => $plan->slug,
                'primary_color' => $request->primary_color ?? '#0F172A',
                'secondary_color' => $request->secondary_color ?? '#3B82F6',
                'accent_color' => $request->accent_color ?? '#F59E0B',
                'status' => 'active',
                'is_active' => true,
            ]);

            // 2. Crear la Suscripción inicial asociada al plan elegido
            Subscription::create([
                'school_id' => $school->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(), // Un mes de prueba o ciclo inicial
            ]);

            // 3. Crear el Administrador Principal de ese Colegio
            User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => Hash::make($request->admin_password),
                'role' => 'ADMIN_COLEGIO',
                'school_id' => $school->id,
                'is_active' => true,
            ]);

            DB::commit();

            return redirect()->route('admin.dashboard')->with('success', "Colegio {$school->name} registrado exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar el colegio: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Muestra el formulario para editar un colegio existente.
     */
    public function edit(School $school)
    {
        $plans = SubscriptionPlan::all();
        return view('admin.schools.edit', compact('school', 'plans'));
    }

    /**
     * Actualiza los datos de la institución.
     */
    public function update(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => "required|string|unique:schools,slug,{$school->id}|max:255",
            'is_active' => 'boolean',
        ]);

        $school->update([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'member_singular' => $request->member_singular,
            'member_plural' => $request->member_plural,
            'primary_color' => $request->primary_color,
            'secondary_color' => $request->secondary_color,
            'accent_color' => $request->accent_color,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Configuración de colegio actualizada.');
    }
}
