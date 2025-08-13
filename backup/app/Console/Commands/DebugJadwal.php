<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPelajaran;
use App\Models\Settings;
use App\Models\Guru;

class DebugJadwal extends Command
{
    protected $signature = 'debug:jadwal {guru_id?}';
    protected $description = 'Debug jadwal data for guru';

    public function handle()
    {
        $guru_id = $this->argument('guru_id');
        $guru_ids = $guru_id ? [$guru_id] : [26, 28, 34, 35, 37];

        // Get current settings
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', '2025/2026');
        
        $this->info("Current Settings:");
        $this->info("Semester: $semester");
        $this->info("Tahun Ajaran: $tahun_ajaran");
        $this->info("");

        foreach ($guru_ids as $gid) {
            $guru = Guru::find($gid);
            if (!$guru) {
                $this->error("Guru ID $gid not found");
                continue;
            }

            $this->info("=== GURU: {$guru->nama_lengkap} (ID: $gid) ===");
            
            // All jadwal for this guru
            $all_jadwal = JadwalPelajaran::where('guru_id', $gid)
                ->where('is_active', true)
                ->get();
            
            $this->info("Total active jadwal: " . $all_jadwal->count());
            
            // Check current semester/tahun_ajaran
            $current_jadwal = JadwalPelajaran::where('guru_id', $gid)
                ->where('is_active', true)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->get();
            
            $this->info("Jadwal for current semester/tahun: " . $current_jadwal->count());
            
            // Check scheduled entries
            $scheduled = JadwalPelajaran::where('guru_id', $gid)
                ->where('is_active', true)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->scheduled()
                ->get();
                
            $this->info("Scheduled jadwal (with time info): " . $scheduled->count());
            
            // Check assignments only
            $assignments = JadwalPelajaran::where('guru_id', $gid)
                ->where('is_active', true)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahun_ajaran)
                ->assignments()
                ->get();
                
            $this->info("Assignment-only jadwal: " . $assignments->count());
            
            // Show sample data
            if ($current_jadwal->count() > 0) {
                $this->info("\nSample records:");
                foreach ($current_jadwal->take(3) as $jadwal) {
                    $this->info("- ID: {$jadwal->id}");
                    $this->info("  Hari: " . ($jadwal->hari ?? 'NULL'));
                    $this->info("  Jam: " . ($jadwal->jam_mulai ?? 'NULL') . " - " . ($jadwal->jam_selesai ?? 'NULL'));
                    $this->info("  Kelas: " . ($jadwal->kelas ? $jadwal->kelas->nama_kelas : 'N/A'));
                    $this->info("  Mapel: " . ($jadwal->mapel ? $jadwal->mapel->nama_mapel : 'N/A'));
                    $this->info("  Semester: {$jadwal->semester}");
                    $this->info("  Tahun Ajaran: {$jadwal->tahun_ajaran}");
                    $this->info("");
                }
            }
            
            $this->info(str_repeat("-", 50));
        }
        
        return 0;
    }
}
