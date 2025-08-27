<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePPDBSession
{
    public function handle(Request $request, Closure $next): Response
    {
        // Simply check if the user is authenticated with the pendaftar guard
        if (!Auth::guard('pendaftar')->check()) {
            return redirect()->route('pendaftar.login')
                ->with('error', 'Anda harus login sebagai pendaftar untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
