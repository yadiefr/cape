<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'whatsapp',
        'nisn',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Handle password hashing using a mutator
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Relasi ke kelas (untuk siswa)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke kelas yang diwalikan (untuk guru)
    public function kelas_wali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas');
    }

    // Relasi ke jadwal mengajar (untuk guru)
    public function jadwal_mengajar()
    {
        return $this->hasMany(JadwalPelajaran::class, 'guru_id');
    }

    // Relasi ke nilai (untuk siswa)
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    // Relasi ke absensi (untuk siswa)
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    // Relasi ke pengumuman yang dibuat (untuk admin/guru)
    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'author_id');
    }

    // Helper method untuk cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }

    /**
     * Check if user is kepala sekolah
     */
    public function isKepsek()
    {
        return $this->role === 'kepsek';
    }

    /**
     * Check if user is TU
     */
    public function isTU()
    {
        return $this->role === 'tu';
    }

    /**
     * Relationship to mata pelajaran for teachers
     */
    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class, 'guru_id');
    }

    /**
     * Relasi ke model Guru menggunakan email sebagai penghubung.
     */
    public function guru()
    {
        return $this->hasOne(Guru::class, 'email', 'email');
    }

    /**
     * Relationship to pendaftaran for PPDB registrants
     */
    public function pendaftaran()
    {
        return $this->hasOne(Pendaftaran::class);
    }

    /**
     * Check if user is PPDB registrant
     */
    public function isPendaftar()
    {
        return $this->role === 'pendaftar';
    }

}

// Model ini sudah tidak dipakai, bisa dihapus jika sudah tidak ada dependensi.
// Jika ingin tetap ada model admin, buat model Admin baru untuk tabel admin.
