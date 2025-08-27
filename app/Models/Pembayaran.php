<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'siswa_id',
        'tagihan_id',
        'keterangan',
        'jumlah',
        'status',
        'tanggal',
        'metode_pembayaran',
        'order_id',
        'admin_id',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
    
}
