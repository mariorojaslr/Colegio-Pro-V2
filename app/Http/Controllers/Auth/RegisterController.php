<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Collegiate;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form (Verification Step).
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the Step 1: Verification
     */
    public function verify(Request $request)
    {
        $request->validate([
            'dni' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $tenant = app('tenant');

        // Look up the collegiate in the padrón by any of the identifiers
        $collegiate = Collegiate::where('school_id', $tenant->id)
            ->where(function ($query) use ($request) {
                if (!empty($request->dni)) $query->orWhere('dni', $request->dni);
                if (!empty($request->email)) $query->orWhere('email', $request->email);
                if (!empty($request->phone)) $query->orWhere('phone', $request->phone);
            })->first();

        if (!$collegiate) {
            // Option 1: Closed Registration
            return back()->with('error', 'Tus datos no figuran en el padrón oficial del Colegio. Por favor, comunícate con administración para solicitar tu alta.');
        }

        // Check if a User already exists for this email
        $userExists = User::where('email', $request->email)->exists();
        if ($userExists) {
            return redirect()->route('login')->with('info', 'El correo ya está registrado. Por favor, inicia sesión.');
        }

        // Store data in session to proceed to step 2
        session([
            'register_collegiate_id' => $collegiate->id,
            'register_email' => $request->email,
            'register_phone' => $request->phone,
        ]);

        return redirect()->route('register.finalize');
    }

    /**
     * Show the application registration form (Finalize Step).
     */
    public function showFinalizeForm()
    {
        if (!session()->has('register_collegiate_id')) {
            return redirect()->route('register');
        }

        $collegiate = Collegiate::find(session('register_collegiate_id'));
        return view('auth.register_finalize', compact('collegiate'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $collegiate = Collegiate::find(session('register_collegiate_id'));
        if (!$collegiate) {
            return redirect()->route('register');
        }

        // Update Collegiate with new email and phone if they changed
        $collegiate->update([
            'email' => session('register_email'),
            'phone' => session('register_phone'),
        ]);

        $user = $this->create([
            'name' => $collegiate->first_name . ' ' . $collegiate->last_name,
            'email' => session('register_email'),
            'password' => $request->password,
            'school_id' => $collegiate->school_id,
        ]);

        // Vincular el usuario creado con el registro de colegiado
        $collegiate->update([
            'user_id' => $user->id,
        ]);

        $this->guard()->login($user);

        // Clear session
        session()->forget(['register_collegiate_id', 'register_email', 'register_phone']);

        return redirect($this->redirectPath());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'school_id' => $data['school_id'],
            'role' => 'COLEGIADO',
            'is_active' => true,
        ]);
    }
}
