<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Models\Settings;

class HostingStorageHelper
{
    /**
     * Deteksi apakah aplikasi berjalan di hosting
     */
    public static function isHostingEnvironment(): bool
    {
        // Cek struktur direktori hosting yang umum
        $indicators = [
            // Path mengandung 'project_laravel'
            strpos(base_path(), 'project_laravel') !== false,
            // Ada direktori project_laravel dan public_html
            is_dir(base_path('../project_laravel')) && is_dir(base_path('../public_html')),
            // Path dimulai dengan /home/
            strpos(base_path(), '/home/') === 0,
            // Environment bukan localhost
            !in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']),
        ];
        
        return count(array_filter($indicators)) >= 2;
    }
    
    /**
     * Get hosting paths yang umum
     */
    public static function getHostingPaths(): array
    {
        $basePath = base_path();
        
        // Detect actual hosting structure
        if (strpos($basePath, '/home/') === 0) {
            // Hosting environment detected
            $userDir = dirname($basePath); // /home/smkpgric from /home/smkpgric/cape
            
            return [
                'current_laravel' => $basePath,
                'laravel_project' => $userDir . '/project_laravel',
                'public_html' => $userDir . '/public_html',
                'current_storage' => $basePath . '/storage/app/public',
                'laravel_storage' => $userDir . '/project_laravel/storage/app/public',
                'public_storage' => $userDir . '/public_html/storage',
            ];
        } else {
            // Fallback untuk localhost atau struktur lain
            return [
                'current_laravel' => $basePath,
                'laravel_project' => base_path('../project_laravel'),
                'public_html' => base_path('../public_html'),
                'current_storage' => $basePath . '/storage/app/public',
                'laravel_storage' => base_path('../project_laravel/storage/app/public'),
                'public_storage' => base_path('../public_html/storage'),
            ];
        }
    }
    
    /**
     * Ensure directory structure exists untuk hosting
     */
    public static function ensureHostingDirectories(): array
    {
        $results = [];
        $paths = self::getHostingPaths();
        
        $requiredDirs = [
            'laravel_storage_settings' => $paths['laravel_storage'] . '/settings',
            'public_storage' => $paths['public_storage'],
            'public_storage_settings' => $paths['public_storage'] . '/settings',
        ];
        
        foreach ($requiredDirs as $name => $path) {
            if (!is_dir($path)) {
                try {
                    if (File::makeDirectory($path, 0755, true)) {
                        @chmod($path, 0755);
                        $results[$name] = "Created: $path";
                        Log::info("Created hosting directory: $path");
                    } else {
                        $results[$name] = "Failed to create: $path";
                        Log::warning("Failed to create hosting directory: $path");
                    }
                } catch (\Exception $e) {
                    $results[$name] = "Error: " . $e->getMessage();
                    Log::error("Error creating hosting directory $path: " . $e->getMessage());
                }
            } else {
                $results[$name] = "Exists: $path";
            }
        }
        
        return $results;
    }
    
    /**
     * Handle file upload untuk hosting environment
     */
    public static function handleHostingUpload($file, $directory = 'settings', $filename = null): ?string
    {
        if (!$file || !$file->isValid()) {
            Log::error("Invalid file for hosting upload");
            return null;
        }
        
        try {
            // Generate filename jika tidak disediakan
            if (!$filename) {
                $filename = $directory . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            }
            
            $relativePath = $directory . '/' . $filename;
            
            // Get hosting paths
            $paths = self::getHostingPaths();
            Log::info("Hosting paths: " . json_encode($paths));
            
            // Target paths untuk hosting
            $currentStoragePath = $paths['current_storage'] . '/' . $relativePath;
            $publicStoragePath = $paths['public_storage'] . '/' . $relativePath;
            
            // Ensure directories exist
            $currentDir = dirname($currentStoragePath);
            $publicDir = dirname($publicStoragePath);
            
            foreach ([$currentDir, $publicDir] as $dir) {
                if (!is_dir($dir)) {
                    if (!File::makeDirectory($dir, 0755, true)) {
                        Log::error("Failed to create directory: $dir");
                        continue;
                    }
                    @chmod($dir, 0755);
                    Log::info("Created directory: $dir");
                }
            }
            
            // Move uploaded file to current storage
            if ($file->move($currentDir, $filename)) {
                @chmod($currentStoragePath, 0644);
                Log::info("File moved to current storage: $currentStoragePath");
                
                // Copy to public HTML storage
                if (copy($currentStoragePath, $publicStoragePath)) {
                    @chmod($publicStoragePath, 0644);
                    Log::info("File copied to public storage: $publicStoragePath");
                } else {
                    Log::error("Failed to copy to public storage: $publicStoragePath");
                }
                
                return $relativePath;
            } else {
                Log::error("Failed to move file to: $currentStoragePath");
                return null;
            }
            
        } catch (\Exception $e) {
            Log::error("Error in hosting upload: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Sync single file untuk hosting
     */
    public static function syncFileToHosting(string $relativePath): bool
    {
        if (!self::isHostingEnvironment()) {
            return true; // No need to sync in localhost
        }
        
        $paths = self::getHostingPaths();
        $sourceFile = storage_path('app/public/' . $relativePath);
        $targetFile = $paths['public_storage'] . '/' . $relativePath;
        
        // Ensure source file exists
        if (!file_exists($sourceFile)) {
            Log::warning("Source file not found for hosting sync: $sourceFile");
            return false;
        }
        
        // Ensure target directory exists
        $targetDir = dirname($targetFile);
        if (!is_dir($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
            @chmod($targetDir, 0755);
        }
        
        // Copy file
        try {
            if (File::copy($sourceFile, $targetFile)) {
                @chmod($targetFile, 0644);
                Log::info("File synced to hosting: $relativePath");
                return true;
            } else {
                Log::error("Failed to copy file to hosting: $relativePath");
                return false;
            }
        } catch (\Exception $e) {
            Log::error("Error syncing file to hosting $relativePath: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sync all settings images ke hosting
     */
    public static function syncAllSettingsToHosting(): array
    {
        $results = [];
        
        if (!self::isHostingEnvironment()) {
            return ['message' => 'Not in hosting environment - sync not needed'];
        }
        
        // Ensure directories exist first
        $dirResults = self::ensureHostingDirectories();
        $results['directories'] = $dirResults;
        
        // Get all image settings
        try {
            $imageSettings = Settings::where('type', 'image')->whereNotNull('value')->get();
            $syncResults = [];
            
            foreach ($imageSettings as $setting) {
                if (empty($setting->value)) continue;
                
                $success = self::syncFileToHosting($setting->value);
                $syncResults[] = [
                    'key' => $setting->key,
                    'path' => $setting->value,
                    'success' => $success
                ];
            }
            
            $results['files'] = $syncResults;
            $results['total_files'] = count($syncResults);
            $results['successful'] = count(array_filter($syncResults, fn($r) => $r['success']));
            
        } catch (\Exception $e) {
            $results['error'] = $e->getMessage();
            Log::error("Error syncing settings to hosting: " . $e->getMessage());
        }
        
        return $results;
    }
    
    /**
     * Check hosting status
     */
    public static function getHostingStatus(): array
    {
        $status = [
            'is_hosting' => self::isHostingEnvironment(),
            'base_path' => base_path(),
            'timestamp' => now()->toISOString(),
        ];
        
        if ($status['is_hosting']) {
            $paths = self::getHostingPaths();
            
            $status['paths'] = $paths;
            $status['directory_status'] = [
                'laravel_project_exists' => is_dir($paths['laravel_project']),
                'public_html_exists' => is_dir($paths['public_html']),
                'laravel_storage_exists' => is_dir($paths['laravel_storage']),
                'public_storage_exists' => is_dir($paths['public_storage']),
            ];
            
            // Check settings files
            try {
                $imageSettings = Settings::where('type', 'image')->whereNotNull('value')->get();
                $fileStatus = [];
                
                foreach ($imageSettings as $setting) {
                    if (empty($setting->value)) continue;
                    
                    $sourceFile = storage_path('app/public/' . $setting->value);
                    $targetFile = $paths['public_storage'] . '/' . $setting->value;
                    
                    $fileStatus[] = [
                        'key' => $setting->key,
                        'path' => $setting->value,
                        'source_exists' => file_exists($sourceFile),
                        'target_exists' => file_exists($targetFile),
                        'needs_sync' => file_exists($sourceFile) && !file_exists($targetFile),
                    ];
                }
                
                $status['files'] = $fileStatus;
                $status['needs_sync'] = count(array_filter($fileStatus, fn($f) => $f['needs_sync'])) > 0;
                
            } catch (\Exception $e) {
                $status['error'] = $e->getMessage();
            }
        }
        
        return $status;
    }
}
