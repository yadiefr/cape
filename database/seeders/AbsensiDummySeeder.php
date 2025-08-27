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

class AbsensiDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data dummy sebelumnya jika ada
        Absensi::where('keterangan', 'LIKE', '%DUMMY%')->delete();
        AbsensiHarian::where('keterangan', 'LIKE', '%DUMMY%')->delete();

        // Status absensi yang akan digunakan
        $statusOptions = ['Hadir', 'Sakit', 'Izin', 'Alpha'];
        $statusWeights = [70, 15, 10, 5]; // Persentase kemungkinan setiap status

        // Ambil data yang diperlukan
        $siswaList = Siswa::where('status', 'aktif')->with('kelas')->get();
        $guruList = Guru::where('is_active', true)->get();
        $kelasIds = Kelas::pluck('id')->toArray();
        
        // Ambil jadwal pelajaran aktif (yang sudah terjadwal dengan guru)
        $jadwalList = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])
            ->whereNotNull('guru_id')
            ->whereNotNull('mapel_id') 
            ->whereNotNull('kelas_id')
            ->where('is_active', true)
            ->get();

        echo "Generating absensi dummy data...\n";
        echo "Total siswa: " . $siswaList->count() . "\n";
        echo "Total guru: " . $guruList->count() . "\n";
        echo "Total jadwal: " . $jadwalList->count() . "\n";

        // Generate data untuk 30 hari terakhir
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $totalAbsensi = 0;
        $totalAbsensiHarian = 0;

        // Loop untuk setiap hari
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Skip weekend (Sabtu dan Minggu)
            if ($date->isWeekend()) {
                continue;
            }

            echo "Processing date: " . $date->format('Y-m-d') . " (" . $date->format('l') . ")\n";

            // 1. Generate Absensi per Mata Pelajaran
            foreach ($jadwalList as $jadwal) {
                // Skip jika tidak ada data lengkap
                if (!$jadwal->kelas || !$jadwal->mapel || !$jadwal->guru) {
                    continue;
                }

                // Hanya proses jika hari sesuai dengan jadwal
                $hariIndonesia = [
                    'Monday' => 'Senin',
                    'Tuesday' => 'Selasa', 
                    'Wednesday' => 'Rabu',
                    'Thursday' => 'Kamis',
                    'Friday' => 'Jumat',
                    'Saturday' => 'Sabtu',
                    'Sunday' => 'Minggu'
                ];

                if ($jadwal->hari && $jadwal->hari !== $hariIndonesia[$date->format('l')]) {
                    continue;
                }

                // Ambil siswa dari kelas ini
                $siswaKelas = $siswaList->where('kelas_id', $jadwal->kelas_id);

                foreach ($siswaKelas as $siswa) {
                    // Probabilitas untuk menggenerate absensi (90% kemungkinan ada data)
                    if (rand(1, 100) > 90) {
                        continue;
                    }

                    // Cek apakah sudah ada absensi untuk siswa ini di tanggal ini
                    $existingAbsensi = Absensi::where('siswa_id', $siswa->id)
                        ->where('mapel_id', $jadwal->mapel_id)
                        ->where('tanggal', $date->format('Y-m-d'))
                        ->first();

                    if ($existingAbsensi) {
                        continue; // Skip jika sudah ada
                    }

                    // Pilih status secara random berdasarkan bobot
                    $status = $this->getWeightedRandomStatus($statusOptions, $statusWeights);

                    // Generate keterangan berdasarkan status
                    $keterangan = $this->generateKeterangan($status);

                    Absensi::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $jadwal->kelas_id,
                        'mapel_id' => $jadwal->mapel_id,
                        'guru_id' => $jadwal->guru_id,
                        'tanggal' => $date->format('Y-m-d'),
                        'status' => $status,
                        'keterangan' => $keterangan . ' [DUMMY DATA]'
                    ]);

                    $totalAbsensi++;
                }
            }

            // 2. Generate Absensi Harian (rekap kehadiran harian per kelas)
            foreach ($kelasIds as $kelasId) {
                $siswaKelas = $siswaList->where('kelas_id', $kelasId);
                
                if ($siswaKelas->isEmpty()) {
                    continue;
                }

                // Ambil wali kelas atau guru random untuk kelas ini
                $guruKelas = $guruList->where('is_wali_kelas', true)->first();
                if (!$guruKelas) {
                    $guruKelas = $guruList->random();
                }

                foreach ($siswaKelas as $siswa) {
                    // Probabilitas untuk menggenerate absensi harian (95% kemungkinan ada data)
                    if (rand(1, 100) > 95) {
                        continue;
                    }

                    // Cek apakah sudah ada absensi harian untuk siswa ini di tanggal ini
                    $existingAbsensiHarian = AbsensiHarian::where('siswa_id', $siswa->id)
                        ->where('tanggal', $date->format('Y-m-d'))
                        ->first();

                    if ($existingAbsensiHarian) {
                        continue; // Skip jika sudah ada
                    }

                    // Status harian cenderung lebih positif
                    $statusHarianWeights = [85, 8, 5, 2]; // Lebih banyak hadir
                    $status = $this->getWeightedRandomStatus($statusOptions, $statusHarianWeights);

                    $keterangan = $this->generateKeterangan($status, true);

                    AbsensiHarian::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelasId,
                        'guru_id' => $guruKelas->id,
                        'tanggal' => $date->format('Y-m-d'),
                        'status' => $status,
                        'keterangan' => $keterangan . ' [DUMMY DATA]'
                    ]);

                    $totalAbsensiHarian++;
                }
            }

            // Progress indicator
            if ($totalAbsensi % 100 == 0) {
                echo "Generated $totalAbsensi absensi records...\n";
            }
        }

        echo "\n=== SEEDING COMPLETED ===\n";
        echo "Total Absensi per Mapel: $totalAbsensi\n";
        echo "Total Absensi Harian: $totalAbsensiHarian\n";
        echo "Period: " . $startDate->format('Y-m-d') . " to " . $endDate->format('Y-m-d') . "\n";
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
                'Mengikuti semua kegiatan',
                ''  // kosong untuk hadir
            ],
            'Sakit' => [
                'Demam tinggi',
                'Flu dan batuk',
                'Sakit perut',
                'Sakit kepala',
                'Tidak enak badan',
                'Ke dokter'
            ],
            'Izin' => [
                'Keperluan keluarga',
                'Acara keluarga',
                'Urusan administrasi',
                'Keperluan mendadak',
                'Acara penting',
                'Izin orang tua'
            ],
            'Alpha' => [
                'Tanpa keterangan',
                'Tidak hadir tanpa izin',
                'Bolos',
                ''
            ]
        ];

        if ($isHarian) {
            // Untuk absensi harian, tambahkan konteks
            $prefix = [
                'Hadir' => 'Kehadiran harian: ',
                'Sakit' => 'Tidak masuk sekolah: ',
                'Izin' => 'Izin tidak masuk: ',
                'Alpha' => 'Tidak hadir: '
            ];
            
            $keterangan = $prefix[$status] . $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
        } else {
            $keterangan = $keteranganOptions[$status][array_rand($keteranganOptions[$status])];
        }

        return $keterangan;
    }
}
