<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Nilai::query()
            ->with(['siswa', 'mapel']);
            
        // Filter by class if requested
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
        
        // Filter by subject if requested
        if ($request->has('mapel_id') && $request->mapel_id) {
            $query->where('mapel_id', $request->mapel_id);
        }

        // Filter by student if requested
        if ($request->has('siswa_id') && $request->siswa_id) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }
        
        $nilaiList = $query->latest()->paginate(15);
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        
        return view('admin.nilai.index', compact('nilaiList', 'kelas', 'mapel'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::where('status', 'aktif')->get();
        $mapel = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('admin.nilai.create', compact('siswa', 'mapel', 'kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);
        
        try {
            Nilai::create($validated);
            
            return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan nilai: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Nilai $nilai)
    {
        $nilai->load(['siswa', 'mapel']);
        
        return view('admin.nilai.show', compact('nilai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nilai $nilai)
    {
        $siswa = Siswa::where('status', 'aktif')->get();
        $mapel = MataPelajaran::all();
        
        return view('admin.nilai.edit', compact('nilai', 'siswa', 'mapel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);
        
        try {
            $nilai->update($validated);
            
            return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui nilai: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nilai $nilai)
    {
        try {
            $nilai->delete();
            
            return redirect()->route('admin.nilai.index')->with('success', 'Nilai berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }
    }

    /**
     * Display the student report card.
     */
    public function rapor(Request $request)
    {
        $siswa = null;
        $nilaiList = collect();
        $kelas = Kelas::all();
        
        if ($request->has('siswa_id') && $request->siswa_id) {
            $siswa = Siswa::with('kelas')->findOrFail($request->siswa_id);
              $nilaiList = Nilai::where('siswa_id', $request->siswa_id)
                ->with(['mapel' => function($query) {
                    $query->withDefault([
                        'nama' => 'Mata Pelajaran Tidak Ditemukan',
                        'kategori' => 'Tidak Terkategori',
                        'kode' => 'N/A'
                    ]);
                }])
                ->get()
                ->groupBy(function($nilai) {
                    return $nilai->mapel->kategori ?? 'Tidak Terkategori';
                });
        }
        
        $siswaList = Siswa::where('status', 'aktif')->get();
        
        return view('admin.nilai.rapor', compact('siswa', 'nilaiList', 'siswaList', 'kelas'));
    }
}
