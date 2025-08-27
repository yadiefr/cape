<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class SetupHostingStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:setup-hosting {--force : Force the operation to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup storage for hosting environments (alternative to storage:link)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $publicStorage = public_path('storage');
        
        // Check if storage link/directory already exists
        if ((is_link($publicStorage) || is_dir($publicStorage)) && !$this->option('force')) {
            $this->info('Storage already linked or directory exists.');
            return;
        }

        // Try to create symbolic link first
        if ($this->createSymbolicLink()) {
            $this->info('Storage symbolic link created successfully.');
            return;
        }

        // If symlink fails, create directory and copy files
        if ($this->copyStorageFiles()) {
            $this->info('Storage directory created and files copied successfully.');
            $this->warn('Note: Files are copied, not linked. You may need to re-run this command after uploading new files.');
        } else {
            $this->error('Failed to setup storage.');
        }
    }

    /**
     * Try to create symbolic link
     */
    private function createSymbolicLink()
    {
        try {
            $target = storage_path('app/public');
            $link = public_path('storage');

            if (is_link($link)) {
                unlink($link);
            }

            return symlink($target, $link);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Copy storage files to public directory
     */
    private function copyStorageFiles()
    {
        try {
            $source = storage_path('app/public');
            $destination = public_path('storage');

            // Remove existing directory if exists
            if (is_dir($destination)) {
                File::deleteDirectory($destination);
            }

            // Create destination directory
            if (!File::makeDirectory($destination, 0755, true)) {
                return false;
            }

            // Copy files recursively
            if (is_dir($source)) {
                $this->recursiveCopy($source, $destination);
            }

            return true;
        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Recursively copy files
     */
    private function recursiveCopy($source, $destination)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            
            if ($item->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                copy($item, $destPath);
            }
        }
    }
}
