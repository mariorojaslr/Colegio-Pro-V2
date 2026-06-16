<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collegiate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AccountActivationController extends Controller
{
    /**
     * Show step 1: Search by any identifying data.
     */
    public function showSearchForm()
    {
        return view('auth.activate.step1');
    }

    /**
     * Process step 1: Search for the collegiate.
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:3'
        ]);

        $query = $request->input('query');

        // Search by dni, email, phone, or registration_number
        $collegiate = Collegiate::where(function($q) use ($query) {
            $q->where('dni', $query)
              ->orWhere('email', $query)
              ->orWhere('phone', $query)
              ->orWhere('registration_number', $query);
        })->first();

        if (!$collegiate) {
            return back()->with('error', 'No pudimos encontrar tus datos. Verifica que el valor ingresado coincida con el registrado en el padrón.');
        }

        if ($collegiate->user_id) {
            return redirect()->route('login')->with('info', 'Tu cuenta ya está activada. Por favor, inicia sesión o usa la opción "¿Olvidaste tu contraseña?" si no la recuerdas.');
        }

        // Store collegiate ID in session to allow them to proceed to step 2
        session(['activation_collegiate_id' => $collegiate->id]);

        return redirect()->route('activate.step2');
    }

    /**
     * Show step 2: Enter email and set password.
     */
    public function showRegisterForm()
    {
        $collegiateId = session('activation_collegiate_id');
        
        if (!$collegiateId) {
            return redirect()->route('activate.step1');
        }

        $collegiate = Collegiate::findOrFail($collegiateId);

        return view('auth.activate.step2', compact('collegiate'));
    }

    /**
     * Process step 2: Create user and log in.
     */
    public function register(Request $request)
    {
        $collegiateId = session('activation_collegiate_id');
        
        if (!$collegiateId) {
            return redirect()->route('activate.step1');
        }

        $collegiate = Collegiate::findOrFail($collegiateId);

        $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $collegiate->first_name . ' ' . $collegiate->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'COLEGIADO',
            'school_id' => $collegiate->school_id,
            'is_active' => true,
        ]);

        // Link the user to the collegiate
        $collegiate->update([
            'user_id' => $user->id,
            'email' => $request->email,
            'phone' => $request->phone ?? $collegiate->phone,
        ]);

        // Clear the session
        session()->forget('activation_collegiate_id');

        // Log the user in
        Auth::login($user);

        return redirect()->route('home')->with('success', '¡Cuenta activada exitosamente! Bienvenido a la plataforma.');
    }
}
