<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

if (!function_exists('setting')) {
    /**
     * Get setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            $setting = Settings::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }
}

if (!function_exists('settings')) {
    /**
     * Get all settings as array
     *
     * @return array
     */
    function settings()
    {
        return Cache::remember('all_settings', 3600, function () {
            return Settings::pluck('value', 'key')->toArray();
        });
    }
}

if (!function_exists('setting_group')) {
    /**
     * Get settings by group
     *
     * @param string $group
     * @return array
     */
    function setting_group($group)
    {
        return Cache::remember("settings_group_{$group}", 3600, function () use ($group) {
            return Settings::where('group', $group)->pluck('value', 'key')->toArray();
        });
    }
}

if (!function_exists('clear_settings_cache')) {
    /**
     * Clear settings cache
     *
     * @return void
     */
    function clear_settings_cache()
    {
        $keys = Settings::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
        Cache::forget('all_settings');
        
        $groups = Settings::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
    }
}

if (!function_exists('setting_with_cache_bust')) {
    /**
     * Get setting value with cache busting for images
     *
     * @param string $key
     * @param mixed $default
     * @param bool $asset_url If true, will prepend asset('storage/') and add cache bust
     * @return mixed
     */
    function setting_with_cache_bust($key, $default = null, $asset_url = false)
    {
        $value = setting($key, $default);
        
        if ($asset_url && $value) {
            // Handle hosting environment where storage paths differ
            $storage_file_path = storage_path('app/public/' . $value);
            $public_file_path = public_path('storage/' . $value);
            
            $cache_bust = '';
            
            // Try to get file modification time for cache busting
            if (file_exists($public_file_path)) {
                $cache_bust = '?v=' . filemtime($public_file_path);
            } elseif (file_exists($storage_file_path)) {
                $cache_bust = '?v=' . filemtime($storage_file_path);
                
                // If file exists in storage but not in public, try to copy it
                if (!file_exists($public_file_path)) {
                    $public_dir = dirname($public_file_path);
                    if (!is_dir($public_dir)) {
                        @mkdir($public_dir, 0755, true);
                    }
                    @copy($storage_file_path, $public_file_path);
                }
            } else {
                // Fallback: use current timestamp
                $cache_bust = '?v=' . time();
            }
            
            return asset('storage/' . $value) . $cache_bust;
        }
        
        return $value;
    }
}

if (!function_exists('sync_storage_file_to_public')) {
    /**
     * Sync file from storage to public folder (for hosting environments)
     *
     * @param string $relative_path Relative path from storage/app/public/
     * @return bool
     */
    function sync_storage_file_to_public($relative_path)
    {
        $storage_path = storage_path('app/public/' . $relative_path);
        $public_path = public_path('storage/' . $relative_path);
        
        // Ensure source file exists
        if (!file_exists($storage_path)) {
            return false;
        }
        
        // Create directory if it doesn't exist
        $public_dir = dirname($public_path);
        if (!is_dir($public_dir)) {
            @mkdir($public_dir, 0755, true);
        }
        
        // Copy file
        return @copy($storage_path, $public_path);
    }
}

if (!function_exists('ensure_storage_link_exists')) {
    /**
     * Ensure the public storage symbolic link exists
     *
     * @return bool
     */
    function ensure_storage_link_exists()
    {
        $target = public_path('storage');
        
        if (file_exists($target)) {
            return true;
        }
        
        try {
            if (PHP_OS_FAMILY === 'Windows') {
                // Windows needs admin privileges for symlinks, use directory junction as alternative
                exec(sprintf('mklink /J %s %s', escapeshellarg($target), escapeshellarg(storage_path('app/public'))));
            } else {
                // On Unix systems, create a symbolic link
                symlink(storage_path('app/public'), $target);
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Failed to create storage link: ' . $e->getMessage());
            
            // Fallback: If symlink fails, try to create directory and copy files for current request
            if (!file_exists($target)) {
                @mkdir($target, 0755, true);
            }
            
            return false;
        }
    }
}