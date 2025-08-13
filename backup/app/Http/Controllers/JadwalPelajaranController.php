<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\SettingsJadwal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalPelajaranController extends Controller
{
    /**
     * Display jadwal for guru.
     *
     * @return \Illuminate\Http\Response
     */
    public function jadwalGuru()
    {
        // Get current authenticated guru
        $guru = Auth::user();
        
        // Debug information
        \Log::info('Guru Schedule Debug:', [
            'guru_id' => $guru->id,
            'guru_name' => $guru->nama
        ]);
        
        // Get all jadwal for this guru first (without semester/year filter)
        $jadwal = JadwalPelajaran::where('guru_id', $guru->id)
            ->with(['kelas', 'mapel'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
            
        \Log::info('Jadwal found:', [
            'count' => $jadwal->count(),
            'jadwal' => $jadwal->toArray()
        ]);
        
        // Get active semester settings (for display purposes)
        $activeSemester = SettingsJadwal::where('is_active', true)->first();
        $semester = $activeSemester ? $activeSemester->semester : 1;
        $tahunAjaran = $activeSemester ? $activeSemester->tahun_ajaran : Carbon::now()->year . '/' . (Carbon::now()->year + 1);
        
        // Generate time slots dynamically from settings
        $timeSlots = $this->generateTimeSlots();
        
        return view('guru.jadwal.index', compact('jadwal', 'timeSlots', 'semester', 'tahunAjaran'));
    }

    /**
     * Generate time slots based on admin settings
     */
    private function generateTimeSlots()
    {
        // Get any active settings (regardless of day since settings are used globally)
        $settings = SettingsJadwal::where('is_active', true)->first();
        
        if (!$settings) {
            // Fallback to default time slots if no settings found
            return [
                ['time' => '07:00 - 07:45', 'start' => '07:00:00', 'end' => '07:45:00', 'isBreak' => false],
                ['time' => '07:45 - 08:30', 'start' => '07:45:00', 'end' => '08:30:00', 'isBreak' => false],
                ['time' => '08:30 - 09:15', 'start' => '08:30:00', 'end' => '09:15:00', 'isBreak' => false],
                ['time' => '09:15 - 09:30', 'start' => '09:15:00', 'end' => '09:30:00', 'isBreak' => true],
                ['time' => '09:30 - 10:15', 'start' => '09:30:00', 'end' => '10:15:00', 'isBreak' => false],
                ['time' => '10:15 - 11:00', 'start' => '10:15:00', 'end' => '11:00:00', 'isBreak' => false],
                ['time' => '11:00 - 11:45', 'start' => '11:00:00', 'end' => '11:45:00', 'isBreak' => false],
                ['time' => '11:45 - 12:45', 'start' => '11:45:00', 'end' => '12:45:00', 'isBreak' => true],
                ['time' => '12:45 - 13:30', 'start' => '12:45:00', 'end' => '13:30:00', 'isBreak' => false],
                ['time' => '13:30 - 14:15', 'start' => '13:30:00', 'end' => '14:15:00', 'isBreak' => false],
                ['time' => '14:15 - 15:00', 'start' => '14:15:00', 'end' => '15:00:00', 'isBreak' => false],
            ];
        }

        // Log the settings being used
        \Log::info('Using settings for time slots:', [
            'settings' => $settings->toArray()
        ]);

        $timeSlots = [];
        $currentTime = Carbon::parse($settings->jam_mulai);
        $endTime = Carbon::parse($settings->jam_selesai);
        $duration = (int)$settings->durasi_per_jam; // Duration in minutes
        
        // Parse break times
        $istirahat1Start = $settings->jam_istirahat_mulai ? Carbon::parse($settings->jam_istirahat_mulai) : null;
        $istirahat1End = $settings->jam_istirahat_selesai ? Carbon::parse($settings->jam_istirahat_selesai) : null;
        $istirahat2Start = $settings->jam_istirahat2_mulai ? Carbon::parse($settings->jam_istirahat2_mulai) : null;
        $istirahat2End = $settings->jam_istirahat2_selesai ? Carbon::parse($settings->jam_istirahat2_selesai) : null;
        
        $jamKe = 1;
        
        while ($currentTime < $endTime && $jamKe <= $settings->jumlah_jam_pelajaran) {
            $jamMulai = $currentTime->copy();
            $jamSelesai = $currentTime->copy()->addMinutes($duration);
            
            // If we reach the end time, stop
            if ($jamSelesai > $endTime) {
                break;
            }
            
            // Check if current time overlaps with break time 1
            if ($istirahat1Start && $istirahat1End && 
                $jamMulai < $istirahat1End && $jamSelesai > $istirahat1Start) {
                // Add break time if we haven't added it yet
                if (!collect($timeSlots)->contains('start', $istirahat1Start->format('H:i:s'))) {
                    $timeSlots[] = [
                        'time' => $istirahat1Start->format('H:i') . ' - ' . $istirahat1End->format('H:i'),
                        'start' => $istirahat1Start->format('H:i:s'),
                        'end' => $istirahat1End->format('H:i:s'),
                        'isBreak' => true
                    ];
                }
                // Skip this slot and move to after break
                $currentTime = $istirahat1End->copy();
                continue;
            }
            
            // Check if current time overlaps with break time 2
            if ($istirahat2Start && $istirahat2End && 
                $jamMulai < $istirahat2End && $jamSelesai > $istirahat2Start) {
                // Add break time if we haven't added it yet
                if (!collect($timeSlots)->contains('start', $istirahat2Start->format('H:i:s'))) {
                    $timeSlots[] = [
                        'time' => $istirahat2Start->format('H:i') . ' - ' . $istirahat2End->format('H:i'),
                        'start' => $istirahat2Start->format('H:i:s'),
                        'end' => $istirahat2End->format('H:i:s'),
                        'isBreak' => true
                    ];
                }
                // Skip this slot and move to after break
                $currentTime = $istirahat2End->copy();
                continue;
            }
            
            // Regular class time
            $timeSlots[] = [
                'time' => $jamMulai->format('H:i') . ' - ' . $jamSelesai->format('H:i'),
                'start' => $jamMulai->format('H:i:s'),
                'end' => $jamSelesai->format('H:i:s'),
                'isBreak' => false
            ];
            
            $currentTime = $jamSelesai->copy();
            $jamKe++;
        }
        
        \Log::info('Generated time slots:', [
            'count' => count($timeSlots),
            'slots' => $timeSlots
        ]);
        
        return $timeSlots;
    }

    /**
     * Display jadwal for siswa.
     *
     * @return \Illuminate\Http\Response
     */
    public function jadwalSiswa()
    {
        // Get current authenticated siswa
        $siswa = Auth::user();
        $kelas_id = $siswa->kelas_id;
        
        // Debug information
        \Log::info('Student Schedule Debug:', [
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas_id,
            'siswa_name' => $siswa->nama_lengkap
        ]);
        
        // If siswa doesn't have a class assigned, show a message
        if (!$kelas_id) {
            return view('siswa.jadwal.index', ['error' => 'Anda belum memiliki kelas. Silahkan hubungi administrator.']);
        }
          // Get active semester settings - try multiple approaches
        $activeSemester = SettingsJadwal::where('is_active', true)->first();
        
        if (!$activeSemester) {
            // If no active settings, try to get any existing setting
            $activeSemester = SettingsJadwal::orderBy('created_at', 'desc')->first();
        }
          $semester = $activeSemester ? $activeSemester->semester : "1"; // Use string format to match database
        $tahunAjaran = $activeSemester ? $activeSemester->tahun_ajaran : Carbon::now()->year . '/' . (Carbon::now()->year + 1);
        
        \Log::info('Semester Settings:', [
            'activeSemester' => $activeSemester,
            'semester' => $semester,
            'semester_type' => gettype($semester),
            'tahunAjaran' => $tahunAjaran
        ]);
        
        // Try different query approaches to find schedule data
        $jadwalQuery = JadwalPelajaran::where('kelas_id', $kelas_id)
            ->with(['guru', 'mapel']);
            
        // First try with exact semester and year match
        $jadwal = $jadwalQuery->where('semester', $semester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
            
        \Log::info('First query attempt:', [
            'semester_param' => $semester,
            'tahun_ajaran_param' => $tahunAjaran,
            'result_count' => $jadwal->count()
        ]);
            
        // If no results, try without year filter
        if ($jadwal->isEmpty()) {
            $jadwal = JadwalPelajaran::where('kelas_id', $kelas_id)
                ->where('semester', $semester)
                ->with(['guru', 'mapel'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
                
            \Log::info('Second query attempt (no year filter):', [
                'result_count' => $jadwal->count()
            ]);
        }
        
        // If still no results, try without semester filter (get any available schedule)
        if ($jadwal->isEmpty()) {
            $jadwal = JadwalPelajaran::where('kelas_id', $kelas_id)
                ->with(['guru', 'mapel'])
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();
                
            \Log::info('Third query attempt (no semester filter):', [
                'result_count' => $jadwal->count()
            ]);
        }
              \Log::info('Schedule Query Results:', [
            'count' => $jadwal->count(),
            'query_params' => [
                'kelas_id' => $kelas_id,
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran
            ],
            'sample_schedule' => $jadwal->first() ? [
                'hari' => $jadwal->first()->hari,
                'mapel' => $jadwal->first()->mapel->nama ?? null,
                'guru' => $jadwal->first()->guru->nama ?? null,
            ] : null
        ]);
        
        // Order schedules by day of week (Monday = 1, Tuesday = 2, etc.)
        $dayOrder = [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
            'minggu' => 7
        ];
        
        $jadwal = $jadwal->sortBy(function($item) use ($dayOrder) {
            $hari = strtolower($item->hari);
            return ($dayOrder[$hari] ?? 8) * 100 + (int)substr($item->jam_mulai, 0, 2);
        })->values();
            
        // Define time slots
        $timeSlots = [
            ['time' => '07:00 - 07:45', 'start' => '07:00:00', 'end' => '07:45:00', 'isBreak' => false],
            ['time' => '07:45 - 08:30', 'start' => '07:45:00', 'end' => '08:30:00', 'isBreak' => false],
            ['time' => '08:30 - 09:15', 'start' => '08:30:00', 'end' => '09:15:00', 'isBreak' => false],
            ['time' => '09:15 - 09:30', 'start' => '09:15:00', 'end' => '09:30:00', 'isBreak' => true],  // Istirahat
            ['time' => '09:30 - 10:15', 'start' => '09:30:00', 'end' => '10:15:00', 'isBreak' => false],
            ['time' => '10:15 - 11:00', 'start' => '10:15:00', 'end' => '11:00:00', 'isBreak' => false],
            ['time' => '11:00 - 11:45', 'start' => '11:00:00', 'end' => '11:45:00', 'isBreak' => false],
            ['time' => '11:45 - 12:45', 'start' => '11:45:00', 'end' => '12:45:00', 'isBreak' => true],  // Istirahat
            ['time' => '12:45 - 13:30', 'start' => '12:45:00', 'end' => '13:30:00', 'isBreak' => false],
            ['time' => '13:30 - 14:15', 'start' => '13:30:00', 'end' => '14:15:00', 'isBreak' => false],
            ['time' => '14:15 - 15:00', 'start' => '14:15:00', 'end' => '15:00:00', 'isBreak' => false],
        ];
        
        $kelas = Kelas::find($kelas_id);
        
        return view('siswa.jadwal.index', compact('jadwal', 'timeSlots', 'semester', 'tahunAjaran', 'kelas'));
    }
}
