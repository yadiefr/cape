<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPengawas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pengawas';

    protected $fillable = [
        'jadwal_ujian_id',
        'guru_id',
        'jenis_pengawas',
        'catatan',
        'is_hadir',
        'waktu_hadir',
        'keterangan_tidak_hadir',
        'is_active',
    ];

    protected $casts = [
        'is_hadir' => 'boolean',
        'is_active' => 'boolean',
        'waktu_hadir' => 'datetime',
    ];

    // Relationships
    public function jadwalUjian()
    {
        return $this->belongsTo(JadwalUjian::class, 'jadwal_ujian_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Accessors
    public function getJenisPengawasTextAttribute()
    {
        $jenis = [
            'utama' => 'Pengawas Utama',
            'pendamping' => 'Pengawas Pendamping',
            'cadangan' => 'Pengawas Cadangan'
        ];

        return $jenis[$this->jenis_pengawas] ?? $this->jenis_pengawas;
    }

    public function getJenisPengawasColorAttribute()
    {
        $colors = [
            'utama' => 'blue',
            'pendamping' => 'green',
            'cadangan' => 'yellow'
        ];

        return $colors[$this->jenis_pengawas] ?? 'gray';
    }

    public function getStatusKehadiranAttribute()
    {
        if ($this->is_hadir) {
            return 'Hadir';
        } elseif ($this->keterangan_tidak_hadir) {
            return 'Tidak Hadir';
        } else {
            return 'Belum Konfirmasi';
        }
    }

    public function getStatusKehadiranColorAttribute()
    {
        if ($this->is_hadir) {
            return 'green';
        } elseif ($this->keterangan_tidak_hadir) {
            return 'red';
        } else {
            return 'yellow';
        }
    }

    public function getWaktuHadirFormattedAttribute()
    {
        return $this->waktu_hadir ? $this->waktu_hadir->format('H:i') : '-';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pengawas', $jenis);
    }

    public function scopeHadir($query)
    {
        return $query->where('is_hadir', true);
    }

    public function scopeBelumHadir($query)
    {
        return $query->where('is_hadir', false);
    }

    public function scopeByJadwalUjian($query, $jadwalUjianId)
    {
        return $query->where('jadwal_ujian_id', $jadwalUjianId);
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }
}
