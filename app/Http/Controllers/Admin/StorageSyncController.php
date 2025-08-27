<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class StorageSyncController extends Controller
{
    /**
     * Halaman sync storage
     */
    public function index()
    {
        $isHosting = $this->detectHostingEnvironment();
        $syncStatus = $this->checkSyncStatus();
        
        return view('admin.storage-sync.index', compact('isHosting', 'syncStatus'));
    }
    
    /**
     * Sinkronisasi files melalui web
     */
    public function sync(Request $request)
    {
        try {
            $results = [];
            $totalSynced = 0;
            $totalErrors = 0;
            
            // Deteksi environment hosting
            $isHosting = $this->detectHostingEnvironment();
            
            if (!$isHosting) {
                return response()->json([
                    'success' => true,
                    'message' => 'Localhost environment - no sync needed',
                    'results' => []
                ]);
            }
            
            // Get all image settings
            $imageSettings = Settings::where('type', 'image')->get();
            
            foreach ($imageSettings as $setting) {
                if (!$setting->value) {
                    continue;
                }
                
                $result = $this->syncSingleFile($setting->value, $setting->key);
                $results[] = $result;
                
                if ($result['success']) {
                    $totalSynced++;
                } else {
                    $totalErrors++;
                }
            }
            
            // Juga sync direktori guru jika ada
            $this->syncDirectory('guru');
            
            $message = "Sync completed: {$totalSynced} files synced";
            if ($totalErrors > 0) {
                $message .= ", {$totalErrors} errors";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'results' => $results,
                'total_synced' => $totalSynced,
                'total_errors' => $totalErrors
            ]);
            
        } catch (\Exception $e) {
            Log::error('Storage sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Sync failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Sync single file
     */
    private function syncSingleFile($relativePath, $key = null)
    {
        try {
            $laravelStoragePath = base_path('../project_laravel/storage/app/public/' . $relativePath);
            $publicStoragePath = base_path('../public_html/storage/' . $relativePath);
            
            // Fallback jika path tidak ditemukan, coba path standard
            if (!file_exists($laravelStoragePath)) {
                $laravelStoragePath = storage_path('app/public/' . $relativePath);
            }
            
            if (!file_exists($laravelStoragePath)) {
                return [
                    'file' => $relativePath,
                    'key' => $key,
                    'success' => false,
                    'message' => 'Source file not found'
                ];
            }
            
            // Ensure target directory exists
            $targetDir = dirname($publicStoragePath);
            if (!is_dir($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }
            
            // Copy file
            if (File::copy($laravelStoragePath, $publicStoragePath)) {
                @chmod($publicStoragePath, 0644);
                
                return [
                    'file' => $relativePath,
                    'key' => $key,
                    'success' => true,
                    'message' => 'File synced successfully'
                ];
            } else {
                return [
                    'file' => $relativePath,
                    'key' => $key,
                    'success' => false,
                    'message' => 'Failed to copy file'
                ];
            }
            
        } catch (\Exception $e) {
            return [
                'file' => $relativePath,
                'key' => $key,
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Sync entire directory
     */
    private function syncDirectory($directory)
    {
        try {
            $laravelDir = base_path('../project_laravel/storage/app/public/' . $directory);
            $publicDir = base_path('../public_html/storage/' . $directory);
            
            // Fallback path
            if (!is_dir($laravelDir)) {
                $laravelDir = storage_path('app/public/' . $directory);
            }
            
            if (!is_dir($laravelDir)) {
                return false;
            }
            
            // Ensure target directory exists
            if (!is_dir($publicDir)) {
                File::makeDirectory($publicDir, 0755, true);
            }
            
            // Copy all files from source to target
            $files = File::allFiles($laravelDir);
            foreach ($files as $file) {
                $relativePath = str_replace($laravelDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $targetFile = $publicDir . DIRECTORY_SEPARATOR . $relativePath;
                
                // Ensure subdirectory exists
                $targetSubdir = dirname($targetFile);
                if (!is_dir($targetSubdir)) {
                    File::makeDirectory($targetSubdir, 0755, true);
                }
                
                File::copy($file->getPathname(), $targetFile);
                @chmod($targetFile, 0644);
            }
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Directory sync error for ' . $directory . ': ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deteksi environment hosting
     */
    private function detectHostingEnvironment()
    {
        // Cek apakah ada struktur hosting (project_laravel dan public_html terpisah)
        $projectLaravelPath = base_path('../project_laravel');
        $publicHtmlPath = base_path('../public_html');
        
        return (is_dir($projectLaravelPath) && is_dir($publicHtmlPath));
    }
    
    /**
     * Check sync status
     */
    private function checkSyncStatus()
    {
        $status = [];
        
        if (!$this->detectHostingEnvironment()) {
            return ['environment' => 'localhost'];
        }
        
        $imageSettings = Settings::where('type', 'image')->get();
        $totalFiles = 0;
        $syncedFiles = 0;
        $missingFiles = 0;
        
        foreach ($imageSettings as $setting) {
            if (!$setting->value) continue;
            
            $totalFiles++;
            $publicFile = base_path('../public_html/storage/' . $setting->value);
            
            if (file_exists($publicFile)) {
                $syncedFiles++;
            } else {
                $missingFiles++;
            }
        }
        
        return [
            'environment' => 'hosting',
            'total_files' => $totalFiles,
            'synced_files' => $syncedFiles,
            'missing_files' => $missingFiles,
            'sync_needed' => $missingFiles > 0
        ];
    }
}
