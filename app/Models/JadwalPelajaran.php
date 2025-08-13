<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajaran';
    
    protected $fillable = [
        'kelas_id',
        'mapel_id',
        'guru_id',
        'hari',
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'semester',
        'tahun_ajaran',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

        /**
     * Scope for assignment entries only (teacher-subject assignments without specific schedule)
     * These entries have NULL values for scheduling fields (hari, jam_ke, jam_mulai, jam_selesai)
     * Used when assigning subjects to teachers without creating actual scheduled classes
     */
    public function scopeAssignments($query)
    {
        return $query->whereNull('hari')
                    ->whereNull('jam_ke')
                    ->whereNull('jam_mulai')
                    ->whereNull('jam_selesai');
    }

    /**
     * Scope for scheduled entries only (actual scheduled classes with specific times)
     * These entries have filled values for scheduling fields
     * Used for the actual jadwal/timetable display and management
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('hari')
                    ->whereNotNull('jam_ke')
                    ->whereNotNull('jam_mulai')
                    ->whereNotNull('jam_selesai');
    }

    /**
     * Check if this entry is an assignment (no specific schedule)
     */
    public function isAssignment()
    {
        return is_null($this->hari) && is_null($this->jam_ke);
    }

    /**
     * Check if this entry is a scheduled class (has specific time)
     */
    public function isScheduled()
    {
        return !is_null($this->hari) && !is_null($this->jam_ke);
    }

    // Mata pelajaran
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Alias untuk mata pelajaran (untuk konsistensi)
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
