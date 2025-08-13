<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;

class CleanInvalidSchedules extends Command
{
    protected $signature = 'schedule:clean-invalid';
    protected $description = 'Clean schedules with invalid kelas_id references';

    public function handle()
    {
        $this->info('Cleaning invalid schedules...');
        
        // Find schedules with invalid kelas_id
        $invalidSchedules = JadwalPelajaran::whereNotIn('kelas_id', function($query) {
            $query->select('id')->from('kelas');
        })->get();
        
        if ($invalidSchedules->count() > 0) {
            $this->warn("Found {$invalidSchedules->count()} schedules with invalid kelas_id:");
            
            foreach ($invalidSchedules as $schedule) {
                $this->line("- Schedule ID: {$schedule->id}, Kelas ID: {$schedule->kelas_id}, Subject: " . ($schedule->mapel->nama ?? 'Unknown'));
            }
            
            if ($this->confirm('Do you want to delete these invalid schedules?')) {
                $deleted = JadwalPelajaran::whereNotIn('kelas_id', function($query) {
                    $query->select('id')->from('kelas');
                })->delete();
                
                $this->info("Deleted {$deleted} invalid schedules.");
            } else {
                $this->info('No schedules deleted.');
            }
        } else {
            $this->info('No invalid schedules found.');
        }
        
        return 0;
    }
}
