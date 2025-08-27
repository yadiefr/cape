<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            try {
                // Get commonly used settings and share with all views
                $commonSettings = [
                    'site_title' => setting('site_title', 'SMK PGRI CIKAMPEK'),
                    'nama_sekolah' => setting('nama_sekolah', 'SMK PGRI CIKAMPEK'),
                    'site_description' => setting('site_description', 'Pendidikan Berkualitas untuk Masa Depan'),
                    'timezone' => setting('timezone', 'Asia/Jakarta'),
                ];
                
                $view->with('globalSettings', $commonSettings);
            } catch (\Exception $e) {
                // Fallback if database is not ready
                $view->with('globalSettings', [
                    'site_title' => 'SMK PGRI CIKAMPEK',
                    'nama_sekolah' => 'SMK PGRI CIKAMPEK',
                    'site_description' => 'Pendidikan Berkualitas untuk Masa Depan',
                    'timezone' => 'Asia/Jakarta',
                ]);
            }
        });
    }
}
