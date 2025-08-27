<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PreventAuthConflicts
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If accessing main system (admin/guru/siswa) routes and user is logged in as pendaftar
        if ($request->is('admin/*') || $request->is('guru/*') || $request->is('siswa/*')) {
            if (Auth::guard('pendaftar')->check() && !Auth::guard('web')->check() && !Auth::guard('guru')->check() && !Auth::guard('siswa')->check()) {
                return redirect()->route('pendaftar.login')
                    ->with('warning', 'Silakan login dengan akun yang sesuai untuk mengakses halaman ini.');
            }
        }

        // If accessing PPDB routes and user is logged in as other guards
        if ($request->is('ppdb/*') || $request->is('pendaftaran/*')) {
            if ((Auth::guard('web')->check() || Auth::guard('guru')->check() || Auth::guard('siswa')->check()) && !Auth::guard('pendaftar')->check()) {
                return redirect()->route('pendaftaran.index')
                    ->with('info', 'Silakan login sebagai pendaftar untuk mengakses sistem PPDB.');
            }
        }

        return $next($request);
    }
}
