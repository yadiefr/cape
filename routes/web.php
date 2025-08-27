<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\Admin\PengumumanController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\GuruAuthController;
use App\Http\Controllers\SiswaAuthController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\JadwalSettingsController;
use App\Http\Controllers\Admin\RuangUjianController;
use App\Http\Controllers\Admin\UjianController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\Ujian\JenisUjianController;
use App\Http\Controllers\Admin\PengawasController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\CalendarController;
use App\Models\Jurusan;
use App\Models\Pengumuman;
use App\Models\Berita;
use App\Models\Galeri;
use App\Http\Controllers\PendaftaranController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $jurusan = Jurusan::all();
    $pengumuman = Pengumuman::where('is_active', true)
                    ->where(function($query) {
                        $query->whereNull('tanggal_selesai')
                            ->orWhere('tanggal_selesai', '>=', now());
                    })
                    ->where('tanggal_mulai', '<=', now())
                    ->where('show_on_homepage', true)
                    ->orderBy('tanggal_mulai', 'desc')
                    ->take(3)
                    ->get();
                    
    $berita = Berita::orderBy('created_at', 'desc')->take(6)->get();
    $galeri = Galeri::orderBy('created_at', 'desc')->take(12)->get();
    
    return view('welcome', compact('jurusan', 'pengumuman', 'berita', 'galeri'));
});

// Galeri Routes
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
Route::get('/galeri/{id}', [GaleriController::class, 'show'])->name('galeri.show');
Route::get('/api/galeri/{id}/photos', [GaleriController::class, 'getPhotos'])->name('galeri.photos');

// Berita Routes
Route::get('/berita', [App\Http\Controllers\BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{id}', [App\Http\Controllers\BeritaController::class, 'show'])->name('berita.show');

// Route untuk login dan logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout'); // Main logout route
Route::middleware(['auth:web'])->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

// PPDB Public Routes
Route::group(['prefix' => 'ppdb', 'middleware' => ['web']], function () {
    // Public routes (no auth required)
    Route::get('/', [App\Http\Controllers\PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/check', [App\Http\Controllers\PendaftaranController::class, 'check'])->name('pendaftaran.check');
    Route::post('/check', [App\Http\Controllers\PendaftaranController::class, 'checkStatus'])->name('pendaftaran.check.status');
    Route::get('/success/{nomor}', [App\Http\Controllers\PendaftaranController::class, 'success'])->name('pendaftaran.success');
    Route::get('/print/{nomor}/{nisn}', [App\Http\Controllers\PendaftaranController::class, 'print'])->name('pendaftaran.print');
    
    // Auth routes (for registration and login)
    Route::middleware(['guest:pendaftar'])->group(function () {
        Route::get('/register', [\App\Http\Controllers\Auth\PendaftarAuthController::class, 'showRegistrationForm'])->name('pendaftar.register');
        Route::post('/register', [\App\Http\Controllers\Auth\PendaftarAuthController::class, 'register'])->name('pendaftar.register.submit');
        Route::get('/login', [\App\Http\Controllers\Auth\PendaftarAuthController::class, 'showLoginForm'])->name('pendaftar.login');
        Route::post('/login', [\App\Http\Controllers\Auth\PendaftarAuthController::class, 'login'])->name('pendaftar.login.submit');
    });

    // Protected PPDB Routes
    Route::middleware(['web', 'auth:pendaftar'])->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Auth\PendaftarAuthController::class, 'logout'])->name('pendaftar.logout');
        Route::get('/form', [App\Http\Controllers\PendaftaranController::class, 'showForm'])->name('pendaftaran.form');
        Route::post('/form', [App\Http\Controllers\PendaftaranController::class, 'store'])->name('pendaftaran.store');
        Route::get('/dashboard', [App\Http\Controllers\PendaftaranController::class, 'dashboard'])->name('pendaftar.dashboard');
        Route::get('/edit', [App\Http\Controllers\PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
        Route::put('/update', [App\Http\Controllers\PendaftaranController::class, 'update'])->name('ppdb.update');
    });
});

// Guru
Route::get('/guru/login', [GuruAuthController::class, 'showLoginForm'])->name('guru.login');
Route::post('/guru/login', [GuruAuthController::class, 'login'])->name('guru.login.process');
Route::post('/guru/logout', [GuruAuthController::class, 'logout'])->name('guru.logout');
Route::middleware(['auth:guru'])->group(function () {
    Route::post('/guru/logout', [AuthController::class, 'logout'])->name('guru.auth.logout');
});

// Siswa (using unified login)
Route::get('/siswa/login', function() {
    return redirect()->route('login');
})->name('siswa.login');
Route::post('/siswa/logout', [SiswaAuthController::class, 'logout'])->name('siswa.logout');

// Route khusus untuk login siswa ujian
Route::get('/ujian/login', [App\Http\Controllers\Siswa\AuthController::class, 'showLoginForm'])->name('ujian.login');
Route::post('/ujian/login', [App\Http\Controllers\Siswa\AuthController::class, 'login']);
Route::post('/ujian/logout', [App\Http\Controllers\Siswa\AuthController::class, 'logout'])->name('ujian.logout');

// Route untuk forgot password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('forgot.password.process');


// Redirect /admin/ujian/ruangan to the new route
Route::get('/admin/ujian/ruangan', function() {
    return redirect()->route('admin.ujian.ruangan.index');
});

// Route umum untuk dashboard - akan melakukan redirect sesuai peran pengguna
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth:web,guru,siswa'])->group(function () {
    
    // Route untuk Admin
    Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {

        // Admin Profile Routes
        Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/update', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
        
        Route::get('/', function() {
            return redirect()->route('admin.dashboard');
        });
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Alias untuk admin.dashboard -> admin.index (untuk kompatibilitas)
        Route::get('/index', function() {
            return redirect()->route('admin.dashboard');
        })->name('index');
        
        Route::get('/support', function() { return view('admin.support'); })->name('support');
        
        // Settings
        Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'store'])->name('settings.store');
        Route::put('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        
        // Jadwal Settings Routes
        Route::prefix('settings/jadwal')->name('settings.jadwal.')->group(function () {
            
            Route::get('/', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'index'])->name('index');
            Route::post('/batch', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'batchStore'])->name('batch-store');
            Route::post('/', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'store'])->name('store');
            Route::get('/create', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'create'])->name('create');
            Route::get('/{settingsJadwal}/edit', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'edit'])->name('edit');
            Route::put('/{settingsJadwal}', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'update'])->name('update');
            Route::delete('/{settingsJadwal}', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'destroy'])->name('destroy');
            Route::patch('/{settingsJadwal}/toggle-status', [App\Http\Controllers\Admin\JadwalSettingsController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Manajemen Guru
        Route::resource('guru', App\Http\Controllers\Admin\GuruController::class);
        Route::get('guru/{guru}/assign-subjects', [App\Http\Controllers\Admin\GuruController::class, 'assignSubjects'])->name('guru.assign-subjects');
        Route::post('guru/{guru}/assign-subjects', [App\Http\Controllers\Admin\GuruController::class, 'storeSubjectAssignment'])->name('guru.store-subject-assignment');
        Route::delete('guru/{guru}/remove-subject/{jadwalId}', [App\Http\Controllers\Admin\GuruController::class, 'removeSubjectAssignment'])->name('guru.remove-subject-assignment');
        
        // Import/Export Guru
        Route::get('guru/template/download', [App\Http\Controllers\Admin\GuruController::class, 'downloadTemplate'])->name('guru.download-template');
        Route::post('guru/import', [App\Http\Controllers\Admin\GuruController::class, 'import'])->name('guru.import');
        
        // Manajemen Kelas
        Route::resource('kelas', App\Http\Controllers\Admin\KelasController::class);
        
        // Jadwal Pelajaran
        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\Admin\JadwalController::class, 'create'])->name('create');
            // Table view endpoint
            Route::get('/create-table', [App\Http\Controllers\Admin\JadwalController::class, 'createTable'])->name('create-table');
            Route::get('/settings', [App\Http\Controllers\Admin\JadwalController::class, 'settings'])->name('settings');
            Route::get('/get-schedule', [App\Http\Controllers\Admin\JadwalController::class, 'getSchedule'])->name('get-schedule');
            Route::post('/get-schedule', [App\Http\Controllers\Admin\JadwalController::class, 'getSchedule'])->name('post-schedule');
            Route::post('/bulk-store', [App\Http\Controllers\Admin\JadwalController::class, 'bulkStore'])->name('bulk-store');
            
            // Test endpoint
            Route::any('/test', [App\Http\Controllers\Admin\JadwalController::class, 'test'])->name('test');
            
            // API endpoints for dynamic filtering
            Route::get('/guru-by-mapel', [App\Http\Controllers\Admin\JadwalController::class, 'getGuruByMapel'])->name('guru-by-mapel');
            Route::get('/mapel-by-guru', [App\Http\Controllers\Admin\JadwalController::class, 'getMapelByGuru'])->name('mapel-by-guru');
            Route::get('/data-by-kelas', [App\Http\Controllers\Admin\JadwalController::class, 'getDataByKelas'])->name('data-by-kelas');
            
            // Import and Template routes
            Route::post('/import', [App\Http\Controllers\Admin\JadwalController::class, 'import'])->name('import');
            Route::get('/template', [App\Http\Controllers\Admin\JadwalController::class, 'downloadTemplate'])->name('template');
            
            // Cleanup orphaned schedules
            Route::post('/cleanup-orphaned', [App\Http\Controllers\Admin\JadwalController::class, 'cleanupOrphanedSchedules'])->name('cleanup-orphaned');

            Route::get('/by-class/{kelas?}', [App\Http\Controllers\Admin\JadwalController::class, 'byClass'])->name('by-class');
            Route::get('/by-teacher/{guru?}', [App\Http\Controllers\Admin\JadwalController::class, 'byTeacher'])->name('by-teacher');


            Route::get('/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [App\Http\Controllers\Admin\JadwalController::class, 'edit'])->name('edit');
            Route::post('/', [App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('store');
            Route::put('/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('destroy');
        });
        Route::post('jadwal/bulk-delete', [App\Http\Controllers\Admin\JadwalController::class, 'bulkDelete'])->name('jadwal.bulk-delete');
        Route::post('jadwal/bulk-activate', [App\Http\Controllers\Admin\JadwalController::class, 'bulkActivate'])->name('jadwal.bulk-activate');
        Route::post('jadwal/bulk-deactivate', [App\Http\Controllers\Admin\JadwalController::class, 'bulkDeactivate'])->name('jadwal.bulk-deactivate');
        Route::post('jadwal/batch-delete-by-day', [App\Http\Controllers\Admin\JadwalController::class, 'batchDeleteByDay'])->name('jadwal.batch-delete-by-day');
        
        // Penilaian
        Route::resource('nilai', App\Http\Controllers\Admin\NilaiController::class);
        Route::get('nilai/rapor', [App\Http\Controllers\Admin\NilaiController::class, 'rapor'])->name('nilai.rapor');
        
        // Kehadiran/Absensi
        Route::controller(App\Http\Controllers\Admin\AbsensiController::class)->group(function () {
            Route::get('/absensi', 'index')->name('absensi.index');
            Route::get('/absensi/rekap', 'rekap')->name('absensi.rekap');
            Route::get('/absensi/rekap/export', 'export')->name('absensi.rekap.export');
            Route::get('/absensi/{id}', 'show')->name('absensi.show');
            Route::get('/absensi/{id}/edit', 'edit')->name('absensi.edit');
            Route::put('/absensi/{id}', 'update')->name('absensi.update');
            Route::delete('/absensi/{id}', 'destroy')->name('absensi.destroy');
            Route::post('/absensi/import', 'import')->name('absensi.import');
            Route::get('/absensi/template', 'downloadTemplate')->name('absensi.template');
        });
        
        // Ujian Online
        Route::resource('ujian', UjianController::class)->except(['show']);
        Route::post('ujian/{ujian}/toggle', [UjianController::class, 'toggle'])->name('ujian.toggle');
        Route::get('ujian/{ujian}/print-token', [UjianController::class, 'printToken'])->name('ujian.print-token');
        
        // Kelola Siswa Ujian
        Route::get('ujian/{ujian}/kelola-siswa', [UjianController::class, 'kelolaSiswa'])->name('ujian.kelola-siswa');
        Route::post('ujian/{ujian}/tambah-siswa', [UjianController::class, 'tambahSiswa'])->name('ujian.tambah-siswa');
        Route::get('ujian/{ujian}/cari-siswa', [UjianController::class, 'cariSiswa'])->name('ujian.cari-siswa');
        Route::delete('ujian/{ujian}/peserta/{pesertaUjian}', [UjianController::class, 'hapusPeserta'])->name('ujian.hapus-peserta');
        Route::patch('ujian/{ujian}/peserta/{pesertaUjian}/status', [UjianController::class, 'updateStatusPeserta'])->name('ujian.update-status-peserta');
        
        // Manajemen Peserta Global
        Route::get('ujian/peserta', [UjianController::class, 'semuaPeserta'])->name('ujian.peserta.index');
        Route::get('ujian/peserta/aktif', [UjianController::class, 'pesertaAktif'])->name('ujian.peserta.aktif');
        Route::get('ujian/monitoring', [UjianController::class, 'monitoring'])->name('ujian.monitoring');
        Route::get('ujian/hasil', [UjianController::class, 'hasilUjian'])->name('ujian.hasil');
        Route::get('ujian/statistik', [UjianController::class, 'statistik'])->name('ujian.statistik');
        Route::get('ujian/laporan', [UjianController::class, 'laporan'])->name('ujian.laporan');
        
        // Master Data - Jenis Ujian
        Route::resource('jenis-ujian', JenisUjianController::class)->parameters([
            'jenis-ujian' => 'jenisUjian'
        ]);
        Route::post('jenis-ujian/{jenisUjian}/toggle', [JenisUjianController::class, 'toggle'])->name('jenis-ujian.toggle');
        Route::get('jenis-ujian/{jenisUjian}/data', [JenisUjianController::class, 'getExamTypeData'])->name('jenis-ujian.data');
        
        // Test route
        Route::get('jenis-ujian-test', function() {
            return view('admin.jenis-ujian.test');
        })->name('jenis-ujian.test');
        
        // Keuangan
        Route::resource('keuangan', App\Http\Controllers\Admin\KeuanganController::class);
        Route::get('keuangan/pembayaran', [App\Http\Controllers\Admin\KeuanganController::class, 'pembayaran'])->name('keuangan.pembayaran');
        Route::get('keuangan/siswa/{siswa}', [App\Http\Controllers\Admin\KeuanganController::class, 'riwayat'])->name('keuangan.riwayat');
        Route::get('keuangan/tagihan/create', [App\Http\Controllers\Admin\KeuanganController::class, 'createTagihan'])->name('keuangan.tagihan.create');
        Route::post('keuangan/tagihan', [App\Http\Controllers\Admin\KeuanganController::class, 'storeTagihan'])->name('keuangan.tagihan.store');

        // Test route for debugging kelas by year
        Route::get('keuangan/test-kelas/{year}', function($year) {
            $kelasIds = \App\Models\Siswa::where('tahun_masuk', $year)
                ->where('status', 'aktif')
                ->distinct()
                ->pluck('kelas_id');

            $kelas = \App\Models\Kelas::whereIn('id', $kelasIds)
                ->where('is_active', true)
                ->with('jurusan')
                ->orderBy('nama_kelas')
                ->get();

            return response()->json([
                'year' => $year,
                'kelas_ids' => $kelasIds,
                'kelas' => $kelas,
                'count' => $kelas->count()
            ]);
        })->name('keuangan.test-kelas');

        // Debug route for tagihan grouping
        Route::get('keuangan/debug-tagihan', function() {
            $allTagihan = \App\Models\Tagihan::orderByDesc('created_at')->get();

            // Test the new grouping logic
            $groupTagihan = \App\Models\Tagihan::select([
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan'
            ])
            ->selectRaw('MIN(id) as id')
            ->selectRaw('MIN(created_at) as created_at')
            ->selectRaw('COUNT(*) as jumlah_siswa')
            ->selectRaw('MIN(kelas_id) as kelas_id')
            ->where(function($query) {
                $query->where('keterangan', 'LIKE', '%[Kelas %')
                      ->orWhere('keterangan', 'LIKE', '%[Angkatan%')
                      ->orWhere('keterangan', 'LIKE', '%[Semua Siswa]%');
            })
            ->groupBy([
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan'
            ])
            ->get();

            $individualTagihan = \App\Models\Tagihan::select([
                'id',
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan',
                'kelas_id',
                'siswa_id',
                'created_at'
            ])
            ->selectRaw('1 as jumlah_siswa')
            ->where('keterangan', 'NOT LIKE', '%[Kelas %')
            ->where('keterangan', 'NOT LIKE', '%[Angkatan%')
            ->where('keterangan', 'NOT LIKE', '%[Semua Siswa]%')
            ->get();

            return response()->json([
                'total_tagihan' => $allTagihan->count(),
                'group_tagihan_count' => $groupTagihan->count(),
                'individual_tagihan_count' => $individualTagihan->count(),
                'group_tagihan' => $groupTagihan->toArray(),
                'individual_tagihan' => $individualTagihan->toArray(),
                'sample_keterangan' => $allTagihan->pluck('keterangan')->unique()->values()->toArray()
            ]);
        })->name('keuangan.debug-tagihan');

        // Debug route for siswa data
        Route::get('keuangan/debug-siswa', function() {
            return response()->json([
                'test' => 'Route working',
                'timestamp' => now()->toDateTimeString()
            ]);
        })->name('keuangan.debug-siswa');

        // Simple test route for create tagihan
        Route::get('keuangan/test-create', function() {
            try {
                $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
                $siswaList = \App\Models\Siswa::orderBy('nama_lengkap')->get();

                return response()->json([
                    'status' => 'success',
                    'kelas_count' => $kelasList->count(),
                    'siswa_count' => $siswaList->count(),
                    'message' => 'Controller data loading works'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
            }
        })->name('keuangan.test-create');

        // Simple siswa list route
        Route::get('keuangan/siswa-list', function() {
            try {
                $siswa = \App\Models\Siswa::all();
                $html = '<h1>Debug Siswa Data</h1>';
                $html .= '<p><strong>Total Siswa:</strong> ' . $siswa->count() . '</p>';

                if ($siswa->count() > 0) {
                    $html .= '<table border="1" style="border-collapse: collapse; width: 100%; margin-top: 20px;">';
                    $html .= '<tr style="background: #f0f0f0;"><th>ID</th><th>Nama Lengkap</th><th>Nama</th><th>NIS</th><th>Status</th><th>Kelas ID</th></tr>';

                    foreach ($siswa->take(10) as $s) {
                        $html .= '<tr>';
                        $html .= '<td>' . ($s->id ?? 'NULL') . '</td>';
                        $html .= '<td>' . ($s->nama_lengkap ?? 'NULL') . '</td>';
                        $html .= '<td>' . ($s->nama ?? 'NULL') . '</td>';
                        $html .= '<td>' . ($s->nis ?? 'NULL') . '</td>';
                        $html .= '<td>' . ($s->status ?? 'NULL') . '</td>';
                        $html .= '<td>' . ($s->kelas_id ?? 'NULL') . '</td>';
                        $html .= '</tr>';
                    }

                    $html .= '</table>';
                    if ($siswa->count() > 10) {
                        $html .= '<p><em>Menampilkan 10 dari ' . $siswa->count() . ' siswa</em></p>';
                    }
                } else {
                    $html .= '<p style="color: red;"><strong>Tidak ada data siswa di database!</strong></p>';
                    $html .= '<p>Kemungkinan penyebab:</p>';
                    $html .= '<ul>';
                    $html .= '<li>Database siswa kosong</li>';
                    $html .= '<li>Tabel siswa belum di-seed</li>';
                    $html .= '<li>Koneksi database bermasalah</li>';
                    $html .= '</ul>';
                }

                return $html;
            } catch (\Exception $e) {
                return '<h1>Error Database</h1><p style="color: red;">Error: ' . $e->getMessage() . '</p><p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
            }
        })->name('keuangan.siswa-list');
        Route::get('keuangan/tagihan/{tagihan}/edit', [App\Http\Controllers\Admin\KeuanganController::class, 'editTagihan'])->name('keuangan.tagihan.edit');
        Route::put('keuangan/tagihan/{tagihan}', [App\Http\Controllers\Admin\KeuanganController::class, 'updateTagihan'])->name('keuangan.tagihan.update');
        Route::delete('keuangan/tagihan/{tagihan}', [App\Http\Controllers\Admin\KeuanganController::class, 'deleteTagihan'])->name('keuangan.tagihan.delete');
        Route::post('keuangan/siswa/{siswa}/update-pembayaran', [App\Http\Controllers\Admin\KeuanganController::class, 'updatePembayaran'])->name('keuangan.updatePembayaran');
        Route::delete('keuangan/siswa/{siswa}/delete-pembayaran', [App\Http\Controllers\Admin\KeuanganController::class, 'deletePembayaran'])->name('keuangan.deletePembayaran');
        Route::post('keuangan/tagihan/{tagihan}/bayar', [App\Http\Controllers\Admin\KeuanganController::class, 'bayar'])->name('keuangan.bayar');
        
        // Admin Users Management
        Route::resource('admin-users', App\Http\Controllers\Admin\AdminUserController::class);
        Route::post('admin-users/{adminUser}/toggle-status', [App\Http\Controllers\Admin\AdminUserController::class, 'toggleStatus'])->name('admin-users.toggle-status');
        Route::post('admin-users/{adminUser}/reset-password', [App\Http\Controllers\Admin\AdminUserController::class, 'resetPassword'])->name('admin-users.reset-password');

        // Midtrans Settings
        Route::get('midtrans/settings', function() {
            return view('admin.midtrans.settings');
        })->name('midtrans.settings');
        
        // PPDB
        Route::resource('ppdb', App\Http\Controllers\Admin\PPDBController::class);
        Route::get('ppdb/dashboard', [App\Http\Controllers\Admin\PPDBController::class, 'dashboard'])->name('ppdb.dashboard');
        Route::put('ppdb/{id}/status', [App\Http\Controllers\Admin\PPDBController::class, 'updateStatus'])->name('ppdb.status');
        Route::get('ppdb/export', [App\Http\Controllers\Admin\PPDBController::class, 'export'])->name('ppdb.export');
        Route::post('ppdb/update-status-setting', [App\Http\Controllers\Admin\PPDBController::class, 'updateStatusSetting'])->name('ppdb.updateStatusSetting');
        Route::put('ppdb/{pendaftaran}/update', [App\Http\Controllers\Admin\PPDBController::class, 'update'])->name('admin.ppdb.update');

        // Laporan
        // Route::resource('laporan', App\Http\Controllers\Admin\LaporanController::class);
        
        // Aktivitas - Commented out until controller is created
        // Route::get('aktivitas', [App\Http\Controllers\Admin\AktivitasController::class, 'index'])->name('aktivitas');
        
        // Manajemen Konten Website
        
        // Hero Banner
        Route::resource('hero', App\Http\Controllers\Admin\HeroBannerController::class);
        Route::patch('hero/{hero}/toggle-status', [App\Http\Controllers\Admin\HeroBannerController::class, 'toggleStatus'])->name('hero.toggle-status');
        
        // Hero Background
        Route::resource('hero-background', App\Http\Controllers\Admin\HeroBackgroundController::class)->parameters([
            'hero-background' => 'heroBackground'
        ]);
        Route::put('hero-background/{heroBackground}/toggle-status', [App\Http\Controllers\Admin\HeroBackgroundController::class, 'toggleStatus'])->name('hero-background.toggle-status');
        
        // Program Keahlian (Jurusan)
        Route::resource('jurusan', App\Http\Controllers\Admin\JurusanController::class);
        
        // Manajemen Siswa - routes khusus harus sebelum resource
        Route::get('siswa/template', [App\Http\Controllers\Admin\SiswaController::class, 'template'])->name('siswa.template');
        Route::post('siswa/import', [App\Http\Controllers\Admin\SiswaController::class, 'import'])->name('siswa.import');
        Route::delete('siswa/bulk-delete', [App\Http\Controllers\Admin\SiswaController::class, 'bulkDelete'])->name('siswa.bulkDelete');
        Route::resource('siswa', App\Http\Controllers\Admin\SiswaController::class);
        
        // Manajemen Guru
        Route::resource('guru', App\Http\Controllers\Admin\GuruController::class);
        Route::get('guru/{guru}/assign-subjects', [App\Http\Controllers\Admin\GuruController::class, 'assignSubjects'])->name('guru.assign-subjects');
        Route::post('guru/{guru}/assign-subjects', [App\Http\Controllers\Admin\GuruController::class, 'storeSubjectAssignment'])->name('guru.store-subject-assignment');
        Route::delete('guru/{guru}/remove-subject/{jadwalId}', [App\Http\Controllers\Admin\GuruController::class, 'removeSubjectAssignment'])->name('guru.remove-subject-assignment');
        
        // Manajemen Kelas
        Route::resource('kelas', App\Http\Controllers\Admin\KelasController::class);
        
        // Keunggulan
        Route::resource('keunggulan', App\Http\Controllers\Admin\KeunggulanController::class);
        Route::patch('keunggulan/{keunggulan}/toggle-status', [App\Http\Controllers\Admin\KeunggulanController::class, 'toggleStatus'])->name('keunggulan.toggle-status');
        
        // Agenda/Kalender
        Route::resource('agenda', App\Http\Controllers\Admin\AgendaController::class);
        Route::patch('agenda/{agenda}/toggle-status', [App\Http\Controllers\Admin\AgendaController::class, 'toggleStatus'])->name('admin.agenda.toggle-status');
        Route::patch('agenda/{agenda}/toggle-status', [App\Http\Controllers\Admin\AgendaController::class, 'toggleStatus'])->name('agenda.toggle-status');
        
        // Galeri
        Route::get('galeri', [App\Http\Controllers\Admin\GaleriController::class, 'index'])->name('galeri.index');
        Route::get('galeri/create', function() {
            // Get PHP upload limits
            $uploadMaxFilesize = ini_get('upload_max_filesize');
            $postMaxSize = ini_get('post_max_size');
            
            // Convert to numeric value in MB
            $maxFileSize = (float) $uploadMaxFilesize;
            
            return view('admin.galeri.create', compact('maxFileSize'));
        })->name('galeri.create');
        Route::post('galeri', [App\Http\Controllers\Admin\GaleriController::class, 'store'])->name('galeri.store');
        Route::get('galeri/{galeri}', [App\Http\Controllers\Admin\GaleriController::class, 'show'])->name('galeri.show');
        Route::get('galeri/{galeri}/edit', [App\Http\Controllers\Admin\GaleriController::class, 'edit'])->name('galeri.edit');
        Route::put('galeri/{galeri}', [App\Http\Controllers\Admin\GaleriController::class, 'update'])->name('galeri.update');
        Route::delete('galeri/{galeri}', [App\Http\Controllers\Admin\GaleriController::class, 'destroy'])->name('galeri.destroy');
        
        // Berita
        Route::resource('berita', App\Http\Controllers\Admin\BeritaController::class);
        
        // Pengumuman
        Route::resource('pengumuman', App\Http\Controllers\Admin\PengumumanController::class);
        
        // Manajemen Bel Sekolah
        Route::resource('bel', App\Http\Controllers\Admin\BelSekolahController::class);
        Route::put('bel/{bel}/toggle-aktif', [App\Http\Controllers\Admin\BelSekolahController::class, 'toggleAktif'])->name('bel.toggle-aktif');
        Route::post('bel/{bel}/bunyikan', [App\Http\Controllers\Admin\BelSekolahController::class, 'bunyikanBel'])->name('bel.bunyikan');
        Route::get('bel-hari-ini', [App\Http\Controllers\Admin\BelSekolahController::class, 'getBelHariIni'])->name('bel.hari-ini');
        
        // AJAX routes untuk bel sekolah
        Route::put('bel/{id}/ajax-toggle-aktif', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxToggleAktif'])->name('bel.ajax-toggle-aktif');
        Route::post('bel/{id}/ajax-bunyikan', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxBunyikanBel'])->name('bel.ajax-bunyikan');
        Route::delete('bel/{id}/ajax-destroy', [App\Http\Controllers\Admin\BelSekolahController::class, 'ajaxDestroy'])->name('bel.ajax-destroy');
        
        // Mata Pelajaran - routes khusus harus sebelum resource
        Route::get('matapelajaran/template', [App\Http\Controllers\Admin\MataPelajaranController::class, 'downloadTemplate'])->name('matapelajaran.template');
        Route::get('matapelajaran/import-info', [App\Http\Controllers\Admin\MataPelajaranController::class, 'importInfo'])->name('matapelajaran.import-info');
        Route::post('matapelajaran/import', [App\Http\Controllers\Admin\MataPelajaranController::class, 'import'])->name('matapelajaran.import');
        Route::get('matapelajaran/{id}/assignments', [App\Http\Controllers\Admin\MataPelajaranController::class, 'getAssignments'])->name('matapelajaran.assignments');
        Route::post('matapelajaran/{id}/assign-teacher', [App\Http\Controllers\Admin\MataPelajaranController::class, 'assignTeacher'])->name('matapelajaran.assign-teacher');
        Route::resource('matapelajaran', App\Http\Controllers\Admin\MataPelajaranController::class);
        
        // Jadwal Pelajaran - untuk menghapus assignment
        Route::delete('jadwal-pelajaran/{id}', function($id) {
            $jadwal = \App\Models\JadwalPelajaran::find($id);
            if ($jadwal) {
                $jadwal->delete();
                return response()->json(['success' => true, 'message' => 'Assignment berhasil dihapus']);
            }
            return response()->json(['success' => false, 'message' => 'Assignment tidak ditemukan'], 404);
        })->name('jadwal-pelajaran.destroy');
        
        // Jadwal routes
        Route::get('jadwal/settings', [App\Http\Controllers\Admin\JadwalController::class, 'settings'])->name('jadwal.settings');
        
        // Website Analytics
        Route::get('analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');

        // Partner routes

        Route::resource('pkl', App\Http\Controllers\Admin\PraktikKerjaLapanganController::class);

        // Siswa Routes
        Route::resource('siswa', SiswaController::class);
        Route::post('siswa/{siswa}/reset-password', [SiswaController::class, 'resetPassword'])->name('siswa.reset-password');
        // Tambah route bayar semua tagihan siswa
        Route::post('siswa/{siswa}/bayar-semua', [SiswaController::class, 'bayarSemuaTagihan'])->name('siswa.bayarSemua');
    });

    // Route untuk Guru
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/', function() {
            return redirect()->route('guru.dashboard');
        });
        Route::get('/dashboard', [DashboardController::class, 'guruDashboard'])->name('dashboard');

        // Rute Absensi untuk Guru
        Route::controller(App\Http\Controllers\Guru\AbsensiController::class)->group(function () {
            Route::get('/absensi', 'index')->name('absensi.index');
            Route::get('/absensi/create', 'create')->name('absensi.create');
            Route::post('/absensi', 'store')->name('absensi.store');
            Route::get('/absensi/rekap', 'rekap')->name('absensi.rekap'); // This route needs to be before the {id} routes
            Route::get('/absensi/rekap/cetak', 'cetakRekap')->name('absensi.rekap.cetak');
            Route::get('/absensi/rekap/export', 'exportExcel')->name('absensi.rekap.export');
            Route::get('/absensi/kelas-by-mapel', 'getKelasByMapel')->name('absensi.kelas-by-mapel'); // New route
            Route::get('/absensi/kelas/{kelas_id}', 'kelasList')->name('absensi.kelas');
            Route::get('/absensi/{id}', 'show')->name('absensi.show');
            Route::get('/absensi/{id}/edit', 'edit')->name('absensi.edit');
            Route::put('/absensi/{id}', 'update')->name('absensi.update');
            Route::delete('/absensi/{id}', 'destroy')->name('absensi.destroy');
        });
        
        // Rute Nilai untuk Guru
        Route::controller(App\Http\Controllers\Guru\NilaiController::class)->group(function () {
            Route::get('/nilai', 'index')->name('nilai.index');
            Route::get('/nilai/create', 'create')->name('nilai.create');
            Route::post('/nilai', 'store')->name('nilai.store');
            Route::get('/nilai/{id}', 'show')->name('nilai.show');
            Route::post('/nilai/get', 'getNilai')->name('nilai.get');
            Route::get('/nilai/edit-batch', 'editBatch')->name('nilai.edit-batch');
            Route::put('/nilai/update-batch', 'updateBatch')->name('nilai.update-batch');
            Route::delete('/nilai/delete-batch', 'deleteBatch')->name('nilai.delete-batch');
        });

        // Rute Pengumuman untuk Guru
        Route::controller(App\Http\Controllers\Guru\PengumumanController::class)->group(function () {
            Route::get('/pengumuman', 'index')->name('pengumuman.index');
            Route::get('/pengumuman/{id}', 'show')->name('pengumuman.show');
        });

        // Rute Jadwal Pelajaran untuk Guru (menggunakan controller yang sudah diperbaiki)
        Route::get('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
        Route::get('/jadwal/debug', [App\Http\Controllers\Guru\JadwalController::class, 'debug'])->name('jadwal.debug');

        // Rute Kelas untuk Guru
        Route::get('/kelas', [App\Http\Controllers\Guru\KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{id}', [App\Http\Controllers\Guru\KelasController::class, 'show'])->name('kelas.show');

        // Rute Data Siswa untuk Guru
        Route::controller(App\Http\Controllers\Guru\SiswaController::class)->group(function () {
            Route::get('/siswa', 'index')->name('siswa.index');
            Route::get('/siswa/{id}', 'show')->name('siswa.show');
            Route::get('/siswa/kelas/{kelas_id}', 'getByKelas')->name('siswa.by-kelas');
            Route::get('/siswa/attendance-summary', 'getAttendanceSummary')->name('siswa.attendance-summary');
            Route::get('/siswa/export', 'export')->name('siswa.export');
        });

        // Rute Wali Kelas (khusus untuk guru yang adalah wali kelas)
        Route::controller(App\Http\Controllers\Guru\WaliKelasController::class)->prefix('wali-kelas')->name('wali-kelas.')->group(function () {
            Route::get('/dashboard', 'dashboard')->name('dashboard');
            Route::get('/siswa', 'dataSiswa')->name('siswa.index');
            Route::get('/absensi', 'absensi')->name('absensi');
            Route::post('/absensi', 'simpanAbsensi')->name('absensi.simpan');
            Route::get('/siswa/{id}', 'detailSiswa')->name('siswa.detail');
            Route::get('/rekap-absensi', 'rekapAbsensi')->name('rekap-absensi');
            Route::get('/rekap-keuangan', 'rekapKeuangan')->name('rekap-keuangan');
            Route::get('/bendahara', 'bendahara')->name('bendahara');
            Route::get('/bendahara/kas-masuk', 'kasMasuk')->name('bendahara.kas-masuk');
            Route::get('/bendahara/kas-keluar', 'kasKeluar')->name('bendahara.kas-keluar');
            Route::get('/bendahara/laporan-kas', 'laporanKas')->name('bendahara.laporan-kas');
            Route::get('/keuangan/{siswa}/detail', 'detailKeuangan')->name('keuangan.detail');
            Route::post('/siswa/set-km-bendahara', 'setKmBendahara')->name('siswa.set-km-bendahara');
        });

        // Profile routes for guru
        Route::controller(App\Http\Controllers\ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
        });
    });

    // Route untuk Siswa
    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        Route::get('/', function() {
            return redirect()->route('siswa.dashboard');
        });
        Route::get('/dashboard', [App\Http\Controllers\Siswa\DashboardController::class, 'index'])->name('dashboard');

        // Profile routes for students
        Route::controller(App\Http\Controllers\Siswa\ProfileController::class)->group(function () {
            Route::get('/profile', 'index')->name('profile.index');
            Route::get('/profile/edit', 'edit')->name('profile.edit');
            Route::put('/profile/update', 'update')->name('profile.update');
        });

        // Kartu Siswa routes
        Route::controller(App\Http\Controllers\Siswa\KartuController::class)->group(function () {
            Route::get('/kartu', 'index')->name('kartu.index');
            Route::get('/kartu/download', 'download')->name('kartu.download');
        });

        // Rute Absensi untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\AbsensiController::class)->group(function () {
            Route::get('/absensi', 'index')->name('absensi');
            Route::get('/absensi/{id}', 'show')->name('absensi.show');
        });

        // Rute Pengumuman untuk Siswa
        Route::get('/pengumuman', [App\Http\Controllers\PengumumanController::class, 'indexSiswa'])->name('pengumuman.index');
        Route::get('/pengumuman/{id}', [App\Http\Controllers\PengumumanController::class, 'showSiswa'])->name('pengumuman.show');

        // Rute Jadwal Pelajaran untuk Siswa (menggunakan controller yang sudah diperbaiki)
        Route::get('/jadwal', [App\Http\Controllers\Siswa\JadwalController::class, 'index'])->name('jadwal.index');

        // Rute Nilai untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\NilaiController::class)->group(function () {
            Route::get('/nilai', 'index')->name('nilai.index');
            Route::get('/nilai/{id}', 'show')->name('nilai.show');
        });

        // Rute Materi & Tugas untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\MateriController::class)->group(function () {
            Route::get('/materi', 'index')->name('materi.index');
            Route::get('/materi/{id}', 'show')->name('materi.show');
            Route::get('/materi/{id}/download', 'download')->name('materi.download');
        });

        // Rute Tugas untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\TugasController::class)->group(function() {
            Route::get('/tugas', 'index')->name('tugas');
            Route::get('/tugas/{id}', 'show')->name('tugas.show');
            Route::post('/tugas/{id}/submit', 'submit')->name('tugas.submit');
        });

        // Rute PKL untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\PraktikKerjaLapanganController::class)->group(function() {
            Route::get('/pkl', 'index')->name('pkl');
            Route::get('/pkl/{id}', 'show')->name('pkl.show');
            Route::post('/pkl/{id}/laporan', 'uploadLaporan')->name('pkl.laporan.upload');
            Route::get('/pkl/{id}/laporan', 'downloadLaporan')->name('pkl.laporan.download');
            Route::get('/pkl/{id}/surat', 'downloadSurat')->name('pkl.surat.download');
        });

        // Rute Keuangan untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\KeuanganController::class)->group(function() {
            Route::get('/keuangan', 'index')->name('keuangan.index');
            Route::get('/keuangan/{id}', 'show')->name('keuangan.show');
        });

        // Rute Ujian untuk Siswa
        Route::controller(App\Http\Controllers\Siswa\UjianController::class)->group(function() {
            Route::get('/ujian', 'index')->name('ujian.index');
            Route::get('/ujian/{id}', 'show')->name('ujian.show');
            Route::get('/ujian/{id}/start', 'start')->name('ujian.start');
            Route::post('/ujian/{id}/submit', 'submit')->name('ujian.submit');
        });

        // Routes untuk Ketua Kelas (KM)
        Route::prefix('ketua-kelas')->name('ketua-kelas.')->group(function () {
            Route::controller(App\Http\Controllers\Siswa\KetuaKelasController::class)->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/rekap-absensi', 'rekapAbsensi')->name('rekap-absensi');
                Route::get('/daftar-siswa', 'daftarSiswa')->name('daftar-siswa');
                Route::get('/detail-siswa/{siswa}', 'detailSiswa')->name('detail-siswa');
                Route::get('/absensi', 'absensi')->name('absensi');
                Route::post('/absensi/simpan', 'simpanAbsensi')->name('absensi.simpan');
            });
        });

        // Routes untuk Bendahara
        Route::prefix('bendahara')->name('bendahara.')->group(function () {
            Route::controller(App\Http\Controllers\Siswa\BendaharaController::class)->group(function () {
                Route::get('/dashboard', 'dashboard')->name('dashboard');
                Route::get('/kas-masuk', 'kasMasuk')->name('kas-masuk');
                Route::get('/input-kas-masuk', 'inputKasMasuk')->name('input-kas-masuk');
                Route::post('/kas-masuk', 'simpanKasMasuk')->name('simpan-kas-masuk');
                Route::get('/edit-kas-masuk', 'editKasMasuk')->name('edit-kas-masuk');
                Route::put('/kas-masuk', 'updateKasMasuk')->name('update-kas-masuk');
                Route::get('/kas-keluar', 'kasKeluar')->name('kas-keluar');
                Route::get('/input-kas-keluar', 'inputKasKeluar')->name('input-kas-keluar');
                Route::post('/kas-keluar', 'simpanKasKeluar')->name('simpan-kas-keluar');
                Route::get('/laporan-kas', 'laporanKas')->name('laporan-kas');
                Route::get('/export-kas', 'exportKas')->name('export-kas');
                Route::get('/export-data-siswa', 'exportDataSiswa')->name('export-data-siswa');
                Route::get('/export-rekap', 'exportRekap')->name('export-rekap');
                Route::get('/rekap-keuangan', 'rekapKeuangan')->name('rekap-keuangan');
                Route::get('/detail-siswa/{siswa}', 'detailSiswa')->name('detail-siswa');
            });
        });
    });
});

// Notifikasi
Route::get('/notifications/get', [NotificationController::class, 'getNotifications'])->name('notifications.get');
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');

// Direct access to jadwal settings view outside of any middleware
Route::get('/direct-settings', function() {
    return view('admin.jadwal.settings', ['settings' => []]);
})->name('direct.settings');

// Simple test controller route
Route::get('/simple-test-view', [App\Http\Controllers\Admin\SimpleTestController::class, 'renderView'])->name('simple.test.view');

// Student routes moved to main web.php

Route::middleware(['auth:web'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Ujian Routes
    Route::prefix('ujian')->name('ujian.')->group(function () {
        Route::get('/', [UjianController::class, 'index'])->name('dashboard');
        
        // Bank Soal
        Route::prefix('bank-soal')->name('bank-soal.')->group(function () {
            Route::get('/', [UjianController::class, 'bankSoalIndex'])->name('index');
            Route::get('/create', [UjianController::class, 'bankSoalCreate'])->name('create');
            Route::post('/store', [UjianController::class, 'bankSoalStore'])->name('store');
            Route::get('/edit/{id}', [UjianController::class, 'bankSoalEdit'])->name('edit');
            Route::put('/update/{id}', [UjianController::class, 'bankSoalUpdate'])->name('update');
            Route::delete('/delete/{id}', [UjianController::class, 'bankSoalDelete'])->name('delete');
            Route::delete('/destroy/{id}', [UjianController::class, 'bankSoalDestroy'])->name('destroy');
            Route::get('/import', [UjianController::class, 'bankSoalImport'])->name('import');
            Route::post('/import', [UjianController::class, 'bankSoalImportProcess'])->name('import.process');
        });

        // Jadwal Ujian
        Route::prefix('jadwal')->name('jadwal.')->group(function () {
            Route::get('/', [UjianController::class, 'jadwalIndex'])->name('index');
            Route::get('/create', [UjianController::class, 'jadwalCreate'])->name('create');
            Route::post('/store', [UjianController::class, 'jadwalStore'])->name('store');
            Route::get('/create-table', [UjianController::class, 'jadwalCreateTable'])->name('create-table');
            Route::get('/edit/{id}', [UjianController::class, 'jadwalEdit'])->name('edit');
            Route::put('/update/{id}', [UjianController::class, 'jadwalUpdate'])->name('update');
            Route::delete('/destroy/{id}', [UjianController::class, 'jadwalDestroy'])->name('destroy');
        });

        // Pengawas Ujian
        Route::prefix('pengawas')->name('pengawas.')->group(function () {
            Route::get('/', [PengawasController::class, 'index'])->name('index');
            Route::get('/{jadwal}', [PengawasController::class, 'show'])->name('show');
            Route::post('/assign/{jadwal}', [PengawasController::class, 'assign'])->name('assign');
            Route::delete('/remove/{pengawas}', [PengawasController::class, 'remove'])->name('remove');
            Route::post('/update-kehadiran/{pengawas}', [PengawasController::class, 'updateKehadiran'])->name('update-kehadiran');
            Route::put('/update-catatan/{pengawas}', [PengawasController::class, 'updateCatatan'])->name('update-catatan');
            Route::post('/auto-assign', [PengawasController::class, 'autoAssign'])->name('autoAssign');
        });

        // Hasil Ujian
        Route::prefix('hasil')->name('hasil.')->group(function () {
            Route::get('/kelas', [UjianController::class, 'hasilKelas'])->name('kelas');
            Route::get('/siswa', [UjianController::class, 'hasilSiswa'])->name('siswa');
            Route::get('/mapel', [UjianController::class, 'hasilMapel'])->name('mapel');
        });

        // Analisis
        Route::prefix('analisis')->name('analisis.')->group(function () {
            Route::get('/tingkat-kesulitan', [UjianController::class, 'analisisTingkatKesulitan'])->name('tingkat-kesulitan');
            Route::get('/daya-beda', [UjianController::class, 'analisisDayaBeda'])->name('daya-beda');
            Route::get('/statistik', [UjianController::class, 'analisisStatistik'])->name('statistik');
        });

        // Monitoring
        Route::get('/monitoring', [UjianController::class, 'monitoring'])->name('monitoring');
        Route::get('/monitor/{id}', [UjianController::class, 'monitor'])->name('monitor');
        Route::get('/active-count', [UjianController::class, 'getActiveExamCount'])->name('active-count');

        // Pengaturan
        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/', [UjianController::class, 'pengaturan'])->name('index');
            Route::post('/', [UjianController::class, 'pengaturanUpdate'])->name('update');
            
            // Jenis Ujian
            Route::prefix('jenis-ujian')->name('jenis-ujian.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'index'])->name('index');
                Route::get('/create', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'create'])->name('create');
                Route::post('/', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'store'])->name('store');
                Route::get('/{jenisUjian}', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'show'])->name('show');
                Route::get('/{jenisUjian}/edit', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'edit'])->name('edit');
                Route::put('/{jenisUjian}', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'update'])->name('update');
                Route::delete('/{jenisUjian}', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'destroy'])->name('destroy');
                Route::patch('/{jenisUjian}/toggle-status', [App\Http\Controllers\Admin\Ujian\JenisUjianController::class, 'toggleStatus'])->name('toggle-status');
            });

            // Ruangan Ujian
            Route::prefix('ruangan')->name('ruangan.')->group(function () {
                Route::get('/', [RuanganController::class, 'index'])->name('index');
                Route::get('/create', [RuanganController::class, 'create'])->name('create');
                Route::post('/', [RuanganController::class, 'store'])->name('store');
                Route::get('/{ruangan}', [RuanganController::class, 'show'])->name('show');
                Route::get('/{ruangan}/edit', [RuanganController::class, 'edit'])->name('edit');
                Route::put('/{ruangan}', [RuanganController::class, 'update'])->name('update');
                Route::delete('/{ruangan}', [RuanganController::class, 'destroy'])->name('destroy');
                Route::post('/{ruangan}/toggle', [RuanganController::class, 'toggle'])->name('toggle');
                Route::get('/{ruangan}/data', [RuanganController::class, 'getRoomData'])->name('data');
                
                // Ruangan-Kelas Management
                Route::get('/{ruangan}/kelas', [RuanganController::class, 'manageKelas'])->name('manage-kelas');
                Route::post('/{ruangan}/kelas', [RuanganController::class, 'assignKelas'])->name('assign-kelas');
                Route::delete('/{ruangan}/kelas/{kelas}', [RuanganController::class, 'removeKelas'])->name('remove-kelas');
                Route::put('/{ruangan}/kelas/{kelas}', [RuanganController::class, 'updateKelas'])->name('update-kelas');
                Route::post('/{ruangan}/kelas/{kelas}/toggle', [RuanganController::class, 'toggleKelas'])->name('toggle-kelas');
            });
        });
    });
});

// Guru Routes
Route::middleware(['auth:guru'])->prefix('guru')->name('guru.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'guruDashboard'])->name('dashboard');
    
    // Calendar API
    Route::get('/calendar/events', [App\Http\Controllers\CalendarController::class, 'getEvents'])->name('calendar.events');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Jadwal
    Route::get('/jadwal', [App\Http\Controllers\Guru\JadwalController::class, 'index'])->name('jadwal.index');
    
    // Nilai
    Route::get('/nilai', [App\Http\Controllers\Guru\NilaiController::class, 'index'])->name('nilai.index');
    
    // Absensi
    Route::get('/absensi', [App\Http\Controllers\Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/create', [App\Http\Controllers\Guru\AbsensiController::class, 'create'])->name('absensi.create');
    
    // Siswa
    Route::get('/siswa', [App\Http\Controllers\Guru\SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/debug', [App\Http\Controllers\Guru\SiswaController::class, 'debug'])->name('siswa.debug');
    
    // Materi & Tugas
    Route::get('/materi', [App\Http\Controllers\Guru\MateriController::class, 'index'])->name('materi.index');
    Route::get('/materi/create', [App\Http\Controllers\Guru\MateriController::class, 'createMateri'])->name('materi.create-materi');
    Route::post('/materi/store', [App\Http\Controllers\Guru\MateriController::class, 'storeMateri'])->name('materi.store-materi');
    Route::get('/materi/{id}', [App\Http\Controllers\Guru\MateriController::class, 'showMateri'])->name('materi.show-materi');
    Route::get('/materi/{id}/edit', [App\Http\Controllers\Guru\MateriController::class, 'editMateri'])->name('materi.edit-materi');
    Route::put('/materi/{id}', [App\Http\Controllers\Guru\MateriController::class, 'updateMateri'])->name('materi.update-materi');
    Route::delete('/materi/{id}', [App\Http\Controllers\Guru\MateriController::class, 'deleteMateri'])->name('materi.delete-materi');
    
    Route::get('/tugas/create', [App\Http\Controllers\Guru\MateriController::class, 'createTugas'])->name('materi.create-tugas');
    Route::post('/tugas/store', [App\Http\Controllers\Guru\MateriController::class, 'storeTugas'])->name('materi.store-tugas');
    Route::get('/tugas/{id}', [App\Http\Controllers\Guru\MateriController::class, 'showTugas'])->name('materi.show-tugas');
    Route::get('/tugas/{id}/edit', [App\Http\Controllers\Guru\MateriController::class, 'editTugas'])->name('materi.edit-tugas');
    Route::put('/tugas/{id}', [App\Http\Controllers\Guru\MateriController::class, 'updateTugas'])->name('materi.update-tugas');
    Route::get('/tugas/{id}/submissions', [App\Http\Controllers\Guru\MateriController::class, 'showTugasSubmissions'])->name('materi.tugas-submissions');
    Route::delete('/tugas/{id}', [App\Http\Controllers\Guru\MateriController::class, 'deleteTugas'])->name('materi.delete-tugas');
});

// Route untuk bel player iframe
Route::get('/bel-player', function () {
    return view('partials.bel-player');
})->name('bel-player');

// Midtrans Payment Routes
Route::prefix('midtrans')->name('midtrans.')->group(function () {
    Route::middleware(['auth:web,siswa,guru'])->group(function () {
        Route::post('/create-payment', [App\Http\Controllers\MidtransController::class, 'createPayment'])->name('create-payment');
        Route::get('/finish', [App\Http\Controllers\MidtransController::class, 'finish'])->name('finish');
        Route::get('/status/{orderId}', [App\Http\Controllers\MidtransController::class, 'status'])->name('status');
    });
    Route::post('/notification', [App\Http\Controllers\MidtransController::class, 'notification'])->name('notification');
});

// Test route for debugging
Route::get('/test-midtrans', function() {
    return response()->json([
        'message' => 'Midtrans routes working',
        'config' => [
            'server_key' => config('midtrans.server_key') ? 'configured' : 'not configured',
            'client_key' => config('midtrans.client_key') ? 'configured' : 'not configured',
            'merchant_id' => config('midtrans.merchant_id') ? 'configured' : 'not configured'
        ]
    ]);
});

// Complete Midtrans Payment Routes
Route::middleware(['auth:web,siswa,guru'])->prefix('midtrans')->name('midtrans.')->group(function () {
    Route::post('/create-payment', [App\Http\Controllers\MidtransController::class, 'createPayment'])->name('create-payment');
    Route::post('/create-transaction', [App\Http\Controllers\MidtransController::class, 'createPayment'])->name('create-transaction');
    Route::post('/complete-mock-payment', [App\Http\Controllers\MidtransController::class, 'completeMockPayment'])->name('complete-mock-payment');
    Route::get('/finish', [App\Http\Controllers\MidtransController::class, 'finish'])->name('finish');
    Route::get('/status/{orderId}', [App\Http\Controllers\MidtransController::class, 'status'])->name('status');
});

// Notification route (no auth needed for webhook)
Route::post('/midtrans/notification', [App\Http\Controllers\MidtransController::class, 'notification'])->name('midtrans.notification');

// Testing route for Midtrans connection
Route::get('/test-midtrans', function() {
    try {
        // Test simple API call first
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $isProduction = config('midtrans.is_production');
        
        // Basic validation
        if (empty($serverKey) || $serverKey === 'your-server-key') {
            return response()->json([
                'status' => 'error',
                'message' => 'Server key tidak dikonfigurasi dengan benar'
            ]);
        }
        
        // Try direct API call with CURL
        $url = $isProduction ? 'https://api.midtrans.com/v2/charge' : 'https://api.sandbox.midtrans.com/v2/charge';
        
        $data = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => 'TEST-' . time(),
                'gross_amount' => 10000
            ],
            'bank_transfer' => [
                'bank' => 'bca'
            ]
        ];
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ),
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ));
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if ($error) {
            return response()->json([
                'status' => 'error',
                'message' => 'CURL Error: ' . $error
            ]);
        }
        
        $responseData = json_decode($response, true);
        
        return response()->json([
            'status' => $httpCode == 200 || $httpCode == 201 ? 'success' : 'error',
            'http_code' => $httpCode,
            'response' => $responseData,
            'server_key_prefix' => substr($serverKey, 0, 15) . '...',
            'is_production' => $isProduction,
            'api_url' => $url
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Exception: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Testing route for new Midtrans service
Route::get('/test-midtrans-new', function () {
    try {
        // Get the first student and tagihan for testing
        $siswa = \App\Models\Siswa::first();
        $tagihan = \App\Models\Tagihan::first();
        
        if (!$siswa || !$tagihan) {
            return response()->json([
                'error' => 'No siswa or tagihan found for testing',
                'siswa_count' => \App\Models\Siswa::count(),
                'tagihan_count' => \App\Models\Tagihan::count()
            ]);
        }
        
        // Test with new service
        $midtransService = new \App\Services\MidtransServiceNew();
        $result = $midtransService->createTransaction($siswa, $tagihan, 50000, 'qris');
        
        return response()->json([
            'status' => 'success',
            'message' => 'New Midtrans service test completed',
            'siswa' => $siswa->nama,
            'tagihan' => $tagihan->nama_tagihan ?? $tagihan->nama ?? 'Unknown',
            'amount' => 50000,
            'method' => 'qris',
            'result' => $result
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test email format generation
Route::get('/test-email-format', function () {
    try {
        // Get siswa dengan kelas
        $siswa = \App\Models\Siswa::with('kelas')->first();
        $tagihan = \App\Models\Tagihan::first();
        
        if (!$siswa || !$tagihan) {
            return response()->json([
                'error' => 'No siswa or tagihan found for testing'
            ]);
        }
        
        // Test dengan new service
        $midtransService = new \App\Services\MidtransServiceNew();
        
        // Get private method untuk test
        $reflection = new ReflectionClass($midtransService);
        $method = $reflection->getMethod('generateCustomerEmail');
        $method->setAccessible(true);
        
        $generatedEmail = $method->invoke($midtransService, $siswa);
        
        return response()->json([
            'status' => 'success',
            'siswa_info' => [
                'nama_lengkap' => $siswa->nama_lengkap,
                'nis' => $siswa->nis ?? $siswa->nisn,
                'kelas' => $siswa->kelas ? $siswa->kelas->nama_kelas : 'No class',
                'original_email' => $siswa->email
            ],
            'generated_email' => $generatedEmail,
            'format' => 'Nama Siswa - Kelas - NIS',
            'format_example' => 'mohamadfadilahakbar-xiitkr1-232410045@student.smk.ac.id'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
