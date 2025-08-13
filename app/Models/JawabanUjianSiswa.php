<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanUjianSiswa extends Model
{
    use HasFactory;

    protected $table = 'jawaban_ujian_siswa';

    protected $fillable = [
        'siswa_id',
        'bank_soal_id',
        'jawaban',
        'is_correct',
        'score',
        'waktu_mulai',
        'waktu_selesai'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score' => 'float',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime'
    ];

    // Relationships
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }
}
