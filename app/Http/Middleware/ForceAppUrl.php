<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class ForceAppUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set app URL dynamically based on current request
        $currentUrl = $request->getSchemeAndHttpHost();
        
        // Force Laravel to use current domain for URL generation
        URL::forceRootUrl($currentUrl);
        
        // Set app URL in config
        config(['app.url' => $currentUrl]);
        
        return $next($request);
    }
}
