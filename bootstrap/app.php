<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\IdentifyTenant::class,
            \App\Http\Middleware\SetLocale::class,
        ]);
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            'webhook/mercadopago',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Si la sesión o el token CSRF expiraron (Error 419 Page Expired), cerramos cualquier resto de sesión
        // y redirigimos suavemente al login sin mostrar pantalla de error
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if (auth()->check()) {
                auth()->logout();
            }
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('info', 'Tu sesión ha expirado por inactividad. Por favor, vuelve a ingresar.');
        });

        // Si un enlace firmado expiró (Error 403 InvalidSignatureException), redirigimos al login sin pantalla de error
        $exceptions->render(function (\Illuminate\Routing\Exceptions\InvalidSignatureException $e, \Illuminate\Http\Request $request) {
            if (auth()->check()) {
                auth()->logout();
            }
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('info', 'El tiempo del enlace de acceso ha expirado. Por favor, inicia sesión nuevamente.');
        });

        // Captura general de HttpException 419 (por si se dispara como HttpException directamente)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, \Illuminate\Http\Request $request) {
            if ($e->getStatusCode() === 419) {
                if (auth()->check()) {
                    auth()->logout();
                }
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('info', 'Tu sesión ha expirado por inactividad. Por favor, vuelve a ingresar.');
            }
        });
    })->create();
