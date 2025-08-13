<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BelSekolah;
use Carbon\Carbon;

class MonitorBelSystem extends Command
{
    protected $signature = 'bel:monitor';
    protected $description = 'Monitor the bell system and show when bells should ring';

    public function handle()
    {
        $this->info('🔔 Starting Bell System Monitor');
        $this->info('Press Ctrl+C to stop');
        $this->line('');

        $previousTime = null;

        while (true) {
            $now = Carbon::now();
            $currentTime = $now->format('H:i');
            
            // Only show when minute changes
            if ($currentTime !== $previousTime) {
                $this->line("[{$now->format('H:i:s')}] Checking for bells...");
                
                // Get active bells for current time
                $bells = BelSekolah::where('aktif', true)->get();
                
                $bellsForNow = $bells->filter(function($b) use ($currentTime, $now) {
                    $bellTime = substr($b->waktu, 0, 5);
                    $timeMatches = $bellTime === $currentTime;
                    
                    if (!$timeMatches) return false;
                    
                    $currentDay = $now->format('l');
                    $dayMatches = is_null($b->hari) || 
                                 $b->hari === '' || 
                                 $b->hari === 'Setiap Hari' || 
                                 $b->hari === $currentDay;
                    
                    return $dayMatches;
                });
                
                if ($bellsForNow->count() > 0) {
                    $this->warn('🔔 BELLS SHOULD RING NOW:');
                    foreach ($bellsForNow as $bell) {
                        $this->line("  - {$bell->nama} (ID: {$bell->id})");
                    }
                    
                    // Test API
                    $this->line('');
                    $this->info('Testing API endpoint...');
                    $response = $this->testBelAPI();
                    
                    if ($response) {
                        $data = json_decode($response, true);
                        if ($data && isset($data['shouldRing'])) {
                            if ($data['shouldRing']) {
                                $this->info("✅ API Response: YES - SHOULD RING");
                                $this->line("   Bell: " . ($data['bell']['nama'] ?? 'Unknown'));
                            } else {
                                $this->error("❌ API Response: NO - Should not ring");
                            }
                        } else {
                            $this->error("❌ API Response: Invalid JSON");
                        }
                    } else {
                        $this->error("❌ API Response: Failed to connect");
                    }
                    
                    $this->line('');
                } else {
                    $this->comment("No bells scheduled for {$currentTime}");
                }
                
                $previousTime = $currentTime;
            }
            
            sleep(5); // Check every 5 seconds
        }
    }
    
    private function testBelAPI()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('app.url') . '/api/bel/check-current-time');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'X-Requested-With: XMLHttpRequest'
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200 ? $response : null;
    }
}
