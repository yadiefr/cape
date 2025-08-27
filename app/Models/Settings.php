<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description'
    ];

    /**
     * Get a setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if ($setting) {
            return $setting->type == 'json' ? json_decode($setting->value) : $setting->value;
        }
        return $default;
    }

    /**
     * Set a setting value
     *
     * @param string $key
     * @param mixed $value
     * @param string $group
     * @param string $type
     * @param string $description
     * @return bool
     */
    public static function setValue(string $key, $value, string $group = 'general', string $type = 'string', string $description = '')
    {
        // Format value for storage based on type
        if ($type == 'json' && !is_string($value)) {
            $value = json_encode($value);
        }

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
                'description' => $description
            ]
        );

        // Clear cache after setting value
        self::clearCacheForKey($key);
        
        return $setting;
    }
    
    /**
     * Clear cache for specific setting key
     */
    public static function clearCacheForKey(string $key): void
    {
        Cache::forget("setting_{$key}");
        Cache::forget('all_settings');
        
        // Clear group cache
        $setting = self::where('key', $key)->first();
        if ($setting && $setting->group) {
            Cache::forget("settings_group_{$setting->group}");
        }
    }
    
    /**
     * Clear all settings cache
     */
    public static function clearAllCache(): void
    {
        $keys = self::pluck('key');
        foreach ($keys as $key) {
            Cache::forget("setting_{$key}");
        }
        
        Cache::forget('all_settings');
        
        $groups = self::distinct()->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("settings_group_{$group}");
        }
    }
}
