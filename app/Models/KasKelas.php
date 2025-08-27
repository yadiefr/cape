<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasKelas extends Model
{
    use HasFactory;

    protected $table = 'kas_kelas';

    protected $fillable = [
        'kelas_id',
        'tipe',
        'kategori',
        'keterangan',
        'nominal',
        'tanggal',
        'diinput_oleh',
        'created_by',
        'siswa_id',
        'catatan',
        'bukti_transaksi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'nominal' => 'decimal:2'
    ];

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke siswa yang input
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // Relasi ke siswa yang membuat transaksi (diinput_oleh atau created_by)
    public function createdBy()
    {
        return $this->belongsTo(Siswa::class, 'diinput_oleh');
    }

    // Alias untuk createdBy jika menggunakan created_by field
    public function inputBy()
    {
        return $this->belongsTo(Siswa::class, 'created_by');
    }

    // Scope untuk filter berdasarkan tipe
    public function scopeTipeMasuk($query)
    {
        return $query->where('tipe', 'masuk');
    }

    public function scopeTipeKeluar($query)
    {
        return $query->where('tipe', 'keluar');
    }

    // Scope untuk filter berdasarkan kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('tanggal', $month)->whereYear('tanggal', $year);
    }
}
