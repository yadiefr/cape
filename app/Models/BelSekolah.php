<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BelSekolah extends Model
{
    use HasFactory;
    
    /**
     * Nama tabel yang terkait dengan model
     *
     * @var string
     */
    protected $table = 'bel_sekolah';
    
    /**
     * Atribut yang dapat diisi
     *
     * @var array
     */
    protected $fillable = [
        'nama',
        'hari',
        'waktu',
        'file_suara',
        'deskripsi',
        'aktif',
        'tipe',
        'kode_warna',
        'ikon'
    ];

    /**
     * Atribut yang harus dikonversi
     *
     * @var array
     */
    protected $casts = [
        'aktif' => 'boolean',
        // Jangan cast waktu ke datetime karena kita hanya perlu string H:i
    ];

    /**
     * Scope untuk mendapatkan bel yang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk bel pada hari tertentu
     */
    public function scopeUntukHari($query, $hari)
    {
        return $query->where('hari', $hari)->orWhereNull('hari');
    }

    /**
     * Scope untuk mendapatkan bel berdasarkan tipe
     */
    public function scopeDenganTipe($query, $tipe)
    {
        return $query->where('tipe', $tipe);
    }
}
