<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotSiswa
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
        if (!Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('siswa')->user();
        if (!$user->siswa) {
            return redirect()->back()
                ->with('error', 'Data siswa tidak ditemukan.');
        }

        return $next($request);
    }
}
