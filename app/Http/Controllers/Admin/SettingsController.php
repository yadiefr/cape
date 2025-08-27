<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Helpers\HostingStorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    /**
     * Display settings page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Ensure storage link exists for public access to uploaded files
        $this->ensureStorageAccess();
        
        // Get all settings grouped by their group
        $settingsByGroup = Settings::orderBy('group')
                                   ->orderBy('key')
                                   ->get()
                                   ->groupBy('group');
        
        // Get all settings as key-value array for easy access in view
        $settings = Settings::pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settingsByGroup', 'settings'));
    }
    
    /**
     * Ensure storage is accessible from public directory
     */
    private function ensureStorageAccess()
    {
        $publicStoragePath = public_path('storage');
        
        // Create public/storage directory if it doesn't exist
        if (!file_exists($publicStoragePath)) {
            mkdir($publicStoragePath, 0755, true);
        }
        
        // Create settings subdirectory
        $settingsPath = $publicStoragePath . '/settings';
        if (!file_exists($settingsPath)) {
            mkdir($settingsPath, 0755, true);
        }
        
        // Copy existing files from storage/app/public to public/storage
        $sourceDir = storage_path('app/public');
        if (file_exists($sourceDir)) {
            $this->copyDirectory($sourceDir, $publicStoragePath);
        }
    }
    
    /**
     * Recursively copy directory contents
     */
    private function copyDirectory($source, $destination)
    {
        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $item) {
            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            if ($item->isDir()) {
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                if (!file_exists($destPath) || filemtime($item) > filemtime($destPath)) {
                    copy($item, $destPath);
                }
            }
        }
    }

    /**
     * Update settings
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function update(Request $request)
    {
        try {
            \Log::info("Settings update starting with request data: " . json_encode($request->except(['_token', '_method'])));
            $settings = $request->except(['_token', '_method']);
            
            // Process regular settings first
            if(isset($settings['settings']) && is_array($settings['settings'])) {
                foreach ($settings['settings'] as $key => $settingData) {
                    if (!isset($settingData['key'])) {
                        continue;
                    }

                    $settingModel = Settings::firstOrNew(['key' => $settingData['key']]);
                    
                    // Skip if this is a file upload field but no file was uploaded
                    // We'll handle file uploads separately
                    if (isset($settingData['type']) && $settingData['type'] === 'image' && !$request->hasFile("settings.{$key}.value")) {
                        \Log::info("Skipping image field without file: " . $settingData['key']);
                        continue;
                    }
                    
                    // Only update the value if it's not empty or it's a boolean or it's not a file field
                    if ((isset($settingData['value']) && ($settingData['value'] !== '' || (isset($settingData['type']) && $settingData['type'] === 'boolean')))
                         && (!isset($settingData['type']) || $settingData['type'] !== 'image')) {
                        
                        $settingModel->value = $settingData['value'];
                        $settingModel->group = $settingData['group'] ?? 'general';
                        $settingModel->type = $settingData['type'] ?? 'string';
                        $settingModel->save();
                        
                        // Clear cache for this specific setting
                        Cache::forget("setting_{$settingData['key']}");
                        
                        \Log::info("Updated regular setting: {$settingData['key']} with value: {$settingData['value']}");
                    }
                }
            }
            
            // Handle file uploads
            $uploadedFiles = $this->handleFileUploads($request);
            
            // Clear settings cache after all updates
            $this->clearSettingsCache();
            
            \Log::info("Settings update completed with " . count($uploadedFiles) . " file uploads");
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error("Error updating settings: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()
                ->route('admin.settings.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui pengaturan: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle file uploads for settings
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array  Array of uploaded file information
     */
    private function handleFileUploads(Request $request)
    {
        // Get image type setting keys
        $imageSettings = ['logo_sekolah', 'site_favicon', 'school_photo'];
        $uploadedFiles = [];
        
        foreach ($imageSettings as $key) {
            // Find the array key for this setting in the request
            $settingsKey = null;
            
            if (isset($request->settings) && is_array($request->settings)) {
                foreach ($request->settings as $k => $v) {
                    if (isset($v['key']) && $v['key'] === $key) {
                        $settingsKey = $k;
                        break;
                    }
                }
            }
            
            // Check if file was uploaded
            $fileKey = "settings.{$key}.value";
            if ($settingsKey !== null) {
                $fileKey = "settings.{$settingsKey}.value";
            }
            
            \Log::info("Checking for file upload with key: {$fileKey}");
            
            if ($request->hasFile($fileKey)) {
                $file = $request->file($fileKey);
                \Log::info("File found for setting {$key}: " . $file->getClientOriginalName());
                
                // Validate file
                $validator = Validator::make(
                    ['file' => $file],
                    ['file' => 'image|mimes:jpeg,png,jpg,gif|max:2048']
                );
                
                if ($validator->fails()) {
                    \Log::error("File validation failed for {$key}: " . implode(', ', $validator->errors()->all()));
                    continue;
                }
                
                // Get the setting to check for existing file
                $setting = Settings::where('key', $key)->first();
                
                // Get the old file path before we update
                $oldFilePath = null;
                if ($setting && $setting->value && !empty($setting->value)) {
                    $oldFilePath = $setting->value;
                }
                
                // Generate a unique filename
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                try {
                    // Ensure directories exist
                    $storagePath = storage_path('app/public/settings');
                    if (!is_dir($storagePath)) {
                        mkdir($storagePath, 0755, true);
                        chmod($storagePath, 0755);
                    }
                    
                    $publicStoragePath = public_path('storage/settings');
                    if (!is_dir($publicStoragePath)) {
                        mkdir($publicStoragePath, 0755, true);
                        chmod($publicStoragePath, 0755);
                    }
                    
                    // Store the file in the public disk under 'settings' directory
                    $path = $file->storeAs('settings', $filename, 'public');
                    \Log::info("File stored at: {$path}");
                    
                    // For hosting environments, use HostingStorageHelper
                    if (HostingStorageHelper::isHostingEnvironment()) {
                        HostingStorageHelper::syncFileToHosting($path);
                    } else {
                        // Copy to public/storage for localhost
                        $sourceFile = storage_path('app/public/' . $path);
                        $destFile = public_path('storage/' . $path);
                        
                        if (file_exists($sourceFile)) {
                            if (copy($sourceFile, $destFile)) {
                                chmod($destFile, 0644);
                                \Log::info("File copied to public storage: {$destFile}");
                            } else {
                                \Log::error("Failed to copy file to public storage: {$destFile}");
                            }
                        }
                    }
                    
                    // For hosting environments, also try to copy to hosting paths
                    $this->copyToHostingPaths($path);
                    
                    // Delete old file if exists
                    if ($oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                        try {
                            Storage::disk('public')->delete($oldFilePath);
                            // Also delete from public storage
                            $oldPublicFile = public_path('storage/' . $oldFilePath);
                            if (file_exists($oldPublicFile)) {
                                unlink($oldPublicFile);
                            }
                            // Delete from hosting paths if applicable
                            $this->deleteFromHostingPaths($oldFilePath);
                            \Log::info("Deleted old file: {$oldFilePath}");
                        } catch (\Exception $e) {
                            \Log::error("Failed to delete old file {$oldFilePath}: " . $e->getMessage());
                        }
                    }
                    
                    // Update the setting with the new file path
                    if ($setting) {
                        $setting->value = $path;
                        $setting->type = 'image';
                        $setting->save();
                        \Log::info("Updated existing setting {$key} with new file path: {$path}");
                    } else {
                        // Create new setting if it doesn't exist
                        $setting = Settings::create([
                            'key' => $key,
                            'value' => $path,
                            'type' => 'image',
                            'group' => 'sekolah',
                        ]);
                        \Log::info("Created new setting {$key} with file path: {$path}");
                    }
                    
                    // Clear cache for this specific setting
                    Cache::forget("setting_{$key}");
                    
                    $uploadedFiles[] = [
                        'key' => $key,
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize()
                    ];
                    
                } catch (\Exception $e) {
                    \Log::error("Error handling file upload for {$key}: " . $e->getMessage());
                    throw $e;
                }
            }
        }
        
        return $uploadedFiles;
    }
    
    /**
     * Copy file to hosting paths if detected
     */
    private function copyToHostingPaths($relativePath)
    {
        $sourceFile = storage_path('app/public/' . $relativePath);
        
        // Try common hosting paths
        $hostingPaths = [
            base_path('../project_laravel/storage/app/public/' . $relativePath),
            base_path('../public_html/storage/' . $relativePath),
        ];
        
        foreach ($hostingPaths as $hostingPath) {
            try {
                $hostingDir = dirname($hostingPath);
                if (!is_dir($hostingDir)) {
                    mkdir($hostingDir, 0755, true);
                }
                
                if (file_exists($sourceFile) && copy($sourceFile, $hostingPath)) {
                    chmod($hostingPath, 0644);
                    \Log::info("File copied to hosting path: {$hostingPath}");
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to copy to hosting path {$hostingPath}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Delete file from hosting paths
     */
    private function deleteFromHostingPaths($relativePath)
    {
        $hostingPaths = [
            base_path('../project_laravel/storage/app/public/' . $relativePath),
            base_path('../public_html/storage/' . $relativePath),
        ];
        
        foreach ($hostingPaths as $hostingPath) {
            if (file_exists($hostingPath)) {
                try {
                    unlink($hostingPath);
                    \Log::info("Deleted file from hosting path: {$hostingPath}");
                } catch (\Exception $e) {
                    \Log::warning("Failed to delete from hosting path {$hostingPath}: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Clear all settings cache
     */
    private function clearSettingsCache()
    {
        try {
            // Clear specific settings cache used by helper function
            $settingKeys = Settings::pluck('key');
            foreach ($settingKeys as $key) {
                Cache::forget("setting_{$key}");
            }
            
            // Clear grouped cache
            Cache::forget('all_settings');
            
            $groups = Settings::distinct()->pluck('group');
            foreach ($groups as $group) {
                Cache::forget("settings_group_{$group}");
            }
            
            // Also clear any other cache that might be related
            Cache::forget('settings');
            Cache::forget('site_settings');
            
            \Log::info("Settings cache cleared successfully");
        } catch (\Exception $e) {
            \Log::error("Error clearing settings cache: " . $e->getMessage());
        }
    }
}
