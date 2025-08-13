<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class SettingsJadwal extends Model
{
    use HasFactory;

    protected $table = 'settings_jadwal';    protected $fillable = [
        'hari',
        'jam_mulai',
        'jam_selesai',
        'jam_istirahat_mulai',
        'jam_istirahat_selesai',
        'jam_istirahat2_mulai',
        'jam_istirahat2_selesai',
        'jumlah_jam_pelajaran',
        'durasi_per_jam',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'jumlah_jam_pelajaran' => 'integer',
        'durasi_per_jam' => 'integer',
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'jam_istirahat_mulai' => 'datetime:H:i',
        'jam_istirahat_selesai' => 'datetime:H:i',
        'jam_istirahat2_mulai' => 'datetime:H:i',
        'jam_istirahat2_selesai' => 'datetime:H:i'
    ];

    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($model) {
            Log::info('Retrieved SettingsJadwal:', ['model' => $model->toArray()]);
        });
    }

    // Scope untuk jadwal aktif
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // Method untuk mendapatkan durasi jadwal dalam menit
    public function getDurasiAttribute()
    {
        try {
            $jamMulai = \Carbon\Carbon::parse($this->jam_mulai);
            $jamSelesai = \Carbon\Carbon::parse($this->jam_selesai);
            
            return $jamSelesai->diffInMinutes($jamMulai);
        } catch (\Exception $e) {
            Log::error('Error calculating duration:', ['error' => $e->getMessage(), 'model' => $this->toArray()]);
            return 0;
        }
    }

    // Method untuk format jam
    public function getJamFormatAttribute()
    {
        try {
            return \Carbon\Carbon::parse($this->jam_mulai)->format('H:i') . ' - ' . 
                   \Carbon\Carbon::parse($this->jam_selesai)->format('H:i');
        } catch (\Exception $e) {
            Log::error('Error formatting time:', ['error' => $e->getMessage(), 'model' => $this->toArray()]);
            return '-';
        }
    }

    // Accessor untuk format jam istirahat
    public function getJamIstirahatFormatAttribute()
    {
        try {
            if ($this->jam_istirahat_mulai && $this->jam_istirahat_selesai) {
                return \Carbon\Carbon::parse($this->jam_istirahat_mulai)->format('H:i') . ' - ' . 
                       \Carbon\Carbon::parse($this->jam_istirahat_selesai)->format('H:i');
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error formatting break time:', ['error' => $e->getMessage(), 'model' => $this->toArray()]);
            return null;
        }
    }

    // Accessor untuk format jam istirahat 2
    public function getJamIstirahat2FormatAttribute()
    {
        try {
            if ($this->jam_istirahat2_mulai && $this->jam_istirahat2_selesai) {
                return \Carbon\Carbon::parse($this->jam_istirahat2_mulai)->format('H:i') . ' - ' . 
                       \Carbon\Carbon::parse($this->jam_istirahat2_selesai)->format('H:i');
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error formatting break time 2:', ['error' => $e->getMessage(), 'model' => $this->toArray()]);
            return null;
        }
    }
} 