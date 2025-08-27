<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoSyncStorage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only run for storage file requests that result in 404
        $response = $next($request);
        
        if ($response->getStatusCode() === 404 && 
            $request->is('storage/*') && 
            strpos($request->path(), 'settings/') !== false) {
            
            $this->syncMissingFile($request->path());
            
            // Try again after sync
            $response = $next($request);
        }
        
        return $response;
    }
    
    /**
     * Sync missing file from storage to public
     */
    private function syncMissingFile($requestPath)
    {
        try {
            // Extract relative path from request (remove 'storage/' prefix)
            $relativePath = substr($requestPath, 8); // Remove 'storage/' 
            
            $sourcePath = storage_path('app/public/' . $relativePath);
            $targetPath = public_path('storage/' . $relativePath);
            
            if (file_exists($sourcePath) && !file_exists($targetPath)) {
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                
                if (@copy($sourcePath, $targetPath)) {
                    @chmod($targetPath, 0644);
                    \Log::info("Auto-synced missing file: {$relativePath}");
                }
            }
        } catch (\Exception $e) {
            \Log::error("Auto-sync failed for {$requestPath}: " . $e->getMessage());
        }
    }
}
