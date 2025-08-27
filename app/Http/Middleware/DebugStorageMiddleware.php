<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DebugStorageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run in production for debugging
        if (app()->environment('production') && $request->has('debug_storage')) {
            $this->debugStorageInfo();
        }

        return $next($request);
    }

    private function debugStorageInfo()
    {
        $info = [
            'storage_path' => storage_path('app/public'),
            'public_storage_path' => public_path('storage'),
            'storage_link_exists' => is_link(public_path('storage')),
            'storage_dir_exists' => is_dir(public_path('storage')),
            'app_url' => config('app.url'),
            'filesystem_disk' => config('filesystems.default'),
            'public_disk_url' => config('filesystems.disks.public.url'),
        ];

        // Check some sample files
        $sampleFiles = [
            'siswa/foto/PhUqdwCpwelU6kgESLxIgWkUxB2K69V8mjQXJTaj.jpg',
            'guru/4lEJdEfdtB3kSqhJdE2eFdC5JYHTI5truFYdmmuz.jpg',
            'settings/logo_sekolah_1752032684_686de5ac08411.png',
        ];

        foreach ($sampleFiles as $file) {
            $storagePath = storage_path('app/public/' . $file);
            $publicPath = public_path('storage/' . $file);
            
            $info['files'][$file] = [
                'storage_exists' => file_exists($storagePath),
                'public_exists' => file_exists($publicPath),
                'storage_readable' => file_exists($storagePath) && is_readable($storagePath),
                'public_readable' => file_exists($publicPath) && is_readable($publicPath),
                'asset_url' => asset('storage/' . $file),
                'storage_asset_url' => function_exists('storage_asset') ? storage_asset($file) : 'helper not loaded',
            ];
        }

        Log::info('Storage Debug Info', $info);
        
        // Also return as response for immediate viewing
        return response()->json($info);
    }
}
