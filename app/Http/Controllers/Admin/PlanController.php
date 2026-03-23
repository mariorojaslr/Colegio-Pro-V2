<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isOwner()) abort(403);
        $plans = SubscriptionPlan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function edit(SubscriptionPlan $plan)
    {
        if (!Auth::user()->isOwner()) abort(403);
        return view('admin.plans.edit', compact('plan'));
    }

    /**
     * Actualiza los detalles y límites de un plan de suscripción.
     */
    public function update(Request $request, SubscriptionPlan $plan)
    {
        // Solo el OWNER tiene permiso para modificar los planes de negocio
        if (!Auth::user()->isOwner()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_users' => 'required|integer|min:1',
            'max_storage' => 'required|integer|min:1',
            'max_traffic' => 'required|integer|min:1',
            'max_files' => 'required|integer|min:1',
            'max_images' => 'required|integer|min:1',
            'max_streaming' => 'required|integer|min:0',
            'features_list' => 'required|string',
        ]);

        // Procesar lista de características (un feature por línea) para guardarlo como array JSON
        $features = preg_split('/\r\n|\r|\n/', $request->features_list);
        $features = array_filter(array_map('trim', $features));

        $plan->update([
            'name' => $request->name,
            'price' => $request->price,
            'max_users' => $request->max_users,
            'max_storage' => $request->max_storage,
            'max_traffic' => $request->max_traffic,
            'max_files' => $request->max_files,
            'max_images' => $request->max_images,
            'max_streaming' => $request->max_streaming,
            'is_one_time' => $request->has('is_one_time'),
            'features' => array_values($features),
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan SaaS actualizado correctamente.');
    }
}
