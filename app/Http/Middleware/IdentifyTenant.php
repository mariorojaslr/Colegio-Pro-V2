<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        $tenant = null;

        // 1. Check via custom domain first
        $tenant = \App\Models\School::where('custom_domain', $host)->first();

        // 2. Check via subdomain if not found by custom domain
        if (!$tenant && count($parts) > 1 && $host !== 'localhost' && $host !== '127.0.0.1') {
            $subdomain = $parts[0];
            $tenant = \App\Models\School::where('slug', $subdomain)->first();
        }

        // 2. Check via query parameter (Pseudo-subdominio para facilitar enlaces temporales)
        if (!$tenant && $request->has('tenant')) {
            $tenant = \App\Models\School::where('slug', $request->query('tenant'))->first();
            // Guardar en sesión para recordarlo
            if ($tenant) {
                session(['active_tenant_id' => $tenant->id]);
            }
        }

        // 3. Check via session
        if (!$tenant && session()->has('active_tenant_id')) {
            $tenant = \App\Models\School::find(session('active_tenant_id'));
        }

        // Fallback for local testing or unmapped domains
        if (!$tenant) {
            $tenant = \App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first();
        }

        // Store globally in the application container
        app()->instance('tenant', $tenant);
        
        // Share with all views
        \Illuminate\Support\Facades\View::share('currentTenant', $tenant);

        if ($tenant) {
            config(['app.name' => $tenant->name]);
        }

        return $next($request);
    }
}
