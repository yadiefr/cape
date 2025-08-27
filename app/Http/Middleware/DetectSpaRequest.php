<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectSpaRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Deteksi apakah ini adalah request AJAX/SPA
        if ($request->ajax() && !$request->wantsJson()) {
            // Set flag untuk template agar dapat mengetahui ini request SPA
            $request->attributes->add(['isSpaRequest' => true]);
            
            // Tambahkan header untuk memberitahu browser bahwa ini adalah request SPA
            $response = $next($request);
            
            if ($response instanceof Response) {
                $response->headers->set('X-SPA-Response', 'true');
            }
            
            return $response;
        }
        
        return $next($request);
    }
}
