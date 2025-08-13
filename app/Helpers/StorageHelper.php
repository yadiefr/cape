<?php

if (!function_exists('storage_asset')) {
    /**
     * Generate an asset path for storage files with proper fallback
     */
    function storage_asset($path)
    {
        if (!$path) {
            return null;
        }

        // Remove leading slash if exists
        $path = ltrim($path, '/');
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $path);
        if (!file_exists($storagePath)) {
            return null;
        }
        
        $publicStoragePath = public_path('storage');
        
        // Check if symlink exists and works
        if (is_link($publicStoragePath) || is_dir($publicStoragePath)) {
            $publicFilePath = public_path('storage/' . $path);
            if (file_exists($publicFilePath) && is_readable($publicFilePath)) {
                return asset('storage/' . $path);
            }
        }
        
        // Fallback to storage.php
        return url('storage.php?file=' . $path);
    }
}

if (!function_exists('check_storage_link')) {
    /**
     * Check if storage link exists and is functional
     */
    function check_storage_link()
    {
        $publicStoragePath = public_path('storage');
        
        if (!is_link($publicStoragePath) && !is_dir($publicStoragePath)) {
            return false;
        }
        
        // Test with a known file if exists
        $testFiles = [
            'settings/logo_sekolah_1752032684_686de5ac08411.png',
            'siswa/foto/PhUqdwCpwelU6kgESLxIgWkUxB2K69V8mjQXJTaj.jpg',
        ];
        
        foreach ($testFiles as $testFile) {
            $storagePath = storage_path('app/public/' . $testFile);
            $publicPath = public_path('storage/' . $testFile);
            
            if (file_exists($storagePath) && file_exists($publicPath) && is_readable($publicPath)) {
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('debug_storage_info')) {
    /**
     * Get debug information about storage configuration
     */
    function debug_storage_info()
    {
        return [
            'storage_path' => storage_path('app/public'),
            'public_storage_path' => public_path('storage'),
            'storage_link_exists' => is_link(public_path('storage')),
            'storage_dir_exists' => is_dir(public_path('storage')),
            'storage_link_functional' => check_storage_link(),
            'app_url' => config('app.url'),
            'filesystem_disk' => config('filesystems.default'),
            'public_disk_url' => config('filesystems.disks.public.url'),
        ];
    }
}
