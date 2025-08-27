<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\SettingsJadwal;
use App\Models\Settings;
use App\Exports\JadwalTemplateExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class JadwalController extends Controller
{
    /**
     * Get schedule data including mapel and guru lists
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getSchedule(Request $request): JsonResponse
    {
        try {
            Log::info('getSchedule request received', [
                'params' => $request->all(),
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'content' => $request->getContent()
            ]);
            
            // Validate basic required fields without restrictions on semester
            $request->validate([
                'kelas_id' => 'nullable|exists:kelas,id', // Make kelas_id nullable for batch delete preview
                'tahun_ajaran' => 'nullable',
                'hari' => 'nullable|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu' // Add hari validation for batch delete
            ]);
            
            // Get parameters with defaults from settings
            $kelas_id = $request->input('kelas_id');
            $hari = $request->input('hari'); // Add hari parameter for batch delete preview
            $isPreview = $request->input('preview', false); // Check if this is a preview request
            
            // Get semester param and ensure it's in the right format
            $semester = $request->input('semester');
            // Log the raw semester value
            Log::info('Raw semester value:', ['value' => $semester, 'type' => gettype($semester)]);
            
            // Convert semester to numeric format
            if ($semester === 'Ganjil' || $semester === '1' || $semester === 1) {
                $semester = 1;
            } else if ($semester === 'Genap' || $semester === '2' || $semester === 2) {
                $semester = 2;
            } else {
                // Default value if not provided or invalid
                $semester = Settings::getValue('semester_aktif', 1);
            }
            
            $tahun_ajaran = $request->input('tahun_ajaran', Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1)));
            
            Log::info('getSchedule parameters after processing', [
                'kelas_id' => $kelas_id,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran
            ]);

            // Get schedules for the class
            Log::info('Fetching schedules with parameters:', [
                'kelas_id' => $kelas_id,
                'semester' => $semester,
                'tahun_ajaran' => $tahun_ajaran,
                'hari' => $hari,
                'is_preview' => $isPreview
            ]);
            
            $jadwalQuery = JadwalPelajaran::with(['mapel', 'guru', 'kelas']);
            
            // Apply filters based on request
            if ($kelas_id) {
                $jadwalQuery->where('kelas_id', $kelas_id);
            }
            if ($hari) {
                $jadwalQuery->where('hari', $hari);
            }
            if ($semester) {
                $jadwalQuery->where('semester', $semester);
            }
            if ($tahun_ajaran) {
                $jadwalQuery->where('tahun_ajaran', $tahun_ajaran);
            }
            
            // Only show scheduled items (have time assigned)
            $jadwalQuery->scheduled(); // Use scope instead of individual whereNotNull
                
            Log::info('JadwalPelajaran SQL query:', [
                'sql' => $jadwalQuery->toSql(),
                'bindings' => $jadwalQuery->getBindings()
            ]);
                
            $schedules = $jadwalQuery->get()
                ->map(function($jadwal) {
                    // Log each jadwal record to debug
                    Log::info('Processing jadwal record:', [
                        'id' => $jadwal->id,
                        'hari' => $jadwal->hari,
                        'jam_ke' => $jadwal->jam_ke,
                        'mapel_id' => $jadwal->mapel_id,
                        'mapel' => $jadwal->mapel ? $jadwal->mapel->nama : null,
                        'guru_id' => $jadwal->guru_id,
                        'guru' => $jadwal->guru ? $jadwal->guru->nama : null
                    ]);
                    
                    return [
                        'id' => $jadwal->id,
                        'hari' => $jadwal->hari,
                        'jam_ke' => $jadwal->jam_ke,
                        'jam_mulai' => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                        'mapel_id' => $jadwal->mapel_id,
                        'mapel' => $jadwal->mapel, // Return full object for preview
                        'guru_id' => $jadwal->guru_id,
                        'guru' => $jadwal->guru, // Return full object for preview
                        'kelas' => $jadwal->kelas, // Include kelas for preview
                        'mapel_nama' => $jadwal->mapel ? $jadwal->mapel->nama : 'Mata Pelajaran Tidak Ditemukan',
                        'guru_nama' => $jadwal->guru ? $jadwal->guru->nama : 'Guru Tidak Ditemukan',
                        'keterangan' => $jadwal->keterangan,
                        'is_active' => $jadwal->is_active
                    ];
                });
                
            Log::info('Returning schedules count:', ['count' => $schedules->count()]);
            
            // Get lists for dropdowns
            $mapel_list = MataPelajaran::orderBy('nama')->get();
            $guru_list = Guru::orderBy('nama')->get();

            return response()->json([
                'success' => true,
                'jadwal' => $schedules, // Change key to 'jadwal' for consistency
                'schedules' => $schedules, // Keep old key for backward compatibility
                'mapel_list' => $mapel_list,
                'guru_list' => $guru_list
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getSchedule:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get basic data
        $kelasId = request('kelas_id');
        $kelas_list = Kelas::orderBy('nama_kelas')->get();
        $guru_list = Guru::orderBy('nama')->get();
        $mapel_list = MataPelajaran::orderBy('nama')->get();
        
        // Get settings jadwal data
        $settingsJadwal = SettingsJadwal::where('is_active', true)->get();
        
        // Get active semester and year from settings
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));

        // Get jadwal with relationships and apply filters
        $jadwal_pelajaran = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])
            ->whereHas('kelas') // Only get jadwal that have valid kelas
            ->scheduled() // Only show scheduled items using scope
            ->when($kelasId, function($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })
            ->when(request('guru_id'), function($query) {
                return $query->where('guru_id', request('guru_id'));
            })
            ->when(request('hari'), function($query) {
                return $query->where('hari', request('hari'));
            })
            ->when(request('semester'), function($query) {
                return $query->where('semester', request('semester'));
            })
            ->when(request('tahun_ajaran'), function($query) {
                return $query->where('tahun_ajaran', request('tahun_ajaran'));
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(10);

        // Get ALL jadwal for weekly view (unfiltered by day)
        $jadwal_mingguan = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])
            ->whereHas('kelas') // Only get jadwal that have valid kelas
            ->scheduled() // Only show scheduled items using scope
            ->when($kelasId, function($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })
            ->when(request('guru_id'), function($query) {
                return $query->where('guru_id', request('guru_id'));
            })
            // Note: No day filter for weekly view
            ->when(request('semester'), function($query) {
                return $query->where('semester', request('semester'));
            })
            ->when(request('tahun_ajaran'), function($query) {
                return $query->where('tahun_ajaran', request('tahun_ajaran'));
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        // Statistics for dashboard
        $stats = [
            'total_jadwal' => JadwalPelajaran::where('is_active', true)
                ->scheduled() // Only count scheduled items
                ->whereHas('mapel') // Only count jadwal with valid mata pelajaran
                ->whereHas('guru') // Only count jadwal with valid guru
                ->whereHas('kelas') // Only count jadwal with valid kelas
                ->count(),
            'total_kelas' => Kelas::count(),
            'total_mapel' => MataPelajaran::count(),
            'total_guru' => Guru::count(),
            'conflicts' => JadwalPelajaran::where('is_active', true)
                ->scheduled() // Only check conflicts in scheduled items
                ->whereHas('mapel') // Only check jadwal with valid mata pelajaran
                ->whereHas('guru') // Only check jadwal with valid guru
                ->whereHas('kelas') // Only check jadwal with valid kelas
                ->whereExists(function($query) {
                    $query->select(DB::raw(1))
                        ->from('jadwal_pelajaran as j2')
                        ->whereColumn('jadwal_pelajaran.kelas_id', 'j2.kelas_id')
                        ->whereColumn('jadwal_pelajaran.hari', 'j2.hari')
                        ->whereColumn('jadwal_pelajaran.id', '!=', 'j2.id')
                        ->where('j2.is_active', true)
                        ->whereNotNull('j2.jam_mulai') // Only compare with scheduled items
                        ->whereNotNull('j2.jam_selesai')
                        ->where(function($q) {
                            $q->whereBetween('j2.jam_mulai', [DB::raw('jadwal_pelajaran.jam_mulai'), DB::raw('jadwal_pelajaran.jam_selesai')])
                            ->orWhereBetween('j2.jam_selesai', [DB::raw('jadwal_pelajaran.jam_mulai'), DB::raw('jadwal_pelajaran.jam_selesai')]);
                        });
                })->count()
        ];

        // Get list of active days from settings_jadwal
        $hari_list = [];
        if ($settingsJadwal->isNotEmpty()) {
            $activeDaysFromSettings = $settingsJadwal->pluck('hari')->toArray();
            // Ensure proper order of days
            $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            $hari_list = array_values(array_intersect($dayOrder, $activeDaysFromSettings));
        } else {
            // Fallback: use all days if no settings available
            $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        }

        // List of semesters and tahun ajaran for filter
        $semester_list = [1, 2];
        $tahun_ajaran_list = [];
        $currentYear = date('Y');
        for ($i = 0; $i < 5; $i++) {
            $year = $currentYear - 2 + $i;
            $tahun_ajaran_list[] = $year . '/' . ($year + 1);
        }

        // This will provide the complete list of jadwal
        $jadwalList = $jadwal_pelajaran;

        // Get currently selected filters
        $selectedHari = request('hari');
        $selectedGuruId = request('guru_id');
        $filter_tahun_ajaran = request('tahun_ajaran', $tahun_ajaran);
        $filter_semester = request('semester', $semester);

        return view('admin.jadwal.index', compact(
            'jadwal_pelajaran',
            'jadwal_mingguan',
            'jadwalList',
            'kelas_list',
            'guru_list',
            'mapel_list',
            'stats',
            'hari_list',
            'semester_list',
            'tahun_ajaran_list',
            'semester',
            'tahun_ajaran',
            'settingsJadwal',
            'kelasId',
            'selectedHari',
            'selectedGuruId',
            'filter_tahun_ajaran',
            'filter_semester'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru = Guru::orderBy('nama')->get();
        $mataPelajaran = MataPelajaran::orderBy('nama')->get();
        $settingsJadwal = SettingsJadwal::where('is_active', true)->get();
        $timeSlots = [];
        
        // Get active semester from settings
        $semester = Settings::getValue('semester_aktif', 1);
        $tahun_ajaran = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));

        return view('admin.jadwal.create', compact(
            'kelas',
            'guru', 
            'mataPelajaran',
            'settingsJadwal',
            'timeSlots',
            'semester',
            'tahun_ajaran'
        ));
    }

    /**
     * Get time schedule mapping based on jam_ke using actual settings jadwal
     */
    private function getTimeSchedule($jam_ke)
    {
        // Get settings jadwal
        $settingsJadwal = SettingsJadwal::where('is_active', true)->first();
        
        if (!$settingsJadwal) {
            // Fallback to hardcoded schedule if no settings
            $schedule = [
                1 => ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'],
                2 => ['jam_mulai' => '08:30', 'jam_selesai' => '09:05'],
                3 => ['jam_mulai' => '09:20', 'jam_selesai' => '09:55'],
                4 => ['jam_mulai' => '09:55', 'jam_selesai' => '10:30'],
                5 => ['jam_mulai' => '10:45', 'jam_selesai' => '11:20'],
                6 => ['jam_mulai' => '11:20', 'jam_selesai' => '11:55'],
                7 => ['jam_mulai' => '12:10', 'jam_selesai' => '12:45'],
                8 => ['jam_mulai' => '12:45', 'jam_selesai' => '13:20'],
                9 => ['jam_mulai' => '13:35', 'jam_selesai' => '14:10'],
                10 => ['jam_mulai' => '14:10', 'jam_selesai' => '14:45'],
            ];
            return $schedule[$jam_ke] ?? ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'];
        }
        
        // Generate dynamic schedule based on settings
        $numPeriods = (int)($settingsJadwal->jumlah_jam_pelajaran ?? 10);
        $periodDuration = (int)($settingsJadwal->durasi_per_jam ?? 35);
        $startTime = \Carbon\Carbon::parse($settingsJadwal->jam_mulai ?? '07:55');
        
        // Prepare break times
        $breaks = [];
        if ($settingsJadwal->jam_istirahat_mulai && $settingsJadwal->jam_istirahat_selesai) {
            $breaks[] = [
                'start' => \Carbon\Carbon::parse($settingsJadwal->jam_istirahat_mulai),
                'end' => \Carbon\Carbon::parse($settingsJadwal->jam_istirahat_selesai)
            ];
        }
        if ($settingsJadwal->jam_istirahat2_mulai && $settingsJadwal->jam_istirahat2_selesai) {
            $breaks[] = [
                'start' => \Carbon\Carbon::parse($settingsJadwal->jam_istirahat2_mulai),
                'end' => \Carbon\Carbon::parse($settingsJadwal->jam_istirahat2_selesai)
            ];
        }
        
        // Generate time slots
        $schedule = [];
        $currentTime = clone $startTime;
        
        for ($i = 1; $i <= $numPeriods; $i++) {
            $periodStart = clone $currentTime;
            $periodEnd = (clone $currentTime)->addMinutes($periodDuration);
            
            // Check for breaks and adjust timing
            foreach ($breaks as $break) {
                // If this period starts exactly when a break should start
                if ($periodStart->format('H:i') === $break['start']->format('H:i')) {
                    // Move period start to after the break
                    $periodStart = clone $break['end'];
                    $periodEnd = (clone $periodStart)->addMinutes($periodDuration);
                    break;
                }
                // If break starts during this period
                else if ($periodStart->format('H:i') < $break['start']->format('H:i') && 
                        $periodEnd->format('H:i') > $break['start']->format('H:i')) {
                    // End this period at break start
                    $periodEnd = clone $break['start'];
                    break;
                }
            }
            
            $schedule[$i] = [
                'jam_mulai' => $periodStart->format('H:i'),
                'jam_selesai' => $periodEnd->format('H:i')
            ];
            
            // Move to next period
            $currentTime = clone $periodEnd;
            
            // If we hit a break, skip over it
            foreach ($breaks as $break) {
                if ($currentTime->format('H:i') === $break['start']->format('H:i')) {
                    $currentTime = clone $break['end'];
                    break;
                }
            }
        }
        
        return $schedule[$jam_ke] ?? $schedule[1] ?? ['jam_mulai' => '07:55', 'jam_selesai' => '08:30'];
    }

    /**
     * Test endpoint to check if controller can return JSON
     */
    public function test(Request $request)
    {
        \Log::info('JadwalController@test called', [
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'data' => $request->all()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Test endpoint working',
            'data' => [
                'method' => $request->method(),
                'ajax' => $request->ajax(),
                'wants_json' => $request->wantsJson(),
                'accept_header' => $request->header('Accept'),
                'csrf_token_exists' => $request->hasHeader('X-CSRF-TOKEN')
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request
            \Log::info('JadwalController@store called', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            // Get allowed days from settings or use default
            $allowedDays = [];
            $settingsJadwal = SettingsJadwal::where('is_active', true)->get();
            $maxPeriods = 10; // Default
            
            if ($settingsJadwal->isNotEmpty()) {
                $daySettings = $settingsJadwal->first();
                $allowedDays = $settingsJadwal->pluck('hari')->toArray();
                $maxPeriods = $daySettings->jumlah_jam_pelajaran ?? 10;
            } else {
                $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            }
            
            \Log::info('Validation settings', [
                'allowed_days' => $allowedDays,
                'max_periods' => $maxPeriods
            ]);
            
            $validated = $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mapel_id' => 'required|exists:mata_pelajaran,id',
                'guru_id' => 'required|exists:guru,id',
                'hari' => 'required|in:' . implode(',', $allowedDays),
                'jam_ke' => 'required|integer|min:1|max:' . $maxPeriods,
                'jumlah_jam' => 'required|integer|min:1|max:5',
                'semester' => 'required|in:1,2',
                'tahun_ajaran' => 'required',
                'keterangan' => 'nullable|string',
                'is_active' => 'nullable|in:0,1'
            ]);
            
            \Log::info('Validation passed', ['validated_data' => $validated]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed in store method', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Unexpected error in store method validation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }

        // Get jumlah_jam parameter
        $jumlahJam = (int) $request->input('jumlah_jam', 1);
        $jamKe = (int) $request->input('jam_ke');
        
        // Validate that jam_ke + jumlah_jam doesn't exceed limit based on settings
        if ($jamKe + $jumlahJam - 1 > $maxPeriods) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat membuat {$jumlahJam} jam berturut-turut mulai dari jam ke-{$jamKe}. Jam maksimal adalah jam ke-{$maxPeriods}."
                ], 422);
            }
            return redirect()->back()->with('error', "Tidak dapat membuat {$jumlahJam} jam berturut-turut mulai dari jam ke-{$jamKe}. Jam maksimal adalah jam ke-{$maxPeriods}.");
        }

        // Check for time conflicts for all jam_ke that will be created
        $conflictJams = [];
        for ($i = 0; $i < $jumlahJam; $i++) {
            $currentJamKe = $jamKe + $i;
            $conflict = JadwalPelajaran::where([
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
                'jam_ke' => $currentJamKe,
                'semester' => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran
            ])->exists();

            if ($conflict) {
                $conflictJams[] = $currentJamKe;
            }
        }

        if (!empty($conflictJams)) {
            $conflictJamText = implode(', ', $conflictJams);
            \Log::warning('Schedule conflict detected', [
                'kelas_id' => $request->kelas_id,
                'hari' => $request->hari,
                'conflicting_jams' => $conflictJams
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Waktu jadwal bertabrakan dengan jadwal lain di jam ke-{$conflictJamText} pada kelas yang sama"
                ], 422);
            }
            return redirect()->back()->with('error', "Waktu jadwal bertabrakan dengan jadwal lain di jam ke-{$conflictJamText} pada kelas yang sama");
        }

        try {
            // Create multiple jadwal entries - these are independent of assignments
            for ($i = 0; $i < $jumlahJam; $i++) {
                $currentJamKe = $jamKe + $i;
                
                // Get time schedule based on current jam_ke
                $timeSchedule = $this->getTimeSchedule($currentJamKe);
                
                // First, check if there's an existing assignment for this combination
                $existingAssignment = JadwalPelajaran::where([
                    'guru_id' => $request->guru_id,
                    'mapel_id' => $request->mapel_id,
                    'kelas_id' => $request->kelas_id,
                    'semester' => $request->semester,
                    'tahun_ajaran' => $request->tahun_ajaran
                ])->assignments()->first();
                
                if ($existingAssignment) {
                    // Update the existing assignment to become a scheduled item
                    $existingAssignment->update([
                        'hari' => $request->hari,
                        'jam_ke' => $currentJamKe,
                        'jam_mulai' => $timeSchedule['jam_mulai'],
                        'jam_selesai' => $timeSchedule['jam_selesai'],
                        'keterangan' => $request->keterangan,
                        'is_active' => $request->is_active == '1' || $request->is_active === true
                    ]);
                    
                    $jadwal = $existingAssignment;
                    \Log::info('Updated existing assignment to scheduled', ['jadwal_id' => $jadwal->id, 'jam_ke' => $currentJamKe]);
                } else {
                    // Create new scheduled jadwal entry if no assignment exists
                    $jadwal = JadwalPelajaran::create([
                        'kelas_id' => $request->kelas_id,
                        'mapel_id' => $request->mapel_id,
                        'guru_id' => $request->guru_id,
                        'hari' => $request->hari,
                        'jam_ke' => $currentJamKe,
                        'jam_mulai' => $timeSchedule['jam_mulai'],
                        'jam_selesai' => $timeSchedule['jam_selesai'],
                        'semester' => $request->semester,
                        'tahun_ajaran' => $request->tahun_ajaran,
                        'keterangan' => $request->keterangan,
                        'is_active' => $request->is_active == '1' || $request->is_active === true
                    ]);
                    
                    \Log::info('Created new scheduled jadwal', ['jadwal_id' => $jadwal->id, 'jam_ke' => $currentJamKe]);
                }
                
                $createdJadwals[] = $jadwal;
                \Log::info('New jadwal created', ['jadwal_id' => $jadwal->id, 'jam_ke' => $currentJamKe]);
            }

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $jumlahJam > 1 
                        ? "Berhasil menambahkan {$jumlahJam} jam pelajaran berturut-turut" 
                        : 'Jadwal berhasil ditambahkan',
                    'jadwals' => $createdJadwals
                ]);
            }

            return redirect()->route('admin.jadwal.index')->with('success', $jumlahJam > 1 
                ? "Berhasil menambahkan {$jumlahJam} jam pelajaran berturut-turut" 
                : 'Jadwal berhasil ditambahkan');
            
        } catch (\Exception $e) {
            \Log::error('Error creating jadwal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan jadwal: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan jadwal')->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $jadwal = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'jadwal' => $jadwal
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $jadwal = JadwalPelajaran::with(['kelas', 'mapel', 'guru'])->findOrFail($id);
            
            \Log::info('Edit jadwal data:', [
                'id' => $id,
                'jam_ke' => $jadwal->jam_ke,
                'jam_mulai' => $jadwal->jam_mulai,
                'jam_selesai' => $jadwal->jam_selesai
            ]);
            
            return response()->json([
                'success' => true,
                'jadwal' => $jadwal
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in edit method:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Jadwal tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // Get allowed days from settings or use default
            $allowedDays = [];
            $settingsJadwal = SettingsJadwal::where('is_active', true)->get();
            if ($settingsJadwal->isNotEmpty()) {
                $allowedDays = $settingsJadwal->pluck('hari')->toArray();
            } else {
                $allowedDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            }
            
            // Log the incoming data for debugging
            \Log::info('Update validation data:', [
                'jam_mulai' => $request->jam_mulai,
                'jam_mulai_type' => gettype($request->jam_mulai),
                'jam_selesai' => $request->jam_selesai,
                'jam_selesai_type' => gettype($request->jam_selesai),
                'all_data' => $request->all()
            ]);
            
            $validated = $request->validate([
                'kelas_id' => 'required|exists:kelas,id',
                'mapel_id' => 'required|exists:mata_pelajaran,id',
                'guru_id' => 'required|exists:guru,id',
                'hari' => 'required|in:' . implode(',', $allowedDays),
                'jam_ke' => 'required|integer|min:1|max:10',
                'semester' => 'required|integer|in:1,2',
                'tahun_ajaran' => 'required|string',
                'keterangan' => 'nullable|string',
                'is_active' => 'nullable|in:0,1'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for jadwal update:', [
                'id' => $id,
                'request_data' => $request->all(),
                'errors' => $e->errors()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        }

        \Log::info('Updating jadwal with data:', [
            'id' => $id,
            'request_data' => $request->all(),
            'validated_data' => $validated
        ]);

        try {
            $jadwal = JadwalPelajaran::findOrFail($id);
            
            // Get time schedule based on jam_ke
            $timeSchedule = $this->getTimeSchedule($request->jam_ke);
            
            // Update all fields
            $jadwal->kelas_id = $request->kelas_id;
            $jadwal->mapel_id = $request->mapel_id;
            $jadwal->guru_id = $request->guru_id;
            $jadwal->hari = $request->hari;
            $jadwal->jam_ke = $request->jam_ke;
            $jadwal->jam_mulai = $timeSchedule['jam_mulai'];
            $jadwal->jam_selesai = $timeSchedule['jam_selesai'];
            $jadwal->semester = $request->semester;
            $jadwal->tahun_ajaran = $request->tahun_ajaran;
            $jadwal->keterangan = $request->keterangan;
            $jadwal->is_active = $request->is_active == '1' || $request->is_active === true;
            
            $jadwal->save();

            \Log::info('Jadwal updated successfully:', [
                'id' => $id,
                'updated_jadwal' => $jadwal->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui',
                'jadwal' => $jadwal->load(['kelas', 'mapel', 'guru'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error updating jadwal:', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * Convert scheduled item back to assignment instead of deleting completely
     */
    public function destroy($id)
    {
        $jadwal = JadwalPelajaran::findOrFail($id);
        
        // Instead of deleting, convert scheduled item back to assignment
        $jadwal->update([
            'hari' => null,
            'jam_ke' => null,
            'jam_mulai' => null,
            'jam_selesai' => null,
            'keterangan' => null
        ]);
        
        \Log::info('Converted scheduled jadwal back to assignment', [
            'jadwal_id' => $jadwal->id,
            'guru_id' => $jadwal->guru_id,
            'mapel_id' => $jadwal->mapel_id,
            'kelas_id' => $jadwal->kelas_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus dan dikembalikan ke assignment'
        ]);
    }



    public function checkConflict(Request $request)
    {
        $existingJadwal = JadwalPelajaran::where([
            'kelas_id' => $request->kelas_id,
            'hari' => $request->hari,
        ])
        ->where(function($query) use ($request) {
            $query->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
        })
        ->when($request->jadwal_id, function($query, $id) {
            return $query->where('id', '!=', $id);
        })
        ->exists();

        return response()->json([
            'success' => true,
            'conflict' => $existingJadwal
        ]);
    }

    public function byClass($kelas_id = null)
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $selectedKelas = $kelas_id ? Kelas::findOrFail($kelas_id) : null;
        $jadwal = $selectedKelas ? 
            JadwalPelajaran::with(['guru', 'mapel'])
                ->where('kelas_id', $kelas_id)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get() : 
            collect();

        return view('admin.jadwal.by-class', compact('kelas', 'selectedKelas', 'jadwal'));
    }

    public function byTeacher($guru_id = null)
    {
        $guru = Guru::all();
        $selectedGuru = $guru_id ? Guru::findOrFail($guru_id) : null;
        $jadwal = $selectedGuru ? 
            JadwalPelajaran::with(['kelas', 'mapel'])
                ->where('guru_id', $guru_id)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get() : 
            collect();

        return view('admin.jadwal.by-teacher', compact('guru', 'selectedGuru', 'jadwal'));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jadwal_pelajaran,id'
        ]);

        JadwalPelajaran::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }

    public function bulkActivate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jadwal_pelajaran,id'
        ]);

        JadwalPelajaran::whereIn('id', $request->ids)
            ->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diaktifkan'
        ]);
    }

    public function bulkDeactivate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:jadwal_pelajaran,id'
        ]);

        JadwalPelajaran::whereIn('id', $request->ids)
            ->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dinonaktifkan'
        ]);
    }

    /**
     * Get settings jadwal for table view
     * 
     * @return JsonResponse
     */
    public function settings(): JsonResponse
    {
        try {
            $settings = SettingsJadwal::where('is_active', true)
                ->orderBy('hari')
                ->get();
            
            foreach ($settings as $setting) {
                Log::info('Retrieved SettingsJadwal:', ['model' => $setting]);
            }
                
            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('Error in settings method:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data pengaturan jadwal.'
            ], 500);
        }
    }



    /**
     * Store multiple schedule entries at once
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'jadwal' => 'required|array',
            'jadwal.*.kelas_id' => 'required|exists:kelas,id',
            'jadwal.*.mapel_id' => 'required|exists:mata_pelajaran,id',
            'jadwal.*.guru_id' => 'required|exists:guru,id',
            'jadwal.*.hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jadwal.*.jam_ke' => 'required|integer',
            'jadwal.*.jam_mulai' => 'required',
            'jadwal.*.jam_selesai' => 'required',
        ]);

        foreach ($request->jadwal as $item) {
            JadwalPelajaran::updateOrCreate(
                [
                    'kelas_id' => $item['kelas_id'],
                    'hari' => $item['hari'],
                    'jam_ke' => $item['jam_ke'],
                ],
                [
                    'mapel_id' => $item['mapel_id'],
                    'guru_id' => $item['guru_id'],
                    'jam_mulai' => $item['jam_mulai'],
                    'jam_selesai' => $item['jam_selesai'],
                    'is_active' => isset($item['is_active']) ? $item['is_active'] : true,
                    'semester' => isset($item['semester']) ? $item['semester'] : 1,
                    'tahun_ajaran' => isset($item['tahun_ajaran']) ? $item['tahun_ajaran'] : Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1))
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil disimpan.'
        ]);
    }

    public function createTable()
    {
        // Get basic data
        $kelas_list = Kelas::orderBy('nama_kelas')->get();
        $mapel_list = MataPelajaran::orderBy('nama')->get();
        $guru_list = Guru::orderBy('nama')->get();
        
        // Get active semester and year from settings
        $tahun_ajaran_aktif = Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1));
        
        return view('admin.jadwal.create-table', compact(
            'kelas_list', 
            'tahun_ajaran_aktif',
            'mapel_list',
            'guru_list'
        ));
    }

    /**
     * Import jadwal from Excel/CSV file
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function import(Request $request)
    {
        try {
            // Validate file upload
            $request->validate([
                'file' => 'required|file|mimes:xlsx,csv|max:5120', // 5MB max
                'replace_existing' => 'nullable|boolean',
                'validate_only' => 'nullable|boolean'
            ]);

            $file = $request->file('file');
            $replaceExisting = $request->boolean('replace_existing');
            $validateOnly = $request->boolean('validate_only');

            // Load the Excel/CSV file
            $data = [];
            if ($file->getClientOriginalExtension() === 'xlsx') {
                $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                $spreadsheet = $reader->load($file->getRealPath());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();
                
                // Skip header row and convert to associative array
                $header = array_shift($rows);
                foreach ($rows as $row) {
                    if (!empty(array_filter($row))) { // Skip empty rows
                        $data[] = array_combine($header, $row);
                    }
                }
            } else {
                // Handle CSV
                $content = file_get_contents($file->getRealPath());
                $lines = explode("\n", $content);
                $header = str_getcsv(array_shift($lines));
                
                foreach ($lines as $line) {
                    $row = str_getcsv($line);
                    if (!empty(array_filter($row))) {
                        $data[] = array_combine($header, $row);
                    }
                }
            }

            if (empty($data)) {
                return redirect()->back()->with('error', 'File kosong atau tidak ada data yang valid');
            }

            // Validate required columns
            $requiredColumns = ['hari', 'jam_mulai', 'jam_selesai', 'kelas', 'mata_pelajaran', 'guru'];
            $firstRow = $data[0];
            $missingColumns = array_diff($requiredColumns, array_keys($firstRow));
            
            if (!empty($missingColumns)) {
                return redirect()->back()->with('error', 'Kolom yang diperlukan tidak ditemukan: ' . implode(', ', $missingColumns));
            }

            $validData = [];
            $errors = [];
            $warnings = [];

            foreach ($data as $index => $row) {
                $rowNumber = $index + 2; // +2 because of header and 0-based index
                
                try {
                    // Validate and process each row
                    $hari = strtolower(trim($row['hari']));
                    $hariMapping = [
                        'senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu',
                        'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu'
                    ];
                    
                    if (!isset($hariMapping[$hari])) {
                        $errors[] = "Baris $rowNumber: Hari '$hari' tidak valid";
                        continue;
                    }

                    // Find kelas
                    $kelasName = trim($row['kelas']);
                    $kelas = Kelas::where('nama', $kelasName)->first();
                    if (!$kelas) {
                        $errors[] = "Baris $rowNumber: Kelas '$kelasName' tidak ditemukan";
                        continue;
                    }

                    // Find mata pelajaran
                    $mapelName = trim($row['mata_pelajaran']);
                    $mapel = MataPelajaran::where('nama', $mapelName)->first();
                    if (!$mapel) {
                        $errors[] = "Baris $rowNumber: Mata pelajaran '$mapelName' tidak ditemukan";
                        continue;
                    }

                    // Find guru
                    $guruName = trim($row['guru']);
                    $guru = Guru::where('nama', $guruName)->first();
                    if (!$guru) {
                        $errors[] = "Baris $rowNumber: Guru '$guruName' tidak ditemukan";
                        continue;
                    }

                    // Validate time format
                    $jamMulai = trim($row['jam_mulai']);
                    $jamSelesai = trim($row['jam_selesai']);
                    
                    if (!preg_match('/^\d{2}:\d{2}$/', $jamMulai) || !preg_match('/^\d{2}:\d{2}$/', $jamSelesai)) {
                        $errors[] = "Baris $rowNumber: Format jam tidak valid (gunakan HH:mm)";
                        continue;
                    }

                    // Check for conflicts if not replacing
                    if (!$replaceExisting) {
                        $conflict = JadwalPelajaran::where([
                            'hari' => $hariMapping[$hari],
                            'jam_mulai' => $jamMulai,
                            'kelas_id' => $kelas->id
                        ])->first();
                        
                        if ($conflict) {
                            $warnings[] = "Baris $rowNumber: Jadwal sudah ada untuk $kelasName pada {$hariMapping[$hari]} $jamMulai-$jamSelesai";
                            continue;
                        }
                    }

                    $validData[] = [
                        'hari' => $hariMapping[$hari],
                        'jam_mulai' => $jamMulai,
                        'jam_selesai' => $jamSelesai,
                        'kelas_id' => $kelas->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'guru_id' => $guru->id,
                        'ruangan' => isset($row['ruangan']) ? trim($row['ruangan']) : null,
                        'tahun_ajaran' => Settings::getValue('tahun_ajaran', date('Y').'/'.(date('Y')+1)),
                        'semester' => Settings::getValue('semester_aktif', 1),
                        'status' => 'aktif',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                } catch (\Exception $e) {
                    $errors[] = "Baris $rowNumber: Error - " . $e->getMessage();
                }
            }

            // If validation only, return results
            if ($validateOnly) {
                $message = "Validasi selesai. Data valid: " . count($validData) . ", Error: " . count($errors) . ", Warning: " . count($warnings);
                return redirect()->back()->with('success', $message);
            }

            // If there are errors, don't proceed
            if (!empty($errors)) {
                $errorMessage = "Import dibatalkan karena ada error:\n" . implode("\n", array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMessage .= "\n... dan " . (count($errors) - 10) . " error lainnya";
                }
                return redirect()->back()->with('error', $errorMessage);
            }

            // Import valid data
            if (!empty($validData)) {
                DB::beginTransaction();
                
                try {
                    if ($replaceExisting) {
                        // Delete existing schedules for the classes and times being imported
                        foreach ($validData as $item) {
                            JadwalPelajaran::where([
                                'hari' => $item['hari'],
                                'jam_mulai' => $item['jam_mulai'],
                                'kelas_id' => $item['kelas_id']
                            ])->delete();
                        }
                    }
                    
                    // Insert new data
                    JadwalPelajaran::insert($validData);
                    
                    DB::commit();
                    
                    $message = "Import berhasil! " . count($validData) . " jadwal berhasil diimport";
                    if (!empty($warnings)) {
                        $message .= ". Peringatan: " . count($warnings) . " data dilewati";
                    }
                    
                    return redirect()->back()->with('success', $message);
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->with('warning', 'Tidak ada data valid untuk diimport');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saat import: ' . $e->getMessage());
        }
    }

    /**
     * Download template for jadwal import
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate(Request $request)
    {
        $format = $request->get('format', 'excel');
        
        // Get sample data for template
        $sampleData = [
            [
                'hari' => 'Senin',
                'jam_mulai' => '07:30',
                'jam_selesai' => '09:00',
                'kelas' => 'X RPL 1',
                'mata_pelajaran' => 'Matematika',
                'guru' => 'Budi Santoso, S.Pd',
                'ruangan' => 'Lab RPL 1'
            ],
            [
                'hari' => 'Senin',
                'jam_mulai' => '09:15',
                'jam_selesai' => '10:45',
                'kelas' => 'X RPL 1',
                'mata_pelajaran' => 'Bahasa Indonesia',
                'guru' => 'Siti Nurhaliza, S.Pd',
                'ruangan' => 'Kelas X-1'
            ],
            [
                'hari' => 'Selasa',
                'jam_mulai' => '07:30',
                'jam_selesai' => '09:00',
                'kelas' => 'XI TKJ 1',
                'mata_pelajaran' => 'Jaringan Komputer',
                'guru' => 'Ahmad Fauzi, S.Kom',
                'ruangan' => 'Lab TKJ'
            ]
        ];

        if ($format === 'excel') {
            // Create Excel template with instructions
            $data = [
                ['TEMPLATE IMPORT JADWAL PELAJARAN'],
                ['Instruksi:'],
                ['1. Isi data sesuai dengan format yang tersedia'],
                ['2. Hari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu'],
                ['3. Jam: format HH:mm (contoh: 07:30)'],
                ['4. Kelas, Mata Pelajaran, dan Guru harus sudah ada di sistem'],
                ['5. Ruangan boleh kosong'],
                ['6. Jangan mengubah header kolom'],
                [''],
                ['hari', 'jam_mulai', 'jam_selesai', 'kelas', 'mata_pelajaran', 'guru', 'ruangan']
            ];
            
            foreach ($sampleData as $row) {
                $data[] = array_values($row);
            }
            
            return \Maatwebsite\Excel\Facades\Excel::download(
                new JadwalTemplateExport($data), 
                'template_jadwal_pelajaran.xlsx'
            );
        } else {
            // Create CSV template
            $filename = 'template_jadwal_pelajaran.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($sampleData) {
                $file = fopen('php://output', 'w');
                
                // Write instructions as comments
                fwrite($file, "# TEMPLATE IMPORT JADWAL PELAJARAN\n");
                fwrite($file, "# Instruksi:\n");
                fwrite($file, "# 1. Isi data sesuai dengan format yang tersedia\n");
                fwrite($file, "# 2. Hari: Senin, Selasa, Rabu, Kamis, Jumat, Sabtu\n");
                fwrite($file, "# 3. Jam: format HH:mm (contoh: 07:30)\n");
                fwrite($file, "# 4. Kelas, Mata Pelajaran, dan Guru harus sudah ada di sistem\n");
                fwrite($file, "# 5. Ruangan boleh kosong\n");
                fwrite($file, "# 6. Jangan mengubah header kolom\n");
                fwrite($file, "#\n");
                
                // Write header
                fputcsv($file, ['hari', 'jam_mulai', 'jam_selesai', 'kelas', 'mata_pelajaran', 'guru', 'ruangan']);
                
                // Write sample data
                foreach ($sampleData as $row) {
                    fputcsv($file, array_values($row));
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Batch delete schedules by day
     */
    public function batchDeleteByDay(Request $request)
    {
        try {
            $request->validate([
                'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
                'kelas_id' => 'nullable|exists:kelas,id',
                'semester' => 'nullable|in:1,2',
                'tahun_ajaran' => 'nullable|string'
            ]);

            $query = JadwalPelajaran::where('hari', $request->hari);
            
            // Apply filters if provided
            if ($request->kelas_id) {
                $query->where('kelas_id', $request->kelas_id);
            }
            
            if ($request->semester) {
                $query->where('semester', $request->semester);
            }
            
            if ($request->tahun_ajaran) {
                $query->where('tahun_ajaran', $request->tahun_ajaran);
            }

            // Only delete scheduled items (have time assigned)
            $query->scheduled(); // Use scope for scheduled items

            // Get count before deletion for response
            $count = $query->count();
            
            // Get details for logging
            $jadwals = $query->with(['kelas', 'mapel', 'guru'])->get();
            
            if ($count === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal yang ditemukan untuk dihapus pada hari ' . $request->hari
                ], 404);
            }

            // Log before deletion
            \Log::info('Batch delete jadwal by day', [
                'hari' => $request->hari,
                'kelas_id' => $request->kelas_id,
                'semester' => $request->semester,
                'tahun_ajaran' => $request->tahun_ajaran,
                'count' => $count,
                'jadwals' => $jadwals->map(function($j) {
                    return [
                        'id' => $j->id,
                        'kelas' => $j->kelas->nama_kelas ?? 'Unknown',
                        'mapel' => $j->mapel->nama ?? 'Unknown',
                        'guru' => $j->guru->nama ?? 'Unknown',
                        'jam' => $j->jam_mulai . '-' . $j->jam_selesai
                    ];
                })
            ]);

            // Convert scheduled items back to assignments instead of deleting
            $updated = $query->update([
                'hari' => null,
                'jam_ke' => null, 
                'jam_mulai' => null,
                'jam_selesai' => null,
                'keterangan' => null
            ]);

            $message = "Berhasil menghapus {$updated} jadwal pada hari {$request->hari} dan dikembalikan ke assignment";
            
            if ($request->kelas_id) {
                $kelas = Kelas::find($request->kelas_id);
                $message .= " untuk kelas " . ($kelas->nama_kelas ?? 'Unknown');
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated_count' => $updated
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error in batch delete by day', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get guru berdasarkan mata pelajaran yang dipilih
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getGuruByMapel(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'mapel_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'nullable|exists:kelas,id'
            ]);

            $mapelId = $request->input('mapel_id');
            $kelasId = $request->input('kelas_id');

            // Query untuk mendapatkan guru berdasarkan assignment mata pelajaran
            $guruQuery = Guru::select('guru.id', 'guru.nama', 'guru.nip')
                ->join('jadwal_pelajaran', 'guru.id', '=', 'jadwal_pelajaran.guru_id')
                ->where('jadwal_pelajaran.mapel_id', $mapelId)
                ->where('guru.is_active', true)
                ->distinct();

            // Filter berdasarkan kelas jika dipilih
            if ($kelasId) {
                $guruQuery->where('jadwal_pelajaran.kelas_id', $kelasId);
            }

            $guruList = $guruQuery->orderBy('guru.nama')->get();

            return response()->json([
                'success' => true,
                'data' => $guruList
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting guru by mapel', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data guru'
            ], 500);
        }
    }

    /**
     * Get mata pelajaran berdasarkan guru yang dipilih
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getMapelByGuru(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'guru_id' => 'required|exists:guru,id',
                'kelas_id' => 'nullable|exists:kelas,id'
            ]);

            $guruId = $request->input('guru_id');
            $kelasId = $request->input('kelas_id');

            // Query untuk mendapatkan mata pelajaran berdasarkan assignment guru
            $mapelQuery = MataPelajaran::select('mata_pelajaran.id', 'mata_pelajaran.nama', 'mata_pelajaran.kode')
                ->join('jadwal_pelajaran', 'mata_pelajaran.id', '=', 'jadwal_pelajaran.mapel_id')
                ->where('jadwal_pelajaran.guru_id', $guruId)
                ->distinct();

            // Filter berdasarkan kelas jika dipilih
            if ($kelasId) {
                $mapelQuery->where('jadwal_pelajaran.kelas_id', $kelasId);
            }

            $mapelList = $mapelQuery->orderBy('mata_pelajaran.nama')->get();

            return response()->json([
                'success' => true,
                'data' => $mapelList
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting mapel by guru', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data mata pelajaran'
            ], 500);
        }
    }

    /**
     * Clean up orphaned schedules (schedules with deleted mata pelajaran, guru, or kelas)
     * This method can be called periodically or manually to maintain data integrity
     */
    public function cleanupOrphanedSchedules()
    {
        try {
            DB::beginTransaction();

            // Find jadwal with deleted mata pelajaran
            $jadwalWithDeletedMapel = JadwalPelajaran::whereDoesntHave('mapel')->get();
            
            // Find jadwal with deleted guru
            $jadwalWithDeletedGuru = JadwalPelajaran::whereDoesntHave('guru')->get();
            
            // Find jadwal with deleted kelas
            $jadwalWithDeletedKelas = JadwalPelajaran::whereDoesntHave('kelas')->get();

            $totalCleaned = 0;

            // Delete jadwal with orphaned relationships
            if ($jadwalWithDeletedMapel->isNotEmpty()) {
                $count = $jadwalWithDeletedMapel->count();
                JadwalPelajaran::whereDoesntHave('mapel')->delete();
                $totalCleaned += $count;
                \Log::info("Cleaned {$count} jadwal with deleted mata pelajaran");
            }

            if ($jadwalWithDeletedGuru->isNotEmpty()) {
                $count = $jadwalWithDeletedGuru->count();
                JadwalPelajaran::whereDoesntHave('guru')->delete();
                $totalCleaned += $count;
                \Log::info("Cleaned {$count} jadwal with deleted guru");
            }

            if ($jadwalWithDeletedKelas->isNotEmpty()) {
                $count = $jadwalWithDeletedKelas->count();
                JadwalPelajaran::whereDoesntHave('kelas')->delete();
                $totalCleaned += $count;
                \Log::info("Cleaned {$count} jadwal with deleted kelas");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil membersihkan {$totalCleaned} jadwal yang tidak valid",
                'cleaned_count' => $totalCleaned
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error cleaning orphaned schedules', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membersihkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get guru dan mata pelajaran berdasarkan kelas yang dipilih
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataByKelas(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'kelas_id' => 'required|exists:kelas,id'
            ]);

            $kelasId = $request->input('kelas_id');

            // Get guru yang assigned di kelas ini
            $guruList = Guru::select('guru.id', 'guru.nama', 'guru.nip')
                ->join('jadwal_pelajaran', 'guru.id', '=', 'jadwal_pelajaran.guru_id')
                ->where('jadwal_pelajaran.kelas_id', $kelasId)
                ->where('guru.is_active', true)
                ->distinct()
                ->orderBy('guru.nama')
                ->get();

            // Get mata pelajaran yang assigned di kelas ini
            $mapelList = MataPelajaran::select('mata_pelajaran.id', 'mata_pelajaran.nama', 'mata_pelajaran.kode')
                ->join('jadwal_pelajaran', 'mata_pelajaran.id', '=', 'jadwal_pelajaran.mapel_id')
                ->where('jadwal_pelajaran.kelas_id', $kelasId)
                ->distinct()
                ->orderBy('mata_pelajaran.nama')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'guru' => $guruList,
                    'mapel' => $mapelList
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error getting data by kelas', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data berdasarkan kelas'
            ], 500);
        }
    }
}
