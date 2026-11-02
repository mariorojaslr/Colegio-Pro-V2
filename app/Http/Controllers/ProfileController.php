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
            'dni' => 'nullable|string|max:50',
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
            if ($request->has('dni')) $collegiate->dni = $request->dni;
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

    /**
     * Permite al usuario cambiar su propia contraseña
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
    }

    /**
     * Permite al usuario colegiado configurar o actualizar su PIN de 4 dígitos para acceso rápido
     */
    public function updatePin(Request $request)
    {
        $request->validate([
            'pin' => ['required', 'regex:/^[0-9]{4}$/'],
            'pin_confirmation' => 'required|same:pin',
        ], [
            'pin.required' => 'Ingresa tu nuevo código PIN de 4 dígitos.',
            'pin.regex' => 'El PIN debe contener exactamente 4 dígitos numéricos.',
            'pin_confirmation.required' => 'Debes confirmar tu nuevo código PIN.',
            'pin_confirmation.same' => 'Los dos códigos PIN no coinciden.',
        ]);

        $user = auth()->user();
        $pinHash = \Illuminate\Support\Facades\Hash::make($request->pin);

        $user->login_pin = $pinHash;
        $user->save();

        $collegiate = \App\Models\Collegiate::where('user_id', $user->id)->first();
        if ($collegiate) {
            $collegiate->login_pin = $pinHash;
            $collegiate->save();
        }

        return back()->with('success', '¡Tu código PIN de 4 dígitos ha sido configurado con éxito! A partir de ahora puedes usarlo para ingresar rápidamente.');
    }
}
