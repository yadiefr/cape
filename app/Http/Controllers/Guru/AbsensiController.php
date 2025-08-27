<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\Notification;
use App\Models\Settings;
use App\Traits\GuruAccessTrait;
use App\Exports\RekapAbsensiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    use GuruAccessTrait;

    public function index(Request $request)
    {
        $user = Auth::guard('guru')->user();
        $guru_id = $user->id;
        
        // Get mata pelajaran using trait method - ordered alphabetically
        $mapelIds = $this->getGuruMapelIds();
        $mapel = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();
        
        // Get kelas using trait method - ordered alphabetically
        $kelasIds = $this->getGuruKelasIds();
        $kelas = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        
        // Build query for absensi data and group by date, class, and subject
        $query = Absensi::with(['kelas', 'mapel', 'guru'])
                        ->selectRaw('
                            tanggal,
                            kelas_id,
                            mapel_id,
                            COUNT(*) as total_siswa,
                            SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as hadir,
                            SUM(CASE WHEN status IN ("izin", "sakit", "alpha") THEN 1 ELSE 0 END) as tidak_hadir,
                            MAX(id) as id
                        ')
                        ->groupBy('tanggal', 'kelas_id', 'mapel_id');
        
        // Filter by teacher's classes and subjects only
        $query->whereIn('kelas_id', $kelas->pluck('id'))
              ->whereIn('mapel_id', $mapel->pluck('id'));
        
        // Apply additional filters from request
        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }
        
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        
        $absensi_list = $query->orderBy('tanggal', 'desc')->get();
        
        // Load relations manually for grouped data
        $kelas_map = $kelas->keyBy('id');
        $mapel_map = $mapel->keyBy('id');
        
        foreach ($absensi_list as $absensi) {
            $absensi->kelas = $kelas_map->get($absensi->kelas_id);
            $absensi->mapel = $mapel_map->get($absensi->mapel_id);
        }
        
        return view('guru.absensi.index', compact('mapel', 'kelas', 'absensi_list'));
    }    public function create(Request $request)
    {
        $user = Auth::guard('guru')->user();
        $guru_id = $user->id;
        
        // Get mata pelajaran and kelas using trait methods - ordered alphabetically
        $mapelIds = $this->getGuruMapelIds();
        $mapel = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();
        
        $kelasIds = $this->getGuruKelasIds();
        $kelas = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        
        // Ensure we have collections even if empty
        if (!$mapel) {
            $mapel = collect();
        }
        if (!$kelas) {
            $kelas = collect();
        }
        
        // Get pre-selected values from URL parameters
        $selectedKelas = $request->get('kelas_id');
        $selectedMapel = $request->get('mapel_id');
        $jadwalId = $request->get('jadwal_id');
        
        // If jadwal_id is provided, get kelas_id and mapel_id from jadwal
        if ($jadwalId) {
            $jadwal = JadwalPelajaran::find($jadwalId);
            if ($jadwal) {
                $selectedKelas = $jadwal->kelas_id;
                $selectedMapel = $jadwal->mapel_id;
            }
        }
        
        return view('guru.absensi.create', compact('mapel', 'kelas', 'selectedKelas', 'selectedMapel'));
    }

    private function createAbsensiNotification($siswaId, $status, $tanggal, $mapel)
    {
        if (in_array($status, ['izin', 'sakit', 'alpha'])) {
            $message = "Anda tidak hadir pada mata pelajaran {$mapel->nama} tanggal " . 
                      $tanggal->format('d/m/Y') . " dengan status " . strtoupper($status);
            
            Notification::create([
                'user_id' => $siswaId,
                'type' => 'absensi',
                'message' => $message,
                'link' => route('siswa.absensi.index')
            ]);
        }
    }

    public function store(Request $request)
    {
        // Debug logging
        \Log::info('Absensi store called', [
            'all_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'user_id' => Auth::id()
        ]);
        
        // Simplified validation first
        try {
            $validatedData = $request->validate([
                'kelas_id' => 'required',
                'mapel_id' => 'required', 
                'tanggal' => 'required',
            ]);
            \Log::info('Basic validation passed', $validatedData);
        } catch (\Exception $e) {
            \Log::error('Basic validation failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Validasi dasar gagal: ' . $e->getMessage())
                ->withInput();
        }

        // Check for student data
        $siswaIds = $request->input('siswa_id');
        $statuses = $request->input('status');
        
        \Log::info('Student data check', [
            'siswa_ids' => $siswaIds,
            'statuses' => $statuses,
            'siswa_count' => is_array($siswaIds) ? count($siswaIds) : 0,
            'status_count' => is_array($statuses) ? count($statuses) : 0,
        ]);
        
        if (!$siswaIds || !is_array($siswaIds) || empty($siswaIds)) {
            \Log::error('No student data found');
            return redirect()->back()
                ->with('error', 'Data siswa tidak ditemukan. Pastikan Anda telah memilih kelas dan data siswa telah dimuat.')
                ->withInput();
        }
        
        if (!$statuses || !is_array($statuses)) {
            \Log::error('No status data found');
            return redirect()->back()
                ->with('error', 'Data status tidak ditemukan. Pastikan Anda telah memilih status untuk setiap siswa.')
                ->withInput();
        }

        // Cek duplikasi yang lebih ketat - untuk semua siswa di kelas ini
        $existingAbsensi = Absensi::where('kelas_id', $request->kelas_id)
            ->where('mapel_id', $request->mapel_id)
            ->whereDate('tanggal', $request->tanggal)
            ->exists();

        if ($existingAbsensi) {
            \Log::warning('Duplicate attendance attempt', $request->all());
            return redirect()->back()
                ->with('error', 'Absensi untuk kelas dan mata pelajaran ini pada tanggal tersebut sudah ada!')
                ->withInput();
        }

        try {
            \DB::beginTransaction();
            
            $createdCount = 0;
            $guru_id = Auth::id();
            
            foreach ($siswaIds as $index => $siswaId) {
                $status = isset($statuses[$index]) ? $statuses[$index] : 'alpha';
                $keterangan = $request->input("keterangan.$index");
                
                // Validasi nilai status
                if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
                    $status = 'alpha';
                }

                // Cek sekali lagi untuk siswa individual (double check)
                $existingSiswaAbsensi = Absensi::where('siswa_id', $siswaId)
                    ->where('kelas_id', $request->kelas_id)
                    ->where('mapel_id', $request->mapel_id)
                    ->whereDate('tanggal', $request->tanggal)
                    ->first();

                if ($existingSiswaAbsensi) {
                    \Log::warning('Duplicate individual attendance found', [
                        'siswa_id' => $siswaId,
                        'existing_id' => $existingSiswaAbsensi->id
                    ]);
                    continue; // Skip this student
                }
                
                $absensi = Absensi::create([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $request->kelas_id,
                    'mapel_id' => $request->mapel_id,
                    'tanggal' => $request->tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'guru_id' => $guru_id
                ]);
                
                $createdCount++;
                \Log::info('Absensi created', [
                    'id' => $absensi->id, 
                    'siswa_id' => $siswaId, 
                    'status' => $status,
                    'keterangan' => $keterangan
                ]);
            }
            
            \DB::commit();
            
            \Log::info('Absensi batch created successfully', ['count' => $createdCount]);
            
            return redirect()->route('guru.absensi.index')
                ->with('success', "Data absensi berhasil disimpan untuk {$createdCount} siswa");
                
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('Error creating attendance records', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        // Get the reference absensi record
        $referenceAbsensi = Absensi::with(['kelas', 'mapel', 'guru'])->findOrFail($id);

        // Get all attendance records for the same class, subject and date
        $absensi = Absensi::with(['siswa', 'kelas', 'mapel', 'guru'])
            ->whereDate('absensi.tanggal', $referenceAbsensi->tanggal)
            ->where('absensi.mapel_id', $referenceAbsensi->mapel_id)
            ->where('absensi.kelas_id', $referenceAbsensi->kelas_id)
            ->where('absensi.guru_id', $referenceAbsensi->guru_id)
            ->join('siswa', 'absensi.siswa_id', '=', 'siswa.id')
            ->orderBy('siswa.nama_lengkap')
            ->select('absensi.*')
            ->get();

        if ($absensi->isEmpty()) {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Data absensi tidak ditemukan');
        }

        // Calculate statistics for this class and subject
        $stats = [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'total' => $absensi->count()
        ];

        return view('guru.absensi.show', compact('absensi', 'referenceAbsensi', 'stats'));
    }
    
    public function edit($id)
    {
        error_log("=== EDIT METHOD CALLED - ID: $id ===");
        
        $absensi = Absensi::with(['siswa', 'kelas', 'mapel'])->findOrFail($id);
        $user = Auth::user();
        $guru_id = $user->id;
        
        // Get all attendance records for the same session (date, class, subject)
        // Group by siswa_id to avoid duplicates
        $allAbsensi = Absensi::with(['siswa'])
            ->where('tanggal', $absensi->tanggal)
            ->where('kelas_id', $absensi->kelas_id)
            ->where('mapel_id', $absensi->mapel_id)
            ->where('guru_id', $guru_id)
            ->get()
            ->unique('siswa_id') // Pastikan tidak ada duplikasi berdasarkan siswa_id
            ->sortBy('siswa.nama_lengkap')
            ->values(); // Reset array keys
        
        // Log untuk debugging
        \Log::info('Edit absensi - found records', [
            'total_records' => $allAbsensi->count(),
            'siswa_ids' => $allAbsensi->pluck('siswa_id')->toArray(),
            'duplicate_check' => $allAbsensi->pluck('siswa_id')->duplicates()->toArray()
        ]);
        
        // Jika masih ada duplikasi, hapus yang lama dan ambil yang terbaru
        if ($allAbsensi->pluck('siswa_id')->duplicates()->isNotEmpty()) {
            error_log("Found duplicates, cleaning up...");
            
            // Hapus semua record duplikat, sisakan yang terbaru untuk setiap siswa
            $siswaGroups = $allAbsensi->groupBy('siswa_id');
            
            $cleanAbsensi = collect();
            foreach ($siswaGroups as $siswaId => $records) {
                if ($records->count() > 1) {
                    // Jika ada duplikat, ambil yang terakhir (ID tertinggi)
                    $latestRecord = $records->sortBy('id')->last();
                    $cleanAbsensi->push($latestRecord);
                    
                    // Hapus record duplikat yang lama
                    $oldRecords = $records->where('id', '!=', $latestRecord->id);
                    foreach ($oldRecords as $oldRecord) {
                        $oldRecord->delete();
                        error_log("Deleted duplicate record ID: " . $oldRecord->id . " for siswa: " . $siswaId);
                    }
                } else {
                    $cleanAbsensi->push($records->first());
                }
            }
            
            $allAbsensi = $cleanAbsensi->sortBy('siswa.nama_lengkap')->values();
        }
        
        // Get mata pelajaran through jadwal_pelajaran table
        $mapel = MataPelajaran::whereIn('id', function($query) use ($guru_id) {
            $query->select('mapel_id')
                  ->from('jadwal_pelajaran')
                  ->where('guru_id', $guru_id)
                  ->where('is_active', true);
        })->get();
        
        // Get kelas that the teacher teaches (based on jadwal_pelajaran)
        $kelas = Kelas::whereIn('id', function($query) use ($guru_id) {
            $query->select('kelas_id')
                  ->from('jadwal_pelajaran')
                  ->where('guru_id', $guru_id)
                  ->where('is_active', true)
                  ->distinct();
        })->get();
        
        // Check if user has permission to edit this absensi
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        $hasPermission = JadwalPelajaran::where('guru_id', $guru_id)
                                       ->where('mapel_id', $absensi->mapel_id)
                                       ->where('semester', $semester)
                                       ->where('tahun_ajaran', $tahun_ajaran)
                                       ->where('is_active', true)
                                       ->exists();
                                       
        if (!$hasPermission) {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit data absensi ini.');
        }
        
        return view('guru.absensi.edit', compact('absensi', 'allAbsensi', 'mapel', 'kelas'));
    }
    public function update(Request $request, $id)
    {
        // Force write to log file immediately
        error_log("=== ABSENSI UPDATE CALLED - ID: $id ===");
        \Log::info("=== ABSENSI UPDATE CALLED - ID: $id ===");
        
        $absensi = Absensi::findOrFail($id);
        $user = Auth::user();
        $guru_id = $user->id;
        
        // Log incoming request data for debugging
        error_log("Update request data: " . json_encode($request->all()));
        \Log::info('Update absensi request', [
            'id' => $id,
            'guru_id' => $guru_id,
            'request_data' => $request->all(),
            'has_siswa_id_array' => $request->has('siswa_id') && is_array($request->siswa_id),
            'siswa_id_type' => gettype($request->siswa_id ?? 'missing'),
            'siswa_id_value' => $request->siswa_id ?? 'missing'
        ]);
        
        // Check for single vs multiple student update
        if ($request->has('siswa_id') && is_array($request->siswa_id)) {
            error_log("Using updateBulkAbsensi method");
            \Log::info('Using updateBulkAbsensi method');
            // Multiple student update (bulk edit)
            return $this->updateBulkAbsensi($request, $absensi);
        } else {
            error_log("Using updateSingleAbsensi method");
            \Log::info('Using updateSingleAbsensi method');
            // Single student update (legacy)
            return $this->updateSingleAbsensi($request, $absensi);
        }
    }
    
    private function updateBulkAbsensi(Request $request, $originalAbsensi)
    {
        error_log("=== BULK UPDATE ABSENSI STARTED ===");
        
        // Debug specific request data
        error_log("Raw request data: " . json_encode($request->all()));
        error_log("siswa_id data: " . json_encode($request->input('siswa_id')));
        error_log("status data: " . json_encode($request->input('status')));
        error_log("kelas_id: " . $request->input('kelas_id'));
        error_log("mapel_id: " . $request->input('mapel_id'));
        error_log("tanggal: " . $request->input('tanggal'));
        
        // Log the incoming data structure
        \Log::info('updateBulkAbsensi called', [
            'request_all' => $request->all(),
            'original_absensi' => $originalAbsensi->toArray()
        ]);
        
        // Simplified validation to debug the issue
        $siswaIds = $request->input('siswa_id', []);
        $statuses = $request->input('status', []);
        $kelasId = $request->input('kelas_id');
        $mapelId = $request->input('mapel_id');
        $tanggal = $request->input('tanggal');
        
        error_log("Validation check - siswa_id count: " . count($siswaIds));
        error_log("Validation check - status count: " . count($statuses));
        error_log("Validation check - kelas_id: " . ($kelasId ?: 'EMPTY'));
        error_log("Validation check - mapel_id: " . ($mapelId ?: 'EMPTY'));
        error_log("Validation check - tanggal: " . ($tanggal ?: 'EMPTY'));
        
        // Check for basic required fields
        $errors = [];
        if (empty($kelasId)) {
            $errors['kelas_id'] = 'Kelas harus dipilih';
        }
        if (empty($mapelId)) {
            $errors['mapel_id'] = 'Mata pelajaran harus dipilih';
        }
        if (empty($tanggal)) {
            $errors['tanggal'] = 'Tanggal harus diisi';
        }
        if (empty($siswaIds)) {
            $errors['siswa_id'] = 'Data siswa tidak ditemukan';
        }
        if (empty($statuses)) {
            $errors['status'] = 'Status kehadiran harus diisi';
        }
        
        if (!empty($errors)) {
            error_log("Validation errors: " . json_encode($errors));
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }
        
        $guru_id = Auth::user()->id;
        
        // Check permission
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        $hasPermission = JadwalPelajaran::where('guru_id', $guru_id)
                                       ->where('mapel_id', $request->mapel_id)
                                       ->where('semester', $semester)
                                       ->where('tahun_ajaran', $tahun_ajaran)
                                       ->where('is_active', true)
                                       ->exists();
                                       
        if (!$hasPermission) {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data absensi ini.');
        }
        
        try {
            DB::beginTransaction();
            
            // Use the submitted form data instead of original absensi data
            $tanggal = $request->input('tanggal');
            $kelas_id = $request->input('kelas_id');
            $mapel_id = $request->input('mapel_id');
            
            // HAPUS SEMUA RECORD EXISTING TERLEBIH DAHULU untuk menghindari duplikasi
            Absensi::where('tanggal', $tanggal)
                   ->where('kelas_id', $kelas_id)
                   ->where('mapel_id', $mapel_id)
                   ->where('guru_id', $guru_id)
                   ->delete();
            
            \Log::info('Deleted existing absensi records', [
                'tanggal' => $tanggal,
                'kelas_id' => $kelas_id,
                'mapel_id' => $mapel_id,
                'guru_id' => $guru_id
            ]);
            
            // Process the form data
            $siswaIds = $request->input('siswa_id');
            $statuses = $request->input('status');
            $keterangans = $request->input('keterangan', []);
            
            \Log::info('Processing attendance data', [
                'siswa_ids' => $siswaIds,
                'statuses' => $statuses,
                'statuses_structure' => is_array($statuses) ? array_keys($statuses) : 'not_array',
                'keterangans' => $keterangans,
                'siswa_count' => count($siswaIds),
                'status_count' => is_array($statuses) ? count($statuses) : 0
            ]);
            
            $createdCount = 0;
            
            foreach ($siswaIds as $index => $siswaId) {
                // Handle the status array structure from form: status[0], status[1], etc.
                $status = 'hadir'; // default
                
                // Form sends status as status[0], status[1], etc.
                if (isset($statuses[$index])) {
                    $status = $statuses[$index];
                }
                
                // Validate status value
                if (!in_array($status, ['hadir', 'izin', 'sakit', 'alpha'])) {
                    $status = 'hadir';
                }
                
                // Handle keterangan array - should be in same index order
                $keterangan = null;
                if (isset($keterangans[$index])) {
                    $keterangan = $keterangans[$index];
                }
                
                $absensiData = [
                    'siswa_id' => $siswaId,
                    'guru_id' => $guru_id,
                    'kelas_id' => $kelas_id,
                    'mapel_id' => $mapel_id,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan
                ];
                
                // Create new record (karena kita sudah hapus semua record existing)
                error_log("Creating new record for siswa: " . $siswaId);
                
                $newRecord = Absensi::create($absensiData);
                $createdCount++;
                
                \Log::info('Created new absensi record', [
                    'id' => $newRecord->id,
                    'siswa_id' => $siswaId,
                    'status' => $status,
                    'keterangan' => $keterangan,
                    'index' => $index
                ]);
            }
            
            DB::commit();
            
            \Log::info('Bulk absensi update completed', [
                'created_count' => $createdCount
            ]);
            
            $message = "Data absensi berhasil diperbarui untuk {$createdCount} siswa.";
            
            return redirect()->route('guru.absensi.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error updating bulk absensi', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'original_absensi' => $originalAbsensi->toArray()
            ]);
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data absensi: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    private function updateSingleAbsensi(Request $request, $absensi)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'siswa_id' => 'required|exists:siswa,id',
            'tanggal' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255'
        ]);
        
        $guru_id = Auth::user()->id;
        
        // Check permission
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        $hasPermission = JadwalPelajaran::where('guru_id', $guru_id)
                                       ->where('mapel_id', $absensi->mapel_id)
                                       ->where('semester', $semester)
                                       ->where('tahun_ajaran', $tahun_ajaran)
                                       ->where('is_active', true)
                                       ->exists();
                                       
        if (!$hasPermission) {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah data absensi ini.');
        }
        
        // Update absensi data
        $absensi->update([
            'kelas_id' => $request->kelas_id,
            'mapel_id' => $request->mapel_id,
            'tanggal' => $request->tanggal,
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);
        
        // Create notification if status changed to non-hadir
        if ($absensi->status != 'hadir' && $absensi->status != $request->old_status) {
            $mapel = MataPelajaran::find($request->mapel_id);
            $tanggal = \Carbon\Carbon::parse($request->tanggal);
            
            $message = "Status kehadiran Anda untuk mata pelajaran {$mapel->nama} tanggal " . 
                      $tanggal->format('d/m/Y') . " telah diubah menjadi " . strtoupper($request->status);
            
            Notification::create([
                'user_id' => $absensi->siswa->user_id,
                'type' => 'absensi',
                'message' => $message,
                'link' => route('siswa.absensi.index')
            ]);
        }
        
        return redirect()->route('guru.absensi.show', $absensi->id)
            ->with('success', 'Data absensi berhasil diperbarui');
    }
      public function destroy($id)
    {
        error_log("=== DESTROY METHOD CALLED - ID: $id ===");
        
        $absensi = Absensi::findOrFail($id);
        $user = Auth::user();
        $guru_id = $user->guru ? $user->guru->id : $user->id;
        
        error_log("Destroy - absensi found: " . json_encode($absensi->toArray()));
        error_log("Destroy - guru_id: $guru_id");
        
        // Check if user has permission to delete this absensi - check through jadwal_pelajaran
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        $hasPermission = JadwalPelajaran::where('guru_id', $guru_id)
                                       ->where('mapel_id', $absensi->mapel_id)
                                       ->where('semester', $semester)
                                       ->where('tahun_ajaran', $tahun_ajaran)
                                       ->where('is_active', true)
                                       ->exists();
                                       
        if (!$hasPermission) {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus data absensi ini.');
        }
        
        // Delete all absensi records for the same date, class, and subject
        $deletedCount = Absensi::where('tanggal', $absensi->tanggal)
                               ->where('kelas_id', $absensi->kelas_id)
                               ->where('mapel_id', $absensi->mapel_id)
                               ->delete();
        
        return redirect()->route('guru.absensi.index')
            ->with('success', "Data absensi berhasil dihapus ({$deletedCount} siswa)");
    }    public function rekap(Request $request)
    {
        $user = Auth::guard('guru')->user();
        $guru_id = $user->id;
        
        // Get mata pelajaran and kelas using trait methods - ordered alphabetically
        $mapelIds = $this->getGuruMapelIds();
        $mapel = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();
        
        $kelasIds = $this->getGuruKelasIds();
        $kelas = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        
        $data = [
            'mapel' => $mapel,
            'kelas' => $kelas
        ];
        
        if ($request->filled(['kelas_id', 'mapel_id', 'tanggal_awal', 'tanggal_akhir'])) {
            $kelasId = $request->kelas_id;
            $mapelId = $request->mapel_id;
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            
            // Mendapatkan siswa berdasarkan kelas
            $siswa = \App\Models\Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get();
            
            // Mendapatkan data absensi terlebih dahulu
            $absensi = Absensi::where('kelas_id', $kelasId)
                ->where('mapel_id', $mapelId)
                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                ->get();
            
            // Mendapatkan periode absensi hanya dari tanggal yang ada datanya
            $periode = $absensi->pluck('tanggal')
                              ->map(function($tanggal) {
                                  return is_string($tanggal) ? $tanggal : $tanggal->format('Y-m-d');
                              })
                              ->unique()
                              ->sort()
                              ->values()
                              ->toArray();
            
            // Memformat data absensi
            $dataAbsensi = [];
            $rekapData = [];
            
            foreach ($absensi as $a) {
                $dataAbsensi[$a->siswa_id][$a->tanggal->format('Y-m-d')] = $a->status;
                
                if (!isset($rekapData[$a->siswa_id])) {
                    $rekapData[$a->siswa_id] = [
                        'hadir' => 0,
                        'izin' => 0,
                        'sakit' => 0,
                        'alpha' => 0
                    ];
                }
                
                $rekapData[$a->siswa_id][$a->status]++;
            }
            
            $data['siswa'] = $siswa;
            $data['periode'] = $periode;
            $data['dataAbsensi'] = $dataAbsensi;
            $data['rekapData'] = $rekapData;
        }
        
        return view('guru.absensi.rekap', $data);
    }

    public function cetakRekap(Request $request)
    {
        $user = Auth::guard('guru')->user();
        $guru_id = $user->id;
        
        // Validasi parameter yang diperlukan
        if (!$request->filled(['kelas_id', 'mapel_id', 'tanggal_awal', 'tanggal_akhir'])) {
            return redirect()->route('guru.absensi.rekap')
                           ->with('error', 'Silakan pilih filter terlebih dahulu sebelum mencetak.');
        }
        
        $kelasId = $request->kelas_id;
        $mapelId = $request->mapel_id;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        
        // Get mata pelajaran and kelas data
        $mapel = MataPelajaran::findOrFail($mapelId);
        $kelas = Kelas::findOrFail($kelasId);
        
        // Mendapatkan siswa berdasarkan kelas
        $siswa = \App\Models\Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get();
        
        // Mendapatkan data absensi terlebih dahulu
        $absensi = Absensi::where('kelas_id', $kelasId)
            ->where('mapel_id', $mapelId)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->get();
        
        // Mendapatkan periode absensi hanya dari tanggal yang ada datanya
        $periode = $absensi->pluck('tanggal')
                          ->map(function($tanggal) {
                              return is_string($tanggal) ? $tanggal : $tanggal->format('Y-m-d');
                          })
                          ->unique()
                          ->sort()
                          ->values()
                          ->toArray();
        
        // Memformat data absensi
        $dataAbsensi = [];
        $rekapData = [];
        
        foreach ($absensi as $a) {
            $dataAbsensi[$a->siswa_id][$a->tanggal->format('Y-m-d')] = $a->status;
            
            if (!isset($rekapData[$a->siswa_id])) {
                $rekapData[$a->siswa_id] = [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpha' => 0
                ];
            }
            
            $rekapData[$a->siswa_id][$a->status]++;
        }
        
        $data = [
            'siswa' => $siswa,
            'periode' => $periode,
            'dataAbsensi' => $dataAbsensi,
            'rekapData' => $rekapData,
            'mapel' => $mapel,
            'kelas' => $kelas,
            'tanggalAwal' => $tanggalAwal,
            'tanggalAkhir' => $tanggalAkhir,
            'guru' => $user
        ];
        
        return view('guru.absensi.cetak-rekap', $data);
    }

    public function exportExcel(Request $request)
    {
        $user = Auth::guard('guru')->user();
        $guru_id = $user->id;
        
        // Validasi parameter yang diperlukan
        if (!$request->filled(['kelas_id', 'mapel_id', 'tanggal_awal', 'tanggal_akhir'])) {
            return redirect()->route('guru.absensi.rekap')
                           ->with('error', 'Silakan pilih filter terlebih dahulu sebelum export.');
        }
        
        $kelasId = $request->kelas_id;
        $mapelId = $request->mapel_id;
        $tanggalAwal = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;
        
        // Get mata pelajaran and kelas data
        $mapel = MataPelajaran::findOrFail($mapelId);
        $kelas = Kelas::findOrFail($kelasId);
        
        // Mendapatkan siswa berdasarkan kelas
        $siswa = \App\Models\Siswa::where('kelas_id', $kelasId)->orderBy('nama_lengkap')->get();
        
        // Mendapatkan data absensi terlebih dahulu
        $absensi = Absensi::where('kelas_id', $kelasId)
            ->where('mapel_id', $mapelId)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->get();
        
        // Mendapatkan periode absensi hanya dari tanggal yang ada datanya
        $periode = $absensi->pluck('tanggal')
                          ->map(function($tanggal) {
                              return is_string($tanggal) ? $tanggal : $tanggal->format('Y-m-d');
                          })
                          ->unique()
                          ->sort()
                          ->values()
                          ->toArray();
        
        // Memformat data absensi
        $dataAbsensi = [];
        $rekapData = [];
        
        foreach ($absensi as $a) {
            $dataAbsensi[$a->siswa_id][$a->tanggal->format('Y-m-d')] = $a->status;
            
            if (!isset($rekapData[$a->siswa_id])) {
                $rekapData[$a->siswa_id] = [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alpha' => 0
                ];
            }
            
            $rekapData[$a->siswa_id][$a->status]++;
        }
        
        // Generate filename dengan format yang informatif
        $filename = 'Rekap_Absensi_' . 
                   str_replace(' ', '_', $kelas->nama_kelas) . '_' .
                   str_replace(' ', '_', $mapel->nama) . '_' .
                   date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(
            new RekapAbsensiExport($siswa, $periode, $dataAbsensi, $rekapData, $kelas, $mapel, $tanggalAwal, $tanggalAkhir),
            $filename
        );
    }
      public function kelasList($kelas_id)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = Kelas::with(['siswa', 'jurusan'])->findOrFail($kelas_id);
        
        // Check if guru has access to this class (either as wali kelas or has jadwal in this class)
        $hasAccess = $guru->kelas()->where('id', $kelas_id)->exists() || 
                     $guru->jadwal()->where('kelas_id', $kelas_id)->exists();
                     
        if (!$hasAccess) {
            return redirect()->route('guru.dashboard')
                           ->with('error', 'Anda tidak memiliki akses ke kelas ini');
        }
        
        $siswa = $kelas->siswa()
                      ->orderBy('nama_lengkap')
                      ->get();
                      
        // Get all absences for this class
        $absensi = Absensi::whereIn('siswa_id', $siswa->pluck('id'))
                          ->where('guru_id', $guru->id)
                          ->get()
                          ->groupBy('siswa_id');
                          
        // Calculate statistics for each student
        $rekapAbsensi = [];
        foreach ($siswa as $s) {
            $rekapAbsensi[$s->id] = [
                'hadir' => $absensi->get($s->id, collect())->where('status', 'hadir')->count(),
                'izin' => $absensi->get($s->id, collect())->where('status', 'izin')->count(),
                'sakit' => $absensi->get($s->id, collect())->where('status', 'sakit')->count(),
                'alpha' => $absensi->get($s->id, collect())->where('status', 'alpha')->count(),
            ];
        }
        
        $mapelDiajar = $guru->jadwal()
                           ->where('kelas_id', $kelas_id)
                           ->with('mapel')
                           ->get()
                           ->pluck('mapel')
                           ->unique('id');
        
        return view('guru.absensi.kelas', compact('kelas', 'siswa', 'rekapAbsensi', 'mapelDiajar'));
    }
    
    /**
     * Get kelas berdasarkan mata pelajaran yang diampu guru
     */
    public function getKelasByMapel(Request $request)
    {
        $user = Auth::user();
        $guru_id = $user->id;
        $mapel_id = $request->mapel_id;
        
        if ($mapel_id) {
            // Get kelas for specific mata pelajaran
            $kelas = Kelas::whereIn('id', function($query) use ($guru_id, $mapel_id) {
                $query->select('kelas_id')
                      ->from('jadwal_pelajaran')
                      ->where('guru_id', $guru_id)
                      ->where('mapel_id', $mapel_id)
                      ->where('is_active', true)
                      ->distinct();
            })->get();
        } else {
            // Get all kelas that the teacher teaches
            $kelas = Kelas::whereIn('id', function($query) use ($guru_id) {
                $query->select('kelas_id')
                      ->from('jadwal_pelajaran')
                      ->where('guru_id', $guru_id)
                      ->where('is_active', true)
                      ->distinct();
            })->get();
        }
        
        return response()->json($kelas);
    }
}