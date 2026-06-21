<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\AmenityBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmenityController extends Controller
{
    /**
     * Lista todas las amenidades del colegio actual.
     */
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $amenities = Amenity::where('school_id', $schoolId)->get();
        
        return view('amenities.index', compact('amenities'));
    }

    /**
     * Alterna el estado (Activo/Inactivo) de un servicio.
     */
    public function toggle(Amenity $amenity)
    {
        if (!Auth::user()->hasPermission('manage_cms') && !Auth::user()->isOwner()) abort(403);
        
        $amenity->is_active = !$amenity->is_active;
        $amenity->save();

        return back()->with('success', "Estado de '{$amenity->name}' actualizado.");
    }

    /**
     * Procesa la reserva de un servicio.
     */
    public function book(Request $request)
    {
        $user = Auth::user();
        
        // 1. Verificación de Perfil: Solo Colegiados pueden reservar
        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
        if (!$collegiate && !$user->isOwner()) {
            return back()->with('error', 'Solo los colegiados activos pueden reservar servicios.');
        }

        // 2. REGLA DE ORO: Validar Cuotas al Día
        if ($collegiate && !$collegiate->is_fees_compliant) {
            return back()->with('error', 'Acceso Denegado: Debe regularizar su Cuota Societaria para utilizar los servicios del Colegio.');
        }

        $request->validate([
            'amenity_id' => 'required|exists:amenities,id',
            'date' => 'required|date|after_or_equal:today',
            'slot' => 'required|string',
        ]);

        $amenity = Amenity::find($request->amenity_id);
        if (!$amenity->is_active) return back()->with('error', 'Este servicio no está disponible actualmente.');

        // 3. Crear Reserva
        AmenityBooking::create([
            'amenity_id' => $amenity->id,
            'collegiate_id' => $collegiate->id,
            'booking_date' => $request->date,
            'slot_time' => $request->slot,
            'price_paid' => $amenity->getCurrentPrice(),
            'status' => 'confirmed',
        ]);

        return back()->with('success', "¡Reserva confirmada para {$amenity->name}! Se ha enviado un comprobante a su email.");
    }
}
