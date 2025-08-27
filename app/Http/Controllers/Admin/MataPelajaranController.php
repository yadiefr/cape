<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Jurusan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Imports\MataPelajaranImport;
use Maatwebsite\Excel\Facades\Excel;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the mata pelajaran.
     *
     * @return \Illuminate\Http\Response
     */    public function index(Request $request)
    {
        // Default sorting
        $sortField = $request->get('sort', 'kode');
        $sortDirection = $request->get('direction', 'asc');
        
        // Validate sort field
        $validSortFields = ['kode', 'nama', 'jenis', 'kkm', 'tahun_ajaran'];
        if (!in_array($sortField, $validSortFields)) {
            $sortField = 'kode';
        }
        
        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }
        
        // Get mata pelajaran with sorting
        $mataPelajaran = MataPelajaran::with(['jurusan'])
                                     ->orderBy($sortField, $sortDirection)
                                     ->get();
        
        // For each mata pelajaran, get the assigned teachers from jadwal_pelajaran (assignments only)
        foreach ($mataPelajaran as $mapel) {
            $assignedGuru = \App\Models\JadwalPelajaran::where('mapel_id', $mapel->id)
                                                      ->with('guru')
                                                      ->whereHas('guru') // Only get schedules with valid guru
                                                      ->assignments() // Only get assignment entries, not scheduled
                                                      ->distinct()
                                                      ->get()
                                                      ->pluck('guru')
                                                      ->unique('id');
            $mapel->assigned_guru = $assignedGuru;
            
            // Get classes assigned to this subject from jadwal_pelajaran (assignments only)
            $assignedKelas = \App\Models\JadwalPelajaran::where('mapel_id', $mapel->id)
                                                       ->with('kelas.jurusan')
                                                       ->whereHas('kelas') // Only get schedules with valid kelas
                                                       ->assignments() // Only get assignment entries, not scheduled
                                                       ->distinct()
                                                       ->get()
                                                       ->pluck('kelas')
                                                       ->unique('id');
            $mapel->assigned_kelas = $assignedKelas;
        }
        
        $totalMapel = $mataPelajaran->count();
        $totalMapelWajib = $mataPelajaran->where('jenis', 'Wajib')->count();
        $totalMapelKejuruan = $mataPelajaran->where('jenis', 'Kejuruan')->count();
        
        $guruPengajar = Guru::where('is_active', true)->orderBy('nama')->get();
        $jurusan = Jurusan::all(); // Add jurusan data for filtering
        $kelas_list = Kelas::where('is_active', true)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        // Create mapping of class levels to full class names
        $kelasMapping = [];
        foreach ($kelas_list as $kelas) {
            // Create mapping for each tingkat with its full class name
            $tingkat = $kelas->tingkat;
            $tingkat_romawi = '';
            
            // Convert tingkat to Roman numerals
            if ($tingkat == 1) $tingkat_romawi = 'X';
            elseif ($tingkat == 2) $tingkat_romawi = 'XI';
            elseif ($tingkat == 3) $tingkat_romawi = 'XII';
            elseif ($tingkat == 4) $tingkat_romawi = 'XIII';
            
            $kelasMapping[$tingkat_romawi][] = $tingkat_romawi . ' ' . $kelas->jurusan->kode_jurusan;
        }
        
        return view('admin.matapelajaran.index', compact(
            'mataPelajaran', 
            'totalMapel', 
            'totalMapelWajib', 
            'totalMapelKejuruan',
            'guruPengajar',
            'jurusan',
            'kelas_list',
            'kelasMapping',
            'sortField',
            'sortDirection'
        ));
    }

    /**
     * Show the form for creating a new mata pelajaran.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $guru = Guru::where('is_active', true)->orderBy('nama')->get();
        $kelas_list = \App\Models\Kelas::where('is_active', true)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('admin.matapelajaran.create', compact('guru', 'kelas_list'));
    }/**
     * Store a newly created mata pelajaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:10|unique:mata_pelajaran,kode',
            'nama' => 'required|string|max:100',
            'jenis' => 'sometimes|string',
            'is_jurusan' => 'sometimes|boolean',
            'tahun_ajaran' => 'required|string',
            'kkm' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string',
            'materi_pokok' => 'nullable|string',
        ]);

        // Set kelas to empty as it's no longer required
        $kelas = '';
        
        // Set a default jurusan_id (1 for general/any)
        $jurusanId = null;        $mapel = new MataPelajaran();
        $mapel->kode = $request->kode;
        $mapel->nama = $request->nama;
        $mapel->kelas = $kelas;
        $mapel->jurusan_id = $jurusanId; // Set to null as it's not required anymore
        // Remove guru_id assignment as it's managed separately through assignments
        // Set jenis based on is_jurusan checkbox - using 'Kejuruan' instead of 'Jurusan'
        $mapel->jenis = $request->has('is_jurusan') && $request->is_jurusan ? 'Kejuruan' : 'Wajib';
        $mapel->tahun_ajaran = $request->tahun_ajaran;
        $mapel->kkm = $request->kkm;
        $mapel->deskripsi = $request->deskripsi;
        $mapel->materi_pokok = $request->materi_pokok;
        $mapel->is_unggulan = false; // Set to false as unggulan option is removed
        $mapel->save();

        return redirect()->route('admin.matapelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }    /**
     * Display the specified mata pelajaran.
     *
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function show($kode)
    {
        $mapel = MataPelajaran::where('kode', $kode)->firstOrFail();
        
        // Get assigned teachers from jadwal_pelajaran (assignments only, not scheduled items)
        $assignedGuru = \App\Models\JadwalPelajaran::where('mapel_id', $mapel->id)
                                                  ->with('guru')
                                                  ->whereHas('guru') // Only get schedules with valid guru
                                                  ->assignments() // Only get assignment entries, not scheduled
                                                  ->distinct()
                                                  ->get()
                                                  ->pluck('guru')
                                                  ->unique('id');
        
        // Get assigned classes from jadwal_pelajaran (assignments only, not scheduled items)
        $assignedKelas = \App\Models\JadwalPelajaran::where('mapel_id', $mapel->id)
                                                   ->with('kelas.jurusan')
                                                   ->whereHas('kelas') // Only get schedules with valid kelas
                                                   ->assignments() // Only get assignment entries, not scheduled
                                                   ->distinct()
                                                   ->get()
                                                   ->pluck('kelas')
                                                   ->unique('id');
        
        $kelas_list = Kelas::where('is_active', true)->orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        // Create mapping of class levels to full class names
        $kelasMapping = [];
        foreach ($kelas_list as $kelas) {
            // Create mapping for each tingkat with its full class name
            $tingkat = $kelas->tingkat;
            $tingkat_romawi = '';
            
            // Convert tingkat to Roman numerals
            if ($tingkat == 1) $tingkat_romawi = 'X';
            elseif ($tingkat == 2) $tingkat_romawi = 'XI';
            elseif ($tingkat == 3) $tingkat_romawi = 'XII';
            elseif ($tingkat == 4) $tingkat_romawi = 'XIII';
            
            $kelasMapping[$tingkat_romawi][] = $tingkat_romawi . ' ' . $kelas->jurusan->kode_jurusan;
        }
        
        return view('admin.matapelajaran.show', compact('mapel', 'kelasMapping', 'assignedGuru', 'assignedKelas'));
    }

    /**
     * Show the form for editing the specified mata pelajaran.
     *
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function edit($kode)
    {
        $mapel = MataPelajaran::where('kode', $kode)->firstOrFail();
        $guru = Guru::where('is_active', true)->orderBy('nama')->get();
        $jurusan = Jurusan::all();
        
        return view('admin.matapelajaran.edit', compact('mapel', 'guru', 'jurusan'));
    }

    /**
     * Update the specified mata pelajaran in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $kode)
    {
        // Debug incoming request - LOG SEMUA DATA
        Log::info('=== MATA PELAJARAN UPDATE DEBUG ===', [
            'kode' => $kode,
            'method' => $request->method(),
            'all_request' => $request->all(),
        ]);

        $mapel = MataPelajaran::where('kode', $kode)->firstOrFail();
        
        Log::info('Found mapel', [
            'mapel_id' => $mapel->id,
            'mapel_nama' => $mapel->nama
        ]);

        // SIMPLE VALIDATION - without kelas requirement
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tahun_ajaran' => 'required|string',
            'kkm' => 'required|integer|min:1|max:100',
            'deskripsi' => 'nullable|string',
            'materi_pokok' => 'nullable|string',
        ]);
        
        Log::info('Validation passed', ['validated_keys' => array_keys($validated)]);

        // Set kelas to empty as it's no longer required
        $kelas = '';
        
        // Update mata pelajaran basic info
        $mapel->nama = $request->nama;
        $mapel->kelas = $kelas;
        $mapel->jurusan_id = null;
        $mapel->jenis = $request->has('is_jurusan') ? 'Kejuruan' : 'Wajib';
        $mapel->tahun_ajaran = $request->tahun_ajaran;
        $mapel->kkm = $request->kkm;
        $mapel->deskripsi = $request->deskripsi;
        $mapel->materi_pokok = $request->materi_pokok;
        $mapel->is_unggulan = false; // Set to false as unggulan option is removed
        $mapel->save();
        
        Log::info('Mapel basic info updated successfully');

        // Log activity
        Log::info('Mata pelajaran diperbarui', [
            'kode' => $kode,
            'nama' => $request->nama,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('admin.matapelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }    /**
     * Remove the specified mata pelajaran from storage.
     *
     * @param  string  $kode
     * @return \Illuminate\Http\Response
     */
    public function destroy($kode)
    {
        $mapel = MataPelajaran::where('kode', $kode)->firstOrFail();
        $mapel->delete();

        // Log activity
        Log::info('Mata pelajaran dihapus', [
            'kode' => $kode,
            'nama' => $mapel->nama,
            'user_id' => auth()->id()
        ]);

        // Check if this is an AJAX request
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mata pelajaran berhasil dihapus!'
            ]);
        }

        return redirect()->route('admin.matapelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus!');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx',
        ]);

        try {
            $import = new MataPelajaranImport();
            Excel::import($import, $request->file('file'));

            $imported = $import->getImported();
            $updated = $import->getUpdated();
            $errors = $import->getErrors();

            $message = '';
            if ($imported > 0) {
                $message .= "Berhasil mengimpor {$imported} mata pelajaran baru. ";
            }
            if ($updated > 0) {
                $message .= "Berhasil memperbarui {$updated} mata pelajaran. ";
            }

            if ($imported == 0 && $updated == 0 && empty($errors)) {
                $message = 'Import selesai, namun tidak ada data yang diproses. Pastikan file berisi data yang valid.';
            }

            if (!empty($errors)) {
                $message .= "Terdapat " . count($errors) . " error: " . implode(', ', array_slice($errors, 0, 3));
                if (count($errors) > 3) {
                    $message .= " dan " . (count($errors) - 3) . " error lainnya.";
                }
                return back()->with('warning', $message);
            }
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            Log::error('Import Mata Pelajaran - General error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filename = 'template_import_mata_pelajaran_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new \App\Exports\MataPelajaranTemplateExport(), $filename);
    }
    
    /**
     * Show import format information
     */
    public function importInfo()
    {
        $columns = [
            ['name' => 'kode', 'description' => 'Kode mata pelajaran (unique, required)'],
            ['name' => 'nama', 'description' => 'Nama mata pelajaran (required)']
        ];
        
        return response()->json([
            'success' => true,
            'data' => [
                'excel' => [
                    'description' => 'Format Microsoft Excel - Recommended untuk kemudahan penggunaan',
                    'columns' => $columns,
                    'sample_data' => [
                        ['kode', 'nama'],
                        ['MP001', 'Matematika'],
                        ['MP002', 'Bahasa Inggris'],
                        ['MP003', 'Bahasa Indonesia'],
                        ['MP004', 'Fisika'],
                        ['MP005', 'Kimia']
                    ]
                ],
                'csv' => [
                    'description' => 'Format Comma Separated Values - Kompatibel dengan spreadsheet',
                    'columns' => $columns,
                    'sample_data' => [
                        'kode,nama',
                        'MP001,Matematika',
                        'MP002,Bahasa Inggris',
                        'MP003,Bahasa Indonesia',
                        'MP004,Fisika',
                        'MP005,Kimia'
                    ]
                ],
                'tsv' => [
                    'description' => 'Format Tab Separated Values - Mudah dibuat dengan text editor',
                    'columns' => $columns,
                    'sample_data' => [
                        '# Format: Tab-separated values (TSV)',
                        '# Kolom: kode[TAB]nama',
                        'kode	nama',
                        'MP001	Matematika',
                        'MP002	Bahasa Inggris',
                        'MP003	Bahasa Indonesia',
                        'MP004	Fisika',
                        'MP005	Kimia'
                    ]
                ],
                'validation_rules' => [
                    'File maksimal 2MB dan harus dalam format .xlsx, .csv, .tsv, atau .txt',
                    'Hanya perlu 2 kolom: "kode" dan "nama" mata pelajaran',
                    'Kolom "kode" dan "nama" wajib diisi dan tidak boleh kosong',
                    'Kode mata pelajaran harus unik (tidak boleh duplikat)',
                    'Header (baris pertama) akan diabaikan jika berisi nama kolom',
                    'Baris kosong dan baris dengan kode kosong akan diabaikan',
                    'Data yang tidak valid akan ditampilkan sebagai warning',
                    'Pengaturan lain (kelas, guru, jenis, KKM, dll) dilakukan setelah import',
                    'Kelas dan guru akan ditugaskan secara terpisah melalui menu yang tersedia'
                ]
            ]
        ]);
    }
    
    /**
     * Get current teacher assignments for a subject (assignments only, not scheduled items)
     */
    public function getAssignments($id)
    {
        $assignments = \App\Models\JadwalPelajaran::where('mapel_id', $id)
                                                  ->with(['guru', 'kelas'])
                                                  ->whereHas('guru') // Only get schedules with valid guru
                                                  ->whereHas('kelas') // Only get schedules with valid kelas
                                                  ->assignments() // Only get assignment entries, not scheduled
                                                  ->get();
        
        // Get unique assigned teachers and classes
        $assignedGuru = $assignments->pluck('guru')->unique('id');
        $assignedKelas = $assignments->pluck('kelas')->unique('id');
        
        return response()->json([
            'success' => true,
            'assignments' => $assignments,
            'assigned_guru' => $assignedGuru->map(function($guru) {
                return [
                    'id' => $guru->id,
                    'nama' => $guru->nama
                ];
            })->values(),
            'assigned_kelas' => $assignedKelas->map(function($kelas) {
                return [
                    'id' => $kelas->id,
                    'nama_kelas' => $kelas->nama_kelas
                ];
            })->values()
        ]);
    }
    
    /**
     * Assign teacher to subject
     */
    public function assignTeacher(Request $request, $id)
    {
        try {
            // Log incoming request for debugging
            Log::info('Assign Teacher Request', [
                'mata_pelajaran_id' => $id,
                'request_data' => $request->all(),
                'content_type' => $request->header('Content-Type')
            ]);

            // Support both single guru_id (legacy) and multiple guru_ids (new)
            $validationRules = [
                'kelas_ids' => 'required|array|min:1',
                'kelas_ids.*' => 'exists:kelas,id',
            ];
            
            // Check if we have guru_ids (new) or guru_id (legacy)
            if ($request->has('guru_ids')) {
                $validationRules['guru_ids'] = 'required|array|min:1';
                $validationRules['guru_ids.*'] = 'exists:guru,id';
            } else {
                $validationRules['guru_id'] = 'required|exists:guru,id';
            }
            
            $request->validate($validationRules);
        
        // Get guru IDs array
        $guruIds = $request->has('guru_ids') ? $request->guru_ids : [$request->guru_id];
        
        // Get current academic year (July-June cycle)
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // If current month is July or later, academic year starts from current year
        // Otherwise, it started from previous year
        if ($currentMonth >= 7) {
            $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
        } else {
            $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
        }
        
        $addedCount = 0;
        $skippedCount = 0;
        
        foreach ($guruIds as $guruId) {
            foreach ($request->kelas_ids as $kelasId) {
                // Check if this teacher already has assignment for this subject and class (assignments only)
                $exists = \App\Models\JadwalPelajaran::where('guru_id', $guruId)
                                                    ->where('mapel_id', $id)
                                                    ->where('kelas_id', $kelasId)
                                                    ->assignments() // Only check assignments, not scheduled items
                                                    ->exists();
                
                if (!$exists) {
                    // Create assignment entry (without specific schedule details)
                    // This is ONLY an assignment, not a scheduled class
                    \App\Models\JadwalPelajaran::create([
                        'guru_id' => $guruId,
                        'mapel_id' => $id,
                        'kelas_id' => $kelasId,
                        'tahun_ajaran' => $tahunAjaran, // Dynamic academic year
                        'semester' => 1, // Default to semester 1
                        'is_active' => 1, // Set as active
                        // Scheduling fields are left NULL to indicate this is an assignment only
                        'hari' => null,
                        'jam_ke' => null,
                        'jam_mulai' => null,
                        'jam_selesai' => null,
                    ]);
                    $addedCount++;
                } else {
                    $skippedCount++;
                }
            }
        }
        
        $message = "Berhasil menambahkan {$addedCount} assignment guru.";
        if ($skippedCount > 0) {
            $message .= " {$skippedCount} assignment dilewati karena sudah ada.";
        }
        
        Log::info('Assign Teacher Result', [
            'added_count' => $addedCount,
            'skipped_count' => $skippedCount,
            'message' => $message
        ]);
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'created_count' => $addedCount,
            'skipped_count' => $skippedCount
        ], 200, ['Content-Type' => 'application/json']);
        
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Assign Teacher Validation Error', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $e->errors()
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            Log::error('Assign Teacher General Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }
}
