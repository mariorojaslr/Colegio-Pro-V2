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
        // 1. Prioridad: Elección manual del usuario en sesión
        $locale = session('app_locale', null);

        if (!$locale) {
            // 2. Prioridad: Configuración por defecto de la escuela
            if (auth()->check() && auth()->user()->school) {
                $locale = auth()->user()->school->locale;
            } else {
                // 3. Prioridad: Navegador o Default
                $locale = $request->getPreferredLanguage(['es', 'en', 'pt']) ?? 'es';
            }
        }

        app()->setLocale($locale ?: 'es');
        
        // 4. Inyección de traducciones dinámicas desde DB (Prioridad SaaS)
        // Agrupamos por 'group' para inyectarlas de forma masiva y evitar errores de colisión
        $locale = app()->getLocale();
        $dbTranslations = \Illuminate\Support\Facades\Cache::remember("translations_".$locale, 3600, function() {
            return \App\Models\Translation::all();
        });

        $lines = [];
        foreach($dbTranslations as $t) {
            $value = $t->{$locale} ?: $t->es;
            if ($t->group && $t->key) {
                // Inyectamos con formato 'grupo.clave' para compatibilidad total con __('grupo.clave')
                $lines["{$t->group}.{$t->key}"] = $value;
            }
        }

        if (!empty($lines)) {
            \Illuminate\Support\Facades\Lang::addLines($lines, $locale);
        }

        return $next($request);
    }
}
