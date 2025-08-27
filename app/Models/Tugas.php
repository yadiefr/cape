<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\JadwalPelajaran;
use App\Models\TugasSiswa;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'jadwal_id', // keep for backward compatibility
        'guru_id',
        'kelas_id',
        'mapel_id',
        'judul',
        'deskripsi', 
        'deadline',
        'tanggal_deadline',
        'file_path',
        'file_name',
        'is_active'
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'tanggal_deadline' => 'date',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Legacy relationship - keep for backward compatibility
    public function jadwal()
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(TugasSiswa::class);
    }

    public function tugasSiswa()
    {
        return $this->hasMany(TugasSiswa::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByGuru($query, $guruId)
    {
        return $query->where('guru_id', $guruId);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByMapel($query, $mapelId)
    {
        return $query->where('mapel_id', $mapelId);
    }

    // Accessors
    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function getIsOverdueAttribute()
    {
        $deadline = $this->tanggal_deadline ?? $this->deadline;
        return $deadline ? now()->isAfter($deadline) : false;
    }

    public function getIsNearDeadlineAttribute()
    {
        $deadline = $this->tanggal_deadline ?? $this->deadline;
        if (!$deadline) return false;
        
        $daysUntilDeadline = now()->diffInDays($deadline, false);
        return $daysUntilDeadline <= 3 && $daysUntilDeadline >= 0;
    }

    // Helper methods
    public function isExpired()
    {
        $deadline = $this->tanggal_deadline ?? $this->deadline;
        return $deadline ? now()->isAfter($deadline) : false;
    }

    public function isNearDeadline()
    {
        $deadline = $this->tanggal_deadline ?? $this->deadline;
        if (!$deadline) return false;
        
        $daysUntilDeadline = now()->diffInDays($deadline, false);
        return $daysUntilDeadline <= 3 && $daysUntilDeadline >= 0;
    }
}
