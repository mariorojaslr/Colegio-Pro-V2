<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentRecord;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') {
            abort(403, 'Solo el administrador del colegio puede gestionar el plan.');
        }

        $school = $user->school;
        $activeSubscription = $school->activeSubscription;
        $plans = SubscriptionPlan::all();
        $payments = $school->payments()->latest()->get();

        return view('billing.index', compact('activeSubscription', 'plans', 'payments', 'school'));
    }

    public function upgrade(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'ADMIN_COLEGIO') {
            abort(403);
        }

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $school = $user->school;

        // Simulamos la Pasarela de Pago
        // 1. Registrar el pago
        PaymentRecord::create([
            'school_id' => $school->id,
            'amount' => $plan->price,
            'payment_method' => 'card',
            'status' => 'paid',
            'transaction_reference' => 'PRESTIGE-' . strtoupper(uniqid()),
        ]);

        // 2. Actualizar o Crear Suscripción
        Subscription::updateOrCreate(
            ['school_id' => $school->id],
            [
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]
        );

        return back()->with('success', '¡Su plan ha sido actualizado a ' . $plan->name . ' exitosamente!');
    }
}
