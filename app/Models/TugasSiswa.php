<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tugas;
use App\Models\Siswa;

class TugasSiswa extends Model
{
    use HasFactory;

    protected $table = 'tugas_siswa';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'file_path',
        'status', // submitted, graded 
        'nilai',
        'komentar',
        'tanggal_submit'
    ];

    protected $casts = [
        'tanggal_submit' => 'datetime'
    ];

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa() 
    {
        return $this->belongsTo(Siswa::class);
    }
}
