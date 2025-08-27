<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\Settings;
use App\Models\SettingsJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Get active semester and tahun_ajaran from settings (same as admin)
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        // Get current week schedule with proper filters
        $jadwal = JadwalPelajaran::with(['mapel', 'guru', 'kelas'])
                    ->where('kelas_id', $siswa->kelas_id)
                    ->where('semester', $semester)
                    ->where('tahun_ajaran', $tahun_ajaran)
                    ->where('is_active', true)
                    ->scheduled() // Only show scheduled items using scope
                    ->orderBy('hari')
                    ->orderBy('jam_mulai')
                    ->get();
        
        // Get kelas info
        $kelas = $siswa->kelas;
        
        // Get settings jadwal (for break times, similar to admin)
        $settingsJadwal = SettingsJadwal::aktif()->get()->keyBy('hari');
        
        // Merge break times into jadwal collection
        $jadwalWithBreaks = collect();
        
        foreach ($hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day) {
            // Get settings for this day
            $daySetting = $settingsJadwal->get(ucfirst($day)) ?? $settingsJadwal->get(strtolower($day));
            
            // Get jadwal for this day
            $jadwalPerDay = $jadwal->filter(function ($item) use ($day) {
                return strtolower($item->hari) === strtolower($day);
            });
            
            // Add regular jadwal items
            foreach ($jadwalPerDay as $item) {
                $jadwalWithBreaks->push($item);
            }
            
            // Add break times if settings exist
            if ($daySetting) {
                // First break
                if ($daySetting->jam_istirahat_mulai && $daySetting->jam_istirahat_selesai) {
                    $breakItem = new \stdClass();
                    $breakItem->hari = ucfirst($day);
                    $breakItem->jam_mulai = $daySetting->jam_istirahat_mulai;
                    $breakItem->jam_selesai = $daySetting->jam_istirahat_selesai;
                    $breakItem->is_break = true;
                    $jadwalWithBreaks->push($breakItem);
                }
                
                // Second break
                if ($daySetting->jam_istirahat2_mulai && $daySetting->jam_istirahat2_selesai) {
                    $breakItem2 = new \stdClass();
                    $breakItem2->hari = ucfirst($day);
                    $breakItem2->jam_mulai = $daySetting->jam_istirahat2_mulai;
                    $breakItem2->jam_selesai = $daySetting->jam_istirahat2_selesai;
                    $breakItem2->is_break = true;
                    $jadwalWithBreaks->push($breakItem2);
                }
            }
        }
        
        // Sort the combined collection by day and time
        $jadwal = $jadwalWithBreaks->sortBy([
            ['hari', 'asc'],
            ['jam_mulai', 'asc']
        ])->values();
        
        // Group by day
        $jadwalPerHari = $jadwal->groupBy('hari');
        
        // Get all mata pelajaran for color legend
        $allMapel = MataPelajaran::orderBy('nama')->get();
        
        // Pass all required variables to the view
        return view('siswa.jadwal.index', compact(
            'jadwal',          // Passing jadwal collection directly with breaks included
            'jadwalPerHari',   // Grouped by day
            'hari',            // List of days
            'siswa',           // Siswa info
            'kelas',           // Class info
            'semester',        // Active semester
            'tahun_ajaran',    // Academic year
            'settingsJadwal',  // Jadwal settings
            'allMapel'         // All subjects for color legend
        ));
    }
}
