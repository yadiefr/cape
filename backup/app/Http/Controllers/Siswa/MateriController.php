<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Siswa;
use App\Models\Tugas;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
    }

    /**
     * Display a listing of the materials.
     */
    public function index()
    {
        $user = Auth::guard('siswa')->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        if (!$user->kelas_id) {
            return view('siswa.materi.index', ['error' => 'Anda belum memiliki kelas. Silahkan hubungi administrator.']);
        }
        
        $materi = Materi::with(['mataPelajaran', 'guru'])
                        ->where(function($query) use ($user) {
                            // Case 1: Materi has direct kelas_id that matches user's kelas
                            $query->where('kelas_id', $user->kelas_id)
                                  // Case 2: Materi doesn't have kelas_id (null) but mata pelajaran has jadwal for user's kelas
                                  ->orWhere(function($q) use ($user) {
                                      $q->whereNull('kelas_id')
                                        ->whereHas('mataPelajaran.jadwalPelajaran', function($jadwal) use ($user) {
                                            $jadwal->where('kelas_id', $user->kelas_id);
                                        });
                                  })
                                  // Case 3: Materi has kelas_id = 0 (for all classes) but mata pelajaran has jadwal for user's kelas  
                                  ->orWhere(function($q) use ($user) {
                                      $q->where('kelas_id', 0)
                                        ->whereHas('mataPelajaran.jadwalPelajaran', function($jadwal) use ($user) {
                                            $jadwal->where('kelas_id', $user->kelas_id);
                                        });
                                  });
                        })
                        ->where('is_active', true) // Only show active materials
                        ->orderBy('created_at', 'desc')
                        ->take(10) // Limit to 10 items
                        ->get();

        // Get tugas for the student's class
        $tugas = Tugas::with(['mapel', 'guru'])
                     ->where('kelas_id', $user->kelas_id)
                     ->where('is_active', true)
                     ->orderBy('created_at', 'desc')
                     ->take(10) // Limit to 10 items
                     ->get();

        return view('siswa.materi.index', compact('materi', 'tugas'));
    }

    /**
     * Display the specified material.
     */
    public function show($id)
    {
        $materi = Materi::with(['mataPelajaran.jadwalPelajaran', 'guru', 'kelas'])->findOrFail($id);
        
        // Check if student has access to this material
        $user = Auth::guard('siswa')->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        if (!$user->kelas_id) {
            return redirect()->route('siswa.materi.index')
                ->with('error', 'Anda belum memiliki kelas. Silahkan hubungi administrator.');
        }
        
        // First check: if materi has direct kelas_id and it matches user's kelas
        if ($materi->kelas_id && $materi->kelas_id == $user->kelas_id) {
            return view('siswa.materi.show', compact('materi'));
        }
        
        // Second check: if materi is for all classes (kelas_id = 0) and mata pelajaran has jadwal for user's kelas
        if ($materi->kelas_id == 0 && $materi->mataPelajaran) {
            $hasAccess = $materi->mataPelajaran->jadwalPelajaran->where('kelas_id', $user->kelas_id)->count() > 0;
            if ($hasAccess) {
                return view('siswa.materi.show', compact('materi'));
            }
        }
        
        // Third check: if materi has no kelas_id (null) and mata pelajaran has jadwal for user's kelas  
        if (is_null($materi->kelas_id) && $materi->mataPelajaran) {
            $hasAccess = $materi->mataPelajaran->jadwalPelajaran->where('kelas_id', $user->kelas_id)->count() > 0;
            if ($hasAccess) {
                return view('siswa.materi.show', compact('materi'));
            }
        }
        
        // Fourth check: if materi has direct kelas_id but doesn't match
        if ($materi->kelas_id && $materi->kelas_id != $user->kelas_id && $materi->kelas_id != 0) {
            return redirect()->route('siswa.materi.index')
                ->with('error', 'Anda tidak memiliki akses ke materi ini. (Kelas materi: ' . $materi->kelas_id . ', Kelas Anda: ' . $user->kelas_id . ')');
        }
        
        // Final fallback: no access found
        return redirect()->route('siswa.materi.index')
            ->with('error', 'Anda tidak memiliki akses ke materi ini. (Tidak ada jadwal pelajaran untuk kelas Anda)');

        return view('siswa.materi.show', compact('materi'));
    }

    /**
     * Download the material file.
     */
    public function download($id)
    {
        $materi = Materi::with(['mataPelajaran', 'guru', 'kelas'])->findOrFail($id);
        
        // Check if student has access to this material
        $user = Auth::guard('siswa')->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }
        
        if (!$user->kelas_id) {
            return redirect()->route('siswa.materi.index')
                ->with('error', 'Anda belum memiliki kelas. Silahkan hubungi administrator.');
        }
        
        // Check access using direct kelas_id relationship (new structure)
        if ($materi->kelas_id && $materi->kelas_id != $user->kelas_id) {
            return redirect()->route('siswa.materi.index')
                ->with('error', 'Anda tidak memiliki akses ke file ini.');
        }
        
        // Fallback to legacy jadwal check if kelas_id is not set
        if (!$materi->kelas_id) {
            // Check if materi has mata pelajaran and jadwal pelajaran
            if (!$materi->mataPelajaran || !$materi->mataPelajaran->jadwalPelajaran) {
                return redirect()->route('siswa.materi.index')
                    ->with('error', 'Data materi tidak lengkap.');
            }
            
            if (!$materi->mataPelajaran->jadwalPelajaran->where('kelas_id', $user->kelas_id)->count()) {
                return redirect()->route('siswa.materi.index')
                    ->with('error', 'Anda tidak memiliki akses ke file ini.');
            }
        }

        if ($materi->file_path && Storage::exists($materi->file_path)) {
            return Storage::download($materi->file_path, $materi->judul . '.' . pathinfo($materi->file_path, PATHINFO_EXTENSION));
        }

        return redirect()->back()->with('error', 'File tidak ditemukan.');
    }
}
