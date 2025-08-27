<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use HasFactory;
    
    protected $table = 'pendaftaran';
    
    protected $fillable = [
        'nomor_pendaftaran',
        'nama_lengkap',
        'jenis_kelamin',
        'nisn',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'telepon',
        'email',
        'asal_sekolah',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'telepon_orangtua',
        'alamat_orangtua',
        'pilihan_jurusan_1',
        'nilai_matematika',
        'nilai_indonesia',
        'nilai_inggris',
        'status', // menunggu, diterima, ditolak, cadangan
        'user_id',
        'dokumen_ijazah',
        'dokumen_skhun',
        'dokumen_foto',
        'dokumen_kk',
        'dokumen_ktp_ortu',
        'tanggal_pendaftaran',
        'tahun_ajaran',
        'keterangan',
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_pendaftaran' => 'datetime',
    ];

    // Relasi ke jurusan pilihan 1
    public function jurusanPertama()
    {
        return $this->belongsTo(Jurusan::class, 'pilihan_jurusan_1');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Generate nomor pendaftaran
    public static function generateNomorPendaftaran()
    {
        $tahun = date('Y');
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return 'PPDB-' . $tahun . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
    
    // Dapatkan total pendaftar berdasarkan status
    public static function getCountByStatus($status)
    {
        return self::where('status', $status)
                  ->where('tahun_ajaran', Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1)))
                  ->count();
    }
    
    // Dapatkan total pendaftar berdasarkan jurusan
    public static function getCountByJurusan($jurusanId)
    {
        return self::where(function($query) use ($jurusanId) {
                      $query->where('pilihan_jurusan_1', $jurusanId)
                            ->orWhere('pilihan_jurusan_2', $jurusanId);
                  })
                  ->where('tahun_ajaran', Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1)))
                  ->count();
    }
}
