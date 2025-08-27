<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';
    protected $fillable = [
        'judul', 'isi', 'foto', 'lampiran'
    ];

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

            // Coba path di public_html/uploads/berita_foto (untuk file lama)
            $legacyPath = $paths['public_html'] . '/uploads/berita_foto/' . $this->foto;
            if (file_exists($legacyPath)) {
                return asset('uploads/berita_foto/' . $this->foto);
            }

            // Coba path di public_html/storage (fallback)
            $publicStoragePath = $paths['public_storage'] . '/' . $this->foto;
            if (file_exists($publicStoragePath)) {
                $relativePath = str_replace($paths['public_html'] . '/', '', $publicStoragePath);
                return asset($relativePath);
            }
        }

        // Jika file ada di storage/app/public, gunakan storage URL
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->foto)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->foto);
        }

        // Untuk localhost atau fallback
        // Coba path di public/storage
        $publicStoragePath = public_path('storage/' . $this->foto);
        if (file_exists($publicStoragePath)) {
            return asset('storage/' . $this->foto);
        }

        // Fallback ke path lama untuk foto yang sudah ada
        return asset('uploads/berita_foto/' . $this->foto);
    }

    // Accessor untuk URL lampiran
    public function getLampiranUrlAttribute()
    {
        if (!$this->lampiran) {
            return null;
        }

        // Untuk hosting environment, gunakan path yang benar
        if (\App\Helpers\HostingStorageHelper::isHostingEnvironment()) {
            $paths = \App\Helpers\HostingStorageHelper::getHostingPaths();

            // Coba path di public_html/uploads (untuk file baru)
            $publicUploadsPath = $paths['public_uploads'] . '/' . $this->lampiran;
            if (file_exists($publicUploadsPath)) {
                // URL relatif dari public_html
                $relativePath = str_replace($paths['public_html'] . '/', '', $publicUploadsPath);
                return asset($relativePath);
            }

            // Coba path di public_html/uploads/lampiran_berita (untuk file lama)
            $legacyPath = $paths['public_html'] . '/uploads/lampiran_berita/' . $this->lampiran;
            if (file_exists($legacyPath)) {
                return asset('uploads/lampiran_berita/' . $this->lampiran);
            }

            // Coba path di public_html/storage (fallback)
            $publicStoragePath = $paths['public_storage'] . '/' . $this->lampiran;
            if (file_exists($publicStoragePath)) {
                $relativePath = str_replace($paths['public_html'] . '/', '', $publicStoragePath);
                return asset($relativePath);
            }
        }

        // Jika file ada di storage/app/public, gunakan storage URL
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->lampiran)) {
            return \Illuminate\Support\Facades\Storage::disk('public')->url($this->lampiran);
        }

        // Untuk localhost atau fallback
        // Coba path di public/storage
        $publicStoragePath = public_path('storage/' . $this->lampiran);
        if (file_exists($publicStoragePath)) {
            return asset('storage/' . $this->lampiran);
        }

        // Fallback ke path lama untuk lampiran yang sudah ada
        return asset('uploads/lampiran_berita/' . $this->lampiran);
    }
}
