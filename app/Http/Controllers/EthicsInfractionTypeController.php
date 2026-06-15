<?php

namespace App\Http\Controllers;

use App\Models\EthicsInfractionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EthicsInfractionTypeController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        $infractions = EthicsInfractionType::where('school_id', $schoolId)->get();
        return view('admin.ethics_infractions.index', compact('infractions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'severity' => 'required|in:leve,grave',
            'duration_months' => 'nullable|integer|min:1',
        ]);

        EthicsInfractionType::create([
            'school_id' => Auth::user()->school_id,
            'name' => $request->name,
            'description' => $request->description,
            'severity' => $request->severity,
            'duration_months' => $request->duration_months,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Tipo de infracción creado exitosamente.');
    }

    public function update(Request $request, EthicsInfractionType $ethics_infraction)
    {
        if ($ethics_infraction->school_id !== Auth::user()->school_id) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'severity' => 'required|in:leve,grave',
            'duration_months' => 'nullable|integer|min:1',
        ]);

        $ethics_infraction->update($request->only(['name', 'description', 'severity', 'duration_months', 'is_active']));

        return redirect()->back()->with('success', 'Tipo de infracción actualizado.');
    }

    public function destroy(EthicsInfractionType $ethics_infraction)
    {
        if ($ethics_infraction->school_id !== Auth::user()->school_id) abort(403);
        $ethics_infraction->delete();
        return redirect()->back()->with('success', 'Tipo de infracción eliminado.');
    }
}
