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
        try {
            $request->validate([
                'matricula' => 'required|string',
                'apellido' => 'required|string|min:2'
            ]);

            $matricula = trim($request->input('matricula'));
            $apellido = trim(strtolower($request->input('apellido')));

            // Buscamos de forma segura aislando al tenant actual
            $tenant = app('tenant');
            
            $query = Collegiate::where('registration_number', $matricula)
                ->whereRaw('LOWER(last_name) LIKE ?', ["%{$apellido}%"]);
                
            if ($tenant) {
                $query->where('school_id', $tenant->id);
            }

            $collegiate = $query->first();

            if (!$collegiate) {
                return back()->with('error', "No pudimos encontrar tus datos. Verifica que el N° de Matrícula ({$request->input('matricula')}) y el apellido ({$request->input('apellido')}) coincidan. Si el problema persiste, por favor comunícate con el administrador y entrégale estos datos para que lo busquemos nosotros.");
            }

            if ($collegiate->user_id) {
                return redirect()->route('login')->with('info', 'Tu cuenta ya está activada. Por favor, inicia sesión o usa la opción "¿Olvidaste tu contraseña?" si no la recuerdas.');
            }

            // Store collegiate ID in session to allow them to proceed to step 2
            session(['activation_collegiate_id' => $collegiate->id]);

            return redirect()->route('activate.step2');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $matricula = $request->input('matricula', 'No ingresada');
            $apellido = $request->input('apellido', 'No ingresado');
            return back()->with('error', "Ha ocurrido un inconveniente técnico al procesar la solicitud. Por favor, comunícate con el administrador e indícale que intentaste ingresar con Matrícula: {$matricula} y Apellido: {$apellido}. Lo buscaremos nosotros de inmediato.");
        }
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
        try {
            $collegiateId = session('activation_collegiate_id');
            
            if (!$collegiateId) {
                return redirect()->route('activate.step1')->with('error', 'Tu sesión de activación ha caducado. Por favor, vuelve a iniciar el proceso.');
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
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $collegiate = Collegiate::find(session('activation_collegiate_id'));
            $matricula = $collegiate ? $collegiate->registration_number : 'Desconocida';
            $apellido = $collegiate ? $collegiate->last_name : 'Desconocido';
            return back()->with('error', "Se produjo un error al intentar crear tu cuenta. Por favor, comunícate con el administrador y proporciónale esta información: Matrícula: {$matricula}, Apellido: {$apellido}. Nos encargaremos de revisarlo.");
        }
    }
}
