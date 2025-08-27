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

// API untuk mendapatkan foto-foto dalam galeri
Route::get('/galeri/{id}/photos', function($id) {
    $galeri = \App\Models\Galeri::findOrFail($id);
    $photos = \App\Models\GaleriFoto::where('galeri_id', $id)
        ->orderBy('is_thumbnail', 'desc')
        ->orderBy('id', 'asc')
        ->get(['foto', 'is_thumbnail']);
    
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

// Test route untuk debugging
Route::get('test-bel', function() {
    return response()->json(['message' => 'API Test berhasil!', 'timestamp' => now()]);
});
