<?php

use App\Helpers\HostingStorageHelper;

if (!function_exists('asset_url')) {
    /**
     * Generate an asset path for files that could be in 'storage' (local) 
     * or 'uploads' (hosting).
     *
     * @param string|null $path
     * @return string
     */
    function asset_url(?string $path): string
    {
        if (empty($path)) {
            // Return a placeholder or default image URL if path is empty
            return asset('images/no-image.png'); 
        }

        // Determine the base directory based on the environment
        $baseDir = HostingStorageHelper::isHostingEnvironment() ? 'uploads' : 'storage';

        return asset($baseDir . '/' . $path);
    }
}
