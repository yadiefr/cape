<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API untuk mendapatkan daftar siswa dalam kelas
Route::get('/kelas/{id}/siswa', function($id) {
    return \App\Models\Siswa::where('kelas_id', $id)
        // Sementara hapus filter status untuk debugging
        // ->where('status', 'aktif')
        ->orderBy('nama_lengkap')
        ->get(['id', 'nis', 'nama_lengkap as nama', 'status']);
});

// API untuk mendapatkan kelas berdasarkan tahun masuk
Route::get('/tahun-masuk/{year}/kelas', function($year) {
    // Get kelas that actually have students from the specified year
    $kelasIds = \App\Models\Siswa::where('tahun_masuk', $year)
        ->where('status', 'aktif')
        ->distinct()
        ->pluck('kelas_id');

    $kelas = \App\Models\Kelas::whereIn('id', $kelasIds)
        ->where('is_active', true)
        ->with('jurusan')
        ->orderBy('nama_kelas')
        ->get(['id', 'nama_kelas', 'tingkat', 'jurusan_id']);

    // Debug logging
    \Log::info("API tahun-masuk kelas called", [
        'year' => $year,
        'found_kelas_ids' => $kelasIds->toArray(),
        'kelas_count' => $kelas->count(),
        'kelas' => $kelas->toArray()
    ]);

    return $kelas;
});

// API untuk mendapatkan siswa berdasarkan tahun masuk
Route::get('/tahun-masuk/{year}/siswa', function($year) {
    return \App\Models\Siswa::where('tahun_masuk', $year)
        ->where('status', 'aktif')
        ->with('kelas')
        ->orderBy('nama_lengkap')
        ->get(['id', 'nis', 'nama_lengkap as nama', 'kelas_id']);
});

// API untuk mendapatkan siswa berdasarkan tahun masuk dan kelas
Route::get('/tahun-masuk/{year}/kelas/{kelasId}/siswa', function($year, $kelasId) {
    return \App\Models\Siswa::where('tahun_masuk', $year)
        ->where('kelas_id', $kelasId)
        ->where('status', 'aktif')
        ->orderBy('nama_lengkap')
        ->get(['id', 'nis', 'nama_lengkap as nama']);
});

// Debug API untuk melihat data yang tersedia
Route::get('/debug/tahun-kelas', function() {
    $data = [
        'available_years' => \App\Models\Siswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk'),
        'kelas_with_tingkat' => \App\Models\Kelas::select('id', 'nama_kelas', 'tingkat', 'is_active')
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get(),
        'siswa_by_year' => \App\Models\Siswa::select('tahun_masuk', 'kelas_id', \DB::raw('count(*) as count'))
            ->where('status', 'aktif')
            ->groupBy('tahun_masuk', 'kelas_id')
            ->with('kelas:id,nama_kelas,tingkat')
            ->get()
    ];

    return response()->json($data);
});

// API untuk cek bel yang harus dibunyikan saat ini
// Endpoint bel - tanpa middleware untuk memastikan akses mudah
Route::get('/bel/check-current-time', 'App\Http\Controllers\BelApiController@checkCurrentTime');

    // Debug routes untuk galeri
    Route::get('/debug/galeri/{id}', [App\Http\Controllers\Admin\GaleriFotoController::class, 'debugGaleriFile']);
    Route::get('/check/galeri-files', [App\Http\Controllers\Admin\GaleriFotoController::class, 'checkGaleriFiles']);
    Route::post('/sync/galeri-legacy-files', [App\Http\Controllers\Admin\GaleriFotoController::class, 'syncLegacyFiles']);
    Route::get('/debug/hosting-env', [App\Http\Controllers\Admin\GaleriFotoController::class, 'debugHostingEnv']);
    Route::post('/test/galeri-upload', [App\Http\Controllers\Admin\GaleriFotoController::class, 'testGaleriUpload']);
    
    // Debug routes untuk berita
    Route::post('/test/berita-upload', [App\Http\Controllers\Admin\BeritaController::class, 'testBeritaUpload']);

// API untuk mendapatkan foto-foto dalam galeri
Route::get('/galeri/{id}/photos', function($id) {
    $galeri = \App\Models\Galeri::findOrFail($id);
    $photos = \App\Models\GaleriFoto::where('galeri_id', $id)
        ->orderBy('is_thumbnail', 'desc')
        ->orderBy('id', 'asc')
        ->get(['foto', 'is_thumbnail']);
    
    // Tambahkan URL foto untuk setiap item
    $photos = $photos->map(function($photo) {
        return [
            'foto' => $photo->foto,
            'is_thumbnail' => $photo->is_thumbnail,
            'foto_url' => $photo->foto_url,
            'debug_info' => [
                'storage_exists' => \Illuminate\Support\Facades\Storage::disk('public')->exists($photo->foto),
                'public_storage_exists' => file_exists(public_path('storage/' . $photo->foto)),
                'legacy_exists' => file_exists(public_path('uploads/galeri/' . $photo->foto)),
                'is_hosting' => \App\Helpers\HostingStorageHelper::isHostingEnvironment(),
                'environment' => app()->environment(),
            ]
        ];
    });
    
    return response()->json($photos);
});

// API untuk aksi bel sekolah (AJAX)
Route::prefix('bel')->middleware(['web'])->group(function () {
    Route::put('{id}/toggle-aktif', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxToggleAktif']);
    Route::post('{id}/bunyikan', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxBunyikanBel']);
    Route::delete('{id}', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxDestroy']);
});

// API for SPA Assign Subjects
Route::prefix('admin')->middleware(['web', 'auth'])->group(function () {
    Route::get('guru/{guru}/assign-subjects-data', [App\Http\Controllers\Admin\GuruController::class, 'getAssignSubjectsData'])->name('api.guru.assign-subjects-data');
    Route::post('guru/{guru}/store-subject-assignment', [App\Http\Controllers\Admin\GuruController::class, 'storeSubjectAssignment'])->name('api.guru.store-subject-assignment');
    Route::delete('guru/{guru}/remove-subject-assignment/{jadwal}', [App\Http\Controllers\Admin\GuruController::class, 'removeSubjectAssignment'])->name('api.guru.remove-subject-assignment');
});

// Sync galeri files dari direktori lama ke direktori hosting yang benar
Route::post('/sync/galeri-legacy-files', function() {
    if (!\App\Helpers\HostingStorageHelper::isHostingEnvironment()) {
        return response()->json(['message' => 'Not in hosting environment']);
    }
    
    $paths = \App\Helpers\HostingStorageHelper::getHostingPaths();
    $photos = \App\Models\GaleriFoto::all();
    $results = [];
    
    foreach ($photos as $photo) {
        if ($photo->foto) {
            // Cek apakah file ada di direktori lama (cape/public/uploads/galeri)
            $legacyPath = $paths['current_laravel'] . '/public/uploads/galeri/' . $photo->foto;
            $targetPath = $paths['public_storage'] . '/galeri/' . $photo->foto;
            
            if (file_exists($legacyPath)) {
                // Pastikan direktori target ada
                $targetDir = dirname($targetPath);
                if (!is_dir($targetDir)) {
                    \Illuminate\Support\Facades\File::makeDirectory($targetDir, 0755, true);
                    @chmod($targetDir, 0755);
                }
                
                // Copy file dari direktori lama ke direktori hosting yang benar
                if (copy($legacyPath, $targetPath)) {
                    @chmod($targetPath, 0644);
                    $results[] = [
                        'id' => $photo->id,
                        'foto' => $photo->foto,
                        'action' => 'copied',
                        'from' => $legacyPath,
                        'to' => $targetPath,
                        'success' => true,
                        'foto_url' => $photo->foto_url
                    ];
                } else {
                    $results[] = [
                        'id' => $photo->id,
                        'foto' => $photo->foto,
                        'action' => 'copy_failed',
                        'from' => $legacyPath,
                        'to' => $targetPath,
                        'success' => false
                    ];
                }
            } else {
                $results[] = [
                    'id' => $photo->id,
                    'foto' => $photo->foto,
                    'action' => 'not_found_in_legacy',
                    'legacy_path' => $legacyPath,
                    'success' => false
                ];
            }
        }
    }
    
    return response()->json([
        'total_files' => count($results),
        'successful' => count(array_filter($results, fn($r) => $r['success'])),
        'results' => $results
    ]);
});

// Check galeri file status
Route::get('/check/galeri-files', function() {
    $photos = \App\Models\GaleriFoto::all();
    $results = [];
    
    if (\App\Helpers\HostingStorageHelper::isHostingEnvironment()) {
        $paths = \App\Helpers\HostingStorageHelper::getHostingPaths();
        
        foreach ($photos as $photo) {
            if ($photo->foto) {
                $results[] = [
                    'id' => $photo->id,
                    'foto' => $photo->foto,
                    'foto_url' => $photo->foto_url,
                    'paths' => [
                        'laravel_storage' => $paths['current_storage'] . '/' . $photo->foto,
                        'public_storage' => $paths['public_storage'] . '/galeri/' . $photo->foto,
                        'legacy_laravel' => $paths['current_laravel'] . '/public/uploads/galeri/' . $photo->foto,
                        'legacy_public' => $paths['public_html'] . '/uploads/galeri/' . $photo->foto,
                    ],
                    'file_exists' => [
                        'laravel_storage' => file_exists($paths['current_storage'] . '/' . $photo->foto),
                        'public_storage' => file_exists($paths['public_storage'] . '/galeri/' . $photo->foto),
                        'legacy_laravel' => file_exists($paths['current_laravel'] . '/public/uploads/galeri/' . $photo->foto),
                        'legacy_public' => file_exists($paths['public_html'] . '/uploads/galeri/' . $photo->foto),
                    ]
                ];
            }
        }
    } else {
        foreach ($photos as $photo) {
            if ($photo->foto) {
                $results[] = [
                    'id' => $photo->id,
                    'foto' => $photo->foto,
                    'foto_url' => $photo->foto_url,
                    'paths' => [
                        'storage' => storage_path('app/public/' . $photo->foto),
                        'public_storage' => public_path('storage/' . $photo->foto),
                        'legacy' => public_path('uploads/galeri/' . $photo->foto),
                    ],
                    'file_exists' => [
                        'storage' => file_exists(storage_path('app/public/' . $photo->foto)),
                        'public_storage' => file_exists(public_path('storage/' . $photo->foto)),
                        'legacy' => file_exists(public_path('uploads/galeri/' . $photo->foto)),
                    ]
                ];
            }
        }
    }
    
    return response()->json([
        'is_hosting' => \App\Helpers\HostingStorageHelper::isHostingEnvironment(),
        'total_files' => count($results),
        'files' => $results
    ]);
});

// Test upload galeri untuk debugging
Route::post('/test/galeri-upload', function(\Illuminate\Http\Request $request) {
    $request->validate([
        'test_file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    $file = $request->file('test_file');
    $result = \App\Helpers\HostingStorageHelper::uploadFile($file, 'galeri');
    
    return response()->json([
        'success' => $result !== null,
        'path' => $result,
        'hosting_status' => \App\Helpers\HostingStorageHelper::getHostingStatus(),
        'file_url' => $result ? \App\Models\GaleriFoto::where('foto', $result)->first()->foto_url ?? null : null,
    ]);
});

// Debug hosting environment
Route::get('/debug/hosting-env', function() {
    return response()->json([
        'is_hosting' => \App\Helpers\HostingStorageHelper::isHostingEnvironment(),
        'base_path' => base_path(),
        'paths' => \App\Helpers\HostingStorageHelper::getHostingPaths(),
        'server_vars' => [
            'HTTP_HOST' => request()->getHost(),
            'SERVER_NAME' => $_SERVER['SERVER_NAME'] ?? 'unknown',
            'DOCUMENT_ROOT' => $_SERVER['DOCUMENT_ROOT'] ?? 'unknown',
        ],
        'directory_checks' => [
            'base_path_is_dir' => is_dir(base_path()),
            'public_path_is_dir' => is_dir(public_path()),
            'storage_path_is_dir' => is_dir(storage_path()),
            'has_project_laravel' => is_dir(base_path('../project_laravel')),
            'has_public_html' => is_dir(base_path('../public_html')),
            'path_contains_project_laravel' => strpos(base_path(), 'project_laravel') !== false,
            'path_starts_with_home' => strpos(base_path(), '/home/') === 0,
        ]
    ]);
});
