<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Pengumuman;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Guru;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{    public function index()
    {
        // Check which guard is authenticated and redirect to appropriate dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        } elseif (Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }

        // If no one is logged in, redirect to login page with message
        return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengakses dashboard.');
    }    public function adminDashboard()
    {
        $totalSiswa = Siswa::count();
        $totalGuru = Guru::count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();
        $totalJadwal = JadwalPelajaran::count();
        $pengumuman = Pengumuman::with('author')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get latest agenda items
        $agenda = \App\Models\Agenda::latest()->take(5)->get();
        
        $chartData = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'data' => [150, 200, 180, 250, 220, 300],
        ];
        $activities = collect([]);
        
        return view('admin.dashboard', compact('totalSiswa', 'totalGuru', 'totalKelas', 'totalJurusan', 'totalJadwal', 'pengumuman', 'chartData', 'activities', 'agenda'));
    }    public function guruDashboard()
    {
        $guru = auth()->guard('guru')->user();
        
        // Get classes taught by teacher (from jadwal), not just homeroom classes
        $kelasDiajar = Kelas::with(['jurusan'])
            ->whereHas('jadwalPelajaran', function($query) use ($guru) {
                $query->where('guru_id', $guru->id);
            })
            ->distinct()
            ->orderBy('nama_kelas', 'asc')
            ->get();
            
        // Get classes where this teacher is the homeroom teacher (wali kelas)
        $kelasWali = Kelas::with(['jurusan'])
            ->where('wali_kelas', $guru->id)
            ->orderBy('nama_kelas', 'asc')
            ->get();
            
        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'guru'])
            ->where('guru_id', $guru->id)
            ->get();
        
        // Get today's schedule for this teacher
        $jadwalHariIni = JadwalPelajaran::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->where('hari', strtolower(now()->format('l')))
            ->whereHas('mapel') // Only get schedules with valid mapel
            ->whereHas('kelas') // Only get schedules with valid kelas
            ->orderBy('jam_mulai')
            ->get();
            
        $pengumuman = Pengumuman::where('target_role', 'guru')
            ->orWhere('target_role', 'all')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get calendar events data for current month
        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->addMonth()->startOfMonth();
        
        // Get agenda events
        $agendaEvents = \App\Models\Agenda::where('is_active', true)
            ->where(function($query) use ($currentMonth, $nextMonth) {
                $query->whereBetween('tanggal_mulai', [$currentMonth, $nextMonth])
                      ->orWhereBetween('tanggal_selesai', [$currentMonth, $nextMonth]);
            })
            ->get()
            ->map(function($agenda) {
                return [
                    'date' => $agenda->tanggal_mulai->format('Y-m-d'),
                    'type' => 'agenda',
                    'title' => $agenda->judul,
                    'description' => $agenda->deskripsi,
                    'time' => $agenda->tanggal_mulai->format('H:i')
                ];
            });
            
        // Get jadwal events (schedule for the teacher)
        $jadwalEvents = JadwalPelajaran::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->whereNotNull('hari')
            ->get()
            ->flatMap(function($jadwal) use ($currentMonth, $nextMonth) {
                $events = [];
                $startDate = $currentMonth->copy();
                $endDate = $nextMonth->copy();
                
                // Generate events for each occurrence of the day in the month
                while ($startDate->lt($endDate)) {
                    $dayName = strtolower($startDate->format('l'));
                    if ($dayName === strtolower($jadwal->hari)) {
                        $events[] = [
                            'date' => $startDate->format('Y-m-d'),
                            'type' => 'jadwal',
                            'title' => $jadwal->mapel->nama_mapel ?? 'Mata Pelajaran',
                            'description' => 'Kelas: ' . ($jadwal->kelas->nama_kelas ?? 'N/A'),
                            'time' => $jadwal->jam_mulai . ' - ' . $jadwal->jam_selesai
                        ];
                    }
                    $startDate->addDay();
                }
                return $events;
            });
            
        // Get pengumuman events (announcements with specific dates)
        $pengumumanEvents = Pengumuman::where('is_active', true)
            ->where(function($query) {
                $query->where('target_role', 'guru')
                      ->orWhere('target_role', 'all');
            })
            ->whereBetween('created_at', [$currentMonth, $nextMonth])
            ->get()
            ->map(function($pengumuman) {
                return [
                    'date' => $pengumuman->created_at->format('Y-m-d'),
                    'type' => 'pengumuman',
                    'title' => $pengumuman->judul,
                    'description' => strip_tags($pengumuman->isi),
                    'time' => $pengumuman->created_at->format('H:i')
                ];
            });
            
        // Combine all events
        $calendarEvents = $agendaEvents->concat($jadwalEvents)->concat($pengumumanEvents)
            ->groupBy('date')
            ->map(function($events, $date) {
                return [
                    'date' => $date,
                    'events' => $events->toArray(),
                    'hasEvents' => true
                ];
            });
            
        return view('guru.dashboard', compact('guru', 'kelasDiajar', 'kelasWali', 'jadwal', 'jadwalHariIni', 'pengumuman', 'calendarEvents'));
    }



    public function admin()
    {
        return $this->adminDashboard();
    }

    public function guru()
    {
        $jurusan = Jurusan::all();
        return view('guru.dashboard', compact('jurusan'));
    }

    public function siswa()
    {
        return $this->siswaDashboard();
    }

    public function siswaDashboard()
    {
        $siswa = auth()->guard('siswa')->user();
        if (!$siswa) {
            return redirect()->route('siswa.login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $kelas = Kelas::find($siswa->kelas_id);

        // Get today's schedule
        $jadwalHariIni = JadwalPelajaran::with(['mapel', 'guru'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', strtolower(now()->format('l')))
            ->orderBy('jam_mulai')
            ->get();

        // Calculate attendance rate for current month
        $bulanIni = now()->month;
        $totalHadir = Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulanIni)
            ->where('status', 'hadir')
            ->count();
        $totalAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulanIni)
            ->count();
        $attendanceRate = $totalAbsensi > 0 ? round(($totalHadir / $totalAbsensi) * 100, 1) : 0;

        // Get recent grades with eager loading and null checks
        $recentGrades = Nilai::with(['mapel' => function($query) {
            $query->withDefault([
                'nama' => 'Mata Pelajaran Tidak Ditemukan',
                'kode' => 'N/A'
            ]);
        }])
        ->where('siswa_id', $siswa->id)
        ->whereNotNull('nilai_akhir')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();

        // Calculate grade average for current semester with null handling
        $gradeAverage = Nilai::where('siswa_id', $siswa->id)
            ->where('semester', \App\Models\Settings::getValue('semester_aktif'))
            ->where('tahun_ajaran', \App\Models\Settings::getValue('tahun_ajaran'))
            ->whereNotNull('nilai_akhir')
            ->avg('nilai_akhir') ?? 0;
        $gradeAverage = round($gradeAverage, 1);

        // Get announcements for students
        $pengumuman = Pengumuman::where(function($query) {
                $query->where('target_role', 'siswa')
                    ->orWhere('target_role', 'all');
            })
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard.siswa', compact(
            'siswa', 
            'kelas', 
            'jadwalHariIni', 
            'attendanceRate', 
            'gradeAverage', 
            'recentGrades',
            'pengumuman'
        ));
    }
}