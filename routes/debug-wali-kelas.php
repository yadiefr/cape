<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/debug-wali-kelas', function() {
    echo "<h2>Debug Status Wali Kelas</h2>";
    
    // 1. Cek guru yang statusnya is_wali_kelas = true
    $guruWaliKelas = DB::table('guru')
        ->where('is_wali_kelas', true)
        ->get();
    
    echo "<h3>Guru dengan status is_wali_kelas = true:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<tr><th>ID</th><th>Nama</th><th>NIP</th><th>is_wali_kelas</th><th>Kelas yang dipimpin</th></tr>";
    
    foreach($guruWaliKelas as $guru) {
        $kelas = DB::table('kelas')
            ->where('wali_kelas', $guru->id)
            ->first();
        
        echo "<tr>";
        echo "<td>{$guru->id}</td>";
        echo "<td>{$guru->nama}</td>";
        echo "<td>{$guru->nip}</td>";
        echo "<td>" . ($guru->is_wali_kelas ? 'true' : 'false') . "</td>";
        echo "<td>" . ($kelas ? $kelas->nama_kelas : 'Tidak ada kelas') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 2. Cek kelas yang memiliki wali kelas
    $kelasWithWali = DB::table('kelas')
        ->leftJoin('guru', 'kelas.wali_kelas', '=', 'guru.id')
        ->whereNotNull('kelas.wali_kelas')
        ->select('kelas.*', 'guru.nama as nama_guru', 'guru.is_wali_kelas')
        ->get();
    
    echo "<h3>Kelas yang memiliki wali kelas:</h3>";
    echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<tr><th>Kelas ID</th><th>Nama Kelas</th><th>Wali Kelas ID</th><th>Nama Guru</th><th>Status is_wali_kelas</th></tr>";
    
    foreach($kelasWithWali as $kelas) {
        echo "<tr>";
        echo "<td>{$kelas->id}</td>";
        echo "<td>{$kelas->nama_kelas}</td>";
        echo "<td>{$kelas->wali_kelas}</td>";
        echo "<td>{$kelas->nama_guru}</td>";
        echo "<td>" . ($kelas->is_wali_kelas ? 'true' : 'false') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 3. Cek inkonsistensi
    echo "<h3>Cek Inkonsistensi:</h3>";
    
    $inconsistentGuru = DB::table('guru')
        ->leftJoin('kelas', 'guru.id', '=', 'kelas.wali_kelas')
        ->where(function($query) {
            $query->where('guru.is_wali_kelas', true)
                  ->whereNull('kelas.wali_kelas');
        })
        ->orWhere(function($query) {
            $query->where('guru.is_wali_kelas', false)
                  ->whereNotNull('kelas.wali_kelas');
        })
        ->select('guru.*', 'kelas.nama_kelas')
        ->get();
    
    if ($inconsistentGuru->isEmpty()) {
        echo "<p style='color: green;'>✓ Tidak ada inkonsistensi data wali kelas</p>";
    } else {
        echo "<p style='color: red;'>✗ Ditemukan inkonsistensi:</p>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Guru ID</th><th>Nama Guru</th><th>is_wali_kelas</th><th>Kelas</th></tr>";
        
        foreach($inconsistentGuru as $guru) {
            echo "<tr>";
            echo "<td>{$guru->id}</td>";
            echo "<td>{$guru->nama}</td>";
            echo "<td>" . ($guru->is_wali_kelas ? 'true' : 'false') . "</td>";
            echo "<td>" . ($guru->nama_kelas ?: 'Tidak ada') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    return '';
});
