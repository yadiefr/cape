<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';

    protected $fillable = [
        'nama_ruangan',
        'kode_ruangan',
        'kapasitas',
        'lokasi',
        'fasilitas',
        'deskripsi',
        'is_active',
        'status'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'fasilitas' => 'array'
    ];

    // Scope untuk ruangan aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk ruangan tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'tersedia');
    }

    // Scope untuk ordering
    public function scopeOrdered($query)
    {
        return $query->orderBy('kode_ruangan');
    }

    // Relationship dengan kelas yang bisa menggunakan ruangan ini
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'ruangan_kelas', 'ruangan_id', 'kelas_id')
                    ->withPivot('is_active', 'kapasitas_maksimal')
                    ->withTimestamps();
    }

    // Get formatted capacity
    public function getFormattedKapasitasAttribute()
    {
        return $this->kapasitas . ' siswa';
    }

    // Get status badge
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'tersedia' => '<span class="badge bg-success">Tersedia</span>',
            'terpakai' => '<span class="badge bg-warning">Terpakai</span>',
            'maintenance' => '<span class="badge bg-danger">Maintenance</span>'
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    // Get fasilitas as string
    public function getFasilitasStringAttribute()
    {
        if (is_array($this->fasilitas)) {
            return implode(', ', $this->fasilitas);
        }
        return $this->fasilitas ?? '';
    }
}
