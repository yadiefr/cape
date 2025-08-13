<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{    public function index()
    {
        $settingsCollection = Setting::orderBy('group')
            ->orderBy('key')
            ->get();

        // Create a flat array for the form
        $settings = [];
        foreach ($settingsCollection as $setting) {
            $settings[$setting->key] = $setting->value;
        }

        // Group settings by group for display organization
        $groupedSettings = $settingsCollection->groupBy('group');

        return view('admin.settings.index', compact('groupedSettings', 'settings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string',
            'key' => 'required|string|unique:settings,key',
            'value' => 'nullable',
            'description' => 'nullable|string',
            'type' => 'required|string|in:string,textarea,boolean,date,image',
            'is_public' => 'boolean'
        ]);

        // Convert key to snake_case
        $validated['key'] = Str::snake($validated['key']);

        // Handle initial value based on type
        if ($validated['type'] === 'boolean') {
            $validated['value'] = isset($validated['value']) ? true : false;
        }

        Setting::create($validated);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil ditambahkan');
    }    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'nullable',
            'settings.*.group' => 'required|string',
        ]);

        foreach ($validated['settings'] as $settingKey => $settingData) {
            $key = $settingData['key'];
            $value = $settingData['value'] ?? null;
            $group = $settingData['group'];
            
            // Get existing setting to check type
            $existingSetting = Setting::where('key', $key)->first();
            $type = $existingSetting ? $existingSetting->type : 'string';
            
            // Handle file uploads for image type
            if ($type === 'image' && $request->hasFile("settings.{$settingKey}.value")) {
                $file = $request->file("settings.{$settingKey}.value");
                
                // Delete old file if exists
                if ($existingSetting && $existingSetting->value) {
                    Storage::delete('public/' . $existingSetting->value);
                }
                
                // Store new file
                $value = $file->store('settings', 'public');
            } elseif ($type === 'image' && empty($value)) {
                // If no new file uploaded for image type, keep existing value
                $value = $existingSetting ? $existingSetting->value : null;
            }
            
            // Handle boolean values
            if ($type === 'boolean') {
                $value = isset($settingData['value']) && $settingData['value'] ? true : false;
            }            // Only update if we have a value or it's a boolean/image type
            if ($value !== null || $type === 'boolean' || $type === 'image') {
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value,
                        'group' => $group,
                        'type' => $type,
                        'is_public' => true,
                    ]
                );
            }        }

        // Clear settings cache after update
        clear_settings_cache();

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui');
    }
} 