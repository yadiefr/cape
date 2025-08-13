<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class AbsensiTodaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today(); // 2025-08-09
        
        // Status absensi yang akan digunakan
        $statusOptions = ['Hadir', 'Sakit', 'Izin', 'Alpha'];
        $statusWeights = [80, 10, 5, 5]; // Persentase kemungkinan setiap status (lebih banyak hadir)

        // Ambil data yang diperlukan
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->take(50)->get(); // Hanya 50 siswa
        $guruList = Guru::where('is_active', true)->get();
        
        // Ambil beberapa jadwal pelajaran untuk hari ini (Jumat)
        $jadwalList = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])
            ->whereNotNull('guru_id')
            ->whereNotNull('mapel_id') 
            ->whereNotNull('kelas_id')
            ->where('is_active', true)
            ->where('hari', 'Jumat') // Hari ini adalah Jumat
            ->take(20) // Hanya 20 jadwal
            ->get();

        echo "Generating absensi data for today ({$today->format('Y-m-d')})...\n";
        echo "Total siswa: " . $siswaList->count() . "\n";
        echo "Total guru: " . $guruList->count() . "\n";
        echo "Total jadwal Jumat: " . $jadwalList->count() . "\n";

        $totalAbsensi = 0;
        $totalAbsensiHarian = 0;

        // 1. Generate Absensi per Mata Pelajaran untuk hari ini
        foreach ($jadwalList as $jadwal) {
            if (!$jadwal->kelas || !$jadwal->mapel || !$jadwal->guru) {
                continue;
            }

            // Ambil siswa dari kelas ini
            $siswaKelas = $siswaList->where('kelas_id', $jadwal->kelas_id);

            foreach ($siswaKelas as $siswa) {
                // Pilih status secara random berdasarkan bobot
                $status = $this->getWeightedRandomStatus($statusOptions, $statusWeights);
                $keterangan = $this->generateKeterangan($status);

                Absensi::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $jadwal->kelas_id,
                    'mapel_id' => $jadwal->mapel_id,
                    'guru_id' => $jadwal->guru_id,
                    'tanggal' => $today->format('Y-m-d'),
                    'status' => strtolower($status), // Database menggunakan lowercase
                    'keterangan' => $keterangan . ' [DUMMY DATA TODAY]'
                ]);

                $totalAbsensi++;
            }
        }

        // 2. Generate Absensi Harian untuk hari ini
        $kelasIds = $siswaList->pluck('kelas_id')->unique();
        
        foreach ($kelasIds as $kelasId) {
            $siswaKelas = $siswaList->where('kelas_id', $kelasId);
            
            if ($siswaKelas->isEmpty()) {
                continue;
            }

            // Ambil wali kelas atau guru random
            $guruKelas = $guruList->where('is_wali_kelas', true)->first();
            if (!$guruKelas) {
                $guruKelas = $guruList->random();
            }

            foreach ($siswaKelas as $siswa) {
                // Status harian cenderung lebih positif
                $statusHarianWeights = [90, 5, 3, 2]; // Lebih banyak hadir
                $status = $this->getWeightedRandomStatus($statusOptions, $statusHarianWeights);
                $keterangan = $this->generateKeterangan($status, true);

                AbsensiHarian::create([
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasId,
                    'guru_id' => $guruKelas->id,
                    'tanggal' => $today->format('Y-m-d'),
                    'status' => strtolower($status), // Database menggunakan lowercase
                    'keterangan' => $keterangan . ' [DUMMY DATA TODAY]'
                ]);

                $totalAbsensiHarian++;
            }
        }

        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Total Absensi per Mapel: $totalAbsensi\n";
        echo "Total Absensi Harian: $totalAbsensiHarian\n";
        echo "Date: " . $today->format('Y-m-d') . "\n";
        echo "Total records: " . ($totalAbsensi + $totalAbsensiHarian) . "\n";
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
    private function generateKeterangan($status, $isHarian = false)
    {
        $keteranganOptions = [
            'Hadir' => [
                'Mengikuti pelajaran dengan baik',
                'Hadir tepat waktu',
                'Aktif dalam pembelajaran',
                ''  // kosong untuk hadir
            ],
            'Sakit' => [
                'Demam',
                'Flu dan batuk', 
                'Sakit perut',
                'Tidak enak badan'
            ],
            'Izin' => [
                'Keperluan keluarga',
                'Urusan penting',
                'Izin orang tua'
            ],
            'Alpha' => [
                'Tanpa keterangan',
                ''
            ]
        ];

        if ($isHarian) {
            $prefix = [
                'Hadir' => 'Kehadiran harian: ',
                'Sakit' => 'Tidak masuk: ',
                'Izin' => 'Izin: ',
                'Alpha' => 'Alpha: '
            ];
            
            $keterangan = $prefix[$status] . $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
        } else {
            $keterangan = $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
        }

        return $keterangan;
    }
}
