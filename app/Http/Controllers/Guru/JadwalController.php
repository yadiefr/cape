<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Settings;
use App\Models\SettingsJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    public function debug()
    {
        $guru = auth()->guard('guru')->user();
        
        $jadwal = JadwalPelajaran::where('guru_id', $guru->id)
            ->with(['mapel', 'kelas.jurusan'])
            ->whereNotNull('hari')
            ->get();
            
        \Log::info('Debug method - Final jadwal count: ' . $jadwal->count());
        
        return view('guru.jadwal.debug', compact('jadwal'));
    }

    public function index()
    {
        $guru = Auth::guard('guru')->user();
        
        // Debug: Check if there are any schedules for this teacher
        $allJadwalForGuru = JadwalPelajaran::where('guru_id', $guru->id)->get();
        \Log::info('Total jadwal for guru ' . $guru->id . ': ' . $allJadwalForGuru->count());
        
        // Get active semester and tahun_ajaran from settings (same as admin)
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        \Log::info('Active semester: ' . $semester . ', Tahun ajaran: ' . $tahun_ajaran);
        
        // Debug: Check jadwal for current semester/tahun_ajaran
        $currentSemesterJadwal = JadwalPelajaran::where('guru_id', $guru->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('is_active', true)
            ->get();
        \Log::info('Jadwal for current semester/tahun: ' . $currentSemesterJadwal->count());
        
        // Debug: Check if there are assignments for this teacher
        $assignments = JadwalPelajaran::where('guru_id', $guru->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('is_active', true)
            ->whereNull('hari')
            ->get();
        \Log::info('Assignments for guru ' . $guru->id . ': ' . $assignments->count());
        
        // Debug: Check jadwal with hari
        $jadwalWithHari = JadwalPelajaran::where('guru_id', $guru->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('is_active', true)
            ->whereNotNull('hari')
            ->get();
        \Log::info('Jadwal with hari: ' . $jadwalWithHari->count());
        
        // Show sample data structure
        if ($currentSemesterJadwal->count() > 0) {
            $sample = $currentSemesterJadwal->first();
            \Log::info('Sample jadwal data: ', $sample->toArray());
        }
        
        // Get all jadwal for this guru - try to get jadwal with hari information (scheduled by admin)
        $jadwal = JadwalPelajaran::with(['mapel', 'kelas', 'kelas.jurusan'])
            ->where('guru_id', $guru->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->where('is_active', true)
            ->whereNotNull('hari') // At minimum, must have 'hari' set by admin
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get();

        \Log::info('Initial jadwal query result: ' . $jadwal->count());

        $use_assignments = false;

        // If no jadwal with hari found, try to get assignments
        if ($jadwal->isEmpty()) {
            \Log::info('No jadwal with hari found, trying assignments...');
            $assignments_data = JadwalPelajaran::with(['mapel', 'kelas', 'kelas.jurusan'])
                ->where('guru_id', $guru->id)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->where('is_active', true)
                ->whereNull('hari') // Assignments don't have hari set
                ->get();
            
            \Log::info('Assignments found: ' . $assignments_data->count());
            
            if ($assignments_data->isNotEmpty()) {
                $jadwal = $assignments_data;
                $use_assignments = true;
                \Log::info('Using assignments as jadwal data');
            }
        }

        \Log::info('Final jadwal count: ' . $jadwal->count());
        \Log::info('Using assignments: ' . ($use_assignments ? 'Yes' : 'No'));
        
        // Debug: Show detailed jadwal data
        if ($jadwal->count() > 0) {
            \Log::info('Jadwal data sample:');
            foreach ($jadwal->take(3) as $j) {
                \Log::info("- ID: {$j->id}, Hari: {$j->hari}, Mapel: " . ($j->mapel ? $j->mapel->nama : 'N/A') . ", Kelas: " . ($j->kelas ? $j->kelas->nama_kelas : 'N/A'));
            }
        } else {
            \Log::info('No jadwal data found!');
        }
        
        // Temporary debug - uncomment to see data
        // dd([
        //     'jadwal_count' => $jadwal->count(),
        //     'jadwal_sample' => $jadwal->take(2)->toArray(),
        //     'use_assignments' => $use_assignments,
        //     'debug_info' => $debugInfo
        // ]);
        
        // Update debug info
        $debugInfo = [
            'guru_id' => $guru->id,
            'guru_nama' => $guru->nama_lengkap,
            'total_entries' => $allJadwalForGuru->count(),
            'current_semester_entries' => $currentSemesterJadwal->count(),
            'assignments' => $assignments->count(),
            'with_hari' => $jadwalWithHari->count(),
            'final_jadwal_count' => $jadwal->count(),
            'scheduled' => $use_assignments ? 0 : $jadwal->count(), // Add this key back for view compatibility
            'active_semester' => $semester,
            'active_tahun_ajaran' => $tahun_ajaran,
            'use_assignments' => $use_assignments
        ];

        // Debug: Check sample jadwal data structure
        if ($allJadwalForGuru->isNotEmpty()) {
            $sample = $allJadwalForGuru->first();
            \Log::info('Sample jadwal fields: ', $sample->toArray());
        }
        
        // Group jadwal by day (only if not using assignments)
        $jadwalPerHari = collect();
        $jadwalHariIni = collect();
        
        if (!$use_assignments && $jadwal->isNotEmpty()) {
            $jadwalPerHari = $jadwal->groupBy('hari');
            
            // Get today's schedule
            $hariIni = strtolower(Carbon::now()->locale('id')->dayName);
            $jadwalHariIni = $jadwal->where('hari', $hariIni);
        }

        // Get today's name for later use
        $hariIni = strtolower(Carbon::now()->locale('id')->dayName);

        // Get statistics
        $totalJadwal = $jadwal->count();
        $totalKelas = $jadwal->unique('kelas_id')->count();
        $totalMapel = $jadwal->unique('mapel_id')->count();
        
        // Calculate total teaching hours per week (JPL = Jam Pelajaran)
        $totalJam = 0;
        if (!$use_assignments) {
            foreach($jadwal as $j) {
                // Each schedule entry represents 1 JPL (45 minutes)
                if ($j->jam_mulai && $j->jam_selesai) {
                    $start = Carbon::parse($j->jam_mulai);
                    $end = Carbon::parse($j->jam_selesai);
                    
                    // Make sure end time is after start time to avoid negative values
                    if ($end > $start) {
                        $durationInMinutes = $end->diffInMinutes($start);
                        $jpl = round($durationInMinutes / 45); // 45 minutes = 1 JPL
                        $totalJam += max(1, $jpl); // Minimum 1 JPL per schedule entry
                    } else {
                        // If there's an issue with time data, count as 1 JPL
                        $totalJam += 1;
                    }
                } else {
                    // If no time data, count as 1 JPL
                    $totalJam += 1;
                }
            }
        } else {
            // For assignments, count each assignment as 1 JPL since time isn't set
            $totalJam = $jadwal->count();
        }
        
        // Ensure totalJam is never negative
        $totalJam = max(0, $totalJam);
        
        // Debug: Log totalJam calculation
        \Log::info('TotalJam calculation result: ' . $totalJam . ' (use_assignments: ' . ($use_assignments ? 'true' : 'false') . ')');

        // Days of week in Indonesian
        $hariSeminggu = [
            'senin' => 'Senin',
            'selasa' => 'Selasa', 
            'rabu' => 'Rabu',
            'kamis' => 'Kamis',
            'jumat' => 'Jumat',
            'sabtu' => 'Sabtu',
            'minggu' => 'Minggu'
        ];

        // Time slots for schedule display - get from settings
        $timeSlots = $this->getAllTimeSlots();

        // Create schedule matrix for all time slots (only for actual scheduled jadwal)
        $scheduleMatrix = [];
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        
        if (!$use_assignments) {
            // Initialize matrix with all empty slots
            foreach($days as $day) {
                $scheduleMatrix[$day] = [];
                foreach($timeSlots as $slot) {
                    $scheduleMatrix[$day][$slot['time']] = null;
                }
            }
            
            // Fill matrix with actual schedules
            foreach($jadwal as $j) {
                if ($j->hari && $j->jam_mulai && $j->jam_selesai) {
                    $day = strtolower($j->hari);
                    $startTime = Carbon::parse($j->jam_mulai)->format('H:i');
                    $endTime = Carbon::parse($j->jam_selesai)->format('H:i');
                    $timeKey = $startTime . ' - ' . $endTime;
                    
                    if (isset($scheduleMatrix[$day])) {
                        $scheduleMatrix[$day][$timeKey] = $j;
                    }
                }
            }
        }

        return view('guru.jadwal.index', compact(
            'jadwal', 
            'jadwalPerHari', 
            'jadwalHariIni', 
            'totalJadwal', 
            'totalKelas', 
            'totalMapel',
            'totalJam',
            'hariSeminggu',
            'hariIni',
            'timeSlots',
            'scheduleMatrix',
            'debugInfo',
            'use_assignments'
        ));
    }

    public function show($id)
    {
        $guru = Auth::guard('guru')->user();
        
        // Get active semester and tahun_ajaran from settings (same as admin)
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        $jadwal = JadwalPelajaran::with(['mapel', 'kelas.jurusan'])
            ->where('guru_id', $guru->id)
            ->where('semester', $semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->findOrFail($id);

        return view('guru.jadwal.show', compact('jadwal'));
    }

    /**
     * Get time schedule from settings or fallback to hardcoded schedule
     */
    private function getTimeSchedule($jam_ke)
    {
        // Get settings jadwal
        $settingsJadwal = SettingsJadwal::where('is_active', true)->first();
        
        if (!$settingsJadwal) {
            // Fallback to hardcoded schedule if no settings
            $schedule = [
                1 => ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'],
                2 => ['jam_mulai' => '08:30', 'jam_selesai' => '09:05'],
                3 => ['jam_mulai' => '09:20', 'jam_selesai' => '09:55'],
                4 => ['jam_mulai' => '09:55', 'jam_selesai' => '10:30'],
                5 => ['jam_mulai' => '10:45', 'jam_selesai' => '11:20'],
                6 => ['jam_mulai' => '11:20', 'jam_selesai' => '11:55'],
                7 => ['jam_mulai' => '12:10', 'jam_selesai' => '12:45'],
                8 => ['jam_mulai' => '12:45', 'jam_selesai' => '13:20'],
                9 => ['jam_mulai' => '13:35', 'jam_selesai' => '14:10'],
                10 => ['jam_mulai' => '14:10', 'jam_selesai' => '14:45'],
            ];
            return $schedule[$jam_ke] ?? ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'];
        }
        
        // Generate dynamic schedule based on settings
        $numPeriods = (int)($settingsJadwal->jumlah_jam_pelajaran ?? 10);
        $periodDuration = (int)($settingsJadwal->durasi_per_jam ?? 35);
        $startTime = Carbon::parse($settingsJadwal->jam_mulai ?? '07:55');
        
        // Prepare break times
        $breaks = [];
        if ($settingsJadwal->jam_istirahat_mulai && $settingsJadwal->jam_istirahat_selesai) {
            $breaks[] = [
                'start' => Carbon::parse($settingsJadwal->jam_istirahat_mulai),
                'end' => Carbon::parse($settingsJadwal->jam_istirahat_selesai)
            ];
        }
        if ($settingsJadwal->jam_istirahat2_mulai && $settingsJadwal->jam_istirahat2_selesai) {
            $breaks[] = [
                'start' => Carbon::parse($settingsJadwal->jam_istirahat2_mulai),
                'end' => Carbon::parse($settingsJadwal->jam_istirahat2_selesai)
            ];
        }
        
        // Generate schedule dynamically
        $schedule = [];
        $currentTime = $startTime;
        
        for ($period = 1; $period <= $numPeriods; $period++) {
            // Check for breaks before this period
            foreach ($breaks as $break) {
                if ($currentTime->format('H:i') < $break['start']->format('H:i') && 
                    $currentTime->copy()->addMinutes($periodDuration)->format('H:i') > $break['start']->format('H:i')) {
                    $currentTime = $break['end'];
                }
            }
            
            $endTime = $currentTime->copy()->addMinutes($periodDuration);
            
            $schedule[$period] = [
                'jam_mulai' => $currentTime->format('H:i'),
                'jam_selesai' => $endTime->format('H:i')
            ];
            
            $currentTime = $endTime;
        }
        
        return $schedule[$jam_ke] ?? ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'];
    }

    /**
     * Get all time slots based on settings
     */
    private function getAllTimeSlots()
    {
        // Get settings jadwal
        $settingsJadwal = SettingsJadwal::where('is_active', true)->first();
        
        if (!$settingsJadwal) {
            // Fallback to hardcoded schedule if no settings
            return [
                ['time' => '07:55 - 08:30', 'start' => '07:55', 'end' => '08:30', 'isBreak' => false, 'jam_ke' => 1],
                ['time' => '08:30 - 09:05', 'start' => '08:30', 'end' => '09:05', 'isBreak' => false, 'jam_ke' => 2],
                ['time' => '09:05 - 09:20', 'isBreak' => true, 'label' => 'Istirahat 1'],
                ['time' => '09:20 - 09:55', 'start' => '09:20', 'end' => '09:55', 'isBreak' => false, 'jam_ke' => 3],
                ['time' => '09:55 - 10:30', 'start' => '09:55', 'end' => '10:30', 'isBreak' => false, 'jam_ke' => 4],
                ['time' => '10:30 - 10:45', 'isBreak' => true, 'label' => 'Istirahat 2'],
                ['time' => '10:45 - 11:20', 'start' => '10:45', 'end' => '11:20', 'isBreak' => false, 'jam_ke' => 5],
                ['time' => '11:20 - 11:55', 'start' => '11:20', 'end' => '11:55', 'isBreak' => false, 'jam_ke' => 6],
                ['time' => '11:55 - 12:10', 'isBreak' => true, 'label' => 'Istirahat 3'],
                ['time' => '12:10 - 12:45', 'start' => '12:10', 'end' => '12:45', 'isBreak' => false, 'jam_ke' => 7],
                ['time' => '12:45 - 13:20', 'start' => '12:45', 'end' => '13:20', 'isBreak' => false, 'jam_ke' => 8],
                ['time' => '13:20 - 13:35', 'isBreak' => true, 'label' => 'Istirahat 4'],
                ['time' => '13:35 - 14:10', 'start' => '13:35', 'end' => '14:10', 'isBreak' => false, 'jam_ke' => 9],
                ['time' => '14:10 - 14:45', 'start' => '14:10', 'end' => '14:45', 'isBreak' => false, 'jam_ke' => 10],
            ];
        }
        
        // Generate dynamic schedule based on settings
        $slots = [];
        $numPeriods = (int)($settingsJadwal->jumlah_jam_pelajaran ?? 10);
        $periodDuration = (int)($settingsJadwal->durasi_per_jam ?? 35);
        $startTime = Carbon::parse($settingsJadwal->jam_mulai ?? '07:55');
        
        // Prepare break times
        $breaks = [];
        if ($settingsJadwal->jam_istirahat_mulai && $settingsJadwal->jam_istirahat_selesai) {
            $breaks[] = [
                'start' => Carbon::parse($settingsJadwal->jam_istirahat_mulai),
                'end' => Carbon::parse($settingsJadwal->jam_istirahat_selesai),
                'label' => 'Istirahat 1'
            ];
        }
        if ($settingsJadwal->jam_istirahat2_mulai && $settingsJadwal->jam_istirahat2_selesai) {
            $breaks[] = [
                'start' => Carbon::parse($settingsJadwal->jam_istirahat2_mulai),
                'end' => Carbon::parse($settingsJadwal->jam_istirahat2_selesai),
                'label' => 'Istirahat 2'
            ];
        }
        
        // Generate schedule dynamically with breaks
        $currentTime = clone $startTime;
        $jamKe = 1;
        
        // Create all time slots with breaks inserted at correct positions
        while ($jamKe <= $numPeriods) {
            // Check if we need to insert a break before this period
            $needBreak = false;
            $breakInfo = null;
            
            foreach ($breaks as $break) {
                // Check if break should start at current time
                if ($currentTime->format('H:i') === $break['start']->format('H:i')) {
                    $needBreak = true;
                    $breakInfo = $break;
                    break;
                }
            }
            
            // Add break slot if needed
            if ($needBreak && $breakInfo) {
                $slots[] = [
                    'time' => $breakInfo['start']->format('H:i') . ' - ' . $breakInfo['end']->format('H:i'),
                    'isBreak' => true,
                    'label' => $breakInfo['label']
                ];
                
                // Move current time to after break
                $currentTime = clone $breakInfo['end'];
            }
            
            // Add period slot
            $endTime = (clone $currentTime)->addMinutes($periodDuration);
            
            $slots[] = [
                'time' => $currentTime->format('H:i') . ' - ' . $endTime->format('H:i'),
                'start' => $currentTime->format('H:i'),
                'end' => $endTime->format('H:i'),
                'isBreak' => false,
                'jam_ke' => $jamKe
            ];
            
            $currentTime = $endTime;
            $jamKe++;
        }
        
        return $slots;
    }
}
