<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'jurusan_id',
        'tingkat',
        'wali_kelas',
        'tahun_ajaran',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relasi ke jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    // Relasi ke wali kelas (guru)
    public function wali()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas');
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    // Relasi ke jadwal pelajaran
    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    // Alias untuk jadwal pelajaran (untuk konsistensi)
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    // Relationship dengan ruangan ujian melalui pivot table
    public function ruanganUjian()
    {
        return $this->belongsToMany(RuanganUjian::class, 'ruangan_kelas', 'kelas_id', 'ruangan_ujian_id')
                   ->withPivot('kapasitas_kelas', 'keterangan', 'is_active')
                   ->withTimestamps();
    }

    // Scope untuk kelas aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk tahun ajaran tertentu
    public function scopeTahunAjaran($query, $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }

    // Method untuk mendapatkan nama lengkap kelas (contoh: "X RPL 1")
    public function getNamaLengkapAttribute()
    {
        $tingkat_romawi = ['X', 'XI', 'XII', 'XIII'];
        return $tingkat_romawi[$this->tingkat - 1] . ' ' . $this->jurusan->kode_jurusan . ' ' . $this->nama_kelas;
    }
}