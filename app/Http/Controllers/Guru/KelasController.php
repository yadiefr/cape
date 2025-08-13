<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Traits\GuruAccessTrait;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    use GuruAccessTrait;
    
    public function index()
    {
        $guru = Auth::guard('guru')->user();
        
        // Get classes that this teacher teaches (from jadwal)
        $kelasDiajar = Kelas::with(['jurusan', 'wali'])
            ->whereHas('jadwalPelajaran', function($query) use ($guru) {
                $query->where('guru_id', $guru->id);
            })
            ->distinct()
            ->orderBy('tingkat')
            ->orderBy('nama_kelas')
            ->get();
            
        // Get class where this teacher is homeroom teacher
        $kelasWali = Kelas::with(['jurusan', 'siswa'])
            ->where('wali_kelas', $guru->id)
            ->get();
            
        // Get statistics for each class
        $statistikKelas = [];
        foreach ($kelasDiajar as $kelas) {
            $totalSiswa = $kelas->siswa()->count();
            $jadwalGuru = $guru->jadwal()
                ->where('kelas_id', $kelas->id)
                ->with('mapel')
                ->count();
                
            $statistikKelas[$kelas->id] = [
                'total_siswa' => $totalSiswa,
                'total_jadwal' => $jadwalGuru,
                'is_wali' => $kelas->wali_kelas == $guru->id
            ];
        }
        
        return view('guru.kelas.index', compact('kelasDiajar', 'kelasWali', 'statistikKelas'));
    }
    
    public function show($id)
    {
        $guru = Auth::guard('guru')->user();

        // Optimize kelas query - only load needed relationships and columns
        $kelas = Kelas::with(['jurusan:id,nama_jurusan'])
                     ->select('id', 'nama_kelas', 'jurusan_id', 'wali_kelas')
                     ->findOrFail($id);

        // Check if guru has access to this class using trait method
        if (!$this->hasAccessToKelas($id)) {
            return redirect()->route('guru.dashboard')
                           ->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }

        // Optimize siswa query - only select needed columns
        $siswa = $kelas->siswa()
                      ->select('id', 'nama_lengkap', 'nis', 'jenis_kelamin', 'status', 'foto')
                      ->orderBy('nama_lengkap')
                      ->get();

        // Optimize absensi query - get all attendance data in one query instead of N+1
        $siswaIds = $siswa->pluck('id');

        // Use cache for attendance data (cache for 5 minutes)
        $cacheKey = "kelas_absensi_{$id}_" . md5($siswaIds->implode(','));
        $absensiData = cache()->remember($cacheKey, 300, function() use ($siswaIds) {
            return Absensi::whereIn('siswa_id', $siswaIds)
                          ->whereIn('status', ['hadir', 'izin', 'sakit', 'alpha']) // Ensure only valid statuses
                          ->selectRaw('siswa_id, status, COUNT(*) as total')
                          ->groupBy('siswa_id', 'status')
                          ->get();
        });

        // Build rekap absensi efficiently
        $rekapAbsensi = [];
        foreach ($siswa as $s) {
            $rekapAbsensi[$s->id] = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
            ];
        }

        // Populate with actual data
        foreach ($absensiData as $absensi) {
            if (isset($rekapAbsensi[$absensi->siswa_id])) {
                $rekapAbsensi[$absensi->siswa_id][$absensi->status] = $absensi->total;
            }
        }

        // Debug logging (can be removed in production)
        if (config('app.debug')) {
            \Log::info('Kelas Show Performance', [
                'kelas_id' => $id,
                'siswa_count' => $siswa->count(),
                'absensi_records' => $absensiData->count(),
                'cache_key' => $cacheKey
            ]);
        }
        
        // Get raw jadwal data with caching and optimization
        $jadwalCacheKey = "kelas_jadwal_{$guru->id}_{$id}";
        $jadwalKelasRaw = cache()->remember($jadwalCacheKey, 600, function() use ($guru, $id) {
            return $guru->jadwal()
                        ->where('kelas_id', $id)
                        ->with(['mapel:id,nama']) // Only select needed columns
                        ->select('id', 'guru_id', 'kelas_id', 'mapel_id', 'hari', 'jam_mulai', 'jam_selesai') // Only select needed columns
                        ->orderByRaw("FIELD(hari, 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
                        ->orderBy('jam_mulai')
                        ->get();
        });

        // Group by mata pelajaran and hari to avoid duplicates within same day
        $jadwalGrouped = $jadwalKelasRaw->groupBy(function($item) {
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

            return $first;
        })->values();

        // Group the processed jadwal by day with proper ordering (Senin-Minggu)
        $hariUrutan = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        $jadwalKelas = $jadwalGrouped->groupBy('hari')
                                   ->sortBy(function($group, $hari) use ($hariUrutan) {
                                       return array_search(strtolower($hari), $hariUrutan);
                                   });
        
        return view('guru.kelas.show', compact('kelas', 'siswa', 'rekapAbsensi', 'jadwalKelas'));
    }
}
