<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class AdminUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'phone',
        'address',
        'birth_date',
        'gender',
        'photo',
        'status',
        'permissions',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'last_login_at' => 'datetime',
        'permissions' => 'array',
    ];

    // Role constants
    const ROLES = [
        'admin' => 'Super Admin',
        'guru' => 'Guru',
        'kurikulum' => 'Kurikulum',
        'tata_usaha' => 'Tata Usaha',
        'bendahara' => 'Bendahara',
        'hubin' => 'Hubungan Industri',
        'perpustakaan' => 'Perpustakaan',
        'kesiswaan' => 'Kesiswaan',
    ];

    // Role permissions
    const ROLE_PERMISSIONS = [
        'admin' => ['*'], // All permissions
        'guru' => ['view_siswa', 'manage_nilai', 'view_jadwal', 'manage_materi'],
        'kurikulum' => ['manage_kurikulum', 'manage_jadwal', 'view_siswa', 'manage_mapel'],
        'tata_usaha' => ['manage_siswa', 'view_keuangan', 'manage_administrasi'],
        'bendahara' => ['manage_keuangan', 'view_siswa', 'manage_pembayaran'],
        'hubin' => ['manage_pkl', 'manage_industri', 'view_siswa'],
        'perpustakaan' => ['manage_buku', 'manage_peminjaman', 'view_siswa'],
        'kesiswaan' => ['manage_siswa', 'manage_kegiatan', 'manage_absensi'],
    ];

    /**
     * Password mutator
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayAttribute()
    {
        return self::ROLES[$this->role] ?? $this->role;
    }

    /**
     * Get user permissions
     */
    public function getPermissionsAttribute($value)
    {
        $decoded = json_decode($value, true) ?? [];
        $rolePermissions = self::ROLE_PERMISSIONS[$this->role] ?? [];
        
        return array_unique(array_merge($rolePermissions, $decoded));
    }

    /**
     * Check if user has permission
     */
    public function hasPermission($permission)
    {
        $permissions = $this->permissions;
        return in_array('*', $permissions) || in_array($permission, $permissions);
    }

    /**
     * Check if user has role
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Scope for specific role
     */
    public function scopeRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Get photo URL
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/admin_photos/' . $this->photo);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Update last login
     */
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }
}
