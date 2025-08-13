<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Different redirects based on guard
                if ($guard === 'web') {
                    return redirect()->route('dashboard');
                } elseif ($guard === 'guru') {
                    return redirect()->route('guru.dashboard');
                } elseif ($guard === 'siswa') {
                    return redirect()->route('siswa.dashboard');
                } elseif ($guard === 'pendaftar') {
                    return redirect()->route('pendaftar.dashboard');
                }
                
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
