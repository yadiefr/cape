<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Setting;

class MaintenanceMode
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
        // Cek status maintenance dari database
        try {
            $maintenanceMode = Setting::where('key', 'maintenance_mode')->first();
            $isMaintenanceEnabled = $maintenanceMode && ($maintenanceMode->value === '1' || $maintenanceMode->value === 1 || $maintenanceMode->value === true);
            
            // Jika maintenance mode tidak aktif, lanjutkan request
            if (!$isMaintenanceEnabled) {
                return $next($request);
            }

            // Allow login routes and API routes to be accessed during maintenance
            if ($request->is('login*') || 
                $request->is('guru/login*') || 
                $request->is('siswa/login*') || 
                $request->is('api/*') ||
                $request->is('logout') ||
                $request->is('guru/logout') ||
                $request->is('siswa/logout')) {
                return $next($request);
            }

            // Check user authentication status across all guards
            $isAdmin = auth()->guard('web')->check();
            $isAuthenticated = $isAdmin || 
                             auth()->guard('guru')->check() || 
                             auth()->guard('siswa')->check();

            // Get active guard for authenticated user
            $activeGuard = null;
            if (auth()->guard('web')->check()) {
                $activeGuard = 'web';
            } elseif (auth()->guard('guru')->check()) {
                $activeGuard = 'guru';
            } elseif (auth()->guard('siswa')->check()) {
                $activeGuard = 'siswa';
            }

            // Allow admin routes and admin access
            $isAdminRoute = $request->is('admin') || $request->is('admin/*') || $request->routeIs('admin.*');
            if ($isAdminRoute) {
                return $isAdmin 
                    ? $next($request)
                    : redirect()->route('login');
            }

            // For non-admin routes during maintenance, show maintenance page
            // Allow admins to bypass maintenance mode
            if ($isAdmin) {
                return $next($request);
            }

            // Show maintenance page with authentication status
            return response()->view('maintenance', [
                'site_title' => setting('site_title', 'SMK PGRI CIKAMPEK'),
                'nama_sekolah' => setting('nama_sekolah', 'SMK PGRI CIKAMPEK'),
                'isAuthenticated' => $isAuthenticated,
                'activeGuard' => $activeGuard
            ], 503);
            
        } catch (\Exception $e) {
            \Log::error('Error in maintenance mode check: ' . $e->getMessage());
            // If there's an error checking maintenance mode, continue the request
            return $next($request);
        }
    }
}
