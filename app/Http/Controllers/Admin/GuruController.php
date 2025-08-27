<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Imports\GuruImport;
use App\Exports\GuruTemplateExport;
use App\Helpers\HostingStorageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with(['kelasWali.jurusan']);

        // Search by name or NIP
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter by gender
        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        // Filter by wali kelas status
        if ($request->filled('is_wali_kelas')) {
            $query->where('is_wali_kelas', $request->is_wali_kelas == '1');
        }

        $guru = $query->orderBy('nama', 'asc')->get();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        $kelas = \App\Models\Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        return view('admin.guru.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255|unique:guru,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'required|email|unique:guru,email',
            'password' => 'required|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wali_kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $data = $request->only(['nama', 'nip', 'email', 'jenis_kelamin', 'alamat', 'no_hp']);
        $data['password'] = Hash::make($request->password);
        
        // Handle wali kelas status dari checkbox
        $data['is_wali_kelas'] = $request->has('is_wali_kelas') && $request->is_wali_kelas == '1';
        
        // Handle wali kelas ID
        $waliKelasId = $request->wali_kelas_id;

        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoPath = HostingStorageHelper::uploadFile($foto, 'guru');
            
            if (!$fotoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto guru. Silakan coba lagi.');
            }
            
            $data['foto'] = $fotoPath;
        }

        $guru = Guru::create($data);

        // Handle kelas wali kelas assignment
        $isWaliKelas = $request->has('is_wali_kelas') && $request->is_wali_kelas == '1';
        
        if ($isWaliKelas && $waliKelasId) {
            // Jika guru menjadi wali kelas dan ada kelas yang dipilih
            // Validasi kelas belum memiliki wali kelas
            $existingWali = \App\Models\Kelas::where('id', $waliKelasId)
                                           ->whereNotNull('wali_kelas')
                                           ->first();
            
            if ($existingWali) {
                // Hapus guru yang baru dibuat jika ada konflik
                $guru->delete();
                if (isset($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kelas yang dipilih sudah memiliki wali kelas. Silakan pilih kelas yang berbeda.');
            }
            
            // Assign wali kelas
            \App\Models\Kelas::where('id', $waliKelasId)->update(['wali_kelas' => $guru->id]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil ditambahkan');
    }

    public function show(Guru $guru)
    {
        return view('admin.guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        $kelas = \App\Models\Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        return view('admin.guru.edit', compact('guru', 'kelas'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('guru', 'nip')->ignore($guru->id)
            ],
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('guru', 'email')->ignore($guru->id)
            ],
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'wali_kelas_id' => 'nullable|exists:kelas,id',
        ]);

        $data = $request->only(['nama', 'nip', 'email', 'jenis_kelamin', 'alamat', 'no_hp']);
        
        // Handle status wali kelas dari checkbox
        $data['is_wali_kelas'] = $request->has('is_wali_kelas') && $request->is_wali_kelas == '1';
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle photo upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($guru->foto) {
                Storage::disk('public')->delete($guru->foto);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/' . $guru->foto;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $foto = $request->file('foto');
            $fotoPath = HostingStorageHelper::uploadFile($foto, 'guru');
            
            if (!$fotoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto guru. Silakan coba lagi.');
            }
            
            $data['foto'] = $fotoPath;
        }

        // Update guru data
        $guru->update($data);

        // Handle kelas wali kelas assignment
        $waliKelasId = $request->wali_kelas_id;
        $isWaliKelas = $request->has('is_wali_kelas') && $request->is_wali_kelas == '1';
        
        if ($isWaliKelas && $waliKelasId) {
            // Jika guru menjadi wali kelas dan ada kelas yang dipilih
            // Validasi kelas belum memiliki wali kelas lain
            $existingWali = \App\Models\Kelas::where('id', $waliKelasId)
                                           ->where('wali_kelas', '!=', $guru->id)
                                           ->whereNotNull('wali_kelas')
                                           ->first();
            
            if ($existingWali) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kelas yang dipilih sudah memiliki wali kelas lain. Silakan pilih kelas yang berbeda.');
            }
            
            // Hapus assignment wali kelas sebelumnya dari guru ini
            \App\Models\Kelas::where('wali_kelas', $guru->id)->update(['wali_kelas' => null]);
            
            // Assign ke kelas baru
            \App\Models\Kelas::where('id', $waliKelasId)->update(['wali_kelas' => $guru->id]);
        } else {
            // Jika guru tidak lagi menjadi wali kelas atau tidak ada kelas yang dipilih, hapus dari semua kelas
            \App\Models\Kelas::where('wali_kelas', $guru->id)->update(['wali_kelas' => null]);
        }

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy(Guru $guru)
    {
        // Hapus assignment wali kelas sebelum menghapus guru
        \App\Models\Kelas::where('wali_kelas', $guru->id)->update(['wali_kelas' => null]);
        
        // Hapus foto jika ada
        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return redirect()->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus');
    }
    
    /**
     * Show form to assign subjects to teacher
     * This method handles teacher-subject ASSIGNMENTS only
     * Assignments are NOT the same as scheduled classes
     */
    public function assignSubjects(Guru $guru)
    {
        // Get ALL teacher-subject assignments (both scheduled and unscheduled)
        // But group them to avoid showing duplicates when an assignment becomes scheduled
        $allAssignments = \App\Models\JadwalPelajaran::where('guru_id', $guru->id)
                                                    ->with(['mapel', 'kelas', 'kelas.jurusan'])
                                                    ->whereHas('mapel') // Only include records with valid mapel
                                                    ->whereHas('kelas') // Only include records with valid kelas
                                                    ->get();
        
        // Group by combination of guru_id, mapel_id, kelas_id to avoid duplicates
        // Show only one entry per combination, prioritizing scheduled over unscheduled
        $groupedAssignments = $allAssignments->groupBy(function($item) {
            return $item->guru_id . '-' . $item->mapel_id . '-' . $item->kelas_id;
        })->map(function($group) {
            // If there's a scheduled item in the group, return that; otherwise return the assignment
            $scheduled = $group->filter(function($item) {
                return $item->isScheduled();
            })->first();
            
            return $scheduled ?: $group->first();
        })->values();
        
        $assignedSubjects = $groupedAssignments;
        
        $mapel = \App\Models\MataPelajaran::orderBy('nama')->get();
        $kelas = \App\Models\Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        $jurusan = \App\Models\Jurusan::orderBy('nama_jurusan')->get();
        
        // Get available subjects (all subjects for selection)
        $availableSubjects = $mapel;
        
        // Get available classes (all classes for selection)
        $availableClasses = $kelas;
        
        return view('admin.guru.assign-subjects', compact('guru', 'assignedSubjects', 'mapel', 'kelas', 'jurusan', 'availableSubjects', 'availableClasses'));
    }

    /**
     * Show SPA version of assign subjects form
     */
    public function assignSubjectsSpa(Guru $guru)
    {
        return view('admin.guru.assign-subjects-spa', compact('guru'));
    }

    /**
     * API endpoint to get assign subjects data for SPA
     */
    public function getAssignSubjectsData(Guru $guru)
    {
        // Get ALL teacher-subject assignments (both scheduled and unscheduled)
        // But group them to avoid showing duplicates when an assignment becomes scheduled
        $allAssignments = \App\Models\JadwalPelajaran::where('guru_id', $guru->id)
                                                    ->with(['mapel', 'kelas', 'kelas.jurusan'])
                                                    ->whereHas('mapel') // Only include records with valid mapel
                                                    ->whereHas('kelas') // Only include records with valid kelas
                                                    ->get();
        
        // Group by combination of guru_id, mapel_id, kelas_id to avoid duplicates
        // Show only one entry per combination, prioritizing scheduled over unscheduled
        $groupedAssignments = $allAssignments->groupBy(function($item) {
            return $item->guru_id . '-' . $item->mapel_id . '-' . $item->kelas_id;
        })->map(function($group) {
            // If there's a scheduled item in the group, return that; otherwise return the assignment
            $scheduled = $group->filter(function($item) {
                return $item->isScheduled();
            })->first();
            
            return $scheduled ?: $group->first();
        })->values();
        
        $assignedSubjects = $groupedAssignments;
        
        $mapel = \App\Models\MataPelajaran::orderBy('nama')->get();
        $kelas = \App\Models\Kelas::with('jurusan')->orderBy('nama_kelas')->get();
        
        return response()->json([
            'guru' => $guru,
            'assignedSubjects' => $assignedSubjects,
            'availableSubjects' => $mapel,
            'availableClasses' => $kelas
        ]);
    }

    /**
     * Store subject assignment for teacher
     * This creates ASSIGNMENTS only - NOT scheduled classes  
     * User must separately create jadwal entries in the jadwal section
     */
    public function storeSubjectAssignment(Request $request, Guru $guru)
    {
        $request->validate([
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'kelas_id' => 'required|array',
            'kelas_id.*' => 'exists:kelas,id'
        ]);
        
        // Determine current academic year
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        if ($currentMonth >= 7) {
            // July to December = first semester of current academic year
            $tahunAjaran = $currentYear . '/' . ($currentYear + 1);
        } else {
            $tahunAjaran = ($currentYear - 1) . '/' . $currentYear;
        }
        
        $addedCount = 0;
        $skippedCount = 0;
        
        foreach ($request->kelas_id as $kelasId) {
            // Check if this teacher already has ANY entry (assignment or scheduled) for this subject and class
            $exists = \App\Models\JadwalPelajaran::where('guru_id', $guru->id)
                                                ->where('mapel_id', $request->mapel_id)
                                                ->where('kelas_id', $kelasId)
                                                ->where('semester', 1) // Default semester
                                                ->where('tahun_ajaran', $tahunAjaran)
                                                ->exists(); // Check both assignments and scheduled items
            
            if (!$exists) {
                // Create assignment entry (without specific schedule details)
                // This is ONLY an assignment, not a scheduled class
                \App\Models\JadwalPelajaran::create([
                    'guru_id' => $guru->id,
                    'mapel_id' => $request->mapel_id,
                    'kelas_id' => $kelasId,
                    'semester' => 1, // Default semester
                    'tahun_ajaran' => $tahunAjaran,
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
        
        $message = "Assignment berhasil disimpan. Ditambahkan: {$addedCount}";
        if ($skippedCount > 0) {
            $message .= ", Dilewati (sudah ada): {$skippedCount}";
        }

        // Check if request wants JSON response (for API/SPA)
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'added_count' => $addedCount,
                'skipped_count' => $skippedCount
            ]);
        }
        
        return redirect()->route('admin.guru.assign-subjects', $guru->id)
                        ->with('success', $message);
    }

    /**
     * Remove subject assignment from teacher
     * This removes ANY teacher-subject assignment (both scheduled and unscheduled)
     */
    public function removeSubjectAssignment(Request $request, Guru $guru, $jadwalId)
    {
        // Debug logging
        \Log::info("Attempting to delete jadwal - Guru ID: {$guru->id}, Jadwal ID: {$jadwalId}");
        
        // Find and delete the specified jadwal entry
        $jadwal = \App\Models\JadwalPelajaran::where('id', $jadwalId)
                                            ->where('guru_id', $guru->id)
                                            ->first();
        
        if ($jadwal) {
            \Log::info("Jadwal found, deleting...", $jadwal->toArray());
            $isScheduled = $jadwal->isScheduled();
            $jadwal->delete();
            
            $message = $isScheduled ? 'Jadwal terjadwal berhasil dihapus' : 'Assignment berhasil dihapus';
            
            // Check if request wants JSON response
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            
            // Return redirect for normal form submission
            return redirect()->route('admin.guru.assign-subjects', $guru->id)
                           ->with('success', $message);
        }
        
        // Debug: Check if jadwal exists at all
        $jadwalExists = \App\Models\JadwalPelajaran::where('id', $jadwalId)->first();
        if ($jadwalExists) {
            \Log::warning("Jadwal exists but belongs to different guru", [
                'jadwal_guru_id' => $jadwalExists->guru_id,
                'requested_guru_id' => $guru->id
            ]);
        } else {
            \Log::warning("Jadwal with ID {$jadwalId} does not exist");
        }
        
        $errorMessage = 'Assignment tidak ditemukan';
        
        // Check if request wants JSON response
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
        
        // Return redirect for normal form submission
        return redirect()->route('admin.guru.assign-subjects', $guru->id)
                       ->with('error', $errorMessage);
    }

    // Import and export methods remain the same...
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240'
        ]);

        try {
            $import = new GuruImport();
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $results = $import->getResults();
            
            return redirect()->route('admin.guru.index')
                ->with('success', "Import berhasil! Berhasil: {$results['success']}, Gagal: {$results['failed']}")
                ->with('import_errors', $import->getErrors());
                
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new GuruTemplateExport, 'template_guru.xlsx');
    }
}
