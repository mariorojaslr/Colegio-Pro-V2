<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    /**
     * Maneja la restricción de acceso por roles.
     * Permite una lista variable de roles admitidos para la ruta.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si el usuario no está autenticado, redirigir al login
        if (!auth()->check()) {
            return redirect('login');
        }

        $user = auth()->user();

        // Si el rol del usuario está dentro de la lista permitida para la ruta, continuar
        $userRole = strtoupper($user->role);
        $allowedRoles = array_map('strtoupper', $roles);
        
        if (in_array($userRole, $allowedRoles)) {
            return $next($request);
        }

        // Si el rol no está permitido, lanzar un error 403 (Prohibido)
        return abort(403, 'Acceso denegado. No tienes permisos para realizar esta acción.');
    }
}
