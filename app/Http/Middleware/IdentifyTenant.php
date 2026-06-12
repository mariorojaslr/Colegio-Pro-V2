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

        // If it's a multi-part domain (e.g., cotolar.gentepiola.net)
        // or just localhost (e.g., we can map it)
        if (count($parts) > 1 && $host !== 'localhost' && $host !== '127.0.0.1') {
            $subdomain = $parts[0];
            $tenant = \App\Models\School::where('slug', $subdomain)->first();
        }

        // Fallback for local testing or unmapped domains
        if (!$tenant) {
            $tenant = \App\Models\School::where('slug', 'cotolar')->first() ?? \App\Models\School::first();
        }

        // Store globally in the application container
        app()->instance('tenant', $tenant);
        
        // Share with all views
        \Illuminate\Support\Facades\View::share('currentTenant', $tenant);

        return $next($request);
    }
}
