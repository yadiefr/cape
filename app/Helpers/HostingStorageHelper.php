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
        // FORCE HOSTING MODE untuk domain tertentu
        $requestHost = request()->getHost();
        $forceHostingDomains = [
            'smkpgricikampek.my.id',
            'smkpgricikampek.com',
            'localhost', // untuk testing
        ];

        if (in_array($requestHost, $forceHostingDomains)) {
            Log::info("FORCED HOSTING MODE for domain: " . $requestHost);
            return true;
        }

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

        $isHosting = count(array_filter($indicators)) >= 2;

        // Log detailed information
        Log::info("Environment Detection Details:");
        Log::info("- Base path: " . base_path());
        Log::info("- Request host: " . $requestHost);
        Log::info("- Contains 'project_laravel': " . (strpos(base_path(), 'project_laravel') !== false ? 'YES' : 'NO'));
        Log::info("- Has ../project_laravel dir: " . (is_dir(base_path('../project_laravel')) ? 'YES' : 'NO'));
        Log::info("- Has ../public_html dir: " . (is_dir(base_path('../public_html')) ? 'YES' : 'NO'));
        Log::info("- Path starts with /home/: " . (strpos(base_path(), '/home/') === 0 ? 'YES' : 'NO'));
        Log::info("- Is localhost: " . (in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']) ? 'YES' : 'NO'));
        Log::info("- Final result: " . ($isHosting ? 'HOSTING' : 'LOCALHOST'));

        return $isHosting;
    }

    /**
     * Force hosting mode untuk testing (temporary)
     */
    public static function forceHostingMode(): bool
    {
        return true;
    }

    /**
     * Universal file upload handler for hosting - FORCE HOSTING MODE
     * Menangani semua jenis upload file dengan konsisten - FOR TESTING
     */
    public static function uploadFileForceHosting($file, $directory, $filename = null): ?string
    {
        if (!$file || !$file->isValid()) {
            Log::error("Invalid file for upload to directory: $directory");
            return null;
        }

        try {
            // Get file info before processing (avoid temp file issues)
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();

            // Generate filename jika tidak disediakan
            if (!$filename) {
                $timestamp = time();
                $random = uniqid();
                $filename = $directory . '_' . $timestamp . '_' . $random . '.' . $extension;
            }

            $relativePath = $directory . '/' . $filename;

            Log::info("UploadFileForceHosting called - Directory: $directory, FORCED HOSTING MODE");
            Log::info("Base path: " . base_path());
            Log::info("Request host: " . request()->getHost());

            // FORCE HOSTING MODE for testing
            Log::info("FORCED: Using HOSTING upload for directory: $directory");
            return self::handleHostingUpload($file, $directory, $filename);

        } catch (\Exception $e) {
            Log::error("Error in uploadFileForceHosting for directory $directory: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get hosting paths yang umum
     */
    public static function getHostingPaths(): array
    {
        $basePath = base_path();

        Log::info("Getting hosting paths for base path: " . $basePath);

        // Detect actual hosting structure
        if (strpos($basePath, '/home/') === 0) {
            // Hosting environment detected
            $userDir = dirname($basePath); // /home/smkpgric from /home/smkpgric/cape
            $projectName = basename($basePath); // 'cape' from /home/smkpgric/cape

            Log::info("Detected hosting structure:");
            Log::info("- User dir: " . $userDir);
            Log::info("- Project name: " . $projectName);

            $paths = [
                'current_laravel' => $basePath,
                'laravel_project' => $userDir . '/project_laravel',
                'public_html' => $userDir . '/public_html',
                'current_storage' => $basePath . '/storage/app/public',
                'laravel_storage' => $userDir . '/project_laravel/storage/app/public',
                'public_storage' => $userDir . '/public_html/storage',
                // Path yang benar untuk hosting - uploads directory
                'public_uploads' => $userDir . '/public_html/uploads',
                'current_uploads' => $basePath . '/public/uploads',
            ];

            Log::info("Final hosting paths: " . json_encode($paths));
            return $paths;
        } else {
            Log::info("Using localhost fallback paths");
            // Fallback untuk localhost atau struktur lain
            return [
                'current_laravel' => $basePath,
                'laravel_project' => base_path('../project_laravel'),
                'public_html' => base_path('../public_html'),
                'current_storage' => $basePath . '/storage/app/public',
                'laravel_storage' => base_path('../project_laravel/storage/app/public'),
                'public_storage' => base_path('../public_html/storage'),
                // Path yang benar untuk hosting - uploads directory
                'public_uploads' => base_path('../public_html/uploads'),
                'current_uploads' => $basePath . '/public/uploads',
            ];
        }
    }
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
     * Universal file upload handler for hosting
     * Menangani semua jenis upload file dengan konsisten
     */
    public static function uploadFile($file, $directory, $filename = null): ?string
    {
        if (!$file || !$file->isValid()) {
            Log::error("Invalid file for upload to directory: $directory");
            return null;
        }
        
        try {
            // Get file info before processing (avoid temp file issues)
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            
            // Generate filename jika tidak disediakan
            if (!$filename) {
                $timestamp = time();
                $random = uniqid();
                $filename = $directory . '_' . $timestamp . '_' . $random . '.' . $extension;
            }
            
            $relativePath = $directory . '/' . $filename;
            
            Log::info("UploadFile called - Directory: $directory, IsHosting: " . (self::isHostingEnvironment() ? 'YES' : 'NO'));
            Log::info("Base path: " . base_path());
            Log::info("Request host: " . request()->getHost());
            
            if (self::isHostingEnvironment()) {
                Log::info("Using HOSTING upload for directory: $directory");
                // Hosting environment - use enhanced upload
                return self::handleHostingUpload($file, $directory, $filename);
            } else {
                Log::info("Using LOCALHOST upload for directory: $directory");
                // Localhost - use standard Laravel upload
                return self::handleLocalhostUpload($file, $directory, $filename);
            }
            
        } catch (\Exception $e) {
            Log::error("Error in uploadFile for directory $directory: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Handle localhost upload
     */
    private static function handleLocalhostUpload($file, $directory, $filename): ?string
    {
        try {
            $relativePath = $directory . '/' . $filename;
            $path = $file->storeAs($directory, $filename, 'public');
            
            // Copy to public/storage for localhost compatibility
            $sourceFile = storage_path('app/public/' . $path);
            $destFile = public_path('storage/' . $path);
            $destDir = dirname($destFile);
            
            if (!is_dir($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            
            if (file_exists($sourceFile) && copy($sourceFile, $destFile)) {
                @chmod($destFile, 0644);
                Log::info("Localhost upload successful: $path");
                return $path;
            }
            
            Log::warning("Failed to copy to public storage for localhost: $destFile");
            return $path; // Still return path even if copy failed
            
        } catch (\Exception $e) {
            Log::error("Error in localhost upload: " . $e->getMessage());
            return null;
        }
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
            // Get file info before moving (avoid temp file issues)
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $tempPath = $file->getPathname();

            // Generate filename jika tidak disediakan
            if (!$filename) {
                $filename = $directory . '_' . time() . '_' . uniqid() . '.' . $extension;
            }

            $relativePath = $directory . '/' . $filename;

            // Get hosting paths
            $paths = self::getHostingPaths();
            Log::info("Hosting upload - Original: $originalName, Target: $relativePath");
            Log::info("Hosting paths: " . json_encode($paths));

            // Target paths untuk hosting - gunakan uploads directory
            $publicUploadsPath = $paths['public_uploads'] . '/' . $relativePath;
            $currentUploadsPath = $paths['current_uploads'] . '/' . $relativePath;

            Log::info("Target paths:");
            Log::info("- Public uploads: " . $publicUploadsPath);
            Log::info("- Current uploads: " . $currentUploadsPath);
            Log::info("- Public uploads dir exists: " . (is_dir(dirname($publicUploadsPath)) ? 'YES' : 'NO'));
            Log::info("- Current uploads dir exists: " . (is_dir(dirname($currentUploadsPath)) ? 'YES' : 'NO'));

            // Ensure directories exist
            $publicDir = dirname($publicUploadsPath);
            $currentDir = dirname($currentUploadsPath);

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

            // Move uploaded file langsung ke public_html/uploads (lokasi utama)
            if ($file->move($publicDir, $filename)) {
                @chmod($publicUploadsPath, 0644);
                Log::info("File moved to public uploads: $publicUploadsPath");

                // Copy juga ke current uploads untuk konsistensi
                if (copy($publicUploadsPath, $currentUploadsPath)) {
                    @chmod($currentUploadsPath, 0644);
                    Log::info("File copied to current uploads: $currentUploadsPath");
                } else {
                    Log::warning("Failed to copy to current uploads: $currentUploadsPath");
                }

                // Double check file exists
                $publicExists = file_exists($publicUploadsPath);
                Log::info("Final check - Public uploads: " . ($publicExists ? 'EXISTS' : 'NOT FOUND'));

                if ($publicExists) {
                    return $relativePath;
                } else {
                    Log::error("File not found after move: $publicUploadsPath");
                    return null;
                }
            } else {
                Log::error("Failed to move file from temp to: $publicUploadsPath");
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
        $targetFile = $paths['public_uploads'] . '/' . $relativePath;

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
                'public_uploads_exists' => is_dir($paths['public_uploads']),
                'current_uploads_exists' => is_dir($paths['current_uploads']),
            ];

            // Check settings files
            try {
                $imageSettings = Settings::where('type', 'image')->whereNotNull('value')->get();
                $fileStatus = [];

                foreach ($imageSettings as $setting) {
                    if (empty($setting->value)) continue;

                    $sourceFile = storage_path('app/public/' . $setting->value);
                    $targetFile = $paths['public_uploads'] . '/' . $setting->value;

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
