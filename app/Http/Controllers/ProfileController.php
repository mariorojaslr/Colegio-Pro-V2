<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Muestra el perfil del usuario actual (y su info colegial si aplica)
     */
    public function index()
    {
        $user = auth()->user();
        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
        $school = $user->school;

        return view('profile.index', compact('user', 'collegiate', 'school'));
    }

    /**
     * Actualiza datos básicos del perfil (Email, Teléfono, etc.)
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
        ]);

        // Actualizar el usuario
        $user->email = $request->email;
        $user->save();

        // Actualizar el colegiado asociado si existe
        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
        if ($collegiate) {
            if ($request->has('phone')) $collegiate->phone = $request->phone;
            if ($request->has('address')) $collegiate->address = $request->address;
            if ($request->has('birth_date')) $collegiate->birth_date = $request->birth_date;
            $collegiate->save();
        }

        return redirect()->route('profile.index')->with('success', 'Tu perfil ha sido actualizado correctamente.');
    }

    /**
     * Permite subir o actualizar la foto de perfil (Avatar)
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120', // Max 5MB
        ]);

        $user = auth()->user();
        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();

        // Eliminar avatar anterior si existe (en storage local)
        if ($collegiate && $collegiate->avatar_url && !str_starts_with($collegiate->avatar_url, 'http')) {
            // Eliminar archivo local antiguo
            $oldPath = str_replace('/storage/', 'public/', parse_url($collegiate->avatar_url, PHP_URL_PATH));
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        $path = $request->file('avatar')->store('public/avatars');
        $url = Storage::url($path);

        if ($collegiate) {
            $collegiate->avatar_url = $url;
            $collegiate->save();
        } else {
            // Si no es colegiado, podríamos guardarlo en una columna avatar_url en user si existiera
            // Por ahora asumimos que los colegiados son los principales que necesitan avatar
        }

        return redirect()->route('profile.index')->with('success', 'Tu foto de perfil ha sido actualizada con éxito.');
    }
}
