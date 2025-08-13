<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JadwalPelajaran;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;

class JadwalDummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data dummy jadwal sebelumnya jika ada
        JadwalPelajaran::where('keterangan', 'LIKE', '%DUMMY%')->delete();

        // Ambil data yang diperlukan
        $kelasIds = Kelas::pluck('id')->toArray();
        $guruIds = Guru::where('status', 'aktif')->pluck('id')->toArray();
        $mapelIds = MataPelajaran::pluck('id')->toArray();

        echo "Generating jadwal dummy data...\n";
        echo "Total kelas: " . count($kelasIds) . "\n";
        echo "Total guru: " . count($guruIds) . "\n";
        echo "Total mata pelajaran: " . count($mapelIds) . "\n";

        if (empty($kelasIds) || empty($guruIds) || empty($mapelIds)) {
            echo "ERROR: Missing required data (kelas, guru, atau mata pelajaran)\n";
            return;
        }

        // Hari dalam seminggu
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        // Jam pelajaran
        $jamPelajaran = [
            ['jam_ke' => 1, 'jam_mulai' => '07:00', 'jam_selesai' => '07:45'],
            ['jam_ke' => 2, 'jam_mulai' => '07:45', 'jam_selesai' => '08:30'],
            ['jam_ke' => 3, 'jam_mulai' => '08:30', 'jam_selesai' => '09:15'],
            ['jam_ke' => 4, 'jam_mulai' => '09:30', 'jam_selesai' => '10:15'],
            ['jam_ke' => 5, 'jam_mulai' => '10:15', 'jam_selesai' => '11:00'],
            ['jam_ke' => 6, 'jam_mulai' => '11:00', 'jam_selesai' => '11:45'],
            ['jam_ke' => 7, 'jam_mulai' => '12:30', 'jam_selesai' => '13:15'],
            ['jam_ke' => 8, 'jam_mulai' => '13:15', 'jam_selesai' => '14:00'],
        ];

        $totalJadwal = 0;

        // Generate jadwal untuk setiap kelas
        foreach ($kelasIds as $kelasId) {
            echo "Processing kelas ID: $kelasId\n";

            // Untuk setiap hari dalam seminggu
            foreach ($hariList as $hari) {
                // Generate 4-6 pelajaran per hari
                $jumlahPelajaran = rand(4, 6);
                $usedJam = [];
                
                for ($i = 0; $i < $jumlahPelajaran; $i++) {
                    // Pilih jam yang belum digunakan
                    $availableJam = array_filter($jamPelajaran, function($jam) use ($usedJam) {
                        return !in_array($jam['jam_ke'], $usedJam);
                    });

                    if (empty($availableJam)) {
                        break; // Tidak ada jam yang tersedia lagi
                    }

                    // Pilih jam random
                    $selectedJam = $availableJam[array_rand($availableJam)];
                    $usedJam[] = $selectedJam['jam_ke'];

                    // Pilih mata pelajaran dan guru random
                    $mapelId = $mapelIds[array_rand($mapelIds)];
                    $guruId = $guruIds[array_rand($guruIds)];

                    // Generate ruangan
                    $ruanganOptions = [
                        'Lab. Komputer 1', 'Lab. Komputer 2', 'Lab. Komputer 3',
                        'Ruang 101', 'Ruang 102', 'Ruang 103', 'Ruang 201', 'Ruang 202', 
                        'Ruang 203', 'Ruang 301', 'Ruang 302', 'Ruang 303',
                        'Lab. Multimedia', 'Lab. Jaringan', 'Lab. Bahasa',
                        'Ruang Praktek', 'Aula', 'Perpustakaan'
                    ];
                    $ruangan = $ruanganOptions[array_rand($ruanganOptions)];

                    // Create jadwal record
                    JadwalPelajaran::create([
                        'kelas_id' => $kelasId,
                        'mapel_id' => $mapelId,
                        'guru_id' => $guruId,
                        'hari' => $hari,
                        'jam_ke' => $selectedJam['jam_ke'],
                        'jam_mulai' => $selectedJam['jam_mulai'],
                        'jam_selesai' => $selectedJam['jam_selesai'],
                        'ruangan' => $ruangan,
                        'semester' => rand(1, 2),
                        'tahun_ajaran' => '2024/2025',
                        'keterangan' => 'Jadwal regular [DUMMY DATA]',
                        'is_active' => true
                    ]);

                    $totalJadwal++;
                }
            }
        }

        // Generate beberapa jadwal assignment (guru mengajar mapel tanpa jadwal spesifik)
        echo "Generating assignment records...\n";
        $totalAssignment = 0;

        foreach ($kelasIds as $kelasId) {
            // Setiap kelas akan memiliki 8-12 mata pelajaran yang diajarkan
            $jumlahMapel = rand(8, 12);
            $usedMapel = [];

            for ($i = 0; $i < $jumlahMapel; $i++) {
                // Pilih mapel yang belum digunakan untuk kelas ini
                $availableMapel = array_diff($mapelIds, $usedMapel);
                
                if (empty($availableMapel)) {
                    break;
                }

                $mapelId = $availableMapel[array_rand($availableMapel)];
                $usedMapel[] = $mapelId;

                $guruId = $guruIds[array_rand($guruIds)];

                // Create assignment record (tanpa jadwal spesifik)
                JadwalPelajaran::create([
                    'kelas_id' => $kelasId,
                    'mapel_id' => $mapelId,
                    'guru_id' => $guruId,
                    'hari' => null,
                    'jam_ke' => null,
                    'jam_mulai' => null,
                    'jam_selesai' => null,
                    'ruangan' => null,
                    'semester' => rand(1, 2),
                    'tahun_ajaran' => '2024/2025',
                    'keterangan' => 'Assignment - guru mengajar mapel [DUMMY DATA]',
                    'is_active' => true
                ]);

                $totalAssignment++;
            }
        }

        echo "\n=== JADWAL SEEDING COMPLETED ===\n";
        echo "Total Jadwal Terjadwal: $totalJadwal\n";
        echo "Total Assignment: $totalAssignment\n";
        echo "Total Records: " . ($totalJadwal + $totalAssignment) . "\n";
    }
}
