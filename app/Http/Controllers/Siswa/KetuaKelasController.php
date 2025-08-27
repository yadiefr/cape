<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\AbsensiHarian;
use App\Models\Kelas;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class KetuaKelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
        $this->middleware(function ($request, $next) {
            $siswa = Auth::guard('siswa')->user();
            if (!$siswa->is_ketua_kelas) {
                return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki akses sebagai Ketua Kelas');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard Ketua Kelas
     */
    public function dashboard()
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Statistik kelas
        $totalSiswa = $kelas->siswa()->count();
        $siswaAktif = $kelas->siswa()->where('status', 'aktif')->count();
        $siswaLaki = $kelas->siswa()->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = $kelas->siswa()->where('jenis_kelamin', 'P')->count();

        // Absensi hari ini
        $today = Carbon::today();
        $absensiHariIni = AbsensiHarian::where('kelas_id', $kelas->id)
                                      ->whereDate('tanggal', $today)
                                      ->get();
        
        $hadirHariIni = $absensiHariIni->where('status', 'hadir')->count();
        $sakitHariIni = $absensiHariIni->where('status', 'sakit')->count();
        $izinHariIni = $absensiHariIni->where('status', 'izin')->count();
        $alphaHariIni = $absensiHariIni->where('status', 'alpha')->count();
        $belumAbsenHariIni = $totalSiswa - $absensiHariIni->count();
        
        // Format data untuk view
        $attendanceToday = [
            'hadir' => $hadirHariIni,
            'sakit' => $sakitHariIni,
            'izin' => $izinHariIni,
            'alpha' => $alphaHariIni,
            'persentase' => $totalSiswa > 0 ? ($hadirHariIni / $totalSiswa) * 100 : 0
        ];

        // Absensi minggu ini
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $absensiMingguIni = AbsensiHarian::where('kelas_id', $kelas->id)
                                        ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
                                        ->get();

        // Siswa dengan absensi bermasalah (alpha > 3 dalam sebulan)
        $startOfMonth = Carbon::now()->startOfMonth();
        $siswaAlphaData = DB::table('absensi_harian')
                       ->select('siswa_id', DB::raw('COUNT(*) as total_alpha'))
                       ->where('kelas_id', $kelas->id)
                       ->where('status', 'alpha')
                       ->whereBetween('tanggal', [$startOfMonth, $today])
                       ->groupBy('siswa_id')
                       ->having('total_alpha', '>', 2)
                       ->get();

        // Ambil data siswa bermasalah dengan detail
        $problematicStudents = [];
        foreach ($siswaAlphaData as $alphaData) {
            $siswaItem = Siswa::find($alphaData->siswa_id);
            if ($siswaItem) {
                // Hitung total absensi dalam bulan ini
                $totalAbsensi = AbsensiHarian::where('siswa_id', $siswaItem->id)
                                           ->whereBetween('tanggal', [$startOfMonth, $today])
                                           ->count();
                $totalHadir = AbsensiHarian::where('siswa_id', $siswaItem->id)
                                        ->where('status', 'hadir')
                                        ->whereBetween('tanggal', [$startOfMonth, $today])
                                        ->count();
                
                $attendancePercentage = $totalAbsensi > 0 ? ($totalHadir / $totalAbsensi) * 100 : 0;
                
                $problematicStudents[] = [
                    'nama_lengkap' => $siswaItem->nama,
                    'alpha_count' => $alphaData->total_alpha,
                    'attendance_percentage' => $attendancePercentage
                ];
            }
        }

        // Data untuk chart absensi minggu ini (7 hari terakhir)
        $weeklyAttendance = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            
            $hadirCount = AbsensiHarian::where('kelas_id', $kelas->id)
                                     ->whereDate('tanggal', $date)
                                     ->where('status', 'hadir')
                                     ->count();
            
            $weeklyAttendance[] = [
                'tanggal' => $date->format('d/m'),
                'hadir' => $hadirCount,
                'percentage' => $totalSiswa > 0 ? ($hadirCount / $totalSiswa) * 100 : 0
            ];
        }

        // Detail absensi hari ini untuk tabel
        $todayAttendanceDetail = AbsensiHarian::where('kelas_id', $kelas->id)
                                            ->whereDate('tanggal', $today)
                                            ->with('siswa')
                                            ->orderBy('created_at', 'asc')
                                            ->get();

        // Daftar siswa kelas
        $daftarSiswa = $kelas->siswa()
                            ->where('status', 'aktif')
                            ->orderBy('nama_lengkap')
                            ->get();

        return view('siswa.ketua-kelas.dashboard', compact(
            'siswa', 'kelas', 'totalSiswa', 'attendanceToday', 'weeklyAttendance',
            'problematicStudents', 'todayAttendanceDetail'
        ));
    }

    /**
     * Rekap Absensi Kelas
     */
    public function rekapAbsensi(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        $monthName = Carbon::create($year, $month, 1)->format('F');
        
        // Total hari kerja dalam bulan (kecuali Sabtu-Minggu)
        $workDays = 0;
        for ($date = $startDate->copy(); $date <= $endDate; $date->addDay()) {
            if (!$date->isWeekend()) {
                $workDays++;
            }
        }
        
        // Rekap absensi per siswa
        $siswaList = $kelas->siswa()->where('status', 'aktif')->get();
        $attendanceData = [];
        $summary = [
            'total_hadir' => 0,
            'total_izin' => 0,
            'total_sakit' => 0,
            'total_alpha' => 0,
        ];
        
        foreach ($siswaList as $siswaItem) {
            $absensiSiswa = AbsensiHarian::where('siswa_id', $siswaItem->id)
                                       ->whereBetween('tanggal', [$startDate, $endDate])
                                       ->get();
            
            $hadir = $absensiSiswa->where('status', 'hadir')->count();
            $izin = $absensiSiswa->where('status', 'izin')->count();
            $sakit = $absensiSiswa->where('status', 'sakit')->count();
            $alpha = $absensiSiswa->where('status', 'alpha')->count();
            
            // Total absensi yang tercatat untuk siswa ini
            $totalAbsensi = $hadir + $izin + $sakit + $alpha;
            
            // Persentase kehadiran berdasarkan total absensi yang tercatat
            // Jika tidak ada data absensi, persentase = 0
            if ($totalAbsensi > 0) {
                $persentase = ($hadir / $totalAbsensi) * 100;
            } else {
                $persentase = 0;
            }
            
            $attendanceData[] = [
                'id' => $siswaItem->id,
                'nama_lengkap' => $siswaItem->nama_lengkap,
                'nis' => $siswaItem->nis,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total_absensi' => $totalAbsensi,
                'persentase' => $persentase
            ];
            
            // Update summary
            $summary['total_hadir'] += $hadir;
            $summary['total_izin'] += $izin;
            $summary['total_sakit'] += $sakit;
            $summary['total_alpha'] += $alpha;
        }

        return view('siswa.ketua-kelas.rekap-absensi', compact(
            'attendanceData', 'kelas', 'month', 'year', 'monthName', 'summary'
        ));
    }

    /**
     * Daftar Siswa Kelas
     */
    public function daftarSiswa()
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        $daftarSiswa = $kelas->siswa()
                            ->where('status', 'aktif')
                            ->with(['pembayaran'])
                            ->orderBy('nama_lengkap')
                            ->get();

        return view('siswa.ketua-kelas.daftar-siswa', compact('daftarSiswa', 'kelas'));
    }

    /**
     * Halaman Input Absensi
     */
    public function absensi(Request $request)
    {
        try {
            \Log::info('=== ABSENSI REQUEST START ===');
            \Log::info('Request method: ' . $request->method());
            \Log::info('Request headers: ' . json_encode($request->headers->all()));
            \Log::info('Request all: ' . json_encode($request->all()));
            \Log::info('Is AJAX: ' . ($request->ajax() ? 'true' : 'false'));
            \Log::info('Wants JSON: ' . ($request->wantsJson() ? 'true' : 'false'));
            
            $siswa = Auth::guard('siswa')->user();
            if (!$siswa) {
                \Log::error('No authenticated siswa found');
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'User tidak terautentikasi'
                    ], 401);
                }
                return redirect()->route('siswa.login');
            }
            
            $kelas = $siswa->kelas;
            if (!$kelas) {
                \Log::error('No kelas found for siswa: ' . $siswa->id);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data kelas tidak ditemukan'
                    ], 404);
                }
                return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
            }
            
            $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
            $selectedDate = Carbon::parse($tanggal);
            
            \Log::info('Selected date: ' . $tanggal);
            \Log::info('Siswa: ' . $siswa->nama_lengkap);
            \Log::info('Kelas: ' . $kelas->nama_kelas);
            
            // Ambil daftar siswa kelas
            $siswaList = $kelas->siswa()
                              ->where('status', 'aktif')
                              ->orderBy('nama_lengkap')
                              ->get();
            
            // Ambil data absensi yang sudah ada untuk tanggal tersebut
            $existingAbsensi = AbsensiHarian::where('kelas_id', $kelas->id)
                                          ->whereDate('tanggal', $selectedDate)
                                          ->get()
                                          ->keyBy('siswa_id');
            
            \Log::info('Siswa count: ' . $siswaList->count());
            \Log::info('Existing absensi count: ' . $existingAbsensi->count());
            
            // Jika request AJAX, return JSON response
            if ($request->ajax() || $request->wantsJson()) {
                // Format data absensi untuk frontend
                $absensiData = $existingAbsensi->mapWithKeys(function($item) {
                    return [$item->siswa_id => [
                        'status' => $item->status,
                        'keterangan' => $item->keterangan
                    ]];
                })->toArray();
                
                $response = [
                    'success' => true,
                    'siswaList' => $siswaList,
                    'absensiData' => $absensiData,
                    'tanggal' => $tanggal,
                    'message' => 'Data berhasil dimuat'
                ];
                
                \Log::info('Returning JSON response: ' . json_encode($response));
                
                return response()->json($response);
            }
            
            \Log::info('Returning view response');
            
            return view('siswa.ketua-kelas.absensi', compact(
                'siswa', 'kelas', 'siswaList', 'existingAbsensi', 'tanggal', 'selectedDate'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Error in absensi method: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('siswa.ketua-kelas.dashboard')
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Simpan Absensi
     */
    public function simpanAbsensi(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        // Validasi kelas memiliki wali kelas
        if (!$kelas->wali_kelas) {
            return redirect()->back()
                           ->with('error', 'Kelas belum memiliki wali kelas yang ditentukan')
                           ->withInput();
        }
        
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array|min:1',
            'absensi.*' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|array',
            'keterangan.*' => 'nullable|string|max:255',
        ]);
        
        $tanggal = Carbon::parse($request->tanggal);
        
        try {
            DB::beginTransaction();
            
            // Log untuk debugging
            Log::info('Menyimpan absensi', [
                'kelas_id' => $kelas->id,
                'wali_kelas' => $kelas->wali_kelas,
                'tanggal' => $tanggal->format('Y-m-d'),
                'total_siswa' => count($request->absensi)
            ]);
            
            // Hapus absensi yang sudah ada untuk tanggal tersebut
            AbsensiHarian::where('kelas_id', $kelas->id)
                        ->whereDate('tanggal', $tanggal)
                        ->delete();
            
            // Simpan absensi baru
            foreach ($request->absensi as $siswaId => $status) {
                $keterangan = $request->keterangan[$siswaId] ?? null;
                
                AbsensiHarian::create([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $kelas->id,
                    'guru_id' => $kelas->wali_kelas, // Ambil ID wali kelas
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $keterangan,
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('siswa.ketua-kelas.absensi', ['tanggal' => $tanggal->format('Y-m-d')])
                           ->with('success', 'Absensi berhasil disimpan');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Gagal menyimpan absensi', [
                'error' => $e->getMessage(),
                'kelas_id' => $kelas->id,
                'wali_kelas' => $kelas->wali_kelas ?? 'null'
            ]);
            
            return redirect()->back()
                           ->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Detail Absensi Siswa
     */
    public function detailSiswa(Request $request, $siswaId)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        // Cari siswa yang diminta
        $targetSiswa = $kelas->siswa()->where('id', $siswaId)->first();
        
        if (!$targetSiswa) {
            abort(404, 'Siswa tidak ditemukan');
        }
        
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        $monthName = Carbon::create($year, $month, 1)->format('F');
        
        // Ambil detail absensi siswa
        $absensiDetail = AbsensiHarian::where('siswa_id', $targetSiswa->id)
                                    ->whereBetween('tanggal', [$startDate, $endDate])
                                    ->orderBy('tanggal', 'asc')
                                    ->get();
        
        // Hitung statistik
        $statistik = [
            'hadir' => $absensiDetail->where('status', 'hadir')->count(),
            'izin' => $absensiDetail->where('status', 'izin')->count(),
            'sakit' => $absensiDetail->where('status', 'sakit')->count(),
            'alpha' => $absensiDetail->where('status', 'alpha')->count(),
        ];
        
        $totalAbsensi = array_sum($statistik);
        $persentaseKehadiran = $totalAbsensi > 0 ? ($statistik['hadir'] / $totalAbsensi) * 100 : 0;
        
        return view('siswa.ketua-kelas.detail-siswa', compact(
            'targetSiswa', 'kelas', 'absensiDetail', 'statistik', 'persentaseKehadiran',
            'month', 'year', 'monthName'
        ));
    }
}
