<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'guru';    
    
    protected $fillable = [
        'nip',
        'nama',
        'email',
        'password',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'foto',
        'is_wali_kelas',
        'is_active',
    ];    

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'is_wali_kelas' => 'boolean',
    ];    

    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : asset('images/default-avatar.png');
    }

    public function getRoleAttribute()
    {
        return 'guru';
    }

    public function hasRole($role)
    {
        return $role === 'guru';
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalPelajaran::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas');
    }

    // Relasi untuk mendapatkan kelas yang dipimpin sebagai wali kelas (single kelas)
    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas');
    }

    // Pengawas relationships
    public function jadwalPengawas()
    {
        return $this->hasMany(JadwalPengawas::class, 'guru_id');
    }

    public function jadwalPengawasAktif()
    {
        return $this->hasMany(JadwalPengawas::class, 'guru_id')
                    ->where('is_active', true);
    }

    // Scope for active teachers only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get teacher's full name with NIP
    public function getNamaLengkapAttribute()
    {
        if (empty($this->nip)) {
            return $this->nama;
        }
        return $this->nama . ' (' . $this->nip . ')';
    }
}