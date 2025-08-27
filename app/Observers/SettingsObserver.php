<?php

namespace App\Observers;

use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class SettingsObserver
{
    /**
     * Handle the Settings "created" event.
     */
    public function created(Settings $setting): void
    {
        $this->clearSettingCache($setting->key);
    }

    /**
     * Handle the Settings "updated" event.
     */
    public function updated(Settings $setting): void
    {
        $this->clearSettingCache($setting->key);
    }

    /**
     * Handle the Settings "deleted" event.
     */
    public function deleted(Settings $setting): void
    {
        $this->clearSettingCache($setting->key);
    }

    /**
     * Clear specific setting cache
     */
    private function clearSettingCache(string $key): void
    {
        // Clear specific setting cache
        Cache::forget("setting_{$key}");
        
        // Clear general caches that might include this setting
        Cache::forget('all_settings');
        
        // Clear group cache if we know the group
        $setting = Settings::where('key', $key)->first();
        if ($setting && $setting->group) {
            Cache::forget("settings_group_{$setting->group}");
        }
        
        // Log the cache clearing
        \Log::info("Settings cache cleared for key: {$key}");
    }
}
