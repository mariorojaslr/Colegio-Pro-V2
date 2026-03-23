<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->school) {
                app()->setLocale($user->school->locale ?? 'es');
            }
        } else {
            // Para la landing page, podríamos usar GeoIP para sugerir idioma,
            // pero por ahora usamos el default o detección de navegador básica.
            app()->setLocale($request->getPreferredLanguage(['es', 'en', 'pt']) ?? 'es');
        }

        return $next($request);
    }
}
