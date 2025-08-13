<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankSoal;
use App\Models\JawabanUjianSiswa;
use App\Models\NilaiUjian;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JadwalUjian;
use App\Models\Guru;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    public function index()
    {
        // Get active exams count
        $activeExams = BankSoal::where('aktif', true)->count();

        // Get today's participants count
        $todayParticipants = JawabanUjianSiswa::whereDate('created_at', Carbon::today())
            ->distinct('siswa_id')
            ->count();

        // Get total questions in bank
        $totalQuestions = BankSoal::count();

        // Get average score
        $averageScore = NilaiUjian::avg('nilai') ?? 0;

        // Get today's exams
        $todayExams = BankSoal::whereDate('created_at', Carbon::today())
            ->with(['mataPelajaran', 'kelas'])
            ->get()
            ->map(function ($exam) {
                return (object) [
                    'id' => $exam->id,
                    'mata_pelajaran' => $exam->mataPelajaran->nama ?? '-',
                    'kelas' => $exam->kelas->nama ?? '-',
                    'waktu' => Carbon::parse($exam->created_at)->format('H:i'),
                    'durasi' => $exam->durasi ?? 60,
                    'status' => $exam->aktif ? 'Aktif' : 'Tidak Aktif',
                    'status_color' => $exam->aktif ? 'success' : 'danger'
                ];
            });

        // Get exam statistics
        $completedExams = BankSoal::where('aktif', false)->count();
        $ongoingExams = BankSoal::where('aktif', true)->count();
        $upcomingExams = 0; // Will be updated when scheduling functionality is added
        $canceledExams = 0; // Will be updated when status tracking is added

        // Prepare chart data
        $chartData = $this->prepareChartData();

        return view('admin.ujian.index', compact(
            'activeExams',
            'todayParticipants',
            'totalQuestions',
            'averageScore',
            'todayExams',
            'completedExams',
            'ongoingExams',
            'upcomingExams',
            'canceledExams',
            'chartData'
        ));
    }

    private function prepareChartData()
    {
        // Get average scores per subject
        $scores = NilaiUjian::join('bank_soals', 'nilai_ujian.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajaran', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajaran.id')
            ->select('mata_pelajaran.nama', DB::raw('AVG(nilai_ujian.nilai) as average_score'))
            ->groupBy('mata_pelajaran.id', 'mata_pelajaran.nama')
            ->get();

        return [
            'subjects' => $scores->pluck('nama')->toArray(),
            'averages' => $scores->pluck('average_score')->toArray()
        ];
    }

    public function bankSoal(Request $request)
    {
        $query = BankSoal::with(['mataPelajaran', 'kelas']);

        // Apply filters
        if ($request->has('mapel')) {
            $query->where('mata_pelajaran_id', $request->mapel);
        }
        if ($request->has('kelas')) {
            $query->where('kelas_id', $request->kelas);
        }
        if ($request->has('jenis')) {
            $query->where('jenis_soal', $request->jenis);
        }

        $soal = $query->paginate(10);
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();

        // Calculate statistics
        $totalAktif = BankSoal::where('aktif', true)->count();
        $totalPilihanGanda = BankSoal::where('jenis_soal', 'pilihan_ganda')->count();
        $totalEssay = BankSoal::where('jenis_soal', 'essay')->count();

        return view('admin.ujian.bank-soal.index', compact('soal', 'mataPelajaran', 'kelas', 'totalAktif', 'totalPilihanGanda', 'totalEssay'));
    }

    public function bankSoalIndex()
    {
        $soal = BankSoal::with(['mataPelajaran', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('admin.ujian.bank-soal.index', compact('soal', 'mataPelajaran', 'kelas'));
    }

    public function bankSoalCreate()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('admin.ujian.bank-soal.create', compact('mataPelajaran', 'kelas'));
    }

    public function bankSoalStore(Request $request)
    {
        try {
            $request->validate([
                'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
                'soal' => 'required|string',
                'jenis_soal' => 'required|in:pilihan_ganda,essay',
                'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            ]);
            
            BankSoal::create($request->all());
            
            return redirect()->route('admin.ujian.bank-soal.index')
                           ->with('success', 'Soal berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menambahkan soal: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function bankSoalDestroy($id)
    {
        try {
            $soal = BankSoal::findOrFail($id);
            $soal->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bankSoalDelete($id)
    {
        try {
            $soal = BankSoal::findOrFail($id);
            $soal->delete();
            
            return redirect()->route('admin.ujian.bank-soal.index')
                           ->with('success', 'Soal berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus soal: ' . $e->getMessage());
        }
    }

    public function bankSoalEdit($id)
    {
        $soal = BankSoal::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('admin.ujian.bank-soal.edit', compact('soal', 'mataPelajaran', 'kelas'));
    }

    public function bankSoalUpdate(Request $request, $id)
    {
        try {
            $soal = BankSoal::findOrFail($id);
            
            $request->validate([
                'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
                'soal' => 'required|string',
                'jenis_soal' => 'required|in:pilihan_ganda,essay',
                'tingkat_kesulitan' => 'required|in:mudah,sedang,sulit',
            ]);
            
            $soal->update($request->all());
            
            return redirect()->route('admin.ujian.bank-soal.index')
                           ->with('success', 'Soal berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui soal: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function bankSoalImport()
    {
        return view('admin.ujian.bank-soal.import');
    }

    public function bankSoalImportProcess(Request $request)
    {
        // Import logic here
        return redirect()->route('admin.ujian.bank-soal.index');
    }

    public function jadwalIndex(Request $request)
    {
        $query = JadwalUjian::with(['mataPelajaran', 'kelas', 'guru', 'ruangan', 'bankSoal']);
        
        // Apply filters
        if ($request->has('mata_pelajaran') && $request->mata_pelajaran != '') {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran);
        }
        
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas_id', $request->kelas);
        }
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('tanggal') && $request->tanggal != '') {
            $query->whereDate('tanggal', $request->tanggal);
        }
        
        // Apply search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_ujian', 'like', '%' . $search . '%')
                  ->orWhereHas('mataPelajaran', function($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('kelas', function($q) use ($search) {
                      $q->where('nama_kelas', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Sort
        $query->orderBy('tanggal', 'asc')->orderBy('waktu_mulai', 'asc');
        
        $jadwal = $query->paginate(10);
        
        // Statistics
        $stats = [
            'total_jadwal' => JadwalUjian::count(),
            'jadwal_hari_ini' => JadwalUjian::today()->count(),
            'jadwal_aktif' => JadwalUjian::byStatus('active')->count(),
            'jadwal_terjadwal' => JadwalUjian::byStatus('scheduled')->count()
        ];
        
        // Get filter data
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        
        return view('admin.ujian.jadwal.index', compact('jadwal', 'stats', 'mataPelajaran', 'kelas'));
    }

    public function jadwalCreate()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $guru = Guru::all();
        $ruangan = Ruangan::all();
        $bankSoal = BankSoal::with('mataPelajaran')->get();
        
        return view('admin.ujian.jadwal.create', compact('mataPelajaran', 'kelas', 'guru', 'ruangan', 'bankSoal'));
    }

    public function jadwalStore(Request $request)
    {
        try {
            // Handle batch mode (table mode)
            if ($request->has('bulk_mode') && $request->bulk_mode == '1') {
                return $this->handleBatchStore($request);
            }
            
            // Handle quick schedule template
            if ($request->has('template')) {
                return $this->handleQuickSchedule($request);
            }
            
            // Handle regular single schedule
            $validated = $request->validate([
                'nama_ujian' => 'required|string|max:255',
                'jenis_ujian' => 'required|string',
                'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
                'guru_id' => 'nullable|exists:guru,id',
                'tanggal' => 'required|date|after_or_equal:today',
                'waktu_mulai' => 'required',
                'durasi' => 'required|integer|min:1|max:480',
                'bank_soal_id' => 'nullable|exists:bank_soal,id',
                'ruangan_id' => 'nullable|exists:ruangan,id',
                'status' => 'required|in:draft,scheduled,active,completed,cancelled',
                'deskripsi' => 'nullable|string',
                'acak_soal' => 'nullable|boolean',
                'acak_jawaban' => 'nullable|boolean',
                'tampilkan_hasil' => 'nullable|boolean',
                'max_peserta' => 'nullable|integer|min:1',
            ]);
            
            // Calculate end time
            $waktu_mulai = Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
            $waktu_selesai = $waktu_mulai->copy()->addMinutes($request->durasi);
            
            // Check for scheduling conflicts
            $conflict = JadwalUjian::where('kelas_id', $request->kelas_id)
                ->where('tanggal', $request->tanggal)
                ->where(function($query) use ($waktu_mulai, $waktu_selesai) {
                    $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                          ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai])
                          ->orWhere(function($q) use ($waktu_mulai, $waktu_selesai) {
                              $q->where('waktu_mulai', '<=', $waktu_mulai)
                                ->where('waktu_selesai', '>=', $waktu_selesai);
                          });
                })
                ->exists();
                
            if ($conflict) {
                return redirect()->back()
                               ->with('error', 'Terdapat konflik jadwal pada waktu yang dipilih!')
                               ->withInput();
            }
            
            // Create the schedule
            $validated['waktu_mulai'] = $waktu_mulai;
            $validated['waktu_selesai'] = $waktu_selesai;
            $validated['is_active'] = $request->status === 'active';
            
            JadwalUjian::create($validated);
            
            return redirect()->route('admin.ujian.jadwal.index')
                           ->with('success', 'Jadwal ujian berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menambahkan jadwal ujian: ' . $e->getMessage())
                           ->withInput();
        }
    }
    
    private function handleBatchStore(Request $request)
    {
        $schedules = $request->input('schedules', []);
        $createdCount = 0;
        $errors = [];
        
        foreach ($schedules as $index => $schedule) {
            // Skip if not selected
            if (!isset($schedule['selected']) || $schedule['selected'] != '1') {
                continue;
            }
            
            try {
                // Validate individual schedule
                $validator = \Validator::make($schedule, [
                    'nama_ujian' => 'required|string|max:255',
                    'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
                    'kelas_id' => 'required|exists:kelas,id',
                    'tanggal' => 'required|date',
                    'waktu_mulai' => 'required',
                    'durasi' => 'required|integer|min:1',
                    'jenis_ujian' => 'required|string',
                    'status' => 'required|string',
                ]);
                
                if ($validator->fails()) {
                    $errors[] = "Baris " . ($index + 1) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }
                
                // Calculate end time
                $waktu_mulai = Carbon::parse($schedule['tanggal'] . ' ' . $schedule['waktu_mulai']);
                $waktu_selesai = $waktu_mulai->copy()->addMinutes($schedule['durasi']);
                
                // Check for conflicts
                $conflict = JadwalUjian::where('kelas_id', $schedule['kelas_id'])
                    ->where('tanggal', $schedule['tanggal'])
                    ->where(function($query) use ($waktu_mulai, $waktu_selesai) {
                        $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                              ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai]);
                    })
                    ->exists();
                    
                if ($conflict) {
                    $errors[] = "Baris " . ($index + 1) . ": Konflik jadwal pada waktu yang dipilih";
                    continue;
                }
                
                // Create jadwal
                $scheduleData = $schedule;
                $scheduleData['waktu_mulai'] = $waktu_mulai;
                $scheduleData['waktu_selesai'] = $waktu_selesai;
                $scheduleData['is_active'] = $schedule['status'] === 'active';
                
                JadwalUjian::create($scheduleData);
                $createdCount++;
                
            } catch (\Exception $e) {
                $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $createdCount > 0,
                'message' => $createdCount > 0 
                    ? "Berhasil menyimpan {$createdCount} jadwal ujian" 
                    : 'Tidak ada jadwal yang berhasil disimpan',
                'created_count' => $createdCount,
                'errors' => $errors
            ]);
        }
        
        $message = $createdCount > 0 
            ? "Berhasil menyimpan {$createdCount} jadwal ujian" 
            : 'Tidak ada jadwal yang berhasil disimpan';
            
        if (!empty($errors)) {
            $message .= '. Beberapa error: ' . implode(', ', array_slice($errors, 0, 3));
        }
        
        return redirect()->route('admin.ujian.jadwal.index')
                       ->with($createdCount > 0 ? 'success' : 'warning', $message);
    }
    
    private function handleQuickSchedule(Request $request)
    {
        $request->validate([
            'template' => 'required|string',
            'kelas_ids' => 'required|array',
            'kelas_ids.*' => 'exists:kelas,id',
            'start_date' => 'required|date',
            'interval' => 'required|integer|min:1|max:7',
            'time_slots' => 'required|array',
        ]);
        
        $templates = [
            'uts' => ['durasi' => 90, 'jenis' => 'uts', 'nama_prefix' => 'UTS'],
            'uas' => ['durasi' => 120, 'jenis' => 'uas', 'nama_prefix' => 'UAS'],
            'quiz_harian' => ['durasi' => 30, 'jenis' => 'quiz', 'nama_prefix' => 'Quiz Harian'],
            'praktek' => ['durasi' => 180, 'jenis' => 'praktek', 'nama_prefix' => 'Ujian Praktek'],
        ];
        
        $templateData = $templates[$request->template];
        $mataPelajaran = MataPelajaran::all();
        $createdCount = 0;
        
        foreach ($request->kelas_ids as $kelasId) {
            $currentDate = Carbon::parse($request->start_date);
            $timeSlotIndex = 0;
            
            foreach ($mataPelajaran as $mapel) {
                try {
                    // Skip weekends
                    while ($currentDate->dayOfWeek == 0 || $currentDate->dayOfWeek == 6) {
                        $currentDate->addDay();
                    }
                    
                    $waktu_mulai = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $request->time_slots[$timeSlotIndex % count($request->time_slots)]);
                    $waktu_selesai = $waktu_mulai->copy()->addMinutes($templateData['durasi']);
                    
                    // Check for conflicts
                    $conflict = JadwalUjian::where('kelas_id', $kelasId)
                        ->where('tanggal', $currentDate->format('Y-m-d'))
                        ->where(function($query) use ($waktu_mulai, $waktu_selesai) {
                            $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                                  ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai]);
                        })
                        ->exists();
                        
                    if (!$conflict) {
                        JadwalUjian::create([
                            'nama_ujian' => $templateData['nama_prefix'] . ' - ' . $mapel->nama,
                            'mata_pelajaran_id' => $mapel->id,
                            'kelas_id' => $kelasId,
                            'tanggal' => $currentDate->format('Y-m-d'),
                            'waktu_mulai' => $waktu_mulai,
                            'waktu_selesai' => $waktu_selesai,
                            'durasi' => $templateData['durasi'],
                            'jenis_ujian' => $templateData['jenis'],
                            'status' => 'scheduled',
                            'is_active' => false
                        ]);
                        $createdCount++;
                    }
                    
                    // Move to next date
                    $currentDate->addDays($request->interval);
                    $timeSlotIndex++;
                } catch (\Exception $e) {
                    // Log error but continue
                    \Log::error('Error creating quick schedule: ' . $e->getMessage());
                }
            }
        }
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $createdCount > 0,
                'message' => "Berhasil generate {$createdCount} jadwal ujian dari template",
                'created_count' => $createdCount
            ]);
        }
        
        return redirect()->route('admin.ujian.jadwal.index')
                       ->with('success', "Berhasil generate {$createdCount} jadwal ujian dari template");
    }
    
    public function jadwalCreateTable()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $guru = Guru::all();
        $ruangan = Ruangan::all();
        $bankSoal = BankSoal::with('mataPelajaran')->get();
        
        return view('admin.ujian.jadwal.create-table', compact('mataPelajaran', 'kelas', 'guru', 'ruangan', 'bankSoal'));
    }

    public function jadwalEdit($id)
    {
        $jadwal = JadwalUjian::with(['mataPelajaran', 'kelas', 'guru', 'ruangan', 'bankSoal'])->findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        $kelas = Kelas::all();
        $guru = Guru::all();
        $ruangan = Ruangan::all();
        $bankSoal = BankSoal::with('mataPelajaran')->get();
        
        return view('admin.ujian.jadwal.edit', compact('jadwal', 'mataPelajaran', 'kelas', 'guru', 'ruangan', 'bankSoal'));
    }

    public function jadwalUpdate(Request $request, $id)
    {
        try {
            $jadwal = JadwalUjian::findOrFail($id);
            
            $validated = $request->validate([
                'nama_ujian' => 'required|string|max:255',
                'jenis_ujian' => 'required|string',
                'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
                'kelas_id' => 'required|exists:kelas,id',
                'guru_id' => 'nullable|exists:guru,id',
                'tanggal' => 'required|date|after_or_equal:today',
                'waktu_mulai' => 'required',
                'durasi' => 'required|integer|min:1|max:480',
                'bank_soal_id' => 'nullable|exists:bank_soal,id',
                'ruangan_id' => 'nullable|exists:ruangan,id',
                'status' => 'required|in:draft,scheduled,active,completed,cancelled',
                'deskripsi' => 'nullable|string',
                'acak_soal' => 'nullable|boolean',
                'acak_jawaban' => 'nullable|boolean',
                'tampilkan_hasil' => 'nullable|boolean',
                'max_peserta' => 'nullable|integer|min:1',
            ]);
            
            // Calculate end time
            $waktu_mulai = Carbon::parse($request->tanggal . ' ' . $request->waktu_mulai);
            $waktu_selesai = $waktu_mulai->copy()->addMinutes($request->durasi);
            
            // Check for scheduling conflicts (excluding current schedule)
            $conflict = JadwalUjian::where('id', '!=', $id)
                ->where('kelas_id', $request->kelas_id)
                ->where('tanggal', $request->tanggal)
                ->where(function($query) use ($waktu_mulai, $waktu_selesai) {
                    $query->whereBetween('waktu_mulai', [$waktu_mulai, $waktu_selesai])
                          ->orWhereBetween('waktu_selesai', [$waktu_mulai, $waktu_selesai])
                          ->orWhere(function($q) use ($waktu_mulai, $waktu_selesai) {
                              $q->where('waktu_mulai', '<=', $waktu_mulai)
                                ->where('waktu_selesai', '>=', $waktu_selesai);
                          });
                })
                ->exists();
                
            if ($conflict) {
                return redirect()->back()
                               ->with('error', 'Terdapat konflik jadwal pada waktu yang dipilih!')
                               ->withInput();
            }
            
            // Update the schedule
            $validated['waktu_mulai'] = $waktu_mulai;
            $validated['waktu_selesai'] = $waktu_selesai;
            $validated['is_active'] = $request->status === 'active';
            
            $jadwal->update($validated);
            
            return redirect()->route('admin.ujian.jadwal.index')
                           ->with('success', 'Jadwal ujian berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal memperbarui jadwal ujian: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function jadwalDestroy($id)
    {
        try {
            $jadwal = JadwalUjian::findOrFail($id);
            
            // Check if schedule can be deleted
            if (!$jadwal->canBeDeleted()) {
                return redirect()->back()
                               ->with('error', 'Jadwal ujian tidak dapat dihapus karena statusnya bukan draft!');
            }
            
            $jadwal->delete();
            
            return redirect()->route('admin.ujian.jadwal.index')
                           ->with('success', 'Jadwal ujian berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus jadwal ujian: ' . $e->getMessage());
        }
    }

    public function hasilKelas()
    {
        return view('admin.ujian.hasil.kelas');
    }

    public function hasilSiswa()
    {
        return view('admin.ujian.hasil.siswa');
    }

    public function hasilMapel()
    {
        return view('admin.ujian.hasil.mapel');
    }

    public function analisisTingkatKesulitan()
    {
        return view('admin.ujian.analisis.tingkat-kesulitan');
    }

    public function analisisDayaBeda()
    {
        return view('admin.ujian.analisis.daya-beda');
    }

    public function analisisStatistik()
    {
        return view('admin.ujian.analisis.statistik');
    }

    public function monitoring()
    {
        return view('admin.ujian.monitoring.index');
    }

    public function monitor($id)
    {
        $exam = BankSoal::findOrFail($id);
        return view('admin.ujian.monitoring.monitor', compact('exam'));
    }

    public function getActiveExamCount()
    {
        $count = BankSoal::where('aktif', true)->count();
        return response()->json(['count' => $count]);
    }

    public function pengaturan()
    {
        return view('admin.ujian.pengaturan');
    }

    public function pengaturanUpdate(Request $request)
    {
        // Update settings logic
        return redirect()->route('admin.ujian.pengaturan');
    }
}
