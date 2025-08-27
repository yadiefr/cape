<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings;
use Illuminate\Support\Facades\Storage;

class SyncStorageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync {--force : Force sync all files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync storage files to public folder for hosting environments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting storage files synchronization...');

        // First, fix any wrong storage paths
        $this->info('Checking for files in wrong storage locations...');
        $fixResults = fix_storage_paths();
        
        if ($fixResults['moved'] > 0) {
            $this->info("Fixed {$fixResults['moved']} files from wrong storage paths");
        }
        
        if ($fixResults['errors'] > 0) {
            $this->warn("Encountered {$fixResults['errors']} errors while fixing paths");
        }

        // Detect hosting environment
        $isHostingEnvironment = $this->detectHostingEnvironment();
        
        if ($isHostingEnvironment) {
            $this->info('Hosting environment detected - using hosting paths');
            return $this->handleHostingSync();
        } else {
            $this->info('Local/development environment detected');
            return $this->handleLocalSync();
        }
    }

    /**
     * Detect if we're in a hosting environment
     */
    protected function detectHostingEnvironment()
    {
        $basePath = base_path();
        
        // Check if we're in a hosting environment by looking for common patterns
        if (strpos($basePath, '/home/') === 0 && strpos($basePath, 'public_html') === false) {
            // We're in /home/user/project_name but not in public_html
            $publicHtmlPath = dirname($basePath) . '/public_html';
            return file_exists($publicHtmlPath);
        }
        
        return false;
    }

    /**
     * Handle synchronization for hosting environments
     */
    protected function handleHostingSync()
    {
        $basePath = base_path();
        $publicHtmlPath = dirname($basePath) . '/public_html';
        
        $this->info("Base path: {$basePath}");
        $this->info("Public HTML path: {$publicHtmlPath}");
        
        // Get all image settings
        $imageSettings = Settings::where('type', 'image')->get();

        if ($imageSettings->isEmpty()) {
            $this->warn('No image settings found.');
            return 0;
        }

        $syncedCount = 0;
        $errorCount = 0;

        // Ensure hosting storage directory exists
        $hostingStorageDir = $publicHtmlPath . '/storage';
        if (!is_dir($hostingStorageDir)) {
            mkdir($hostingStorageDir, 0755, true);
            $this->info('Created hosting storage directory');
        }

        // Ensure settings subdirectory exists
        $hostingSettingsDir = $hostingStorageDir . '/settings';
        if (!is_dir($hostingSettingsDir)) {
            mkdir($hostingSettingsDir, 0755, true);
            $this->info('Created hosting storage/settings directory');
        }

        foreach ($imageSettings as $setting) {
            if (!$setting->value) {
                continue;
            }

            $sourcePath = storage_path('app/public/' . $setting->value);
            $targetPath = $hostingStorageDir . '/' . $setting->value;

            // Check if source file exists
            if (!file_exists($sourcePath)) {
                $this->warn("Source file not found for {$setting->key}: {$sourcePath}");
                continue;
            }

            // Check if target exists and is newer (unless force flag is used)
            if (!$this->option('force') && file_exists($targetPath)) {
                if (filemtime($targetPath) >= filemtime($sourcePath)) {
                    $this->line("✓ {$setting->key} - target is up to date");
                    $syncedCount++;
                    continue;
                }
            }

            // Ensure target directory exists
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Copy the file
            try {
                if (copy($sourcePath, $targetPath)) {
                    @chmod($targetPath, 0644);
                    $syncedCount++;
                    $this->info("Synced {$setting->key} to hosting: {$setting->value}");
                } else {
                    $errorCount++;
                    $this->error("Failed to copy {$setting->key}: {$setting->value}");
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error syncing {$setting->key}: " . $e->getMessage());
            }
        }

        $this->info("Hosting synchronization complete!");
        $this->info("Files synced to hosting: {$syncedCount}");
        
        if ($errorCount > 0) {
            $this->error("Errors encountered: {$errorCount}");
            return 1;
        }

        return 0;
    }

    /**
     * Handle synchronization for local/development environments
     */
    protected function handleLocalSync()
    {
        // Get all image settings
        $imageSettings = Settings::where('type', 'image')->get();

        if ($imageSettings->isEmpty()) {
            $this->warn('No image settings found.');
            return 0;
        }

        $syncedCount = 0;
        $errorCount = 0;

        // Ensure public/storage directory exists
        $publicStorageDir = public_path('storage');
        if (!is_dir($publicStorageDir)) {
            mkdir($publicStorageDir, 0755, true);
            $this->info('Created public/storage directory');
        }

        // Ensure settings subdirectory exists
        $publicSettingsDir = public_path('storage/settings');
        if (!is_dir($publicSettingsDir)) {
            mkdir($publicSettingsDir, 0755, true);
            $this->info('Created public/storage/settings directory');
        }

        foreach ($imageSettings as $setting) {
            if (!$setting->value) {
                continue;
            }

            $sourcePath = storage_path('app/public/' . $setting->value);
            $targetPath = public_path('storage/' . $setting->value);

            // Check if source file exists
            if (!file_exists($sourcePath)) {
                $this->warn("Source file not found for {$setting->key}: {$sourcePath}");
                continue;
            }

            // Check if target exists and is newer (unless force flag is used)
            if (!$this->option('force') && file_exists($targetPath)) {
                if (filemtime($targetPath) >= filemtime($sourcePath)) {
                    $this->line("✓ {$setting->key} - target is up to date");
                    $syncedCount++;
                    continue;
                }
            } elseif (file_exists($targetPath)) {
                $this->line("✓ {$setting->key} - target file exists (use --force to overwrite)");
                $syncedCount++;
                continue;
            }

            // Ensure target directory exists
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Copy the file
            try {
                if (copy($sourcePath, $targetPath)) {
                    @chmod($targetPath, 0644);
                    $syncedCount++;
                    $this->info("Synced {$setting->key}: {$setting->value}");
                } else {
                    $errorCount++;
                    $this->error("Failed to copy {$setting->key}: {$setting->value}");
                }
            } catch (\Exception $e) {
                $errorCount++;
                $this->error("Error syncing {$setting->key}: " . $e->getMessage());
            }
        }

        // Also create/fix storage symbolic link for local environments
        $this->info('Checking storage symbolic link...');
        
        $linkPath = public_path('storage');
        $targetPath = storage_path('app/public');

        if (is_link($linkPath)) {
            $this->info('Storage link exists');
        } elseif (PHP_OS_FAMILY === 'Windows') {
            // On Windows, just ensure directory exists and copy files
            if (!is_dir($linkPath)) {
                @mkdir($linkPath, 0755, true);
            }
            $this->info('Windows detected - using directory copy instead of symlink');
        } else {
            // Try to create symbolic link on Unix systems
            if (function_exists('symlink')) {
                try {
                    if (@symlink($targetPath, $linkPath)) {
                        $this->info('Created storage symbolic link');
                    } else {
                        $this->warn('Failed to create symbolic link - files will be copied instead');
                    }
                } catch (\Exception $e) {
                    $this->warn('Symbolic link creation failed: ' . $e->getMessage());
                }
            } else {
                $this->warn('Symbolic links not supported on this system - files will be copied instead');
            }
        }

        $this->info("Local synchronization complete!");
        $this->info("Files synced: {$syncedCount}");
        
        if ($errorCount > 0) {
            $this->error("Errors encountered: {$errorCount}");
            return 1;
        }

        return 0;
    }
}
