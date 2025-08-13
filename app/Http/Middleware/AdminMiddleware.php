<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is an admin
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Check if user is an admin (you can adjust this logic based on your auth system)
        if (auth()->guard('web')->check()) {
            return $next($request);
        }

        // If not admin, redirect to appropriate dashboard or login
        abort(403, 'Unauthorized access');
    }
}
