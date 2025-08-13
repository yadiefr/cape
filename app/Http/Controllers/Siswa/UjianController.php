<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjian;
use App\Models\JawabanUjianSiswa;
use App\Models\NilaiUjian;
use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UjianController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        try {
            // Ujian yang tersedia untuk siswa (berdasarkan kelas)
            $ujianTersedia = JadwalUjian::with(['mataPelajaran', 'guru', 'bankSoal', 'kelas'])
                              ->where('status', 'aktif')
                              ->where('kelas_id', $siswa->kelas_id)
                              ->where('waktu_mulai', '<=', now())
                              ->where('waktu_selesai', '>=', now())
                              ->orderBy('waktu_mulai', 'asc')
                              ->get();

            // Ujian mendatang
            $ujianMendatang = JadwalUjian::with(['mataPelajaran', 'guru', 'bankSoal', 'kelas'])
                                  ->where('status', 'aktif')
                                  ->where('kelas_id', $siswa->kelas_id)
                                  ->where('waktu_mulai', '>', now())
                                  ->orderBy('waktu_mulai', 'asc')
                                  ->get();

            // Ujian yang sudah selesai
            $ujianSelesai = JadwalUjian::with(['mataPelajaran', 'guru', 'bankSoal', 'kelas'])
                                ->where('status', 'aktif')
                                ->where('kelas_id', $siswa->kelas_id)
                                ->where('waktu_selesai', '<', now())
                                ->orderBy('waktu_selesai', 'desc')
                                ->get();

            // Hasil ujian siswa
            $hasilUjian = NilaiUjian::with(['bankSoal'])
                                   ->where('siswa_id', $siswa->id)
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        } catch (\Exception $e) {
            $ujianTersedia = collect();
            $ujianMendatang = collect();
            $ujianSelesai = collect();
            $hasilUjian = collect();
        }
        
        return view('siswa.ujian.index', compact(
            'ujianTersedia',
            'ujianMendatang', 
            'ujianSelesai',
            'hasilUjian'
        ));
    }

    public function show($id)
    {
        $ujian = JadwalUjian::with(['mataPelajaran', 'guru', 'bankSoal', 'kelas'])
                            ->findOrFail($id);
        
        $siswa = Auth::guard('siswa')->user();
        
        // Cek apakah ujian untuk kelas siswa
        if ($ujian->kelas_id != $siswa->kelas_id) {
            abort(403, 'Ujian ini tidak untuk kelas Anda');
        }
        
        // Cek apakah ujian sedang berlangsung
        $now = now();
        $canTakeExam = $ujian->waktu_mulai <= $now && $ujian->waktu_selesai >= $now;
        
        // Cek apakah sudah mengerjakan
        $hasilUjian = NilaiUjian::where('siswa_id', $siswa->id)
                                ->where('bank_soal_id', $ujian->bank_soal_id)
                                ->first();
        
        return view('siswa.ujian.show', compact(
            'ujian',
            'canTakeExam',
            'hasilUjian'
        ));
    }

    public function start($id)
    {
        $ujian = JadwalUjian::with(['bankSoal.soal'])
                            ->findOrFail($id);
        
        $siswa = Auth::guard('siswa')->user();
        
        // Validasi ujian
        if ($ujian->kelas_id != $siswa->kelas_id) {
            return redirect()->back()->with('error', 'Ujian ini tidak untuk kelas Anda');
        }
        
        $now = now();
        if ($ujian->waktu_mulai > $now || $ujian->waktu_selesai < $now) {
            return redirect()->back()->with('error', 'Ujian tidak sedang berlangsung');
        }
        
        // Cek apakah sudah mengerjakan
        $hasilUjian = NilaiUjian::where('siswa_id', $siswa->id)
                                ->where('bank_soal_id', $ujian->bank_soal_id)
                                ->first();
        
        if ($hasilUjian) {
            return redirect()->back()->with('error', 'Anda sudah mengerjakan ujian ini');
        }
        
        return view('siswa.ujian.exam', compact('ujian'));
    }

    public function submit(Request $request, $id)
    {
        $ujian = JadwalUjian::with(['bankSoal.soal'])
                            ->findOrFail($id);
        
        $siswa = Auth::guard('siswa')->user();
        
        // Validasi ujian
        if ($ujian->kelas_id != $siswa->kelas_id) {
            return response()->json(['error' => 'Ujian ini tidak untuk kelas Anda'], 403);
        }
        
        // Cek apakah sudah mengerjakan
        $hasilUjian = NilaiUjian::where('siswa_id', $siswa->id)
                                ->where('bank_soal_id', $ujian->bank_soal_id)
                                ->first();
        
        if ($hasilUjian) {
            return response()->json(['error' => 'Anda sudah mengerjakan ujian ini'], 400);
        }
        
        try {
            $jawaban = $request->input('jawaban', []);
            $totalSoal = $ujian->bankSoal->soal->count();
            $jawabanBenar = 0;
            
            // Simpan jawaban dan hitung nilai
            foreach ($jawaban as $soalId => $jawabanSiswa) {
                $soal = $ujian->bankSoal->soal->find($soalId);
                if ($soal) {
                    $isCorrect = $soal->jawaban_benar === $jawabanSiswa;
                    if ($isCorrect) {
                        $jawabanBenar++;
                    }
                    
                    JawabanUjianSiswa::create([
                        'siswa_id' => $siswa->id,
                        'bank_soal_id' => $ujian->bank_soal_id,
                        'jawaban' => $jawabanSiswa,
                        'is_correct' => $isCorrect,
                        'score' => $isCorrect ? 1 : 0,
                        'waktu_mulai' => $request->input('waktu_mulai'),
                        'waktu_selesai' => now(),
                    ]);
                }
            }
            
            // Hitung nilai akhir
            $nilai = $totalSoal > 0 ? ($jawabanBenar / $totalSoal) * 100 : 0;
            
            // Simpan nilai
            NilaiUjian::create([
                'siswa_id' => $siswa->id,
                'bank_soal_id' => $ujian->bank_soal_id,
                'nilai' => $nilai,
                'catatan' => "Ujian {$ujian->nama_ujian} - {$jawabanBenar}/{$totalSoal} benar"
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil diselesaikan',
                'nilai' => $nilai,
                'redirect' => route('siswa.ujian.index')
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan jawaban'], 500);
        }
    }
}
