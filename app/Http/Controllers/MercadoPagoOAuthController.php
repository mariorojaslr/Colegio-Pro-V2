<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoOAuthController extends Controller
{
    /**
     * Inicia el flujo de vinculación (Redirige a MP).
     */
    public function redirect(Request $request)
    {
        $school = auth()->user()->school;
        
        $clientId = config('services.mercadopago.client_id');
        $redirectUri = urlencode(config('services.mercadopago.redirect_uri'));
        $state = $school->id; // Usamos el ID como identificador

        $url = "https://auth.mercadopago.com/authorization?client_id={$clientId}&response_type=code&platform_id=mp&state={$state}&redirect_uri={$redirectUri}";

        return redirect()->away($url);
    }

    /**
     * Recibe la respuesta de MP en el Dominio Maestro y guarda las credenciales.
     */
    public function callback(Request $request)
    {
        $code = $request->query('code');
        $state = $request->query('state'); // Este es el school_id

        if (!$code || !$state) {
            return redirect('/')->with('error', 'Error en la vinculación: Faltan parámetros.');
        }

        $school = School::find($state);
        if (!$school) {
            return redirect('/')->with('error', 'Error: Colegio no encontrado.');
        }

        // Intercambiar el authorization_code por los tokens reales
        $response = Http::asForm()->post('https://api.mercadopago.com/oauth/token', [
            'client_id' => config('services.mercadopago.client_id'),
            'client_secret' => config('services.mercadopago.client_secret'),
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => config('services.mercadopago.redirect_uri'),
        ]);

        if ($response->successful()) {
            $data = $response->json();

            // Guardamos las credenciales en el Tenant
            $school->update([
                'mp_access_token' => $data['access_token'],
                'mp_public_key' => $data['public_key'],
                // Podemos guardar el refresh token en el mismo string de acceso o ignorarlo hasta 6 meses
                // 'mp_sandbox_mode' => false 
            ]);

            // Determinar la URL base a donde regresar al cliente (su dominio propio)
            $returnDomain = $school->custom_domain 
                ? "https://" . $school->custom_domain 
                : url('/'); // O fallback a la base local

            return redirect($returnDomain . '/configuracion-institucion')->with('success', '¡Cuenta de Mercado Pago vinculada exitosamente!');
        }

        Log::error('MP OAuth Error', ['response' => $response->body()]);
        return redirect('/')->with('error', 'Error al comunicarse con Mercado Pago.');
    }
}
