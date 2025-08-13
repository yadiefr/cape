<?php
// Temporary debug routes - remove after fixing the issue

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Debug storage route
Route::get('/debug-storage', function (Request $request) {
    if (!app()->environment('production')) {
        return response()->json(['error' => 'Only available in production'], 403);
    }

    $info = [
        'storage_path' => storage_path('app/public'),
        'public_storage_path' => public_path('storage'),
        'storage_link_exists' => is_link(public_path('storage')),
        'storage_dir_exists' => is_dir(public_path('storage')),
        'app_url' => config('app.url'),
        'filesystem_disk' => config('filesystems.default'),
        'public_disk_url' => config('filesystems.disks.public.url'),
        'server_info' => [
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
        ],
    ];

    // Check some sample files
    $sampleFiles = [
        'siswa/foto/PhUqdwCpwelU6kgESLxIgWkUxB2K69V8mjQXJTaj.jpg',
        'guru/4lEJdEfdtB3kSqhJdE2eFdC5JYHTI5truFYdmmuz.jpg',
        'settings/logo_sekolah_1752032684_686de5ac08411.png',
    ];

    foreach ($sampleFiles as $file) {
        $storagePath = storage_path('app/public/' . $file);
        $publicPath = public_path('storage/' . $file);
        
        $info['files'][$file] = [
            'storage_exists' => file_exists($storagePath),
            'public_exists' => file_exists($publicPath),
            'storage_readable' => file_exists($storagePath) && is_readable($storagePath),
            'public_readable' => file_exists($publicPath) && is_readable($publicPath),
            'storage_permissions' => file_exists($storagePath) ? substr(sprintf('%o', fileperms($storagePath)), -4) : 'N/A',
            'public_permissions' => file_exists($publicPath) ? substr(sprintf('%o', fileperms($publicPath)), -4) : 'N/A',
            'storage_size' => file_exists($storagePath) ? filesize($storagePath) : 0,
            'asset_url' => asset('storage/' . $file),
            'storage_asset_url' => function_exists('storage_asset') ? storage_asset($file) : 'helper not loaded',
            'direct_storage_url' => url('storage.php?file=' . $file),
        ];
    }

    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});

// Test a specific file
Route::get('/test-file/{path}', function ($path) {
    if (!app()->environment('production')) {
        return response()->json(['error' => 'Only available in production'], 403);
    }

    $storagePath = storage_path('app/public/' . $path);
    
    if (!file_exists($storagePath)) {
        return response()->json(['error' => 'File not found in storage', 'path' => $storagePath], 404);
    }

    if (!is_readable($storagePath)) {
        return response()->json(['error' => 'File not readable', 'permissions' => substr(sprintf('%o', fileperms($storagePath)), -4)], 403);
    }

    $fileInfo = [
        'path' => $storagePath,
        'exists' => file_exists($storagePath),
        'readable' => is_readable($storagePath),
        'size' => filesize($storagePath),
        'permissions' => substr(sprintf('%o', fileperms($storagePath)), -4),
        'mime_type' => mime_content_type($storagePath),
        'urls' => [
            'asset' => asset('storage/' . $path),
            'storage_php' => url('storage.php?file=' . $path),
            'direct' => url('storage/' . $path),
        ]
    ];

    return response()->json($fileInfo, 200, [], JSON_PRETTY_PRINT);
})->where('path', '.*');
