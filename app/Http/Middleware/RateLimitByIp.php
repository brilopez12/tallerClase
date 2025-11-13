<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitByIp
{
    /**
     * Manejar una petición entrante.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = "rate_limit:{$ip}";

        // Obtener número actual de peticiones
        $requests = Cache::get($key, 0);

        if ($requests >= 6) {
            return response()->json([
                'message' => 'Too Many Requests. Intenta nuevamente en un minuto.'
            ], 429);
        }

        // Incrementar contador y ponerle expiración de 1 minuto
        Cache::put($key, $requests + 1, now()->addMinute());

        return $next($request);
    }
}
