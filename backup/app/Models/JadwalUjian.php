<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalUjian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ujian';

    protected $fillable = [
        'nama_ujian',
        'jenis_ujian',
        'mata_pelajaran_id',
        'kelas_id',
        'guru_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'bank_soal_id',
        'ruangan_id',
        'status',
        'deskripsi',
        'acak_soal',
        'acak_jawaban',
        'tampilkan_hasil',
        'max_peserta',
        'is_active',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
        'tampilkan_hasil' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'status_color',
        'status_text',
        'waktu_mulai_formatted',
        'waktu_selesai_formatted',
        'tanggal_formatted',
        'jenis_ujian_nama',
    ];

    // Relationships
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function jenisUjian()
    {
        return $this->belongsTo(JenisUjian::class, 'jenis_ujian', 'kode');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class, 'bank_soal_id');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    // Pengawas relationships
    public function jadwalPengawas()
    {
        return $this->hasMany(JadwalPengawas::class, 'jadwal_ujian_id');
    }

    // Alias untuk kemudahan akses
    public function pengawas()
    {
        return $this->hasMany(JadwalPengawas::class, 'jadwal_ujian_id')
                    ->where('is_active', true)
                    ->with('guru');
    }

    public function pengawasUtama()
    {
        return $this->hasMany(JadwalPengawas::class, 'jadwal_ujian_id')
                    ->where('jenis_pengawas', 'utama')
                    ->where('is_active', true);
    }

    public function pengawasPendamping()
    {
        return $this->hasMany(JadwalPengawas::class, 'jadwal_ujian_id')
                    ->where('jenis_pengawas', 'pendamping')
                    ->where('is_active', true);
    }

    public function pengawasCadangan()
    {
        return $this->hasMany(JadwalPengawas::class, 'jadwal_ujian_id')
                    ->where('jenis_pengawas', 'cadangan')
                    ->where('is_active', true);
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'draft' => 'gray',
            'scheduled' => 'blue',
            'active' => 'green',
            'completed' => 'purple',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'scheduled' => 'Terjadwal',
            'active' => 'Sedang Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    public function getWaktuMulaiFormattedAttribute()
    {
        return $this->waktu_mulai ? Carbon::parse($this->waktu_mulai)->format('H:i') : '';
    }

    public function getWaktuSelesaiFormattedAttribute()
    {
        return $this->waktu_selesai ? Carbon::parse($this->waktu_selesai)->format('H:i') : '';
    }

    public function getTanggalFormattedAttribute()
    {
        return $this->tanggal ? Carbon::parse($this->tanggal)->format('d/m/Y') : '';
    }

    // Accessor untuk jenis ujian sebagai fallback
    public function getJenisUjianNamaAttribute()
    {
        return $this->jenisUjian ? $this->jenisUjian->nama : ucfirst($this->jenis_ujian);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeByMataPelajaran($query, $mapelId)
    {
        return $query->where('mata_pelajaran_id', $mapelId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', Carbon::today());
    }

    // Methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isScheduled()
    {
        return $this->status === 'scheduled';
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'scheduled']);
    }

    public function canBeDeleted()
    {
        return $this->status === 'draft';
    }

    public function calculateEndTime()
    {
        if ($this->waktu_mulai && $this->durasi) {
            $startTime = Carbon::parse($this->waktu_mulai);
            return $startTime->addMinutes((int)$this->durasi);
        }
        return null;
    }
}
