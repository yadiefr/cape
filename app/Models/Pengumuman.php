<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'judul',
        'isi',
        'author_id',
        'author_type',
        'target_role', // all, guru, siswa, tu
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'lampiran',
        'show_on_homepage'
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_active' => 'boolean',
        'show_on_homepage' => 'boolean'
    ];

    // Relasi ke pembuat pengumuman (polymorphic)
    public function author()
    {
        return $this->morphTo();
    }

    // Scope untuk pengumuman aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true)
            ->where('tanggal_mulai', '<=', now())
            ->where(function($q) {
                $q->where('tanggal_selesai', '>=', now())
                  ->orWhereNull('tanggal_selesai');
            });
    }

    // Scope untuk target role tertentu
    public function scopeForRole($query, $role)
    {
        return $query->where(function($q) use ($role) {
            $q->where('target_role', $role)
              ->orWhere('target_role', 'all');
        });
    }

    // Rules validasi
    public static $rules = [
        'judul' => 'required|string|max:255',
        'isi' => 'required|string',
        'author_id' => 'required|exists:guru,id',
        'target_role' => 'required|in:all,guru,siswa,tu',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'nullable|date|after:tanggal_mulai',
        'is_active' => 'boolean',
        'lampiran' => 'nullable|file|max:10240' // max 10MB
    ];
}