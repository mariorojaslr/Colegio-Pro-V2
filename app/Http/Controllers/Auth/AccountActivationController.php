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

            // Si el colegiado ya tiene usuario asociado, verificar si ya tiene PIN de 4 dígitos
            if ($collegiate->user_id) {
                $user = User::find($collegiate->user_id);
                if ($user && !empty($user->login_pin)) {
                    return redirect()->route('login')->with('info', "¡Hola {$collegiate->first_name}! Tu cuenta ya está activada y protegida con tu PIN de 4 dígitos. Puedes ingresar directamente con tu N° de Legajo ({$collegiate->registration_number}) o tu teléfono y tu PIN.");
                }
                
                // Si tiene usuario pero aún no configuró su PIN de 4 dígitos, le permitimos configurarlo
                session(['activation_collegiate_id' => $collegiate->id]);
                return redirect()->route('activate.step2')->with('info', "¡Hola {$collegiate->first_name}! Vamos a configurar tu código PIN de 4 dígitos para que puedas ingresar rápidamente con tu legajo o celular.");
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

            // Validación estricta y clara: Código PIN de 4 dígitos obligatorio
            $existingUserId = $collegiate->user_id;
            $request->validate([
                'email' => 'required|email|max:255' . ($existingUserId ? '|unique:users,email,' . $existingUserId : '|unique:users,email'),
                'phone' => 'required|string|min:6|max:50',
                'pin' => ['required', 'regex:/^[0-9]{4}$/'],
                'pin_confirmation' => 'required|same:pin',
                'password' => 'nullable|string|min:4|confirmed',
            ], [
                'pin.required' => 'El código PIN de validación de 4 dígitos es obligatorio.',
                'pin.regex' => 'El código PIN debe ser exactamente de 4 dígitos numéricos (ej. 1234).',
                'pin_confirmation.required' => 'Debes confirmar tu código PIN de 4 dígitos.',
                'pin_confirmation.same' => 'Los dos códigos PIN ingresados no coinciden.',
                'phone.required' => 'El número de teléfono o celular es obligatorio para permitir tu acceso rápido.',
            ]);

            $pinHash = Hash::make($request->pin);
            $rawPassword = !empty($request->password) ? $request->password : $request->pin;

            if ($collegiate->user_id) {
                // El colegiado ya tenía usuario creado; actualizamos su PIN y sus datos de acceso
                $user = User::findOrFail($collegiate->user_id);
                $user->email = $request->email;
                $user->login_pin = $pinHash;
                if (!empty($request->password)) {
                    $user->password = Hash::make($request->password);
                }
                $user->is_active = true;
                $user->save();
            } else {
                // Creamos el nuevo usuario para este colegiado
                $user = User::create([
                    'name' => $collegiate->first_name . ' ' . $collegiate->last_name,
                    'email' => $request->email,
                    'password' => Hash::make($rawPassword),
                    'login_pin' => $pinHash,
                    'role' => 'COLEGIADO',
                    'school_id' => $collegiate->school_id,
                    'is_active' => true,
                ]);
            }

            // Actualizamos la ficha del colegiado con su nuevo teléfono, email, PIN y vinculación
            $collegiate->update([
                'user_id' => $user->id,
                'email' => $request->email,
                'phone' => $request->phone,
                'login_pin' => $pinHash,
            ]);

            // Limpiamos la sesión de activación
            session()->forget('activation_collegiate_id');

            // Autenticamos automáticamente al usuario
            Auth::login($user);

            return redirect()->route('home')->with('success', '¡Cuenta activada exitosamente con tu código PIN de 4 dígitos! A partir de ahora podrás ingresar siempre con tu N° de Legajo o tu Teléfono y tu PIN.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $collegiate = Collegiate::find(session('activation_collegiate_id'));
            $matricula = $collegiate ? $collegiate->registration_number : 'Desconocida';
            $apellido = $collegiate ? $collegiate->last_name : 'Desconocido';
            return back()->withInput()->with('error', "Se produjo un error al intentar crear tu cuenta. Por favor, comunícate con el administrador y proporciónale esta información: Matrícula: {$matricula}, Apellido: {$apellido}. Nos encargaremos de revisarlo.");
        }
    }
}
