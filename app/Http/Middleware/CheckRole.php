<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check auth status for different guards
        $guards = ['web', 'guru', 'siswa'];
        $authenticated = false;

        foreach ($guards as $guard) {
            if (auth()->guard($guard)->check()) {
                $user = auth()->guard($guard)->user();
                
                // For students, check if the guard matches and user has role
                if ($guard === 'siswa' && $role === 'siswa') {
                    // Additional check: ensure user has hasRole method and role property
                    if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                        $authenticated = true;
                        break;
                    }
                }
                // For other users (web, guru), check role property or hasRole method
                elseif ($guard !== 'siswa' && $user && ($user->role === $role || (method_exists($user, 'hasRole') && $user->hasRole($role)))) {
                    $authenticated = true;
                    break;
                }
            }
        }

        if (!$authenticated) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 