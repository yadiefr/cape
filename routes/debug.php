<?php

use Illuminate\Support\Facades\Route;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Settings;
use App\Models\KasKelas;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

// Debug route untuk cek kas data
Route::get('/debug-kas', function() {
    // Get kas kelas data
    $kasData = KasKelas::tipeMasuk()
                      ->where('kategori', 'iuran_bulanan')
                      ->with(['siswa', 'kelas'])
                      ->orderBy('siswa_id')
                      ->get();
    
    echo "<h2>Kas Kelas Data (Iuran Bulanan)</h2>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Siswa ID</th><th>Siswa Nama</th><th>Kelas ID</th><th>Nominal</th><th>Tanggal</th></tr>";
    
    foreach($kasData as $kas) {
        echo "<tr>";
        echo "<td>{$kas->id}</td>";
        echo "<td>{$kas->siswa_id}</td>";
        echo "<td>" . ($kas->siswa ? $kas->siswa->nama_lengkap : 'N/A') . "</td>";
        echo "<td>{$kas->kelas_id}</td>";
        echo "<td>{$kas->nominal}</td>";
        echo "<td>{$kas->tanggal}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Get kelas and siswa data
    echo "<br><h2>Kelas dan Siswa Data</h2>";
    $kelasData = Kelas::with('siswa')->get();
    
    foreach($kelasData as $kelas) {
        echo "<h3>Kelas: {$kelas->nama_kelas}</h3>";
        echo "<ul>";
        foreach($kelas->siswa as $siswa) {
            $kasCount = KasKelas::tipeMasuk()
                              ->where('kategori', 'iuran_bulanan')
                              ->where('siswa_id', $siswa->id)
                              ->where('kelas_id', $kelas->id)
                              ->count();
            $kasTotal = KasKelas::tipeMasuk()
                              ->where('kategori', 'iuran_bulanan')
                              ->where('siswa_id', $siswa->id)
                              ->where('kelas_id', $kelas->id)
                              ->sum('nominal');
            echo "<li>{$siswa->nama_lengkap} (ID: {$siswa->id}) - Kas: {$kasCount} transaksi, Total: Rp " . number_format($kasTotal, 0, ',', '.') . "</li>";
        }
        echo "</ul>";
    }
});

// Debug route untuk cek jadwal
Route::get('/debug-jadwal/{guru_id?}', function($guru_id = null) {
    $guru_ids = $guru_id ? [$guru_id] : [26, 28, 34, 35, 37];
    
    // Get current settings
    $semester = Settings::getValue('semester_aktif', 1);
    $tahun_ajaran = Settings::getValue('tahun_ajaran', '2025/2026');
    
    $debug_info = [
        'current_settings' => [
            'semester' => $semester,
            'tahun_ajaran' => $tahun_ajaran
        ],
        'guru_data' => []
    ];
    
    foreach ($guru_ids as $gid) {
        $guru = Guru::find($gid);
        if (!$guru) continue;

        $all_jadwal = JadwalPelajaran::where('guru_id', $gid)
            ->where('is_active', true)
            ->get();
        
        $current_jadwal = JadwalPelajaran::where('guru_id', $gid)
            ->where('is_active', true)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->get();
        
        $scheduled = JadwalPelajaran::where('guru_id', $gid)
            ->where('is_active', true)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->scheduled()
            ->get();
            
        $assignments = JadwalPelajaran::where('guru_id', $gid)
            ->where('is_active', true)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->assignments()
            ->get();
            
        $debug_info['guru_data'][$gid] = [
            'nama' => $guru->nama_lengkap,
            'total_jadwal' => $all_jadwal->count(),
            'current_semester_jadwal' => $current_jadwal->count(),
            'scheduled_jadwal' => $scheduled->count(),
            'assignment_jadwal' => $assignments->count(),
            'sample_records' => $current_jadwal->take(3)->map(function($j) {
                return [
                    'id' => $j->id,
                    'hari' => $j->hari,
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                    'semester' => $j->semester,
                    'tahun_ajaran' => $j->tahun_ajaran,
                    'kelas' => $j->kelas ? $j->kelas->nama_kelas : 'N/A',
                    'mapel' => $j->mapel ? $j->mapel->nama_mapel : 'N/A'
                ];
            })->toArray()
        ];
    }
    
    return response()->json($debug_info, 200, [], JSON_PRETTY_PRINT);
});

// Route debugging untuk guru access
Route::get('/debug-guru-access/{guru_id?}', function($guru_id = 37) {
    $guru = Guru::find($guru_id);
    if (!$guru) {
        return "Guru with ID {$guru_id} not found";
    }
    
    // Simulate login
    Auth::guard('guru')->setUser($guru);
    
    // Test the controller
    $controller = new \App\Http\Controllers\Guru\SiswaController();
    
    // Get data similar to index method
    $kelasIds = $controller->getAllGuruKelasIds();
    $mapelIds = $controller->getGuruMapelIds();
    
    $kelasOptions = \App\Models\Kelas::whereIn('id', $kelasIds)
                                    ->where('is_active', true)
                                    ->with('jurusan')
                                    ->orderBy('nama_kelas')
                                    ->get();
    
    $siswa = \App\Models\Siswa::whereIn('kelas_id', $kelasIds)
                             ->with(['kelas', 'kelas.jurusan'])
                             ->orderBy('nama_lengkap')
                             ->get();
    
    $stats = [
        'total_siswa' => $siswa->count(),
        'kelas_count' => $kelasOptions->count(),
        'mapel_count' => $mapelIds->count()
    ];
    
    return response()->json([
        'guru' => [
            'id' => $guru->id,
            'nama' => $guru->nama,
            'is_wali_kelas' => $guru->is_wali_kelas
        ],
        'kelas_ids' => $kelasIds->toArray(),
        'mapel_ids' => $mapelIds->toArray(),
        'kelas_options' => $kelasOptions->map(function($kelas) {
            return [
                'id' => $kelas->id,
                'nama' => $kelas->nama_kelas,
                'is_active' => $kelas->is_active
            ];
        }),
        'siswa_sample' => $siswa->take(5)->map(function($s) {
            return [
                'id' => $s->id,
                'nama' => $s->nama_lengkap,
                'kelas' => $s->kelas->nama_kelas ?? 'N/A'
            ];
        }),
        'statistics' => $stats
    ], 200, [], JSON_PRETTY_PRINT);
});

// Route debugging untuk jadwal
Route::get('/debug-jadwal/{siswa_id?}', function($siswa_id = null) {
    if (!$siswa_id) {
        $siswa = \App\Models\Siswa::first();
    } else {
        $siswa = \App\Models\Siswa::find($siswa_id);
    }
    
    if (!$siswa) {
        return "Siswa tidak ditemukan";
    }
    
    $hariIni = Carbon::now()->dayName;
    $hariMapping = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa', 
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    ];
    $hariIniIndo = $hariMapping[$hariIni];
    
    $allJadwal = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
                               ->where('kelas_id', $siswa->kelas_id)
                               ->where('is_active', true)
                               ->get();
    
    $jadwalHariIni = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
                                   ->where('kelas_id', $siswa->kelas_id)
                                   ->where('is_active', true)
                                   ->where(function($query) use ($hariIniIndo, $hariIni) {
                                       $query->where('hari', $hariIniIndo)
                                             ->orWhere('hari', strtolower($hariIniIndo))
                                             ->orWhere('hari', strtoupper($hariIniIndo))
                                             ->orWhere('hari', $hariIni)
                                             ->orWhere('hari', strtolower($hariIni))
                                             ->orWhere('hari', strtoupper($hariIni));
                                   })
                                   ->get();
    
    return [
        'siswa' => [
            'id' => $siswa->id,
            'nama' => $siswa->nama_lengkap,
            'kelas_id' => $siswa->kelas_id,
            'kelas_nama' => $siswa->kelas->nama_kelas ?? 'N/A'
        ],
        'hari_info' => [
            'hari_en' => $hariIni,
            'hari_indo' => $hariIniIndo,
            'tanggal' => Carbon::now()->toDateString()
        ],
        'jadwal_info' => [
            'total_jadwal_kelas' => $allJadwal->count(),
            'jadwal_hari_ini' => $jadwalHariIni->count(),
            'hari_tersedia' => $allJadwal->pluck('hari')->unique()->values()->toArray()
        ],
        'all_jadwal' => $allJadwal->map(function($j) {
            return [
                'id' => $j->id,
                'mapel' => $j->mapel->nama ?? 'N/A',
                'guru' => $j->guru->nama ?? 'N/A', 
                'hari' => $j->hari,
                'jam_mulai' => $j->jam_mulai,
                'jam_selesai' => $j->jam_selesai,
                'ruangan' => $j->ruangan ?? 'N/A',
                'is_active' => $j->is_active
            ];
        }),
        'jadwal_hari_ini' => $jadwalHariIni->map(function($j) {
            return [
                'id' => $j->id,
                'mapel' => $j->mapel->nama ?? 'N/A',
                'guru' => $j->guru->nama ?? 'N/A',
                'hari' => $j->hari,
                'jam_mulai' => $j->jam_mulai,
                'jam_selesai' => $j->jam_selesai,
                'ruangan' => $j->ruangan ?? 'N/A'
            ];
        })
    ];
});
