<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;

class KelasController extends Controller
{    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan', 'wali']);
        
        // Handling search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_kelas', 'like', "%$search%")
                  ->orWhere('tahun_ajaran', 'like', "%$search%")
                  ->orWhereHas('jurusan', function($q) use ($search) {
                      $q->where('nama', 'like', "%$search%");
                  })
                  ->orWhereHas('wali', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }
        
        // Allow sorting
        $sortField = $request->input('sort_by', 'nama_kelas');
        $sortDirection = $request->input('sort_direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
        
        // Use pagination instead of get()
        $kelas = $query->paginate(15)->withQueryString();
        
        return view('admin.kelas.index', compact('kelas'));
    }/**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        $guru = Guru::all();
        return view('admin.kelas.create', compact('jurusan', 'guru'));
    }    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusan,id',
            'wali_kelas' => [
                'nullable',
                'exists:guru,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $guru = \App\Models\Guru::find($value);
                        if ($guru && $guru->kelas()->exists()) {
                            $fail('Guru ini sudah menjadi wali kelas lain.');
                        }
                    }
                }
            ],
            'tahun_ajaran' => 'required|string|max:20',
            'tingkat' => 'required|integer|min:10|max:12',
        ]);

        $kelas = new Kelas();
        $kelas->nama_kelas = $validatedData['nama_kelas'];
        $kelas->jurusan_id = $validatedData['jurusan_id'];
        $kelas->wali_kelas = $validatedData['wali_kelas'];
        $kelas->tahun_ajaran = $validatedData['tahun_ajaran'];
        $kelas->tingkat = $validatedData['tingkat'];
        $kelas->save();

        // Update guru's is_wali_kelas status
        if ($validatedData['wali_kelas']) {
            \App\Models\Guru::where('id', $validatedData['wali_kelas'])->update(['is_wali_kelas' => true]);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas baru berhasil ditambahkan.');
    }    /**
     * Display the specified resource.
     */
    public function show($id)
    {        $kelas = Kelas::with(['jurusan', 'wali', 'siswa', 'jadwal.mapel', 'jadwal.guru'])
                    ->findOrFail($id);
                    
        // Get stats for the class
        $totalSiswa = $kelas->siswa->count();
        $siswaLaki = $kelas->siswa->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = $kelas->siswa->where('jenis_kelamin', 'P')->count();
        
        return view('admin.kelas.show', compact('kelas', 'totalSiswa', 'siswaLaki', 'siswaPerempuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kelas = Kelas::findOrFail($id);
        $jurusan = Jurusan::all();
        $guru = Guru::all();

        return view('admin.kelas.edit', compact('kelas', 'jurusan', 'guru'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $oldWaliKelas = $kelas->wali_kelas;
        
        $validatedData = $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'jurusan_id' => 'required|exists:jurusan,id',
            'wali_kelas' => [
                'nullable',
                'exists:guru,id',
                function ($attribute, $value, $fail) use ($kelas) {
                    if ($value && $value != $kelas->wali_kelas) {
                        $guru = \App\Models\Guru::find($value);
                        if ($guru && $guru->kelas()->where('id', '!=', $kelas->id)->exists()) {
                            $fail('Guru ini sudah menjadi wali kelas lain.');
                        }
                    }
                }
            ],
            'tahun_ajaran' => 'required|string|max:20',
        ]);

        $kelas->update($validatedData);

        // Update guru status
        if ($oldWaliKelas && $oldWaliKelas != $validatedData['wali_kelas']) {
            // Remove wali kelas status from old guru if they don't have other classes
            $oldGuru = \App\Models\Guru::find($oldWaliKelas);
            if ($oldGuru && !$oldGuru->kelas()->exists()) {
                $oldGuru->update(['is_wali_kelas' => false]);
            }
        }

        if ($validatedData['wali_kelas']) {
            // Set new guru as wali kelas
            \App\Models\Guru::where('id', $validatedData['wali_kelas'])->update(['is_wali_kelas' => true]);
        }

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $kelas = Kelas::findOrFail($id);
            
            // Check if class has students before deleting
            if ($kelas->siswa()->count() > 0) {
                return redirect()->route('admin.kelas.index')->with('error', 'Kelas tidak dapat dihapus karena memiliki data siswa yang terkait.');
            }
            
            // Update guru status if this was their only class
            if ($kelas->wali_kelas) {
                $guru = \App\Models\Guru::find($kelas->wali_kelas);
                if ($guru && $guru->kelas()->count() == 1) {
                    // This is the only class, so remove wali kelas status
                    $guru->update(['is_wali_kelas' => false]);
                }
            }
            
            $kelas->delete();
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.kelas.index')->with('error', 'Terjadi kesalahan saat menghapus kelas: ' . $e->getMessage());
        }
    }
}
