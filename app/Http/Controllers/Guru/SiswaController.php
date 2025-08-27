<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Traits\GuruAccessTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    use GuruAccessTrait;

    public function __construct()
    {
        $this->middleware('auth:guru');
    }

    /**
     * Display a listing of students from classes where teacher has subjects or is wali kelas
     */
    public function index(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        
        // Get all kelas IDs that guru has access to (teaching + wali kelas)
        $kelasIds = $this->getAllGuruKelasIds();
        
        // Get mata pelajaran IDs that guru teaches
        $mapelIds = $this->getGuruMapelIds();
        
        if ($kelasIds->isEmpty()) {
            // If no classes found, return empty result
            $siswa = collect();
            $kelasOptions = collect();
            $mapelOptions = collect();
            $totalSiswa = 0;
            $siswaAktif = 0;
            $siswaLakiLaki = 0;
            $siswaPerempuan = 0;
        } else {
            // Get kelas options
            $kelasOptions = Kelas::whereIn('id', $kelasIds)
                                ->where('is_active', true)
                                ->with('jurusan')
                                ->orderBy('nama_kelas')
                                ->get();
            
            $mapelOptions = MataPelajaran::whereIn('id', $mapelIds)
                                       ->orderBy('nama')
                                       ->get();
            
            // Base query for students in teacher's classes
            $query = Siswa::whereIn('kelas_id', $kelasIds)
                         ->with(['kelas', 'kelas.jurusan']);
            
            // Apply filters
            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nisn', 'like', "%{$search}%")
                      ->orWhere('nis', 'like', "%{$search}%");
                });
            }
            
            if ($request->filled('jenis_kelamin')) {
                $query->where('jenis_kelamin', $request->jenis_kelamin);
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Handle sorting
            $sortBy = $request->get('sort_by', 'kelas'); // default sort by kelas
            $sortOrder = $request->get('sort_order', 'asc');
            
            switch ($sortBy) {
                case 'nama':
                    $query->orderBy('nama_lengkap', $sortOrder);
                    break;
                case 'kelas':
                    $query->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                          ->orderBy('kelas.nama_kelas', $sortOrder)
                          ->orderBy('siswa.nama_lengkap', 'asc') // secondary sort
                          ->select('siswa.*'); // only select siswa columns
                    break;
                case 'nisn':
                    $query->orderBy('nisn', $sortOrder);
                    break;
                case 'jenis_kelamin':
                    $query->orderBy('jenis_kelamin', $sortOrder)
                          ->orderBy('nama_lengkap', 'asc'); // secondary sort
                    break;
                case 'status':
                    $query->orderBy('status', $sortOrder)
                          ->orderBy('nama_lengkap', 'asc'); // secondary sort
                    break;
                default:
                    // Default sort by kelas name then student name
                    $query->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                          ->orderBy('kelas.nama_kelas', 'asc')
                          ->orderBy('siswa.nama_lengkap', 'asc')
                          ->select('siswa.*');
                    break;
            }
            
            $siswa = $query->get(); // Remove pagination, get all results
            
            // Get statistics
            $totalSiswa = Siswa::whereIn('kelas_id', $kelasIds)->count();
            $siswaAktif = Siswa::whereIn('kelas_id', $kelasIds)->where('status', 'aktif')->count();
            $siswaLakiLaki = Siswa::whereIn('kelas_id', $kelasIds)->where('jenis_kelamin', 'L')->count();
            $siswaPerempuan = Siswa::whereIn('kelas_id', $kelasIds)->where('jenis_kelamin', 'P')->count();
        }
        
        return view('guru.siswa.index', compact(
            'siswa', 
            'kelasOptions', 
            'mapelOptions',
            'totalSiswa',
            'siswaAktif',
            'siswaLakiLaki',
            'siswaPerempuan'
        ));
    }

    /**
     * Debug method to check guru access
     */
    public function debug()
    {
        $guru = Auth::guard('guru')->user();
        
        // Get raw jadwal data
        $rawJadwal = JadwalPelajaran::where('guru_id', $guru->id)
                                   ->where('is_active', true)
                                   ->with(['kelas', 'mapel'])
                                   ->get();
        
        // Get processed kelas IDs
        $kelasIds = $this->getAllGuruKelasIds();
        
        // Get wali kelas IDs if applicable
        $waliKelasIds = collect();
        if ($guru->is_wali_kelas) {
            $waliKelasIds = \App\Models\Kelas::where('wali_kelas', $guru->id)->pluck('id');
        }
        
        // Count students per kelas
        $studentsPerKelas = [];
        foreach($kelasIds as $kelasId) {
            $count = Siswa::where('kelas_id', $kelasId)->count();
            $kelas = Kelas::find($kelasId);
            $studentsPerKelas[] = [
                'kelas_id' => $kelasId,
                'kelas_nama' => $kelas ? $kelas->nama_kelas : 'Unknown',
                'student_count' => $count
            ];
        }
        
        return view('guru.siswa.debug', compact(
            'guru',
            'rawJadwal',
            'kelasIds',
            'waliKelasIds',
            'studentsPerKelas'
        ));
    }

    /**
     * Display the specified student detail
     */
    public function show($id)
    {
        $guru = Auth::guard('guru')->user();
        
        // Get all kelas IDs that guru has access to (teaching + wali kelas)
        $kelasIds = $this->getAllGuruKelasIds();
        
        // Find student and verify access
        $siswa = Siswa::with(['kelas', 'kelas.jurusan'])
                     ->whereIn('kelas_id', $kelasIds)
                     ->findOrFail($id);
        
        // Get teacher's subjects for this student's class (only if guru teaches in this class)
        $jadwalMapelRaw = JadwalPelajaran::where('guru_id', $guru->id)
                                         ->where('kelas_id', $siswa->kelas_id)
                                         ->with('mapel')
                                         ->get();

        // Group by mata pelajaran and hari to avoid duplicates within same day
        // Key: "mapel_name_hari" to group same subject on same day only
        $jadwalMapel = $jadwalMapelRaw->groupBy(function($item) {
            return $item->mapel->nama . '_' . $item->hari;
        })->map(function($group) {
            // Take the first schedule for each mata pelajaran per day
            $first = $group->first();

            // Combine all time slots for the same subject on the same day
            $jamMulaiList = $group->pluck('jam_mulai')->sort()->values();
            $jamSelesaiList = $group->pluck('jam_selesai')->sort()->values();

            // Create a combined time display
            $first->jam_gabungan = $jamMulaiList->first() . '-' . $jamSelesaiList->last();
            $first->total_jam = $group->count();

            // Add day-specific identifier for better organization
            $first->mapel_hari_key = $first->mapel->nama . ' - ' . $first->hari;

            return $first;
        })->values();
        
        // Get attendance summary for this student in teacher's subjects
        $rekapAbsensi = [];
        foreach ($jadwalMapel as $jadwal) {
            if (!$jadwal->mapel) continue; // Skip if mapel is null
            
            $absensi = Absensi::where('siswa_id', $siswa->id)
                             ->where('mapel_id', $jadwal->mapel_id)
                             ->selectRaw('
                                 status,
                                 COUNT(*) as jumlah
                             ')
                             ->groupBy('status')
                             ->get()
                             ->pluck('jumlah', 'status');
            
            $rekapAbsensi[$jadwal->mapel->nama] = [
                'hadir' => $absensi['hadir'] ?? 0,
                'izin' => $absensi['izin'] ?? 0,
                'sakit' => $absensi['sakit'] ?? 0,
                'alpha' => $absensi['alpha'] ?? 0,
            ];
        }
        
        // Get grades for this student in teacher's subjects
        $nilaiMapel = [];
        foreach ($jadwalMapel as $jadwal) {
            if (!$jadwal->mapel) continue; // Skip if mapel is null
            
            $nilai = Nilai::where('siswa_id', $siswa->id)
                         ->where('mapel_id', $jadwal->mapel_id)
                         ->orderBy('created_at', 'desc')
                         ->get();
            
            if ($nilai->count() > 0) {
                $nilaiMapel[$jadwal->mapel->nama] = $nilai;
            }
        }
        
        // Get recent attendance records
        $recentAbsensi = Absensi::where('siswa_id', $siswa->id)
                               ->whereIn('mapel_id', $jadwalMapelRaw->pluck('mapel_id'))
                               ->with('mapel')
                               ->orderBy('tanggal', 'desc')
                               ->limit(10)
                               ->get();
        
        return view('guru.siswa.show', compact(
            'siswa', 
            'jadwalMapel', 
            'rekapAbsensi', 
            'nilaiMapel',
            'recentAbsensi'
        ));
    }

    /**
     * Get students by class (AJAX)
     */
    public function getByKelas(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelasId = $request->kelas_id;
        
        // Verify teacher has access to this class
        $hasAccess = JadwalPelajaran::where('guru_id', $guru->id)
                                   ->where('kelas_id', $kelasId)
                                   ->exists();
        
        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke kelas ini'
            ], 403);
        }
        
        $siswa = Siswa::where('kelas_id', $kelasId)
                     ->orderBy('nama_lengkap')
                     ->get(['id', 'nama_lengkap', 'nisn', 'nis']);
        
        return response()->json([
            'success' => true,
            'data' => $siswa
        ]);
    }

    /**
     * Get student attendance summary for specific subject
     */
    public function getAttendanceSummary(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $siswaId = $request->siswa_id;
        $mapelId = $request->mapel_id;
        
        // Verify teacher teaches this subject
        $hasAccess = JadwalPelajaran::where('guru_id', $guru->id)
                                   ->where('mapel_id', $mapelId)
                                   ->exists();
        
        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak mengajar mata pelajaran ini'
            ], 403);
        }
        
        $absensi = Absensi::where('siswa_id', $siswaId)
                         ->where('mapel_id', $mapelId)
                         ->selectRaw('
                             status,
                             COUNT(*) as jumlah
                         ')
                         ->groupBy('status')
                         ->get()
                         ->pluck('jumlah', 'status');
        
        return response()->json([
            'success' => true,
            'data' => [
                'hadir' => $absensi['hadir'] ?? 0,
                'izin' => $absensi['izin'] ?? 0,
                'sakit' => $absensi['sakit'] ?? 0,
                'alpha' => $absensi['alpha'] ?? 0,
            ]
        ]);
    }

    /**
     * Export students data to Excel
     */
    public function export(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        
        // Get all classes where this teacher has schedule
        $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)
                                  ->distinct()
                                  ->pluck('kelas_id');
        
        $query = Siswa::whereIn('kelas_id', $kelasIds)
                     ->with(['kelas', 'kelas.jurusan']);
        
        // Apply same filters as index
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }
        
        $siswa = $query->orderBy('nama_lengkap')->get();
        
        // Here you would implement the Excel export logic
        // For now, we'll just return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Export functionality will be implemented',
            'count' => $siswa->count()
        ]);
    }
}
