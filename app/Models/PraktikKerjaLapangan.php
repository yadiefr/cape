<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktikKerjaLapangan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'praktik_kerja_lapangan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'siswa_id',
        'nama_perusahaan',
        'alamat_perusahaan',
        'bidang_usaha',
        'nama_pembimbing',
        'telepon_pembimbing',
        'email_pembimbing',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'nilai_teknis',
        'nilai_sikap',
        'nilai_laporan',
        'dokumen_laporan',
        'surat_keterangan',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the student that owns the internship.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
