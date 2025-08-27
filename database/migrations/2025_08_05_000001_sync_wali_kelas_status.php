<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sinkronisasi status is_wali_kelas berdasarkan data di tabel kelas
        
        // 1. Reset semua guru menjadi bukan wali kelas
        DB::table('guru')->update(['is_wali_kelas' => false]);
        
        // 2. Update guru yang menjadi wali kelas berdasarkan tabel kelas
        $waliKelasIds = DB::table('kelas')
            ->whereNotNull('wali_kelas')
            ->distinct()
            ->pluck('wali_kelas');
        
        if ($waliKelasIds->isNotEmpty()) {
            DB::table('guru')
                ->whereIn('id', $waliKelasIds)
                ->update(['is_wali_kelas' => true]);
        }
        
        // Log hasil sinkronisasi
        $totalWaliKelas = DB::table('guru')->where('is_wali_kelas', true)->count();
        $totalKelasWithWali = DB::table('kelas')->whereNotNull('wali_kelas')->count();
        
        echo "Sinkronisasi selesai:\n";
        echo "- Total guru dengan status wali kelas: {$totalWaliKelas}\n";
        echo "- Total kelas yang memiliki wali kelas: {$totalKelasWithWali}\n";
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada reverse action untuk migration ini
        // karena ini adalah perbaikan data
    }
};
