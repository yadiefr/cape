<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;

class SiswaNilaiController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        $nilai = Nilai::where('siswa_id', $siswa->id)
            ->with('mapel')
            ->get()
            ->groupBy('mapel.kategori');

        $mapel = MataPelajaran::all();
        
        return view('siswa.nilai', [
            'nilai' => $nilai,
            'mapel' => $mapel
        ]);
    }
}
