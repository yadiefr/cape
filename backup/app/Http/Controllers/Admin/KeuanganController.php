<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $kelasList = Kelas::orderBy('nama_kelas')->get();
            
            // Build query dengan filter
            $query = Siswa::with('kelas', 'pembayaran');
            
            // Filter berdasarkan kelas
            if ($request->filled('kelas')) {
                $query->where('kelas_id', $request->kelas);
            }
            
            // Filter berdasarkan tahun masuk (sebagai proxy untuk tahun ajaran)
            if ($request->filled('tahun_ajaran')) {
                $tahunAjaran = explode('/', $request->tahun_ajaran)[0]; // Ambil tahun pertama
                $query->where('tahun_masuk', $tahunAjaran);
            }
            
            // Filter berdasarkan nama siswa
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                      ->orWhere('nis', 'like', '%' . $request->search . '%');
                });
            }
            
            // Sorting
            $sortBy = $request->get('sort_by', 'kelas_id');
            $sortOrder = $request->get('sort_order', 'asc');
            
            // Validasi kolom sorting
            $allowedSorts = ['nama_lengkap', 'nis', 'kelas_id', 'tahun_masuk'];
            if (!in_array($sortBy, $allowedSorts)) {
                $sortBy = 'nama_lengkap';
            }
            
            if (!in_array($sortOrder, ['asc', 'desc'])) {
                $sortOrder = 'asc';
            }
            
            // Apply sorting dengan join jika diperlukan
            if ($sortBy === 'kelas_id') {
                $query->leftJoin('kelas', 'siswa.kelas_id', '=', 'kelas.id')
                      ->orderBy('kelas.nama_kelas', $sortOrder)
                      ->select('siswa.*');
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
            
            $siswaList = $query->get();

            // Get grouped tagihan by excluding siswa_id from GROUP BY for group tagihan
            $groupTagihan = Tagihan::select([
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan'
            ])
            ->selectRaw('MIN(id) as id')
            ->selectRaw('MIN(created_at) as created_at')
            ->selectRaw('COUNT(*) as jumlah_siswa')
            ->selectRaw('MIN(kelas_id) as kelas_id')
            ->selectRaw('NULL as siswa_id') // Set siswa_id to NULL for group display
            ->where(function($query) {
                $query->where('keterangan', 'LIKE', '%[Kelas %')
                      ->orWhere('keterangan', 'LIKE', '%[Angkatan%')
                      ->orWhere('keterangan', 'LIKE', '%[Semua Siswa]%');
            })
            ->groupBy([
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan'
            ])
            ->get();

            // Get individual tagihan (those without group markers)
            $individualTagihan = Tagihan::select([
                'id',
                'nama_tagihan',
                'nominal',
                'periode',
                'tanggal_jatuh_tempo',
                'keterangan',
                'kelas_id',
                'siswa_id',
                'created_at'
            ])
            ->selectRaw('1 as jumlah_siswa') // Individual tagihan always affects 1 student
            ->where('keterangan', 'NOT LIKE', '%[Kelas %')
            ->where('keterangan', 'NOT LIKE', '%[Angkatan%')
            ->where('keterangan', 'NOT LIKE', '%[Semua Siswa]%')
            ->get();

            // Combine both collections
            $tagihanList = $groupTagihan->concat($individualTagihan)
                ->sortByDesc('created_at')
                ->map(function($tagihan) {
                    // Load relationships
                    if ($tagihan->kelas_id) {
                        $tagihan->kelas = Kelas::find($tagihan->kelas_id);
                    }
                    if ($tagihan->siswa_id) {
                        $tagihan->siswa = Siswa::find($tagihan->siswa_id);
                    }

                    return $tagihan;
                });

            // Hitung total keuangan untuk setiap siswa dengan perhitungan yang akurat per tagihan
            foreach ($siswaList as $siswa) {
                $tagihanSiswa = Tagihan::where(function($q) use ($siswa) {
                    $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global (semua siswa)
                      ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas siswa ini
                      ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
                })->get();
                
                $totalTagihan = 0;
                $totalTelahDibayar = 0;
                
                // Hitung per tagihan untuk akurasi yang lebih baik
                foreach ($tagihanSiswa as $tagihan) {
                    $pembayaranTagihan = $siswa->pembayaran->where('tagihan_id', $tagihan->id);
                    $totalDibayarTagihan = $pembayaranTagihan->sum('jumlah');
                    
                    $totalTagihan += $tagihan->nominal;
                    $totalTelahDibayar += $totalDibayarTagihan;
                }
                
                $siswa->total_tagihan = $totalTagihan;
                $siswa->total_telah_dibayar = $totalTelahDibayar;
                $siswa->tunggakan = max(0, $totalTagihan - $totalTelahDibayar);
                $siswa->status_keuangan = $siswa->tunggakan <= 0 ? 'Lunas' : 'Belum Lunas';
            }

            return view('admin.keuangan.index', compact('kelasList', 'siswaList', 'tagihanList'));
            
        } catch (\Exception $e) {
            \Log::error('Error in KeuanganController@index: ' . $e->getMessage());
            \Log::error('Request data: ' . json_encode($request->all()));
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data keuangan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Redirect to index since we don't have a specific show view
        return redirect()->route('admin.keuangan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Redirect to create tagihan
        return redirect()->route('admin.keuangan.tagihan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Redirect to store tagihan
        return redirect()->route('admin.keuangan.tagihan.store');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Redirect to index since we don't have edit functionality yet
        return redirect()->route('admin.keuangan.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Redirect to index since we don't have update functionality yet
        return redirect()->route('admin.keuangan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Redirect to index since we don't have delete functionality yet
        return redirect()->route('admin.keuangan.index');
    }

    public function riwayat($siswaId)
    {
        $siswa = Siswa::with('kelas', 'pembayaran')->findOrFail($siswaId);
        // Ambil tagihan yang berlaku untuk siswa ini (global, kelas, atau spesifik)
        $tagihanList = Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global (semua siswa)
              ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas siswa ini
              ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
        })->get();
        $pembayaranSiswa = $siswa->pembayaran;
        $detailTagihan = [];
        foreach ($tagihanList as $tagihan) {
            // Ambil semua pembayaran untuk tagihan ini berdasarkan tagihan_id
            $pembayaranTagihan = $pembayaranSiswa->where('tagihan_id', $tagihan->id);
            $totalDibayar = $pembayaranTagihan->sum('jumlah');
            $sisa = max(0, $tagihan->nominal - $totalDibayar);
            if ($totalDibayar >= $tagihan->nominal && $tagihan->nominal > 0) {
                $status = 'Lunas';
            } elseif ($totalDibayar > 0 && $totalDibayar < $tagihan->nominal) {
                $status = 'Sebagian';
            } else {
                $status = 'Belum Lunas';
            }
            $detailTagihan[] = [
                'id' => $tagihan->id,
                'nama_tagihan' => $tagihan->nama_tagihan,
                'nominal' => $tagihan->nominal,
                'total_dibayar' => $totalDibayar,
                'sisa' => $sisa,
                'status' => $status,
            ];
        }
        return view('admin.keuangan.riwayat', compact('siswa', 'detailTagihan'));
    }

    public function createTagihan()
    {
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Get siswa data - we know there are 295 students
        $siswaList = Siswa::orderBy('nama_lengkap')->get();

        // Debug log
        \Log::info('CreateTagihan siswa data:', [
            'count' => $siswaList->count(),
            'first_siswa' => $siswaList->first() ? [
                'id' => $siswaList->first()->id,
                'nama_lengkap' => $siswaList->first()->nama_lengkap,
                'nis' => $siswaList->first()->nis
            ] : null
        ]);

        // Get available years based on student data
        $availableYears = Siswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk')
            ->map(function($year) {
                return [
                    'year' => $year,
                    'label' => $year . '/' . ($year + 1),
                    'tingkat' => date('Y') - $year + 1 // Calculate current grade level
                ];
            })
            ->filter(function($item) {
                return $item['tingkat'] >= 1 && $item['tingkat'] <= 3; // Only show active students (grades 1-3)
            });

        return view('admin.keuangan.create_tagihan', compact('kelasList', 'siswaList', 'availableYears'));
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'nama_tagihan' => 'required',
            'nominal' => 'required|numeric|min:1',
            'periode' => 'nullable',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable',
            'target' => 'required|in:semua,tahun,kelas,siswa',
            'tahun_masuk' => 'nullable|digits:4',
            'tahun_target_type' => 'nullable|in:all,specific',
            'tahun_kelas_id' => 'nullable|exists:kelas,id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);

        $kelas_id = null;
        $siswa_id = null;
        $tahun_masuk = null;

        if ($request->target === 'tahun') {
            $request->validate(['tahun_masuk' => 'required|digits:4']);
            $tahun_masuk = $request->tahun_masuk;

            // Check if targeting specific class within the year
            $targetType = $request->input('tahun_target_type', 'all');
            $siswaQuery = \App\Models\Siswa::where('tahun_masuk', $tahun_masuk)
                ->where('status', 'aktif');

            if ($targetType === 'specific' && $request->filled('tahun_kelas_id')) {
                $request->validate(['tahun_kelas_id' => 'required|exists:kelas,id']);
                $siswaQuery->where('kelas_id', $request->tahun_kelas_id);
                $targetDescription = 'kelas tertentu dalam tahun masuk ' . $tahun_masuk;
            } else {
                $targetDescription = 'semua siswa tahun masuk ' . $tahun_masuk;
            }

            $siswaList = $siswaQuery->get();

            if ($siswaList->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada siswa aktif ditemukan untuk target yang dipilih.');
            }

            foreach ($siswaList as $siswa) {
                // Add year info to keterangan for identification
                $keteranganWithYear = $request->keterangan;
                if ($targetType === 'specific' && $request->filled('tahun_kelas_id')) {
                    $kelas = \App\Models\Kelas::find($request->tahun_kelas_id);
                    $keteranganWithYear .= " [Angkatan {$tahun_masuk} - Kelas {$kelas->nama_kelas}]";
                } else {
                    $keteranganWithYear .= " [Angkatan {$tahun_masuk}]";
                }

                \App\Models\Tagihan::create([
                    'nama_tagihan' => $request->nama_tagihan,
                    'keterangan' => $keteranganWithYear,
                    'nominal' => $request->nominal,
                    'periode' => $request->periode,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                    'siswa_id' => $siswa->id,
                ]);
            }

            return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil ditambahkan untuk ' . $targetDescription . ' (' . $siswaList->count() . ' siswa)');

        } elseif ($request->target === 'kelas') {
            $request->validate(['kelas_id' => 'required|exists:kelas,id']);
            $kelas = \App\Models\Kelas::find($request->kelas_id);
            $siswaKelas = \App\Models\Siswa::where('kelas_id', $request->kelas_id)
                ->where('status', 'aktif')
                ->get();

            if ($siswaKelas->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada siswa aktif ditemukan di kelas yang dipilih.');
            }

            foreach ($siswaKelas as $siswa) {
                \App\Models\Tagihan::create([
                    'nama_tagihan' => $request->nama_tagihan,
                    'keterangan' => $request->keterangan . " [Kelas {$kelas->nama_kelas}]",
                    'nominal' => $request->nominal,
                    'periode' => $request->periode,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                    'siswa_id' => $siswa->id,
                ]);
            }

            return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil ditambahkan untuk kelas ' . $kelas->nama_kelas . ' (' . $siswaKelas->count() . ' siswa)');

        } elseif ($request->target === 'siswa') {
            $request->validate(['siswa_id' => 'required|exists:siswa,id']);
            $siswa_id = $request->siswa_id;
        }

        if ($request->target === 'semua') {
            // For "semua siswa", create individual tagihan for each active student
            $allSiswa = \App\Models\Siswa::where('status', 'aktif')->get();

            foreach ($allSiswa as $siswa) {
                \App\Models\Tagihan::create([
                    'nama_tagihan' => $request->nama_tagihan,
                    'keterangan' => $request->keterangan . ' [Semua Siswa]',
                    'nominal' => $request->nominal,
                    'periode' => $request->periode,
                    'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                    'siswa_id' => $siswa->id,
                ]);
            }

            return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil ditambahkan untuk semua siswa (' . $allSiswa->count() . ' siswa)');
        } else {
            // For specific kelas or siswa
            \App\Models\Tagihan::create([
                'nama_tagihan' => $request->nama_tagihan,
                'keterangan' => $request->keterangan,
                'nominal' => $request->nominal,
                'periode' => $request->periode,
                'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
                'kelas_id' => $kelas_id,
                'siswa_id' => $siswa_id,
            ]);
        }

        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil ditambahkan');
    }

    public function editTagihan($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        $kelasList = Kelas::orderBy('nama_kelas')->get();
        $siswaList = Siswa::orderBy('nama_lengkap')->get();

        // Get available years based on student data
        $availableYears = Siswa::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk')
            ->map(function($year) {
                return [
                    'year' => $year,
                    'label' => $year . '/' . ($year + 1),
                    'tingkat' => date('Y') - $year + 1 // Calculate current grade level
                ];
            })
            ->filter(function($item) {
                return $item['tingkat'] >= 1 && $item['tingkat'] <= 3; // Only show active students (grades 1-3)
            });

        return view('admin.keuangan.edit_tagihan', compact('tagihan', 'kelasList', 'siswaList', 'availableYears'));
    }

    public function updateTagihan(Request $request, $id)
    {
        $tagihan = Tagihan::findOrFail($id);
        
        $request->validate([
            'nama_tagihan' => 'required',
            'nominal' => 'required|numeric|min:1',
            'periode' => 'nullable',
            'tanggal_jatuh_tempo' => 'nullable|date',
            'keterangan' => 'nullable',
            'target' => 'required|in:semua,kelas,siswa',
            'kelas_id' => 'nullable|exists:kelas,id',
            'siswa_id' => 'nullable|exists:siswa,id',
        ]);

        $kelas_id = null;
        $siswa_id = null;
        if ($request->target === 'kelas') {
            $request->validate(['kelas_id' => 'required|exists:kelas,id']);
            $kelas_id = $request->kelas_id;
        } elseif ($request->target === 'siswa') {
            $request->validate(['siswa_id' => 'required|exists:siswa,id']);
            $siswa_id = $request->siswa_id;
        }

        $tagihan->update([
            'nama_tagihan' => $request->nama_tagihan,
            'keterangan' => $request->keterangan,
            'nominal' => $request->nominal,
            'periode' => $request->periode,
            'tanggal_jatuh_tempo' => $request->tanggal_jatuh_tempo,
            'kelas_id' => $kelas_id,
            'siswa_id' => $siswa_id,
        ]);

        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil diperbarui');
    }

    public function deleteTagihan($id)
    {
        $tagihan = Tagihan::findOrFail($id);
        
        // Check if there are any payments related to this tagihan
        $paymentsCount = Pembayaran::where('tagihan_id', $id)->count();
        
        if ($paymentsCount > 0) {
            return redirect()->route('admin.keuangan.index')
                ->with('error', 'Tagihan tidak dapat dihapus karena sudah ada pembayaran terkait.');
        }
        
        $tagihan->delete();
        
        return redirect()->route('admin.keuangan.index')->with('success', 'Tagihan berhasil dihapus');
    }

    public function updatePembayaran(Request $request, $siswaId)
    {
        $request->validate([
            'keterangan' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'status' => 'required|in:Lunas,Belum Lunas',
            'tanggal' => 'required',  // Remove date validation to accept datetime
            'tagihan_id' => 'nullable|exists:tagihans,id',
        ]);

        // Find tagihan_id if not provided but keterangan matches
        $tagihan_id = $request->tagihan_id;
        if (!$tagihan_id && $request->keterangan) {
            $tagihan = Tagihan::where('nama_tagihan', $request->keterangan)->first();
            $tagihan_id = $tagihan ? $tagihan->id : null;
        }

        // Selalu buat record pembayaran baru (jangan update record lama)
        \App\Models\Pembayaran::create([
            'siswa_id' => $siswaId,
            'tagihan_id' => $tagihan_id,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'status' => $request->status,
            'tanggal' => $request->tanggal,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil diperbarui!');
    }

    public function deletePembayaran(Request $request, $siswaId)
    {
        // Hapus semua pembayaran siswa (atau bisa diubah untuk hapus pembayaran tertentu)
        \App\Models\Pembayaran::where('siswa_id', $siswaId)->delete();
        return redirect()->back()->with('success', 'Semua pembayaran siswa berhasil dihapus!');
    }

    public function bayar(Request $request, $tagihanId)
    {
        $tagihan = Tagihan::findOrFail($tagihanId);

        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required',  // Remove date validation to accept datetime
        ]);

        $pembayaran = new Pembayaran();
        $pembayaran->siswa_id = $request->siswa_id;
        $pembayaran->tagihan_id = $tagihan->id;  // Add tagihan_id reference
        $pembayaran->keterangan = $tagihan->nama_tagihan;
        $pembayaran->jumlah = $request->jumlah;
        $pembayaran->status = $pembayaran->jumlah >= $tagihan->nominal ? 'Lunas' : 'Sebagian';
        $pembayaran->tanggal = $request->tanggal;
        $pembayaran->save();

        return redirect()->back()->with('success', 'Pembayaran berhasil dilakukan!');
    }
}