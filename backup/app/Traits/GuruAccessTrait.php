<?php

namespace App\Traits;

use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

trait GuruAccessTrait
{
    /**
     * Get kelas IDs that the authenticated guru teaches
     */
    public function getGuruKelasIds()
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return collect();
        }
        
        return JadwalPelajaran::where('guru_id', $guru->id)
                             ->where('is_active', true)
                             ->distinct()
                             ->pluck('kelas_id');
    }
    
    /**
     * Get mata pelajaran IDs that the authenticated guru teaches
     */
    public function getGuruMapelIds()
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return collect();
        }
        
        $mapelIds = JadwalPelajaran::where('guru_id', $guru->id)
                                 ->where('is_active', true)
                                 ->whereNotNull('mapel_id')
                                 ->distinct()
                                 ->pluck('mapel_id');
        
        return $mapelIds;
    }
    
    /**
     * Check if guru has access to specific kelas and mapel combination
     */
    public function hasAccessToKelasMapel($kelasId, $mapelId = null)
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return false;
        }
        
        $query = JadwalPelajaran::where('guru_id', $guru->id)
                               ->where('kelas_id', $kelasId)
                               ->where('is_active', true);
        
        if ($mapelId) {
            $query->where('mapel_id', $mapelId);
        }
        
        return $query->exists();
    }
    
    /**
     * Check if guru has access to specific kelas
     */
    public function hasAccessToKelas($kelasId)
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return false;
        }
        
        return JadwalPelajaran::where('guru_id', $guru->id)
                             ->where('kelas_id', $kelasId)
                             ->where('is_active', true)
                             ->exists();
    }
    
    /**
     * Check if guru has access to specific mata pelajaran
     */
    public function hasAccessToMapel($mapelId)
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return false;
        }
        
        return JadwalPelajaran::where('guru_id', $guru->id)
                             ->where('mapel_id', $mapelId)
                             ->where('is_active', true)
                             ->exists();
    }
    
    /**
     * Get siswa IDs from classes that the guru teaches
     */
    public function getGuruSiswaIds()
    {
        $kelasIds = $this->getGuruKelasIds();
        
        if ($kelasIds->isEmpty()) {
            return collect();
        }
        
        return \App\Models\Siswa::whereIn('kelas_id', $kelasIds)
                                ->pluck('id');
    }
    
    /**
     * Filter query to only include data from guru's classes
     */
    public function scopeGuruAccess($query, $kelasColumn = 'kelas_id')
    {
        $kelasIds = $this->getGuruKelasIds();
        
        return $query->whereIn($kelasColumn, $kelasIds);
    }
    
    /**
     * Filter query to only include data from guru's subjects
     */
    public function scopeGuruMapelAccess($query, $mapelColumn = 'mapel_id')
    {
        $mapelIds = $this->getGuruMapelIds();
        
        return $query->whereIn($mapelColumn, $mapelIds);
    }
    
    /**
     * Get unique kelas-mapel combinations that guru teaches
     */
    public function getGuruKelasMapelCombinations()
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return collect();
        }
        
        return JadwalPelajaran::where('guru_id', $guru->id)
                             ->where('is_active', true)
                             ->with(['kelas.siswa', 'kelas.jurusan', 'mataPelajaran'])
                             ->get()
                             ->filter(function ($jadwal) {
                                 return $jadwal->kelas !== null; // Filter out null kelas
                             })
                             ->groupBy('kelas_id')
                             ->map(function ($jadwalGroup) {
                                 $firstJadwal = $jadwalGroup->first();
                                 $kelas = $firstJadwal->kelas;
                                 
                                 if (!$kelas) {
                                     return null; // Skip if kelas is null
                                 }
                                 
                                 $mapels = $jadwalGroup->pluck('mataPelajaran')->filter();
                                 return [
                                     'kelas' => $kelas,
                                     'mapels' => $mapels,
                                     'mapel_count' => $mapels->count()
                                 ];
                             })
                             ->filter(); // Remove null entries
    }
    
    /**
     * Get all kelas IDs that guru has access to (teaching + wali kelas)
     */
    public function getAllGuruKelasIds()
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return collect();
        }
        
        // Get kelas from teaching schedule
        $jadwalRecords = JadwalPelajaran::where('guru_id', $guru->id)
                                      ->where('is_active', true)
                                      ->whereNotNull('kelas_id')
                                      ->whereNotNull('mapel_id')
                                      ->get();
        
        $kelasIdsFromJadwal = $jadwalRecords->pluck('kelas_id')
                                           ->filter(function($kelasId) {
                                               return !is_null($kelasId) && $kelasId > 0;
                                           })
                                           ->unique();
        
        // Get kelas from wali kelas
        $kelasIdsFromWali = collect();
        if ($guru->is_wali_kelas) {
            $kelasIdsFromWali = \App\Models\Kelas::where('wali_kelas', $guru->id)
                                                 ->pluck('id');
        }
        
        // Combine and return unique kelas IDs
        $allKelasIds = $kelasIdsFromJadwal->merge($kelasIdsFromWali)->unique();
        
        return $allKelasIds;
    }
    
    /**
     * Get ONLY kelas IDs from active teaching schedule (exclude wali kelas)
     */
    public function getStrictGuruKelasIds()
    {
        $guru = Auth::guard('guru')->user();
        
        if (!$guru) {
            return collect();
        }
        
        // ONLY get kelas from teaching schedule, NOT from wali kelas
        $kelasIds = JadwalPelajaran::where('guru_id', $guru->id)
                                 ->where('is_active', true)
                                 ->whereNotNull('kelas_id')
                                 ->whereNotNull('mapel_id')
                                 ->distinct()
                                 ->pluck('kelas_id')
                                 ->filter();
        
        return $kelasIds;
    }
}
