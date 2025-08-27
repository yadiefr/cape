<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihan';

    protected $fillable = [
        'nama_tagihan',
        'keterangan',
        'nominal',
        'periode',
        'tanggal_jatuh_tempo',
        'kelas_id',
        'siswa_id',
    ];

    public function kelas()
    {
        return $this->belongsTo(\App\Models\Kelas::class, 'kelas_id');
    }

    public function siswa()
    {
        return $this->belongsTo(\App\Models\Siswa::class, 'siswa_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(\App\Models\Pembayaran::class, 'tagihan_id');
    }
}
