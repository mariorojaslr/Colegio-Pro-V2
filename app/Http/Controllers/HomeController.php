<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'OWNER' || $user->role === 'ADMIN_INTERNO') {
            return redirect()->route('admin.dashboard');
        }

        $school = $user->school;
        
        // 1. Métricas de Gestión Institucional
        $totalColegiados = $school->collegiates()->count();
        $habilitados = $school->collegiates()->where('is_ethics_compliant', true)
                                           ->where('is_fees_compliant', true)
                                           ->where('is_fully_documented', true)
                                           ->count();
        
        // Mora Arancelaria (Basado en deudas reales exigibles)
        $morososCuotas = $school->collegiates()->whereHas('dues', function($q) {
            $q->where('status', 'overdue');
        })->count();

        // Legajos con auditoría pendiente
        $morososDocs = $school->collegiates()->where('is_fully_documented', false)->count();

        // 2. Ranking de Gestión (Top 5 con problemas)
        $topDeudores = $school->collegiates()->where('is_fees_compliant', false)
                                            ->latest()
                                            ->take(5)
                                            ->get();

        // 3. Resumen de Amenidades Activas
        $amenitiesCount = $school->amenities()->where('is_active', true)->count();

        $currency = $school->currency_symbol ?? '$';
        $intlCurrency = 'USD $';
        $location = app(\App\Services\LocationService::class);
        $isLocal = $location->isFromArgentina();

        // 4. Cartelera Académica Estilo Netflix con Precios Diferenciados
        $news = $school->has_academy ? collect([
            [
                'id' => 1,
                'title' => 'Curso de RCP y Primeros Auxilios', 
                'category' => 'Capacitación', 
                'date_short' => '25 Mar', 
                'flyer' => asset('images/flyers/rcp.png'),
                'lecturer' => 'Dr. Alberto Rossi',
                'date_range' => '25 de Marzo - 27 de Marzo',
                'price_local_member' => 12000,
                'price_local_external' => 18000,
                'price_intl_member' => 15,
                'price_intl_external' => 25,
                'rating' => 4.8,
                'description' => 'Aprende las maniobras básicas de reanimación cardiopulmonar y primeros auxilios para situaciones de emergencia civil.',
                'academic_weight' => '3 Créditos'
            ],
            [
                'id' => 2,
                'title' => 'Arquitectura Legal en Salud', 
                'category' => 'Legal', 
                'date_short' => '10 Abr', 
                'flyer' => asset('images/flyers/legal_salud.png'),
                'lecturer' => 'Dra. María Elena Castro',
                'date_range' => '10 de Abril - 15 de Abril',
                'price_local_member' => 22000,
                'price_local_external' => 35000,
                'price_intl_member' => 25,
                'price_intl_external' => 45,
                'rating' => 4.9,
                'description' => 'Un análisis profundo de los marcos regulatorios vigentes en la gestión de servicios de salud institucionales.',
                'academic_weight' => '5 Créditos'
            ],
            [
                'id' => 3,
                'title' => 'Innovación en Gestión Judicial', 
                'category' => 'Tecnología', 
                'date_short' => '15 May', 
                'flyer' => asset('images/flyers/innovacion.png'),
                'lecturer' => 'Ing. Francisco Moreno',
                'date_range' => '15 de Mayo - 15 de Junio',
                'price_local_member' => 40000,
                'price_local_external' => 65000,
                'price_intl_member' => 45,
                'price_intl_external' => 85,
                'rating' => 4.7,
                'description' => 'Taller de transformación digital aplicado a los procesos judiciales modernos y eficiencia procesal.',
                'academic_weight' => '7 Créditos'
            ],
        ])->map(function($course) use ($isLocal, $currency, $intlCurrency, $user) {
            $isMember = in_array($user->role, ['ADMIN_COLEGIO', 'COLLEGIATE']);
            
            if ($isLocal) {
                $course['displayed_currency'] = $currency;
                $course['value'] = $isMember ? $course['price_local_member'] : $course['price_local_external'];
            } else {
                $course['displayed_currency'] = $intlCurrency;
                $course['value'] = $isMember ? $course['price_intl_member'] : $course['price_intl_external'];
            }
            
            $course['member_only_benefit'] = $isMember ? 'Beneficio de Matriculado Aplicado' : 'Precio Público General';
            return $course;
        }) : collect([]);

        // 5. Perfil del Colegiado (si aplica)
        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
        
        // 6. Tarea de Onboarding (Perfilado Progresivo)
        $onboardingTask = $collegiate ? $collegiate->getNextOnboardingTask() : null;

        return view('home', compact(
            'totalColegiados', 
            'habilitados', 
            'morososCuotas', 
            'morososDocs', 
            'topDeudores',
            'amenitiesCount',
            'collegiate',
            'news',
            'onboardingTask'
        ));
    }
}
