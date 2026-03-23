<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LocationService
{
    /**
     * Detects if the current visitor is from Argentina based on IP.
     * Uses session caching to avoid redundant API calls.
     */
    public function isFromArgentina(): bool
    {
        if (session()->has('is_argentina')) {
            return session('is_argentina');
        }

        try {
            // For production, we'd use request()->ip(). 
            // For testing/development, we might need a real external IP.
            $ip = request()->ip();
            
            // Si es localhost o IP privada, asumimos Argentina para la demo por defecto
            if ($ip === '127.0.0.1' || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
                $isArgentina = true;
            } else {
                $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
                $isArgentina = ($response->successful() && $response->json('countryCode') === 'AR');
            }

            session(['is_argentina' => $isArgentina]);
            return $isArgentina;
        } catch (\Exception $e) {
            Log::error("GeoIP Detection failed: " . $e->getMessage());
            return true; // Default to Local (Argentina) in case of error
        }
    }

    /**
     * Clears the cached location from session.
     */
    public function clearCache()
    {
        session()->forget('is_argentina');
    }
}
