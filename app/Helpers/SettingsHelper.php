<?php

use App\Models\Setting;
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
            $setting = Setting::where('key', $key)->first();
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
            return Setting::pluck('value', 'key')->toArray();
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
            return Setting::where('group', $group)->pluck('value', 'key')->toArray();
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
        $keys = Setting::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
        Cache::forget('all_settings');
        
        $groups = Setting::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
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