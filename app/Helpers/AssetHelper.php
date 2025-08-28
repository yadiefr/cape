<?php

use App\Helpers\HostingStorageHelper;

if (!function_exists('asset_url')) {
    /**
     * Generate an asset path for files that could be in 'storage' (local) 
     * or 'uploads' (hosting).
     *
     * @param string|null $path
     * @param string $directory
     * @return string
     */
    function asset_url(?string $path, string $directory = 'galeri'): string
    {
        if (empty($path)) {
            // Return a placeholder or default image URL if path is empty
            return asset('images/no-image.png'); 
        }

        // Determine the base directory based on the environment
        // On local, use 'storage' which symlinks to 'storage/app/public'.
        // On hosting, public files are in 'uploads'.
        $baseDir = config('app.env') === 'local' ? 'storage' : 'uploads';

        // Clean up the path to only have the filename
        $filename = basename($path);

        // Construct the final path
        $fullPath = "{$baseDir}/{$directory}/{$filename}";

        return asset($fullPath);
    }
}
