<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Absensi;
use App\Models\AbsensiHarian;

class CleanDummyAbsensi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:clean-dummy {--keep-date=2025-08-09 : Date to keep (format: Y-m-d)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean dummy absensi data except for specified date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keepDate = $this->option('keep-date');
        
        $this->info("Menghapus data dummy absensi kecuali tanggal: {$keepDate}");
        
        // Delete Absensi dummy data except keep date
        $deletedAbsensi = Absensi::where('keterangan', 'LIKE', '%DUMMY%')
            ->where('tanggal', '!=', $keepDate)
            ->delete();
            
        // Delete AbsensiHarian dummy data except keep date
        $deletedAbsensiHarian = AbsensiHarian::where('keterangan', 'LIKE', '%DUMMY%')
            ->where('tanggal', '!=', $keepDate)
            ->delete();
        
        $this->info("Data Absensi dihapus: {$deletedAbsensi}");
        $this->info("Data AbsensiHarian dihapus: {$deletedAbsensiHarian}");
        $this->info("Data untuk tanggal {$keepDate} tetap disimpan.");
        
        // Show remaining dummy data count
        $remainingAbsensi = Absensi::where('keterangan', 'LIKE', '%DUMMY%')->count();
        $remainingAbsensiHarian = AbsensiHarian::where('keterangan', 'LIKE', '%DUMMY%')->count();
        
        $this->info("Sisa data dummy Absensi: {$remainingAbsensi}");
        $this->info("Sisa data dummy AbsensiHarian: {$remainingAbsensiHarian}");
        
        return Command::SUCCESS;
    }
}
