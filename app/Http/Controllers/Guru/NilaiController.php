<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nilai;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Traits\GuruAccessTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    use GuruAccessTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $guru = auth()->guard('guru')->user();
            
            if (!$guru) {
                return redirect()->route('guru.login')->with('error', 'Silakan login terlebih dahulu');
            }
            
            // Get only kelas that are assigned to this guru in jadwal_pelajaran
            $kelas = Kelas::whereHas('jadwalPelajaran', function($query) use ($guru) {
                $query->where('guru_id', $guru->id)
                      ->where('is_active', true);
            })
            ->with(['siswa', 'jurusan'])
            ->orderBy('nama_kelas', 'asc')
            ->get()
            ->map(function ($kelasItem) use ($guru) {
                // Get mata pelajaran yang diajar guru di kelas ini
                $mapelIds = JadwalPelajaran::where('guru_id', $guru->id)
                    ->where('kelas_id', $kelasItem->id)
                    ->where('is_active', true)
                    ->distinct()
                    ->pluck('mapel_id');
                
                $taughtMapels = MataPelajaran::whereIn('id', $mapelIds)
                    ->orderBy('nama', 'asc')
                    ->get();
                
                $kelasItem->mapel_count = $taughtMapels->count();
                $kelasItem->taught_mapels = $taughtMapels;
                
                return $kelasItem;
            });
            
            return view('guru.nilai.index', compact('kelas'));
        } catch (\Exception $e) {
            \Log::error('Error in NilaiController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data nilai.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $guru = auth()->guard('guru')->user();
            
            if (!$guru) {
                return redirect()->route('guru.login')->with('error', 'Silakan login terlebih dahulu');
            }
            
            $kelasId = $request->kelas_id;
            $mapelId = $request->mapel_id;
            $jenisNilai = $request->jenis_nilai;
            
            if (!$kelasId || !$mapelId) {
                return redirect()->route('guru.nilai.index')->with('error', 'Kelas dan mata pelajaran harus dipilih');
            }
            
            $kelas = Kelas::findOrFail($kelasId);
            $mapel = MataPelajaran::findOrFail($mapelId);
            $siswa = Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap', 'asc')->get();
            
            // Verify guru has access using trait method
            if (!$this->hasAccessToKelasMapel($kelasId, $mapelId)) {
                return redirect()->route('guru.nilai.index')->with('error', 'Anda tidak memiliki akses untuk mengajar mata pelajaran ini di kelas tersebut');
            }
            
            // Tentukan semester berdasarkan bulan saat ini
            // Juli-Desember = Semester 1, Januari-Juni = Semester 2
            $currentMonth = (int) date('n');
            $currentSemester = ($currentMonth >= 7) ? 1 : 2;
            
            // Tentukan tahun ajaran berdasarkan bulan saat ini
            $currentYear = (int) date('Y');
            $academicYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
            
            return view('guru.nilai.create', compact('kelas', 'mapel', 'siswa', 'jenisNilai', 'currentSemester', 'academicYear'));
        } catch (\Exception $e) {
            \Log::error('Error in NilaiController@create: ' . $e->getMessage());
            return redirect()->route('guru.nilai.index')->with('error', 'Terjadi kesalahan saat memuat form input nilai.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|array',
            'nilai' => 'required|array',
            'nilai.*' => 'numeric|between:0,100',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_nilai' => 'required|string|in:tugas,ulangan,uts,uas,praktik',
            'semester' => 'required|integer|in:1,2',
            'tahun_ajaran' => 'required|integer',
            'deskripsi' => 'nullable|string|max:255',
        ]);
        
        $guru = auth()->guard('guru')->user();
        
        // Verify guru has access
        if (!$this->hasAccessToKelasMapel($request->kelas_id, $request->mapel_id)) {
            return redirect()->route('guru.nilai.index')->with('error', 'Anda tidak memiliki akses untuk mengajar mata pelajaran ini di kelas tersebut');
        }
        
        // Determine semester if not provided (fallback logic)
        $semester = $request->semester;
        if (!$semester) {
            $currentMonth = (int) date('n');
            $semester = ($currentMonth >= 7) ? 1 : 2;
        }
        
        // Determine academic year if not provided (fallback logic)
        $tahunAjaran = $request->tahun_ajaran;
        if (!$tahunAjaran) {
            $currentMonth = (int) date('n');
            $currentYear = (int) date('Y');
            $tahunAjaran = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        }
        
        DB::beginTransaction();
        
        try {
            foreach ($request->siswa_id as $key => $siswaId) {
                $jenisNilai = $request->jenis_nilai;
                $nilaiValue = (float) $request->nilai[$key];
                
                // Buat record nilai baru setiap kali input
                $nilai = new Nilai([
                    'siswa_id' => (int) $siswaId,
                    'mapel_id' => (int) $request->mapel_id,
                    'semester' => (int) $semester,
                    'tahun_ajaran' => (int) $tahunAjaran,
                    'jenis_nilai' => $jenisNilai,
                    'deskripsi' => $request->deskripsi,
                ]);
                
                // Determine which column to update based on jenis_nilai
                $columnMapping = [
                    'tugas' => 'nilai_tugas',
                    'ulangan' => 'nilai_tugas',
                    'uts' => 'nilai_uts',
                    'uas' => 'nilai_uas',
                    'praktik' => 'nilai_praktik'
                ];
                
                $columnName = $columnMapping[$jenisNilai] ?? 'nilai';
                
                // Set nilai dan jenis nilai
                $nilai->{$columnName} = $nilaiValue;
                $nilai->nilai = $nilaiValue; // untuk backward compatibility
                $nilai->jenis_nilai = $jenisNilai; // Set jenis nilai
                
                // Update keterangan
                if (!empty($request->keterangan[$key])) {
                    $nilai->catatan = $request->keterangan[$key];
                }
                
                $nilai->created_by = (int) $guru->id;
                $nilai->updated_by = (int) $guru->id;
                
                $nilai->save();
            }
            
            DB::commit();
            
            $jenisNilaiText = [
                'tugas' => 'Tugas Harian',
                'ulangan' => 'Ulangan Harian', 
                'uts' => 'UTS',
                'uas' => 'UAS',
                'praktik' => 'Praktik'
            ][$request->jenis_nilai] ?? 'Nilai';
            
            $semesterText = $semester == 1 ? 'Ganjil' : 'Genap';
            
            return redirect()->to(route('guru.nilai.show', $request->kelas_id) . '?action=detail&mapel_id=' . $request->mapel_id)
                           ->with('success', "Nilai {$jenisNilaiText} berhasil disimpan untuk Semester {$semester} ({$semesterText}) Tahun Ajaran {$tahunAjaran}/" . ($tahunAjaran + 1));
                           
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in NilaiController@store: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan nilai.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        try {
            $guru = auth()->guard('guru')->user();

            if (!$guru) {
                return redirect()->route('guru.login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Handle query params from session (for redirects after delete/update)
            if (session('query_params')) {
                $queryParams = session('query_params');
                session()->forget('query_params');

                $url = route('guru.nilai.show', $id);
                $queryString = http_build_query($queryParams);

                return redirect()->to($url . '?' . $queryString);
            }
            
            $kelas = Kelas::with(['jurusan', 'siswa' => function($query) {
                $query->orderBy('nama_lengkap', 'asc');
            }])->findOrFail($id);
            
            // Temporary: Skip access check for debugging
            // TODO: Fix access check logic later
            /*
            $hasAccess = $this->hasAccessToKelas($id);
            if (!$hasAccess) {
                \Log::warning("Guru {$guru->id} trying to access kelas {$id} without permission");
                return redirect()->route('guru.nilai.index')->with('error', 'Anda tidak memiliki akses ke kelas ini.');
            }
            */

            // Get mata pelajaran yang diajar guru di kelas ini
            $mapelIds = JadwalPelajaran::where('guru_id', $guru->id)
                ->where('kelas_id', $kelas->id)
                ->where('is_active', true)
                ->distinct()
                ->pluck('mapel_id');

            $mapel = MataPelajaran::whereIn('id', $mapelIds)
                ->orderBy('nama', 'asc')
                ->get();

            // Debug: Log if no mapel found
            if ($mapel->isEmpty()) {
                \Log::warning("No mapel found for guru {$guru->id} in kelas {$id}. Mapel IDs: " . $mapelIds->toJson());

                // Fallback: Get all jadwal for this guru
                $allJadwal = JadwalPelajaran::where('guru_id', $guru->id)->get();
                \Log::info("All jadwal for guru {$guru->id}: " . $allJadwal->toJson());

                // Return early with empty mapel to avoid further errors
                return view('guru.nilai.show', compact('kelas', 'mapel'));
            }
            
            // Jika action=detail, tampilkan detail nilai
            if ($request->get('action') === 'detail') {
                $mapelId = $request->get('mapel_id');

                // Jika belum ada mapel_id, tampilkan list mata pelajaran
                if (!$mapelId) {
                    return view('guru.nilai.detail-mapel', compact('kelas', 'mapel'));
                }

                // Jika ada mapel_id, tampilkan detail nilai untuk mata pelajaran tersebut
                $selectedMapel = $mapel->where('id', $mapelId)->first();

                if (!$selectedMapel) {
                    \Log::warning('Mapel not found or no access', [
                        'guru_id' => $guru->id,
                        'kelas_id' => $id,
                        'mapel_id' => $mapelId,
                        'available_mapels' => $mapel->pluck('id')->toArray()
                    ]);
                    return redirect()->route('guru.nilai.show', $id)
                        ->with('error', 'Mata pelajaran tidak ditemukan atau Anda tidak memiliki akses.');
                }

                // Optimized: Ambil siswa dengan nilai dalam satu query yang efisien
                $siswaIds = $kelas->siswa->pluck('id')->toArray();

                // Ambil semua nilai untuk mata pelajaran ini dengan indexing yang optimal
                $allNilai = Nilai::select(['id', 'siswa_id', 'nilai', 'jenis_nilai', 'catatan', 'deskripsi', 'created_at'])
                    ->where('mapel_id', $selectedMapel->id)
                    ->whereIn('siswa_id', $siswaIds)
                    ->orderBy('siswa_id')
                    ->orderBy('jenis_nilai')
                    ->orderBy('created_at')
                    ->get();

                // Group nilai by siswa_id, then by jenis_nilai + created_at untuk menghindari duplikasi tombol
                $groupedNilai = $allNilai->groupBy('siswa_id');

                // Create unique nilai groups untuk tombol edit/hapus berdasarkan created_at
                $uniqueNilaiGroups = $allNilai->groupBy(function($item) {
                    return $item->jenis_nilai . '|' . $item->created_at->format('Y-m-d H:i:s');
                })->map(function($group) {
                    return $group->first(); // Ambil satu sample dari setiap grup
                });

                // Optimized: Proses data dengan minimal memory usage
                $siswaWithNilai = collect();
                $sudahDinilai = 0;

                foreach ($kelas->siswa as $siswa) {
                    $siswaValues = $groupedNilai->get($siswa->id, collect());
                    $hasNilai = $siswaValues->isNotEmpty();

                    if ($hasNilai) {
                        $sudahDinilai++;
                    }

                    $siswaWithNilai->push([
                        'siswa' => $siswa,
                        'nilai_records' => $siswaValues,
                        'has_nilai' => $hasNilai
                    ]);
                }

                // Optimized: Gunakan data yang sudah dihitung
                $totalSiswa = $siswaWithNilai->count();
                $belumDinilai = $totalSiswa - $sudahDinilai;

                $nilaiDetail = [
                    'mapel' => $selectedMapel,
                    'siswa_data' => $siswaWithNilai,
                    'total_siswa' => $totalSiswa,
                    'sudah_dinilai' => $sudahDinilai,
                    'belum_dinilai' => $belumDinilai
                ];

                return view('guru.nilai.detail-single', compact('kelas', 'nilaiDetail', 'selectedMapel', 'uniqueNilaiGroups'));
            }
            
            return view('guru.nilai.show', compact('kelas', 'mapel'));
        } catch (\Exception $e) {
            \Log::error('Error in NilaiController@show: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            // Debug: Show actual error in development
            if (config('app.debug')) {
                throw $e;
            }

            return redirect()->route('guru.nilai.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get students with their values for a specific class and subject.
     */
    public function getNilai(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id'
        ]);
        
        $guru = auth()->guard('guru')->user();        $siswa = Siswa::where('kelas_id', $request->kelas_id)
                      ->with(['nilai' => function($query) use($request) {
                          $query->where('mapel_id', $request->mapel_id);
                      }])
                      ->get();
        
        return response()->json($siswa);
    }

    /**
     * Show form for editing batch nilai by jenis_nilai
     */
    public function editBatch(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'jenis_nilai' => 'required|string|in:tugas,ulangan,uts,uas,praktik',
            'created_at' => 'required|date'
        ]);

        $guru = auth()->guard('guru')->user();
        $kelas = Kelas::with(['jurusan', 'siswa'])->findOrFail($request->kelas_id);
        $mapel = MataPelajaran::findOrFail($request->mapel_id);

        // Get all nilai for this jenis_nilai and created_at (same batch)
        $nilaiRecords = Nilai::where('mapel_id', $request->mapel_id)
            ->where('jenis_nilai', $request->jenis_nilai)
            ->where('created_at', $request->created_at)
            ->whereIn('siswa_id', $kelas->siswa->pluck('id'))
            ->with('siswa')
            ->orderBy('siswa_id')
            ->get();

        // Get current semester and academic year
        $currentMonth = (int) date('n');
        $currentSemester = ($currentMonth >= 7) ? 1 : 2;
        $currentYear = (int) date('Y');
        $academicYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;

        return view('guru.nilai.edit-batch', compact(
            'kelas', 'mapel', 'nilaiRecords', 'currentSemester',
            'academicYear', 'request'
        ));
    }

    /**
     * Update batch nilai
     */
    public function updateBatch(Request $request)
    {
        $request->validate([
            'nilai_ids' => 'required|array',
            'nilai_values' => 'required|array',
            'nilai_values.*' => 'numeric|between:0,100',
            'deskripsi' => 'nullable|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'jenis_nilai' => 'required|string'
        ]);

        try {
            foreach ($request->nilai_ids as $key => $nilaiId) {
                $nilai = Nilai::findOrFail($nilaiId);
                $nilai->update([
                    'nilai' => $request->nilai_values[$key],
                    'deskripsi' => $request->deskripsi
                ]);
            }

            return redirect()
                ->route('guru.nilai.show', $request->kelas_id)
                ->with('success', 'Nilai berhasil diperbarui untuk semua siswa!')
                ->with('query_params', ['action' => 'detail', 'mapel_id' => $request->mapel_id]);

        } catch (\Exception $e) {
            \Log::error('Error updating batch nilai: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui nilai.');
        }
    }

    /**
     * Delete batch nilai by jenis_nilai
     */
    public function deleteBatch(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'jenis_nilai' => 'required|string',
            'created_at' => 'required|date'
        ]);

        try {
            $kelas = Kelas::findOrFail($request->kelas_id);

            // Debug logging
            \Log::info('Delete Batch Request (by created_at):', [
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'jenis_nilai' => $request->jenis_nilai,
                'created_at' => $request->created_at,
                'siswa_ids' => $kelas->siswa->pluck('id')->toArray()
            ]);

            // Delete nilai with specific jenis_nilai and created_at (same batch)
            $query = Nilai::where('mapel_id', $request->mapel_id)
                ->where('jenis_nilai', $request->jenis_nilai)
                ->where('created_at', $request->created_at)
                ->whereIn('siswa_id', $kelas->siswa->pluck('id'));

            // Get records before delete for logging
            $recordsToDelete = $query->get();
            \Log::info('Records to delete (by created_at):', [
                'count' => $recordsToDelete->count(),
                'ids' => $recordsToDelete->pluck('id')->toArray(),
                'created_at_values' => $recordsToDelete->pluck('created_at')->unique()->toArray(),
                'deskripsi_values' => $recordsToDelete->pluck('deskripsi')->unique()->toArray()
            ]);

            $deletedCount = $query->delete();

            $tanggalText = \Carbon\Carbon::parse($request->created_at)->format('d/m/Y H:i');
            return redirect()
                ->route('guru.nilai.show', $request->kelas_id)
                ->with('success', "Berhasil menghapus {$deletedCount} nilai {$request->jenis_nilai} ({$tanggalText})!")
                ->with('query_params', ['action' => 'detail', 'mapel_id' => $request->mapel_id]);

        } catch (\Exception $e) {
            \Log::error('Error deleting batch nilai: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus nilai.');
        }
    }
}
