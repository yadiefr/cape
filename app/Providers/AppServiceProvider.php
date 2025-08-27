<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Settings;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\SettingsJadwal;
use App\Observers\SettingsObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register MidtransService
        $this->app->singleton(\App\Services\MidtransServiceNew::class, function ($app) {
            return new \App\Services\MidtransServiceNew();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Handle CloudFlare tunnels and proxies
        if (request()->header('CF-Visitor') || request()->header('CF-Connecting-IP')) {
            \URL::forceScheme('https');
        }

        // Force HTTPS if environment variable is set
        if (env('FORCE_HTTPS', false)) {
            \URL::forceScheme('https');
        }

        // Set trusted proxies globally for CloudFlare if needed
        if (env('TRUSTED_PROXIES', false)) {
            // Trust all proxies for CloudFlare tunnel - simpler approach
            app('request')->setTrustedProxies(['*'], -1); // -1 means trust all headers
        }

        // Set asset URL for external domains (like cloudflared tunnel)
        if (env('ASSET_URL')) {
            \URL::forceRootUrl(env('ASSET_URL'));
        }

        Schema::defaultStringLength(191);
        
        // Register Settings Observer for automatic cache clearing
        Settings::observe(SettingsObserver::class);
        
        Relation::morphMap([
            'admin' => 'App\Models\Admin',
            'guru' => 'App\Models\Guru',
            'siswa' => 'App\Models\Siswa',
            // tambahkan tipe author lain jika ada
        ]);

        // Share settings with all views if the settings table exists
        try {
            if (Schema::hasTable('settings')) {
                View::composer('*', function ($view) {
                    $view->with('settings', Settings::pluck('value', 'key')->toArray());
                });
            }
        } catch (\Exception $e) {
            // If the table doesn't exist yet (during migration), just continue
        }

        View::composer('admin.settings.jadwal.index', function ($view) {
            $jadwalSettings = SettingsJadwal::orderBy('hari')->get();
            $view->with('jadwalSettings', $jadwalSettings);
        });

        // Ensure siswa guard user always has kelas and jurusan loaded
        View::composer(['siswa.*', 'layouts.siswa'], function ($view) {
            if (auth()->guard('siswa')->check()) {
                $siswa = auth()->guard('siswa')->user();
                if ($siswa && !$siswa->relationLoaded('kelas')) {
                    $siswa->load(['kelas', 'jurusan']);
                }
            }
        });
    }
}
