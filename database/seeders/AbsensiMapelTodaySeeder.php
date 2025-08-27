<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class AbsensiMapelTodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today(); // 2025-08-09
        
        // Status absensi yang akan digunakan
        $statusOptions = ['hadir', 'sakit', 'izin', 'alpha'];
        $statusWeights = [75, 12, 8, 5]; // Persentase kemungkinan setiap status

        // Ambil data yang diperlukan
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->take(30)->get(); // 30 siswa
        $guruList = Guru::where('is_active', true)->get();
        
        // Ambil assignment jadwal (guru mengajar mapel di kelas tertentu)
        $assignments = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])
            ->whereNotNull('guru_id')
            ->whereNotNull('mapel_id') 
            ->whereNotNull('kelas_id')
            ->where('is_active', true)
            // Assignment records (tanpa jadwal spesifik)
            ->whereNull('hari')
            ->take(15) // Hanya 15 assignment
            ->get();

        echo "Generating absensi per mapel data for today ({$today->format('Y-m-d')})...\n";
        echo "Total siswa: " . $siswaList->count() . "\n";
        echo "Total assignments: " . $assignments->count() . "\n";

        $totalAbsensi = 0;

        // Generate Absensi per Mata Pelajaran untuk hari ini
        foreach ($assignments as $assignment) {
            if (!$assignment->kelas || !$assignment->mapel || !$assignment->guru) {
                continue;
            }

            echo "Processing: {$assignment->mapel->nama} - {$assignment->kelas->nama_kelas} - {$assignment->guru->nama}\n";

            // Ambil siswa dari kelas ini
            $siswaKelas = $siswaList->where('kelas_id', $assignment->kelas_id);

            foreach ($siswaKelas as $siswa) {
                // Cek apakah sudah ada absensi untuk siswa ini di mapel ini hari ini
                $existingAbsensi = Absensi::where('siswa_id', $siswa->id)
                    ->where('mapel_id', $assignment->mapel_id)
                    ->where('tanggal', $today->format('Y-m-d'))
                    ->first();

                if ($existingAbsensi) {
                    continue; // Skip jika sudah ada
                }

                // Pilih status secara random berdasarkan bobot
                $status = $this->getWeightedRandomStatus($statusOptions, $statusWeights);
                $keterangan = $this->generateKeterangan($status);

                Absensi::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $assignment->kelas_id,
                    'mapel_id' => $assignment->mapel_id,
                    'guru_id' => $assignment->guru_id,
                    'tanggal' => $today->format('Y-m-d'),
                    'status' => $status,
                    'keterangan' => $keterangan . ' [DUMMY MAPEL TODAY]'
                ]);

                $totalAbsensi++;
            }
        }

        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Total Absensi per Mapel: $totalAbsensi\n";
        echo "Date: " . $today->format('Y-m-d') . "\n";
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
     * Generate appropriate keterangan based on status
     */
    private function generateKeterangan($status)
    {
        $keteranganOptions = [
            'hadir' => [
                'Mengikuti pelajaran dengan baik',
                'Hadir tepat waktu',
                'Aktif dalam pembelajaran',
                ''  // kosong untuk hadir
            ],
            'sakit' => [
                'Demam',
                'Flu dan batuk', 
                'Sakit kepala',
                'Tidak enak badan'
            ],
            'izin' => [
                'Keperluan keluarga',
                'Urusan penting',
                'Izin orang tua'
            ],
            'alpha' => [
                'Tanpa keterangan',
                ''
            ]
        ];

        return $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
    }
}
