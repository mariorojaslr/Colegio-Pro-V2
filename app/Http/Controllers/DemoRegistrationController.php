<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DemoRegistrationController extends Controller
{
    public function show()
    {
        return view('demo.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Asegurarse de que exista el Colegio de Pruebas
        $school = School::firstOrCreate(
            ['slug' => 'demo-school'],
            [
                'name' => 'Colegio Profesional de Pruebas',
                'primary_color' => '#10B981', // Esmeralda para diferenciar
                'secondary_color' => '#F59E0B',
                'plan_category' => 'professional'
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'USER_COLEGIO',
            'school_id' => $school->id,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('status', '¡Bienvenido a la Demo de Colegio-Pro! Ya eres parte de nuestra institución de pruebas.');
    }
}
