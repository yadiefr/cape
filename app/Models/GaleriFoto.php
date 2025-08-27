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

        // Untuk hosting environment, gunakan path yang benar
        if (\App\Helpers\HostingStorageHelper::isHostingEnvironment()) {
            $paths = \App\Helpers\HostingStorageHelper::getHostingPaths();

            // Coba path di public_html/uploads (untuk file baru)
            $publicUploadsPath = $paths['public_uploads'] . '/' . $this->foto;
            if (file_exists($publicUploadsPath)) {
                // URL relatif dari public_html
                $relativePath = str_replace($paths['public_html'] . '/', '', $publicUploadsPath);
                return asset($relativePath);
            }

            // Coba path di public_html/uploads/galeri (untuk file lama)
            $legacyPath = $paths['public_html'] . '/uploads/galeri/' . $this->foto;
            if (file_exists($legacyPath)) {
                return asset('uploads/galeri/' . $this->foto);
            }

            // Coba path di public_html/storage (fallback)
            $publicStoragePath = $paths['public_storage'] . '/' . $this->foto;
            if (file_exists($publicStoragePath)) {
                $relativePath = str_replace($paths['public_html'] . '/', '', $publicStoragePath);
                return asset($relativePath);
            }
        }

        // Jika file ada di storage/app/public, gunakan storage URL
        if (Storage::disk('public')->exists($this->foto)) {
            return Storage::disk('public')->url($this->foto);
        }

        // Untuk localhost atau fallback
        // Coba path di public/storage
        $publicStoragePath = public_path('storage/' . $this->foto);
        if (file_exists($publicStoragePath)) {
            return asset('storage/' . $this->foto);
        }

        // Fallback ke path lama untuk foto yang sudah ada
        return asset('uploads/galeri/' . $this->foto);
    }
}
