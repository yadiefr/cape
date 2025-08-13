<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JawabanSiswa extends Model
{
    use HasFactory;

    protected $table = 'jawaban_siswa';

    protected $fillable = [
        'ujian_siswa_id',
        'soal_ujian_id',
        'jawaban',
        'jawaban_essay',
        'jawaban_benar_salah',
        'is_correct',
        'nilai',
        'waktu_jawab',
        'durasi_jawab',
        'ragu_ragu'
    ];

    protected $casts = [
        'jawaban_benar_salah' => 'boolean',
        'is_correct' => 'boolean',
        'ragu_ragu' => 'boolean',
        'waktu_jawab' => 'datetime',
        'nilai' => 'decimal:2',
    ];

    // Relationships
    public function ujianSiswa()
    {
        return $this->belongsTo(UjianSiswa::class);
    }

    public function soalUjian()
    {
        return $this->belongsTo(SoalUjian::class);
    }

    // Accessor
    public function getWaktuJawabFormatAttribute()
    {
        return $this->waktu_jawab ? Carbon::parse($this->waktu_jawab)->format('d M Y H:i:s') : null;
    }

    public function getDurasiJawabFormatAttribute()
    {
        if (!$this->durasi_jawab) return null;

        $menit = intval($this->durasi_jawab / 60);
        $detik = $this->durasi_jawab % 60;
        
        if ($menit > 0) {
            return $menit . ' menit ' . $detik . ' detik';
        }
        return $detik . ' detik';
    }

    public function getJawabanTextAttribute()
    {
        $soal = $this->soalUjian;
        
        switch ($soal->jenis_soal) {
            case 'pilihan_ganda':
                return $this->jawaban ? strtoupper($this->jawaban) : 'Tidak dijawab';
                
            case 'benar_salah':
                if ($this->jawaban_benar_salah === null) return 'Tidak dijawab';
                return $this->jawaban_benar_salah ? 'Benar' : 'Salah';
                
            case 'essay':
                return $this->jawaban_essay ?: 'Tidak dijawab';
                
            default:
                return 'Tidak dijawab';
        }
    }

    public function getStatusJawabanAttribute()
    {
        if ($this->is_correct === null) {
            return 'Belum dikoreksi';
        }
        
        return $this->is_correct ? 'Benar' : 'Salah';
    }

    public function getStatusColorAttribute()
    {
        if ($this->is_correct === null) {
            return 'warning'; // Belum dikoreksi
        }
        
        return $this->is_correct ? 'success' : 'danger';
    }

    // Helper methods
    public function simpanJawaban($jawaban, $waktuMulaiSoal = null)
    {
        $soal = $this->soalUjian;
        $waktuSekarang = now();
        
        // Hitung durasi jika ada waktu mulai soal
        $durasi = null;
        if ($waktuMulaiSoal) {
            $durasi = Carbon::parse($waktuMulaiSoal)->diffInSeconds($waktuSekarang);
        }

        // Siapkan data untuk update
        $data = [
            'waktu_jawab' => $waktuSekarang,
            'durasi_jawab' => $durasi,
        ];

        // Set jawaban berdasarkan jenis soal
        switch ($soal->jenis_soal) {
            case 'pilihan_ganda':
                $data['jawaban'] = strtoupper($jawaban);
                $data['is_correct'] = $soal->cekJawaban($jawaban);
                break;
                
            case 'benar_salah':
                $data['jawaban_benar_salah'] = (bool) $jawaban;
                $data['is_correct'] = $soal->cekJawaban($jawaban);
                break;
                
            case 'essay':
                $data['jawaban_essay'] = $jawaban;
                // Essay tidak bisa dikoreksi otomatis
                $data['is_correct'] = null;
                break;
        }

        $this->update($data);
    }

    public function setRaguRagu($ragu = true)
    {
        $this->update(['ragu_ragu' => $ragu]);
    }

    public function koreksiManual($benar, $nilai = null, $komentar = null)
    {
        $data = [
            'is_correct' => $benar,
            'nilai' => $nilai
        ];

        $this->update($data);

        // Update nilai ujian siswa
        $this->ujianSiswa->hitungNilai();
    }

    // Scope
    public function scopeByUjianSiswa($query, $ujianSiswaId)
    {
        return $query->where('ujian_siswa_id', $ujianSiswaId);
    }

    public function scopeBySoal($query, $soalId)
    {
        return $query->where('soal_ujian_id', $soalId);
    }

    public function scopeBenar($query)
    {
        return $query->where('is_correct', true);
    }

    public function scopeSalah($query)
    {
        return $query->where('is_correct', false);
    }

    public function scopeBelumDikoreksi($query)
    {
        return $query->whereNull('is_correct');
    }

    public function scopeRaguRagu($query)
    {
        return $query->where('ragu_ragu', true);
    }
}
