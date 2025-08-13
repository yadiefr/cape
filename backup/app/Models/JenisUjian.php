<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisUjian extends Model
{
    use HasFactory;

    protected $table = 'jenis_ujian';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'durasi_default',
        'is_active',
        'urutan'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'durasi_default' => 'integer',
        'urutan' => 'integer'
    ];

    // Scope untuk jenis ujian aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc')->orderBy('nama', 'asc');
    }

    // Relasi dengan jadwal ujian (jika ada)
    public function jadwalUjian()
    {
        return $this->hasMany(JadwalUjian::class, 'jenis_ujian', 'kode');
    }

    // Format durasi dalam menit ke jam:menit
    public function getDurasiFormattedAttribute()
    {
        if (!$this->durasi_default) return '-';
        
        $hours = floor($this->durasi_default / 60);
        $minutes = $this->durasi_default % 60;
        
        if ($hours > 0) {
            return $hours . ' jam' . ($minutes > 0 ? ' ' . $minutes . ' menit' : '');
        }
        
        return $minutes . ' menit';
    }
}
