<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PraktikKerjaLapangan;
use App\Models\Siswa;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PraktikKerjaLapanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $jurusan = Jurusan::all();
        $kelas = Kelas::all();
        
        $query = PraktikKerjaLapangan::with(['siswa.kelas', 'siswa.jurusan'])->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by student's class
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
        }
          // Filter by student's major
        if ($request->has('jurusan_id') && $request->jurusan_id != '') {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('jurusan_id', $request->jurusan_id);
            });
        }
        
        // Filter by student name
        if ($request->has('nama_siswa') && $request->nama_siswa != '') {
            $query->whereHas('siswa', function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->nama_siswa . '%');
            });
        }
        
        $praktikKerjaLapangan = $query->paginate(10);
        
        return view('admin.pkl.index', compact('praktikKerjaLapangan', 'jurusan', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $siswa = Siswa::orderBy('nama_lengkap')->get();
        return view('admin.pkl.create', compact('siswa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'bidang_usaha' => 'required|string|max:255',
            'nama_pembimbing' => 'required|string|max:255',
            'telepon_pembimbing' => 'nullable|string|max:20',
            'email_pembimbing' => 'nullable|email|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:pengajuan,berlangsung,selesai,gagal',
            'keterangan' => 'nullable|string',
        ]);

        PraktikKerjaLapangan::create($request->all());

        return redirect()->route('admin.pkl.index')->with('success', 'Data PKL berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pkl = PraktikKerjaLapangan::with('siswa')->findOrFail($id);
        return view('admin.pkl.show', compact('pkl'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pkl = PraktikKerjaLapangan::findOrFail($id);
        $siswa = Siswa::orderBy('nama_lengkap')->get();
        return view('admin.pkl.edit', compact('pkl', 'siswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswa,id',
            'nama_perusahaan' => 'required|string|max:255',
            'alamat_perusahaan' => 'required|string',
            'bidang_usaha' => 'required|string|max:255',
            'nama_pembimbing' => 'required|string|max:255',
            'telepon_pembimbing' => 'nullable|string|max:20',
            'email_pembimbing' => 'nullable|email|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:pengajuan,berlangsung,selesai,gagal',
            'nilai_teknis' => 'nullable|numeric|min:0|max:100',
            'nilai_sikap' => 'nullable|numeric|min:0|max:100',
            'nilai_laporan' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $pkl = PraktikKerjaLapangan::findOrFail($id);
        
        // Handle document upload if exists
        if ($request->hasFile('dokumen_laporan')) {
            $request->validate([
                'dokumen_laporan' => 'file|mimes:pdf,doc,docx|max:10240',
            ]);
            
            // Delete old file if exists
            if ($pkl->dokumen_laporan) {
                Storage::delete('public/pkl/laporan/' . $pkl->dokumen_laporan);
            }
            
            $filename = time() . '_' . $request->file('dokumen_laporan')->getClientOriginalName();
            $request->file('dokumen_laporan')->storeAs('public/pkl/laporan', $filename);
            $pkl->dokumen_laporan = $filename;
        }
        
        // Handle certificate upload if exists
        if ($request->hasFile('surat_keterangan')) {
            $request->validate([
                'surat_keterangan' => 'file|mimes:pdf,doc,docx|max:10240',
            ]);
            
            // Delete old file if exists
            if ($pkl->surat_keterangan) {
                Storage::delete('public/pkl/surat/' . $pkl->surat_keterangan);
            }
            
            $filename = time() . '_' . $request->file('surat_keterangan')->getClientOriginalName();
            $request->file('surat_keterangan')->storeAs('public/pkl/surat', $filename);
            $pkl->surat_keterangan = $filename;
        }
        
        $pkl->fill($request->except(['dokumen_laporan', 'surat_keterangan']));
        $pkl->save();

        return redirect()->route('admin.pkl.index')->with('success', 'Data PKL berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pkl = PraktikKerjaLapangan::findOrFail($id);
        
        // Delete files if exist
        if ($pkl->dokumen_laporan) {
            Storage::delete('public/pkl/laporan/' . $pkl->dokumen_laporan);
        }
        
        if ($pkl->surat_keterangan) {
            Storage::delete('public/pkl/surat/' . $pkl->surat_keterangan);
        }
        
        $pkl->delete();

        return redirect()->route('admin.pkl.index')->with('success', 'Data PKL berhasil dihapus');
    }
    
    /**
     * Download the report document.
     */
    public function downloadLaporan(string $id)
    {
        $pkl = PraktikKerjaLapangan::findOrFail($id);
        
        if (!$pkl->dokumen_laporan) {
            return back()->with('error', 'Dokumen laporan tidak ditemukan');
        }
        
        return Storage::download('public/pkl/laporan/' . $pkl->dokumen_laporan);
    }
    
    /**
     * Download the certificate document.
     */
    public function downloadSurat(string $id)
    {
        $pkl = PraktikKerjaLapangan::findOrFail($id);
        
        if (!$pkl->surat_keterangan) {
            return back()->with('error', 'Dokumen surat keterangan tidak ditemukan');
        }
        
        return Storage::download('public/pkl/surat/' . $pkl->surat_keterangan);
    }
}
