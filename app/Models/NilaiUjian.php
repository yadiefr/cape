<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiUjian extends Model
{
    protected $table = 'nilai_ujian';

    protected $fillable = [
        'siswa_id',
        'bank_soal_id',
        'nilai',
        'catatan',
        'created_by',
        'updated_by'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
