<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'siswa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nis',
        'nisn',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'telepon',
        'email',
        'kelas_id',
        'jurusan_id',
        'tahun_masuk',
        'status',
        'is_ketua_kelas',
        'is_bendahara',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'no_telp_ayah',
        'no_telp_ibu',
        'alamat_orangtua',
        'foto',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the class that the student belongs to.
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Get the major that the student belongs to.
     */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    /**
     * Get the student's grades.
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }    /**
     * Get the student's attendance records.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Get the student's internship (PKL) records.
     */
    public function praktikKerjaLapangan()
    {
        return $this->hasMany(PraktikKerjaLapangan::class);
    }

    /**
     * Get the student's exam records.
     */
    public function ujianSiswa()
    {
        return $this->hasMany(PesertaUjian::class);
    }

    /**
     * Get the student's exam participations.
     */
    public function ujians()
    {
        return $this->belongsToMany(Ujian::class, 'peserta_ujian')
                    ->withPivot(['status_peserta', 'waktu_mulai', 'waktu_selesai', 'nilai', 'is_selesai'])
                    ->withTimestamps();
    }

    /**
     * Get the student's exam answers.
     */
    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class, 'ujian_siswa_id', 'id')
                    ->join('ujian_siswa', 'jawaban_siswa.ujian_siswa_id', '=', 'ujian_siswa.id')
                    ->where('ujian_siswa.siswa_id', $this->id);
    }

    /**
     * Get the student's payments.
     */
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'siswa_id');
    }      /**
     * Password mutator untuk memastikan password selalu di-hash jika belum di-hash
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = str_starts_with($value, '$2y$') ? $value : bcrypt($value);
        }
    }

    /**
     * Get the student's role attribute.
     */
    public function getRoleAttribute()
    {
        return 'siswa';
    }

    /**
     * Check if the student has a specific role.
     */
    public function hasRole($role)
    {
        return $role === 'siswa';
    }
}
