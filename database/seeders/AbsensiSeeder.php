<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use Carbon\Carbon;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua siswa
        $siswa = Siswa::all();
        
        // Ambil semua jadwal pelajaran
        $jadwal = JadwalPelajaran::with(['kelas', 'mapel'])->get();
        
        // Buat data absensi untuk 7 hari terakhir
        for ($i = 0; $i < 7; $i++) {
            $tanggal = Carbon::now()->subDays($i);
            
            foreach ($jadwal as $j) {
                // Ambil siswa untuk kelas ini
                $siswa_kelas = $siswa->where('kelas_id', $j->kelas_id);
                
                foreach ($siswa_kelas as $s) {
                    // Random status: 70% hadir, 10% izin, 10% sakit, 10% alpha
                    $rand = rand(1, 100);
                    if ($rand <= 70) {
                        $status = 'hadir';
                        $keterangan = null;
                    } elseif ($rand <= 80) {
                        $status = 'izin';
                        $keterangan = 'Izin keperluan keluarga';
                    } elseif ($rand <= 90) {
                        $status = 'sakit';
                        $keterangan = 'Sakit demam';
                    } else {
                        $status = 'alpha';
                        $keterangan = 'Tidak ada keterangan';
                    }
                    
                    Absensi::create([
                        'siswa_id' => $s->id,
                        'kelas_id' => $j->kelas_id,
                        'mapel_id' => $j->mapel_id,
                        'guru_id' => $j->guru_id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                        'status' => $status,
                        'keterangan' => $keterangan
                    ]);
                }
            }
        }
    }
}
