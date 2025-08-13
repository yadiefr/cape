<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PPDBController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $jurusan = $request->query('jurusan', 'all');
        $tahun = $request->query('tahun', Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1)));
        $cari = $request->query('cari', '');
        
        $query = Pendaftaran::with(['jurusanPertama', 'user']);
        
        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter berdasarkan jurusan
        if ($jurusan !== 'all') {
            $query->where('pilihan_jurusan_1', $jurusan);
        }
        
        // Filter berdasarkan tahun ajaran
        $query->where('tahun_ajaran', $tahun);
        
        // Pencarian berdasarkan nama atau nomor pendaftaran
        if (!empty($cari)) {
            $query->where(function($q) use ($cari) {
                $q->where('nama_lengkap', 'like', "%{$cari}%")
                  ->orWhere('nomor_pendaftaran', 'like', "%{$cari}%")
                  ->orWhere('nisn', 'like', "%{$cari}%");
            });
        }
        
        $pendaftaran = $query->orderBy('created_at', 'desc')->paginate(20);
        $daftarJurusan = Jurusan::all();
        
        // Untuk statistik
        $total = [
            'semua' => Pendaftaran::where('tahun_ajaran', $tahun)->count(),
            'menunggu' => Pendaftaran::where('status', 'menunggu')->where('tahun_ajaran', $tahun)->count(),
            'diterima' => Pendaftaran::where('status', 'diterima')->where('tahun_ajaran', $tahun)->count(),
            'ditolak' => Pendaftaran::where('status', 'ditolak')->where('tahun_ajaran', $tahun)->count(),
            'cadangan' => Pendaftaran::where('status', 'cadangan')->where('tahun_ajaran', $tahun)->count(),
        ];
        
        return view('admin.ppdb.index', compact('pendaftaran', 'status', 'jurusan', 'tahun', 'cari', 'daftarJurusan', 'total'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jurusan = Jurusan::all();
        $tahun = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        return view('admin.ppdb.create', compact('jurusan', 'tahun'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nisn' => 'required|string|max:20|unique:pendaftaran',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email',
            'asal_sekolah' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pilihan_jurusan_1' => 'required|exists:jurusan,id',
            'nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'nilai_indonesia' => 'nullable|numeric|min:0|max:100',
            'nilai_inggris' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:menunggu,diterima,ditolak,cadangan',
            'keterangan' => 'nullable|string',
        ]);
        
        // Generate nomor pendaftaran
        $nomor_pendaftaran = Pendaftaran::generateNomorPendaftaran();
        
        // Simpan data ke database
        $pendaftaran = new Pendaftaran();
        $pendaftaran->nomor_pendaftaran = $nomor_pendaftaran;
        $pendaftaran->nama_lengkap = $request->nama_lengkap;
        $pendaftaran->jenis_kelamin = $request->jenis_kelamin;
        $pendaftaran->nisn = $request->nisn;
        $pendaftaran->tempat_lahir = $request->tempat_lahir;
        $pendaftaran->tanggal_lahir = $request->tanggal_lahir;
        $pendaftaran->agama = $request->agama;
        $pendaftaran->alamat = $request->alamat;
        $pendaftaran->telepon = $request->telepon;
        $pendaftaran->email = $request->email;
        $pendaftaran->asal_sekolah = $request->asal_sekolah;
        $pendaftaran->nama_ayah = $request->nama_ayah;
        $pendaftaran->nama_ibu = $request->nama_ibu;
        $pendaftaran->pekerjaan_ayah = $request->pekerjaan_ayah;
        $pendaftaran->pekerjaan_ibu = $request->pekerjaan_ibu;
        $pendaftaran->telepon_orangtua = $request->telepon_orangtua;
        $pendaftaran->alamat_orangtua = $request->alamat_orangtua;
        $pendaftaran->pilihan_jurusan_1 = $request->pilihan_jurusan_1;
        $pendaftaran->nilai_matematika = $request->nilai_matematika;
        $pendaftaran->nilai_indonesia = $request->nilai_indonesia;
        $pendaftaran->nilai_inggris = $request->nilai_inggris;
        $pendaftaran->status = $request->status;
        $pendaftaran->tanggal_pendaftaran = now();
        $pendaftaran->tahun_ajaran = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        $pendaftaran->keterangan = $request->keterangan;
        $pendaftaran->save();
        
        return redirect()->route('admin.ppdb.index')->with('success', 'Data pendaftaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pendaftaran = Pendaftaran::with('jurusanPertama')->findOrFail($id);
        
        return view('admin.ppdb.show', compact('pendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        $jurusan = Jurusan::all();
        
        return view('admin.ppdb.edit', compact('pendaftaran', 'jurusan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nisn' => 'required|string|max:20|unique:pendaftaran,nisn,' . $id,
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'agama' => 'required|string',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:20',
            'email' => 'nullable|email',
            'asal_sekolah' => 'required|string|max:255',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pilihan_jurusan_1' => 'required|exists:jurusan,id',
            'nilai_matematika' => 'nullable|numeric|min:0|max:100',
            'nilai_indonesia' => 'nullable|numeric|min:0|max:100',
            'nilai_inggris' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:menunggu,diterima,ditolak,cadangan',
            'keterangan' => 'nullable|string',
        ]);
        
        // Update data pendaftaran
        $pendaftaran->nama_lengkap = $request->nama_lengkap;
        $pendaftaran->jenis_kelamin = $request->jenis_kelamin;
        $pendaftaran->nisn = $request->nisn;
        $pendaftaran->tempat_lahir = $request->tempat_lahir;
        $pendaftaran->tanggal_lahir = $request->tanggal_lahir;
        $pendaftaran->agama = $request->agama;
        $pendaftaran->alamat = $request->alamat;
        $pendaftaran->telepon = $request->telepon;
        $pendaftaran->email = $request->email;
        $pendaftaran->asal_sekolah = $request->asal_sekolah;
        $pendaftaran->nama_ayah = $request->nama_ayah;
        $pendaftaran->nama_ibu = $request->nama_ibu;
        $pendaftaran->pekerjaan_ayah = $request->pekerjaan_ayah;
        $pendaftaran->pekerjaan_ibu = $request->pekerjaan_ibu;
        $pendaftaran->telepon_orangtua = $request->telepon_orangtua;
        $pendaftaran->alamat_orangtua = $request->alamat_orangtua;
        $pendaftaran->pilihan_jurusan_1 = $request->pilihan_jurusan_1;
        $pendaftaran->nilai_matematika = $request->nilai_matematika;
        $pendaftaran->nilai_indonesia = $request->nilai_indonesia;
        $pendaftaran->nilai_inggris = $request->nilai_inggris;
        $pendaftaran->status = $request->status;
        $pendaftaran->keterangan = $request->keterangan;
        $pendaftaran->save();
        
        return redirect()->route('admin.ppdb.show', $pendaftaran->id)->with('success', 'Data pendaftaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        // Hapus dokumen terkait
        $dokumen_fields = ['dokumen_ijazah', 'dokumen_skhun', 'dokumen_foto', 'dokumen_kk', 'dokumen_ktp_ortu'];
        
        foreach ($dokumen_fields as $field) {
            if ($pendaftaran->$field) {
                Storage::delete($pendaftaran->$field);
            }
        }
        
        // Hapus data pendaftaran
        $pendaftaran->delete();
        
        return redirect()->route('admin.ppdb.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }
    
    /**
     * Update status pendaftaran
     */
    public function updateStatus(Request $request, string $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak,cadangan',
            'keterangan' => 'nullable|string',
        ]);
        
        $pendaftaran->status = $request->status;
        $pendaftaran->keterangan = $request->keterangan;
        $pendaftaran->save();
        
        return redirect()->back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }
    
    /**
     * Export data pendaftaran
     */
    public function export(Request $request)
    {
        $status = $request->query('status', 'all');
        $jurusan = $request->query('jurusan', 'all');
        $tahun = $request->query('tahun', Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1)));
        
        $query = Pendaftaran::with('jurusanPertama');
        
        // Filter berdasarkan status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter berdasarkan jurusan
        if ($jurusan !== 'all') {
            $query->where('pilihan_jurusan_1', $jurusan);
        }
        
        // Filter berdasarkan tahun ajaran
        $query->where('tahun_ajaran', $tahun);
        
        $pendaftaran = $query->orderBy('created_at', 'desc')->get();
        
        // Ekspor ke CSV atau Excel sesuai kebutuhan (implementasi disesuaikan)
        return response()->json(['message' => 'Feature under development']);
    }
    
    /**
     * Dashboard PPDB
     */
    public function dashboard()
    {
        $tahun = Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1));
        
        // Statistik total pendaftar
        $totalPendaftar = Pendaftaran::where('tahun_ajaran', $tahun)->count();
        
        // Statistik berdasarkan status
        $statusPendaftar = [
            'menunggu' => Pendaftaran::where('status', 'menunggu')->where('tahun_ajaran', $tahun)->count(),
            'diterima' => Pendaftaran::where('status', 'diterima')->where('tahun_ajaran', $tahun)->count(),
            'ditolak' => Pendaftaran::where('status', 'ditolak')->where('tahun_ajaran', $tahun)->count(),
            'cadangan' => Pendaftaran::where('status', 'cadangan')->where('tahun_ajaran', $tahun)->count(),
        ];
        
        // Statistik berdasarkan jurusan
        $jurusan = Jurusan::all();
        $pendaftarJurusan = [];
        
        foreach ($jurusan as $j) {
            $pendaftarJurusan[$j->id] = [
                'nama' => $j->nama,
                'total' => Pendaftaran::getCountByJurusan($j->id)
            ];
        }
        
        // Pendaftar terbaru
        $pendaftarTerbaru = Pendaftaran::orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.ppdb.dashboard', compact('totalPendaftar', 'statusPendaftar', 'pendaftarJurusan', 'pendaftarTerbaru', 'tahun'));
    }

    /**
     * Update status PPDB (buka/tutup) dari sidebar
     */
    public function updateStatusSetting(Request $request)
    {
        $request->validate([
            'is_ppdb_open' => 'required|in:true,false',
            'ppdb_year' => 'nullable|string|max:20',
            'ppdb_start_date' => 'nullable|date',
            'ppdb_end_date' => 'nullable|date|after_or_equal:ppdb_start_date',
        ]);
        \App\Models\Settings::setValue('is_ppdb_open', $request->is_ppdb_open, 'ppdb', 'boolean', 'Status PPDB (Buka/Tutup)');
        if ($request->filled('ppdb_year')) {
            \App\Models\Settings::setValue('ppdb_year', $request->ppdb_year, 'ppdb', 'string', 'Tahun PPDB');
        }
        if ($request->filled('ppdb_start_date')) {
            \App\Models\Settings::setValue('ppdb_start_date', $request->ppdb_start_date, 'ppdb', 'date', 'Tanggal mulai PPDB');
        }
        if ($request->filled('ppdb_end_date')) {
            \App\Models\Settings::setValue('ppdb_end_date', $request->ppdb_end_date, 'ppdb', 'date', 'Tanggal selesai PPDB');
        }
        return redirect()->back()->with('success', 'Pengaturan PPDB berhasil diperbarui.');
    }
}
