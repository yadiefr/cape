<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GaleriFoto extends Model
{
    use HasFactory;
    protected $table = 'galeri_foto';
    protected $fillable = [
        'galeri_id', 'foto', 'is_thumbnail'
    ];

    public function galeri()
    {
        return $this->belongsTo(Galeri::class);
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        if (!$this->foto) {
            return null;
        }

        // Jika file ada di storage/app/public, gunakan storage URL
        if (Storage::disk('public')->exists($this->foto)) {
            return Storage::disk('public')->url($this->foto);
        }

        // Untuk hosting environment, coba path alternatif
        if (app()->environment() !== 'local') {
            // Coba path di public_html/storage
            $publicStoragePath = public_path('storage/' . $this->foto);
            if (file_exists($publicStoragePath)) {
                return asset('storage/' . $this->foto);
            }

            // Coba path di public_html/uploads/galeri (untuk file lama)
            $legacyPath = public_path('uploads/galeri/' . $this->foto);
            if (file_exists($legacyPath)) {
                return asset('uploads/galeri/' . $this->foto);
            }
        }

        // Fallback ke path lama untuk foto yang sudah ada
        return asset('uploads/galeri/' . $this->foto);
    }
}
