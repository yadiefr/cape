<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeri';
    protected $fillable = [
        'judul', 'deskripsi', 'gambar', 'kategori'
    ];

    public function foto()
    {
        return $this->hasMany(GaleriFoto::class);
    }

    public function thumbnail()
    {
        return $this->hasOne(GaleriFoto::class)->where('is_thumbnail', true);
    }
}
