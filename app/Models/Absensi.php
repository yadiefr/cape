<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'mapel_id',
        'tanggal',
        'status',
        'keterangan',
        'guru_id'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Boot method untuk menambahkan event listeners
    protected static function boot()
    {
        parent::boot();

        // Event listener sebelum create
        static::creating(function ($absensi) {
            // Cek duplikasi sebelum create
            $exists = static::where('siswa_id', $absensi->siswa_id)
                           ->where('kelas_id', $absensi->kelas_id)
                           ->where('mapel_id', $absensi->mapel_id)
                           ->whereDate('tanggal', $absensi->tanggal)
                           ->exists();

            if ($exists) {
                \Log::warning('Attempted to create duplicate absensi', [
                    'siswa_id' => $absensi->siswa_id,
                    'kelas_id' => $absensi->kelas_id,
                    'mapel_id' => $absensi->mapel_id,
                    'tanggal' => $absensi->tanggal
                ]);

                throw ValidationException::withMessages([
                    'absensi' => 'Data absensi untuk siswa ini pada tanggal dan mata pelajaran yang sama sudah ada.'
                ]);
            }
        });

        // Event listener sebelum update
        static::updating(function ($absensi) {
            // Cek duplikasi sebelum update (kecuali untuk record yang sama)
            $exists = static::where('siswa_id', $absensi->siswa_id)
                           ->where('kelas_id', $absensi->kelas_id)
                           ->where('mapel_id', $absensi->mapel_id)
                           ->whereDate('tanggal', $absensi->tanggal)
                           ->where('id', '!=', $absensi->id)
                           ->exists();

            if ($exists) {
                \Log::warning('Attempted to update creating duplicate absensi', [
                    'id' => $absensi->id,
                    'siswa_id' => $absensi->siswa_id,
                    'kelas_id' => $absensi->kelas_id,
                    'mapel_id' => $absensi->mapel_id,
                    'tanggal' => $absensi->tanggal
                ]);

                throw ValidationException::withMessages([
                    'absensi' => 'Data absensi untuk siswa ini pada tanggal dan mata pelajaran yang sama sudah ada.'
                ]);
            }
        });
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Relasi ke mata pelajaran
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    // Relasi ke kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Scope untuk filter berdasarkan tanggal
    public function scopeTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }

    // Scope untuk filter berdasarkan status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Method untuk membersihkan duplikasi
    public static function cleanupDuplicates($kelas_id, $mapel_id, $tanggal, $guru_id)
    {
        \Log::info('Starting cleanup duplicates', [
            'kelas_id' => $kelas_id,
            'mapel_id' => $mapel_id,
            'tanggal' => $tanggal,
            'guru_id' => $guru_id
        ]);

        // Ambil semua record yang duplikat
        $duplicateGroups = static::where('kelas_id', $kelas_id)
                                ->where('mapel_id', $mapel_id)
                                ->whereDate('tanggal', $tanggal)
                                ->where('guru_id', $guru_id)
                                ->get()
                                ->groupBy('siswa_id');

        $deletedCount = 0;
        
        foreach ($duplicateGroups as $siswaId => $records) {
            if ($records->count() > 1) {
                // Jika ada duplikat, pertahankan yang terbaru (ID tertinggi)
                $latestRecord = $records->sortByDesc('id')->first();
                $oldRecords = $records->where('id', '!=', $latestRecord->id);
                
                foreach ($oldRecords as $oldRecord) {
                    $oldRecord->delete();
                    $deletedCount++;
                    \Log::info('Deleted duplicate absensi', [
                        'id' => $oldRecord->id,
                        'siswa_id' => $siswaId,
                        'kept_id' => $latestRecord->id
                    ]);
                }
            }
        }

        \Log::info('Cleanup duplicates completed', ['deleted_count' => $deletedCount]);
        
        return $deletedCount;
    }
}
