<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    use HasFactory;

    protected $table = 'jurusan';    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
        'deskripsi',
        'kepala_jurusan',
        'is_active',
        'logo',
        'gambar_header',
        'visi',
        'misi',
        'prospek_karir'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi ke siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    // Relasi ke mata pelajaran
    public function mata_pelajaran()
    {
        return $this->hasMany(MataPelajaran::class);
    }

    // Relasi ke kepala jurusan (guru)
    public function kepala()
    {
        // Relationship to Guru as kepala jurusan (head of department)
        return $this->belongsTo(Guru::class, 'kepala_jurusan');
    }

    // Scope untuk jurusan aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}