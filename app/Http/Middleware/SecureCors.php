<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureCors
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Skip adding headers for file downloads (BinaryFileResponse)
        if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
            return $response;
        }

        // Only add CORS headers for API requests or specific paths
        if ($request->is('api/*') || $request->is('sanctum/*')) {
            $allowedOrigins = [
                'https://smk305ckp.my.id',
                'http://localhost:8000',
                'http://127.0.0.1:8000',
            ];

            $origin = $request->header('Origin');
            
            if (in_array($origin, $allowedOrigins)) {
                $response->header('Access-Control-Allow-Origin', $origin);
                $response->header('Access-Control-Allow-Credentials', 'true');
            }

            $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With, X-CSRF-TOKEN');
            $response->header('Access-Control-Max-Age', '86400');
        }

        // Add security headers only for non-file responses
        if (method_exists($response, 'header')) {
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
        }

        return $response;
    }
}
