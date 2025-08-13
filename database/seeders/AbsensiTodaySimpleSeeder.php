<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Carbon\Carbon;

class AbsensiTodaySimpleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tanggal hari ini
        $today = Carbon::today()->format('Y-m-d'); // 2025-08-09
        
        echo "Generating simple absensi data for: $today\n";
        
        // Ambil beberapa kelas saja
        $kelasList = Kelas::take(3)->get(); // Ambil 3 kelas pertama
        $guruList = Guru::where('is_active', true)->take(5)->get(); // 5 guru
        $mapelList = MataPelajaran::take(6)->get(); // 6 mata pelajaran
        
        if ($kelasList->isEmpty() || $guruList->isEmpty() || $mapelList->isEmpty()) {
            echo "ERROR: Not enough data (kelas, guru, atau mata pelajaran)\n";
            return;
        }
        
        echo "Using " . $kelasList->count() . " kelas, " . $guruList->count() . " guru, " . $mapelList->count() . " mapel\n";
        
        $statusOptions = ['hadir', 'sakit', 'izin', 'alpha'];
        $statusWeights = [75, 10, 10, 5]; // Lebih banyak hadir
        
        $totalCreated = 0;
        
        // Untuk setiap kelas
        foreach ($kelasList as $kelas) {
            echo "Processing kelas: " . $kelas->nama_kelas . "\n";
            
            // Ambil siswa dari kelas ini (maksimal 10 siswa per kelas untuk testing)
            $siswaList = Siswa::where('kelas_id', $kelas->id)
                              ->where('status', 'aktif')
                              ->take(10)
                              ->get();
            
            if ($siswaList->isEmpty()) {
                echo "No students found for kelas: " . $kelas->nama_kelas . "\n";
                continue;
            }
            
            echo "Found " . $siswaList->count() . " students\n";
            
            // Untuk setiap mata pelajaran (3-4 mapel per kelas)
            $selectedMapel = $mapelList->take(rand(3, 4));
            
            foreach ($selectedMapel as $mapel) {
                $guru = $guruList->random(); // Pilih guru random
                
                // Untuk setiap siswa di kelas ini
                foreach ($siswaList as $siswa) {
                    // Cek apakah sudah ada absensi untuk siswa, mapel, dan tanggal ini
                    $existing = Absensi::where('siswa_id', $siswa->id)
                                       ->where('mapel_id', $mapel->id)
                                       ->where('tanggal', $today)
                                       ->first();
                    
                    if ($existing) {
                        continue; // Skip jika sudah ada
                    }
                    
                    // Pilih status random berdasarkan bobot
                    $status = $this->getWeightedRandomStatus($statusOptions, $statusWeights);
                    
                    // Generate keterangan
                    $keterangan = $this->generateKeterangan($status);
                    
                    Absensi::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelas->id,
                        'mapel_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'tanggal' => $today,
                        'status' => $status,
                        'keterangan' => $keterangan . ' [DUMMY TEST DATA]'
                    ]);
                    
                    $totalCreated++;
                }
            }
        }
        
        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Date: $today\n";
        echo "Total Absensi Created: $totalCreated\n";
        echo "Classes processed: " . $kelasList->count() . "\n";
    }
    
    /**
     * Get weighted random status
     */
    private function getWeightedRandomStatus($options, $weights)
    {
        $totalWeight = array_sum($weights);
        $randomNumber = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($options as $index => $option) {
            $currentWeight += $weights[$index];
            if ($randomNumber <= $currentWeight) {
                return $option;
            }
        }
        
        return $options[0]; // fallback
    }
    
    /**
     * Generate keterangan based on status
     */
    private function generateKeterangan($status)
    {
        $keteranganOptions = [
            'hadir' => [
                'Mengikuti pelajaran dengan baik',
                'Hadir tepat waktu',
                'Aktif dalam pembelajaran',
                'Mengikuti semua kegiatan',
                ''
            ],
            'sakit' => [
                'Demam',
                'Flu dan batuk',
                'Sakit perut',
                'Sakit kepala',
                'Tidak enak badan'
            ],
            'izin' => [
                'Keperluan keluarga',
                'Acara keluarga',
                'Urusan penting',
                'Izin orang tua'
            ],
            'alpha' => [
                'Tanpa keterangan',
                'Tidak hadir',
                ''
            ]
        ];
        
        return $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
    }
}
