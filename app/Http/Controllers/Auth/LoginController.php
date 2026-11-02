<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\Collegiate;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Acceso rápido para Colegiados utilizando su N° de Legajo o Teléfono Celular
     * y su Código de Validación PIN de 4 dígitos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginWithPin(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'pin' => ['required', 'regex:/^[0-9]{4}$/'],
        ], [
            'identifier.required' => 'Ingresa tu N° de Legajo o Teléfono Celular.',
            'pin.required' => 'Ingresa tu código PIN de 4 dígitos.',
            'pin.regex' => 'El código PIN debe ser exactamente de 4 dígitos numéricos.',
        ]);

        $rawIdentifier = trim($request->input('identifier'));
        $cleanIdentifier = preg_replace('/[^0-9]/', '', $rawIdentifier);
        $pin = trim($request->input('pin'));
        $tenant = app('tenant');

        // 1. Buscamos en el padrón de Colegiados aislando estrictamente por el colegio actual
        $query = Collegiate::query();
        if ($tenant) {
            $query->where('school_id', $tenant->id);
        }

        $collegiate = $query->where(function ($q) use ($rawIdentifier, $cleanIdentifier) {
            // Coincidencia por N° de Legajo / Matrícula
            $q->where('registration_number', $rawIdentifier)
              ->orWhere('registration_number', $cleanIdentifier);

            // Coincidencia por Teléfono celular
            if (!empty($rawIdentifier)) {
                $q->orWhere('phone', $rawIdentifier);
            }
            if (!empty($cleanIdentifier) && strlen($cleanIdentifier) >= 6) {
                $q->orWhereRaw("REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), '+', ''), '(', '') LIKE ?", ["%{$cleanIdentifier}%"]);
            }

            // Coincidencia por DNI si estuviese cargado
            if (!empty($cleanIdentifier) && strlen($cleanIdentifier) >= 6) {
                $q->orWhere('dni', $cleanIdentifier);
            }
        })->first();

        // Si encontramos al colegiado
        if ($collegiate) {
            // Caso A: El colegiado ya tiene usuario en el sistema
            if ($collegiate->user_id) {
                $user = User::find($collegiate->user_id);
                if ($user) {
                    $pinValido = false;

                    // Verificación 1: PIN almacenado en el usuario
                    if (!empty($user->login_pin) && Hash::check($pin, $user->login_pin)) {
                        $pinValido = true;
                    }
                    // Verificación 2: PIN almacenado en el colegiado (sincronización)
                    elseif (!empty($collegiate->login_pin) && Hash::check($pin, $collegiate->login_pin)) {
                        $pinValido = true;
                        $user->login_pin = $collegiate->login_pin;
                        $user->save();
                    }
                    // Verificación 3: Si usó su PIN como password
                    elseif (Hash::check($pin, $user->password)) {
                        $pinValido = true;
                        $user->login_pin = Hash::make($pin);
                        $user->save();
                    }

                    if ($pinValido) {
                        Auth::login($user, $request->boolean('remember'));
                        $request->session()->regenerate();
                        return redirect()->intended('/home')->with('success', "¡Bienvenido/a {$user->name}!");
                    } else {
                        if (empty($user->login_pin)) {
                            // Tiene usuario pero nunca generó su PIN
                            session(['activation_collegiate_id' => $collegiate->id]);
                            return redirect()->route('activate.step2')->with('info', "¡Hola {$collegiate->first_name}! Aún no tienes configurado tu código PIN de 4 dígitos. Ingrésalo ahora para habilitar tu acceso rápido.");
                        }

                        return back()->withInput()->with('error', 'El código PIN de 4 dígitos ingresado es incorrecto. Por favor, verifica e inténtalo nuevamente.');
                    }
                }
            }

            // Caso B: El colegiado figura en el padrón oficial pero aún no ha realizado su primer ingreso
            session(['activation_collegiate_id' => $collegiate->id]);
            return redirect()->route('activate.step2')->with('info', "¡Hola {$collegiate->first_name}! Es tu primera vez ingresando. Configura tu código PIN de 4 dígitos para activar tu cuenta.");
        }

        // 2. Si no lo encontramos en la tabla de Colegiados, buscamos en Users directamente (por si es admin con teléfono)
        $userQuery = User::query();
        if ($tenant) {
            $userQuery->where('school_id', $tenant->id);
        }

        $user = $userQuery->where(function ($q) use ($rawIdentifier) {
            $q->where('email', $rawIdentifier);
        })->first();

        if ($user && !empty($user->login_pin) && Hash::check($pin, $user->login_pin)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->intended('/home')->with('success', "¡Bienvenido/a {$user->name}!");
        }

        return back()->withInput()->with('error', "No encontramos ningún profesional registrado con el Legajo o Teléfono '{$rawIdentifier}' en esta institución. Si es tu primera vez, haz clic en 'Activa tu cuenta'.");
    }

    /**
     * El usuario ha cerrado sesión.
     * Redirigir siempre a la Landing Page institucional.
     */
    protected function loggedOut(Request $request)
    {
        return redirect('/');
    }

    /**
     * El usuario ha iniciado sesión por formulario tradicional.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'OWNER' || $user->role === 'ADMIN_COLEGIO' || $user->role === 'ADMIN_INTERNO') {
            return redirect()->intended('/home');
        }

        // Para usuarios normales, NUNCA usar la URL intended si estaban intentando ver algo de admin.
        // Los forzamos siempre al home.
        return redirect('/home');
    }
}

