<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\TugasSiswa;
use App\Models\JadwalPelajaran;
use App\Models\Settings;
use App\Models\Absensi;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Load relasi kelas dan jurusan untuk tampilan dashboard
        $siswa->load(['kelas', 'jurusan']);
        
        // Pastikan siswa memiliki kelas
        if (!$siswa->kelas_id) {
            return view('siswa.dashboard', [
                'totalMataPelajaran' => 0,
                'tugasPending' => 0,
                'rataNilai' => 0,
                'kehadiran' => 0,
                'jadwalHariIni' => collect(),
                'pengumuman' => collect(),
                'tugasTerbaru' => collect(),
                'nilaiTerbaru' => collect()
            ]);
        }
        
        try {
            // Get active semester and tahun_ajaran from settings (same as admin)
            $semester = Settings::getValue('semester_aktif', 1);
            $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
            
            // Statistik untuk cards
            $totalMataPelajaran = JadwalPelajaran::where('kelas_id', $siswa->kelas_id)
                                               ->where('semester', $semester)
                                               ->where('tahun_ajaran', $tahun_ajaran)
                                               ->where('is_active', true)
                                               ->distinct('mapel_id')
                                               ->count();
            
            $tugasPending = Tugas::where('kelas_id', $siswa->kelas_id)
                                ->where('is_active', true)
                                ->whereDoesntHave('tugasSiswa', function($q) use ($siswa) {
                                    $q->where('siswa_id', $siswa->id);
                                })
                                ->count();
            
            // Rata-rata nilai siswa
            $rataNilai = TugasSiswa::where('siswa_id', $siswa->id)
                                  ->whereNotNull('nilai')
                                  ->avg('nilai');
            $rataNilai = $rataNilai ? round($rataNilai, 1) : 0;
            
            // Kehadiran bulan ini berdasarkan data absensi
            $bulanIni = Carbon::now()->startOfMonth();
            $akhirBulan = Carbon::now()->endOfMonth();
            
            // Hitung total absensi bulan ini
            $totalAbsensi = Absensi::where('siswa_id', $siswa->id)
                                  ->whereBetween('tanggal', [$bulanIni, $akhirBulan])
                                  ->count();
            
            // Hitung total kehadiran (hadir + izin + sakit)
            $totalHadir = Absensi::where('siswa_id', $siswa->id)
                                ->whereBetween('tanggal', [$bulanIni, $akhirBulan])
                                ->whereIn('status', ['hadir', 'izin', 'sakit'])
                                ->count();
            
            // Hitung persentase kehadiran
            $kehadiran = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;

            // Ambil jadwal hari ini
            $today = Carbon::now();
            $hariIni = $today->locale('id')->dayName;
            
            // Ambil jadwal untuk kelas siswa
            $allJadwalKelas = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
                                           ->where('kelas_id', $siswa->kelas_id)
                                           ->get();
            
            // Filter jadwal untuk hari ini
            $jadwalHariIni = $allJadwalKelas->filter(function($jadwal) use ($hariIni, $today) {
                $jadwalHari = trim($jadwal->hari);
                $hariEn = $today->dayName;
                
                return strtolower($jadwalHari) === strtolower($hariIni) ||
                       strtolower($jadwalHari) === strtolower($hariEn) ||
                       ucfirst(strtolower($jadwalHari)) === ucfirst(strtolower($hariIni)) ||
                       ucfirst(strtolower($jadwalHari)) === ucfirst(strtolower($hariEn)) ||
                       strpos(strtolower($jadwalHari), strtolower($hariIni)) !== false ||
                       strpos(strtolower($jadwalHari), strtolower($hariEn)) !== false;
            })->sortBy('jam_mulai')->values();

            // Pengumuman terbaru
            $pengumuman = Pengumuman::where('is_active', true)
                                  ->where(function($query) {
                                      $query->whereNull('tanggal_selesai')
                                          ->orWhere('tanggal_selesai', '>=', now());
                                  })
                                  ->where('tanggal_mulai', '<=', now())
                                  ->where(function($query) {
                                      $query->where('target_role', 'all')
                                            ->orWhere('target_role', 'siswa');
                                  })
                                  ->orderBy('created_at', 'desc')
                                  ->take(3)
                                  ->get();

            // Tugas terbaru untuk kelas siswa
            $tugasTerbaru = Tugas::with(['mapel', 'guru'])
                                ->where('kelas_id', $siswa->kelas_id)
                                ->where('is_active', true)
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get()
                                ->map(function($tugas) use ($siswa) {
                                    // Check if student has submitted this task
                                    $submission = TugasSiswa::where('tugas_id', $tugas->id)
                                                           ->where('siswa_id', $siswa->id)
                                                           ->first();
                                    
                                    $tugas->status_pengerjaan = $submission ? $submission->status : 'pending';
                                    $tugas->tanggal_submit = $submission ? $submission->tanggal_submit : null;
                                    $tugas->nilai = $submission ? $submission->nilai : null;
                                    
                                    return $tugas;
                                });
                                
            // Nilai terbaru siswa
            $nilaiTerbaru = Nilai::with('mapel')
                                ->where('siswa_id', $siswa->id)
                                ->whereNotNull('nilai')
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->map(function($nilai) {
                                    // Add mata_pelajaran attribute using relation
                                    $nilai->mata_pelajaran = $nilai->mapel ? $nilai->mapel->nama_mapel : 'Mata Pelajaran';
                                    $nilai->tanggal_input = $nilai->created_at;
                                    return $nilai;
                                });
        
        } catch (\Exception $e) {
            // If there are any errors, provide default values
            $totalMataPelajaran = 0;
            $tugasPending = 0;
            $rataNilai = 0;
            $kehadiran = 0;
            $jadwalHariIni = collect();
            $pengumuman = collect();
            $tugasTerbaru = collect();
            $nilaiTerbaru = collect();
        }
        
        return view('siswa.dashboard', compact(
            'totalMataPelajaran',
            'tugasPending', 
            'rataNilai',
            'kehadiran',
            'jadwalHariIni',
            'pengumuman',
            'tugasTerbaru',
            'nilaiTerbaru'
        ));
    }
}
