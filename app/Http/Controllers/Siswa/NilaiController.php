<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NilaiController extends Controller
{
    /**
     * Display a listing of student's grades.
     */
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        
        if (!$siswa) {
            return redirect()->route('siswa.login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        $nilai = Nilai::with(['mapel'])
            ->where('siswa_id', $siswa->id)
            ->get()
            ->groupBy('mapel.nama');

        // Get unique semesters for filtering
        $semesters = Nilai::where('siswa_id', $siswa->id)
            ->distinct()
            ->pluck('semester')
            ->sort();

        return view('siswa.nilai.index', compact('nilai', 'semesters'));
    }

    /**
     * Display details of a specific grade.
     */
    public function show($id)
    {
        try {
            $siswa = Auth::guard('siswa')->user();

            if (!$siswa) {
                return redirect()->route('siswa.login')->with('error', 'Silakan login terlebih dahulu');
            }

            $nilai = Nilai::with(['mataPelajaran'])
                ->where('siswa_id', $siswa->id)
                ->findOrFail($id);

            return view('siswa.nilai.show', compact('nilai'));
        } catch (\Exception $e) {
            \Log::error('Error in Siswa NilaiController@show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return redirect()->route('siswa.nilai.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail nilai: ' . $e->getMessage());
        }
    }
}
