<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\BelSekolah;

class BelApiController extends Controller
{
    /**
     * Cek bel yang harus dibunyikan pada waktu saat ini
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCurrentTime()
    {
        // Dapatkan waktu dan hari saat ini
        $now = Carbon::now();
        $currentTime = $now->format('H:i');
        $currentDay = $now->format('l'); // Dalam bahasa Inggris: Monday, Tuesday, dll
        $currentDayIndo = $this->translateDay($currentDay);
        
        // Log waktu dan hari saat ini untuk debugging
        Log::info('Checking bell at current time', [
            'time' => $currentTime,
            'day' => $currentDayIndo,
            'timestamp' => $now->toDateTimeString(),
            'request_path' => request()->path(),
            'request_url' => request()->url(),
            'referrer' => request()->header('referer')
        ]);
        
        // SIMPLIFIED: Coba pendekatan sederhana terlebih dahulu - cari semua bel aktif
        // yang waktunya sama dengan waktu saat ini dan harinya cocok
        $bells = BelSekolah::where('aktif', true)
            ->get();
            
        // Log untuk debugging semua bel
        Log::info('All active bells:', [
            'count' => $bells->count(),
            'bells' => $bells->map(fn($b) => ['id' => $b->id, 'nama' => $b->nama, 'waktu' => $b->waktu, 'hari' => $b->hari])
        ]);
        
        // Kode sederhana - hanya periksa dengan string format yang sama
        $bell = $bells->first(function($b) use ($currentTime, $currentDayIndo, $currentDay) {
            $bellTime = substr($b->waktu, 0, 5); // Ambil hanya "HH:MM" bagian
            
            $timeMatches = $bellTime === $currentTime;
            // Perbaikan: null atau kosong berarti setiap hari
            // Juga handle format hari dalam bahasa Inggris dan Indonesia
            $dayMatches = is_null($b->hari) || 
                         $b->hari === '' || 
                         $b->hari === 'Setiap Hari' || 
                         $b->hari === $currentDayIndo ||
                         $b->hari === $currentDay; // Support English day names
            
            // Log setiap bel dengan debug info 
            if ($timeMatches) {
                Log::info('Found bell with matching time:', [
                    'id' => $b->id,
                    'name' => $b->nama,
                    'bell_time' => $bellTime, 
                    'current_time' => $currentTime,
                    'day_matches' => $dayMatches,
                    'bell_day' => $b->hari,
                    'current_day_indo' => $currentDayIndo,
                    'current_day_english' => $currentDay
                ]);
            }
            
            return $timeMatches && $dayMatches;
        });
        
        // Log untuk debugging
        Log::info('Bell check result', [
            'time_checked' => $currentTime, 
            'day_checked' => $currentDayIndo,
            'bell_found' => $bell ? true : false, 
            'bell_details' => $bell
        ]);
            
        if ($bell) {
            // Menentukan jenis bel dari nama
            $type = $this->determineBellType($bell->nama);
            
            // Buat URL audio file jika ada, dengan cache busting
            $audioFile = null;
            if ($bell->file_suara) {
                $timestamp = time();
                $audioUrl = asset($bell->file_suara);
                $audioFile = "{$audioUrl}?t={$timestamp}";
            }
            
            // Tambahkan beberapa informasi waktu server untuk membantu debugging
            return response()->json([
                'shouldRing' => true,
                'server_time' => [
                    'full' => $now->toDateTimeString(),
                    'time' => $currentTime,
                    'day' => $currentDayIndo,
                    'timestamp' => $now->timestamp
                ],
                'bell' => [
                    'id' => $bell->id,
                    'nama' => $bell->nama,
                    'waktu' => $bell->waktu,
                    'hari' => $bell->hari,
                    'tipe' => $type,
                    'audio_file' => $audioFile,
                    'deskripsi' => $bell->deskripsi
                ],
                'message' => 'Bel ditemukan untuk waktu saat ini'
            ]);
        }
        
        // Jika tidak ada bel yang harus dibunyikan
        return response()->json([
            'shouldRing' => false,
            'server_time' => [
                'full' => $now->toDateTimeString(),
                'time' => $currentTime,
                'day' => $currentDayIndo,
                'timestamp' => $now->timestamp
            ],
            'bell' => null,
            'message' => 'Tidak ada bel untuk waktu saat ini'
        ]);
    }
    
    /**
     * Menerjemahkan nama hari dari bahasa Inggris ke Indonesia
     */
    private function translateDay($englishDay)
    {
        $days = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        
        return $days[$englishDay] ?? $englishDay;
    }
    
    /**
     * Menentukan jenis bel dari namanya
     */
    private function determineBellType($bellName)
    {
        $name = strtolower($bellName);
        
        if (strpos($name, 'istirahat') !== false || strpos($name, 'break') !== false) {
            return 'istirahat';
        } elseif (strpos($name, 'ujian') !== false || strpos($name, 'exam') !== false || strpos($name, 'test') !== false) {
            return 'ujian';
        } elseif (strpos($name, 'khusus') !== false || strpos($name, 'special') !== false || strpos($name, 'upacara') !== false) {
            return 'khusus';
        } else {
            return 'reguler';
        }
    }
}
