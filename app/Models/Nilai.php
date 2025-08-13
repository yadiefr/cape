<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai'; // Ensure this matches your database table name

    protected $fillable = [
        'siswa_id',
        'mapel_id',
        'nilai',
        'nilai_tugas',
        'nilai_uts', 
        'nilai_uas',
        'nilai_praktik',
        'nilai_akhir',
        'semester',
        'tahun_ajaran',
        'jenis_nilai', // Tambahkan jenis_nilai
        'grade',
        'catatan',
        'deskripsi',
        'bank_soal_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Get the student that owns the nilai.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Get the mata pelajaran that owns the nilai.
     */
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * Alias for mapel relationship (for backward compatibility).
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }


}
