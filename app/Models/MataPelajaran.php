<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode',
        'nama',
        'kelas',
        'jurusan_id',
        'jenis',
        'tahun_ajaran',
        'kkm',
        'deskripsi',
        'materi_pokok',
        'is_unggulan'
    ];

    protected $casts = [
        'kkm' => 'integer',
        'is_unggulan' => 'boolean'
    ];

    // Relasi ke jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }



    // Relasi ke jadwal pelajaran
    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'mapel_id');
    }
    
    /**
     * Relasi dengan model PeriodeUjian (many-to-many melalui PeriodeMataPelajaran)
     */
    public function periodeUjian()
    {
        return $this->belongsToMany(PeriodeUjian::class, 'periode_mata_pelajaran', 'mata_pelajaran_id', 'periode_ujian_id')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
    
    /**
     * Relasi dengan model PeriodeMataPelajaran
     */
    public function periodeMataPelajaran()
    {
        return $this->hasMany(PeriodeMataPelajaran::class);
    }

    // Alias untuk jadwal() untuk backward compatibility
    public function jadwalPelajaran()
    {
        return $this->jadwal();
    }

    // Relasi ke nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mapel_id');
    }

    // Relasi ke materi
    public function materi()
    {
        return $this->hasMany(Materi::class, 'mapel_id');
    }

    // Relasi ke tugas
    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'mapel_id');
    }    // Scope untuk mapel aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
    
    // Compatibility scope for is_active usage
    public function scopeWhere($query, $column, $operator = null, $value = null)
    {
        // Handle the specific case of is_active, true
        if ($column === 'is_active' && $value === true) {
            return $query->where('status', 'Aktif');
        }
        
        // For all other cases, pass through to parent implementation
        return parent::scopeWhere($query, $column, $operator, $value);
    }

    // Scope untuk tingkat/kelas tertentu
    public function scopeKelas($query, $kelas)
    {
        return $query->where('kelas', 'LIKE', "%$kelas%");
    }

    // Scope untuk jurusan tertentu
    public function scopeJurusan($query, $jurusan_id)
    {
        return $query->where('jurusan_id', $jurusan_id);
    }

    // Scope untuk mata pelajaran unggulan
    public function scopeUnggulan($query)
    {
        return $query->where('is_unggulan', true);
    }
}