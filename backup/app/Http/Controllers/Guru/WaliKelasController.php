<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Absensi;
use App\Models\AbsensiHarian;
use App\Models\Pembayaran;
use App\Models\Tagihan;
use App\Models\Guru;
use App\Models\KasKelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Response;

class WaliKelasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:guru');
        $this->middleware(function ($request, $next) {
            $guru = Auth::guard('guru')->user();
            if (!$guru->is_wali_kelas) {
                return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki akses sebagai Wali Kelas');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard Wali Kelas
     */
    public function dashboard()
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas');
        }

        // Statistik kelas
        $totalSiswa = $kelas->siswa()->count();
        $siswaAktif = $kelas->siswa()->where('status', 'aktif')->count();
        $siswaLaki = $kelas->siswa()->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = $kelas->siswa()->where('jenis_kelamin', 'P')->count();

        // Absensi harian hari ini (wali kelas)
        $today = Carbon::today();
        $absensiToday = AbsensiHarian::where('kelas_id', $kelas->id)
                                    ->whereDate('tanggal', $today)
                                    ->count();
        
        $hadir = AbsensiHarian::where('kelas_id', $kelas->id)
                              ->whereDate('tanggal', $today)
                              ->where('status', 'hadir')
                              ->count();
        
        $tidakHadir = $absensiToday - $hadir;

        // Persentase kehadiran kelas selama 1 bulan
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        // Total hari kerja dalam bulan ini (exclude weekend)
        $totalHariKerja = 0;
        $currentDate = $startOfMonth->copy();
        while ($currentDate <= $endOfMonth && $currentDate <= $today) {
            if ($currentDate->isWeekday()) { // Senin-Jumat
                $totalHariKerja++;
            }
            $currentDate->addDay();
        }
        
        // Total kehadiran yang seharusnya (siswa aktif × hari kerja)
        $totalKehadiranSeharusnya = $siswaAktif * $totalHariKerja;
        
        // Total kehadiran aktual dalam 1 bulan
        $totalKehadiranAktual = AbsensiHarian::where('kelas_id', $kelas->id)
                                            ->whereBetween('tanggal', [$startOfMonth, $today])
                                            ->where('status', 'hadir')
                                            ->count();
        
        // Hitung persentase kehadiran
        $persentaseKehadiran = $totalKehadiranSeharusnya > 0 
                              ? ($totalKehadiranAktual / $totalKehadiranSeharusnya) * 100 
                              : 0;

        // Keuangan - Siswa yang memiliki tunggakan
        $siswaList = $kelas->siswa()->with(['pembayaran'])->get();
        $belumLunas = 0;
        
        foreach ($siswaList as $siswa) {
            // Ambil tagihan yang berlaku untuk siswa ini
            $tagihanList = Tagihan::where(function($q) use ($siswa) {
                $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global
                  ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas
                  ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
            })->get();

            $totalTagihan = 0;
            $totalDibayar = 0;

            foreach ($tagihanList as $tagihan) {
                $jumlahDibayar = $siswa->pembayaran->where('tagihan_id', $tagihan->id)->sum('jumlah');
                $totalTagihan += $tagihan->nominal;
                $totalDibayar += $jumlahDibayar;
            }

            // Jika ada tunggakan, hitung sebagai siswa belum lunas
            if ($totalTagihan > $totalDibayar) {
                $belumLunas++;
            }
        }

        // Data Bendahara Kelas
        $bendahara = $kelas->siswa()->where('is_bendahara', true)->first();

        // Data Kas Kelas
        $totalKasMasuk = KasKelas::where('kelas_id', $kelas->id)
                                ->where('tipe', 'masuk')
                                ->sum('nominal');

        $totalKasKeluar = KasKelas::where('kelas_id', $kelas->id)
                                 ->where('tipe', 'keluar')
                                 ->sum('nominal');

        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Transaksi kas bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $transaksiKasBulanIni = KasKelas::where('kelas_id', $kelas->id)
                                       ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                                       ->count();

        // Transaksi kas terbaru (5 terakhir)
        $transaksiTerbaru = KasKelas::where('kelas_id', $kelas->id)
                                   ->with(['siswa', 'createdBy'])
                                   ->orderBy('tanggal', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        return view('guru.wali-kelas.dashboard', compact(
            'kelas', 'totalSiswa', 'siswaAktif', 'siswaLaki', 'siswaPerempuan',
            'absensiToday', 'hadir', 'tidakHadir', 'belumLunas',
            'persentaseKehadiran', 'totalKehadiranAktual', 'totalKehadiranSeharusnya', 'totalHariKerja'
        ));
    }

    public function bendahara()
    {
        $guru = Auth::user();
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki kelas yang diampu sebagai wali kelas.');
        }

        // Data Bendahara Kelas
        $bendahara = $kelas->siswa()->where('is_bendahara', true)->first();

        // Data Kas Kelas
        $totalKasMasuk = KasKelas::where('kelas_id', $kelas->id)
                                ->where('tipe', 'masuk')
                                ->sum('nominal');

        $totalKasKeluar = KasKelas::where('kelas_id', $kelas->id)
                                 ->where('tipe', 'keluar')
                                 ->sum('nominal');

        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Transaksi kas bulan ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $transaksiKasBulanIni = KasKelas::where('kelas_id', $kelas->id)
                                       ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
                                       ->count();

        // Transaksi kas terbaru (10 terakhir)
        $transaksiTerbaru = KasKelas::where('kelas_id', $kelas->id)
                                   ->with(['siswa', 'createdBy'])
                                   ->orderBy('tanggal', 'desc')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();

        return view('guru.wali-kelas.bendahara', compact(
            'kelas', 'bendahara', 'totalKasMasuk', 'totalKasKeluar', 'saldoKas',
            'transaksiKasBulanIni', 'transaksiTerbaru'
        ));
    }

    public function kasMasuk()
    {
        $guru = Auth::user();
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki kelas yang diampu sebagai wali kelas.');
        }

        // Ambil bulan dan tahun dari request, default bulan ini
        $bulan = (int) request('bulan', date('m'));
        $tahun = (int) request('tahun', date('Y'));

        // Buat tanggal awal dan akhir bulan
        $tanggalAwal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        // Generate semua hari dalam bulan
        $hariDalamBulan = [];
        $current = $tanggalAwal->copy();

        while ($current <= $tanggalAkhir) {
            $tanggal = $current->format('Y-m-d');

            // Ambil transaksi kas masuk untuk hari ini
            $transaksiHariIni = KasKelas::where('kelas_id', $kelas->id)
                                       ->where('tipe', 'masuk')
                                       ->whereDate('tanggal', $tanggal)
                                       ->get();

            $hariDalamBulan[] = [
                'tanggal' => $tanggal,
                'hari' => $current->locale('id')->dayName,
                'hari_nama' => $current->locale('id')->dayName,
                'tanggal_format' => $current->format('d'),
                'tanggal_formatted' => $current->format('d'),
                'transaksi' => $transaksiHariIni,
                'total' => $transaksiHariIni->sum('nominal'),
                'jumlah_transaksi' => $transaksiHariIni->count()
            ];

            $current->addDay();
        }

        // Total kas masuk bulan ini
        $totalKasMasukBulan = KasKelas::where('kelas_id', $kelas->id)
                                     ->where('tipe', 'masuk')
                                     ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                                     ->sum('nominal');

        // Total transaksi kas masuk bulan ini
        $totalTransaksiBulan = KasKelas::where('kelas_id', $kelas->id)
                                      ->where('tipe', 'masuk')
                                      ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                                      ->count();

        // Data untuk navigasi bulan
        $bulanSebelumnya = $tanggalAwal->copy()->subMonth();
        $bulanSelanjutnya = $tanggalAwal->copy()->addMonth();

        return view('guru.wali-kelas.kas-masuk', compact(
            'kelas', 'hariDalamBulan', 'bulan', 'tahun', 'totalKasMasukBulan', 'totalTransaksiBulan', 
            'tanggalAwal', 'tanggalAkhir', 'bulanSebelumnya', 'bulanSelanjutnya'
        ));
    }

    public function kasKeluar()
    {
        $guru = Auth::user();
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki kelas yang diampu sebagai wali kelas.');
        }

        // Ambil bulan dan tahun dari request, default bulan ini
        $bulan = (int) request('bulan', date('m'));
        $tahun = (int) request('tahun', date('Y'));

        // Buat tanggal awal dan akhir bulan
        $tanggalAwal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();

        // Ambil semua transaksi kas keluar dalam bulan ini
        $transaksiKasKeluar = KasKelas::where('kelas_id', $kelas->id)
                                     ->where('tipe', 'keluar')
                                     ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                                     ->with(['siswa', 'createdBy'])
                                     ->orderBy('tanggal', 'desc')
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        // Total kas keluar bulan ini
        $totalKasKeluarBulan = $transaksiKasKeluar->sum('nominal');

        return view('guru.wali-kelas.kas-keluar', compact(
            'kelas', 'transaksiKasKeluar', 'bulan', 'tahun', 'totalKasKeluarBulan', 'tanggalAwal', 'tanggalAkhir'
        ));
    }

    public function laporanKas()
    {
        $guru = Auth::user();
        $kelas = $guru->kelasWali;

        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki kelas yang diampu sebagai wali kelas.');
        }

        // Ambil bulan dan tahun dari request, default bulan ini
        $bulan = (int) request('bulan', date('m'));
        $tahun = (int) request('tahun', date('Y'));
        $periode = request('periode', 'bulan'); // Default periode bulanan

        // Buat tanggal awal dan akhir berdasarkan periode
        if ($periode == 'bulan') {
            $tanggalAwal = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        } elseif ($periode == 'semester') {
            $semester = (int) request('semester', 1);
            if ($semester == 1) {
                // Semester 1: Juli - Desember
                $tanggalAwal = Carbon::create($tahun, 7, 1)->startOfMonth();
                $tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfMonth();
            } else {
                // Semester 2: Januari - Juni
                $tanggalAwal = Carbon::create($tahun, 1, 1)->startOfMonth();
                $tanggalAkhir = Carbon::create($tahun, 6, 30)->endOfMonth();
            }
        } else { // periode == 'tahun'
            $tanggalAwal = Carbon::create($tahun, 1, 1)->startOfYear();
            $tanggalAkhir = Carbon::create($tahun, 12, 31)->endOfYear();
        }
        
        $semester = (int) request('semester', 1); // Default semester 1

        // Hitung saldo awal (sebelum periode ini)
        $saldoAwal = KasKelas::where('kelas_id', $kelas->id)
                            ->where('tanggal', '<', $tanggalAwal)
                            ->selectRaw('SUM(CASE WHEN tipe = "masuk" THEN nominal ELSE -nominal END) as saldo')
                            ->value('saldo') ?? 0;

        // Total kas masuk periode ini
        $totalKasMasuk = KasKelas::where('kelas_id', $kelas->id)
                                ->where('tipe', 'masuk')
                                ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                                ->sum('nominal');

        // Total kas keluar periode ini
        $totalKasKeluar = KasKelas::where('kelas_id', $kelas->id)
                                 ->where('tipe', 'keluar')
                                 ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                                 ->sum('nominal');

        // Saldo akhir
        $saldoAkhir = $saldoAwal + $totalKasMasuk - $totalKasKeluar;

        // Semua transaksi dalam periode
        $transaksi = KasKelas::where('kelas_id', $kelas->id)
                            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
                            ->with(['siswa', 'createdBy'])
                            ->orderBy('tanggal', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        // Data kas masuk saja untuk view
        $kasMasuk = $transaksi->where('tipe', 'masuk');
        
        // Data kas keluar saja untuk view  
        $kasKeluar = $transaksi->where('tipe', 'keluar');

        // Alias untuk konsistensi dengan view
        $totalKasMasukPeriode = $totalKasMasuk;
        $totalKasKeluarPeriode = $totalKasKeluar;
        
        // Perubahan saldo dalam periode ini (kas masuk - kas keluar)
        $saldoPeriode = $totalKasMasuk - $totalKasKeluar;

        // Data kas masuk per kategori (berdasarkan keterangan)
        $kasMasukPerKategori = $kasMasuk->groupBy('keterangan')->map(function ($items, $kategori) {
            return [
                'kategori' => $kategori,
                'total' => $items->sum('nominal'),
                'jumlah' => $items->count()
            ];
        });

        // Data kas masuk per siswa
        $kasMasukPerSiswa = $kasMasuk->groupBy('siswa_id')->map(function ($items, $siswaId) {
            return [
                'siswa' => $items->first()->siswa,
                'total' => $items->sum('nominal'),
                'jumlah' => $items->count()
            ];
        });

        // Chart data untuk grafik (per hari/minggu/bulan tergantung periode)
        $chartData = $this->generateChartData($kelas->id, $tanggalAwal, $tanggalAkhir, $periode);
        
        // Ensure chart data has minimum structure even if no data
        if (empty($chartData['labels'])) {
            $chartData = [
                'labels' => ['Data'],
                'dataMasuk' => [0],
                'dataKeluar' => [0]
            ];
        }

        return view('guru.wali-kelas.laporan-kas', compact(
            'kelas', 'bulan', 'tahun', 'semester', 'periode', 'tanggalAwal', 'tanggalAkhir',
            'saldoAwal', 'totalKasMasuk', 'totalKasKeluar', 'saldoAkhir', 'saldoPeriode', 'transaksi',
            'kasMasuk', 'kasKeluar', 'totalKasMasukPeriode', 'totalKasKeluarPeriode',
            'kasMasukPerKategori', 'kasMasukPerSiswa', 'chartData'
        ));
    }

    /**
     * Generate data untuk chart laporan
     */
    private function generateChartData($kelasId, $tanggalMulai, $tanggalSelesai, $periode)
    {
        $labels = [];
        $dataMasuk = [];
        $dataKeluar = [];
        
        if ($periode === 'tahun') {
            // Data per bulan dalam setahun
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create()->month($i)->locale('id')->monthName;
                
                $masuk = KasKelas::where('kelas_id', $kelasId)
                               ->where('tipe', 'masuk')
                               ->whereMonth('tanggal', $i)
                               ->whereYear('tanggal', $tanggalMulai->year)
                               ->sum('nominal');
                               
                $keluar = KasKelas::where('kelas_id', $kelasId)
                                ->where('tipe', 'keluar')
                                ->whereMonth('tanggal', $i)
                                ->whereYear('tanggal', $tanggalMulai->year)
                                ->sum('nominal');
                                
                $dataMasuk[] = (float) $masuk;
                $dataKeluar[] = (float) $keluar;
            }
        } elseif ($periode === 'semester') {
            // Data per bulan dalam semester
            $startMonth = $tanggalMulai->month;
            $endMonth = $tanggalSelesai->month;
            
            for ($i = $startMonth; $i <= $endMonth; $i++) {
                $labels[] = Carbon::create()->month($i)->locale('id')->monthName;
                
                $masuk = KasKelas::where('kelas_id', $kelasId)
                               ->where('tipe', 'masuk')
                               ->whereMonth('tanggal', $i)
                               ->whereYear('tanggal', $tanggalMulai->year)
                               ->sum('nominal');
                               
                $keluar = KasKelas::where('kelas_id', $kelasId)
                                ->where('tipe', 'keluar')
                                ->whereMonth('tanggal', $i)
                                ->whereYear('tanggal', $tanggalMulai->year)
                                ->sum('nominal');
                                
                $dataMasuk[] = (float) $masuk;
                $dataKeluar[] = (float) $keluar;
            }
        } else {
            // Data per minggu dalam bulan (sama seperti siswa)
            $currentDate = $tanggalMulai->copy();
            $weekNumber = 1;
            
            while ($currentDate <= $tanggalSelesai) {
                $weekStart = $currentDate->copy();
                $weekEnd = $currentDate->copy()->addDays(6);
                
                if ($weekEnd > $tanggalSelesai) {
                    $weekEnd = $tanggalSelesai->copy();
                }
                
                $labels[] = "Minggu {$weekNumber}";
                
                $masuk = KasKelas::where('kelas_id', $kelasId)
                               ->where('tipe', 'masuk')
                               ->whereBetween('tanggal', [$weekStart, $weekEnd])
                               ->sum('nominal');
                               
                $keluar = KasKelas::where('kelas_id', $kelasId)
                                ->where('tipe', 'keluar')
                                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                                ->sum('nominal');
                                
                $dataMasuk[] = (float) $masuk;
                $dataKeluar[] = (float) $keluar;
                
                $currentDate->addWeek();
                $weekNumber++;
            }
        }
        
        return [
            'labels' => $labels,
            'dataMasuk' => $dataMasuk,
            'dataKeluar' => $dataKeluar
        ];
    }

    /**
     * Absen Harian
     */
    public function absensi(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas');
        }

        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $siswaList = $kelas->siswa()->where('status', 'aktif')->orderBy('nama_lengkap')->get();

        // Get existing absensi harian for the date
        $absensiData = AbsensiHarian::where('kelas_id', $kelas->id)
                                   ->whereDate('tanggal', $tanggal)
                                   ->get()
                                   ->keyBy('siswa_id');

        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'tanggal' => $tanggal,
                'siswaList' => $siswaList->map(function($siswa) {
                    return [
                        'id' => $siswa->id,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nisn' => $siswa->nisn ?? $siswa->nis,
                        'jenis_kelamin' => $siswa->jenis_kelamin
                    ];
                }),
                'absensiData' => $absensiData->mapWithKeys(function($item) {
                    return [$item->siswa_id => [
                        'status' => $item->status,
                        'keterangan' => $item->keterangan
                    ]];
                })->toArray()
            ]);
        }

        return view('guru.wali-kelas.absensi', compact('kelas', 'siswaList', 'tanggal', 'absensiData'));
    }

    /**
     * Simpan Absen Harian
     */
    public function simpanAbsensi(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        // Debug: Log input yang diterima
        \Log::info('Absensi Input Data:', [
            'tanggal' => $request->tanggal,
            'absensi_count' => count($request->absensi ?? []),
            'raw_request' => $request->all()
        ]);
        
        $request->validate([
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
            'absensi.*' => 'required|in:hadir,sakit,izin,alpha'
        ]);

        $tanggal = $request->tanggal;
        
        DB::beginTransaction();
        try {
            // Delete existing absensi harian for this date and class
            AbsensiHarian::where('kelas_id', $kelas->id)
                         ->whereDate('tanggal', $tanggal)
                         ->delete();

            // Insert new absensi harian data
            foreach ($request->absensi as $siswaId => $status) {
                AbsensiHarian::create([
                    'siswa_id' => $siswaId,
                    'kelas_id' => $kelas->id,
                    'guru_id' => $guru->id,
                    'tanggal' => $tanggal,
                    'status' => $status,
                    'keterangan' => $request->get('keterangan.' . $siswaId)
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Absensi harian berhasil disimpan untuk tanggal ' . Carbon::parse($tanggal)->format('d/m/Y'));
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error saving absensi:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Gagal menyimpan absensi harian: ' . $e->getMessage());
        }
    }

    /**
     * Detail Siswa dengan Keuangan
     */
    public function detailSiswa($id)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        $siswa = Siswa::where('kelas_id', $kelas->id)
                     ->with(['kelas', 'kelas.jurusan', 'pembayaran'])
                     ->findOrFail($id);

        // Riwayat absensi harian 30 hari terakhir (wali kelas)
        $riwayatAbsensiHarian = AbsensiHarian::where('siswa_id', $siswa->id)
                                            ->where('tanggal', '>=', Carbon::now()->subDays(30))
                                            ->orderBy('tanggal', 'desc')
                                            ->get();

        // Riwayat absensi per mapel 30 hari terakhir
        $riwayatAbsensiMapel = Absensi::where('siswa_id', $siswa->id)
                                     ->where('tanggal', '>=', Carbon::now()->subDays(30))
                                     ->orderBy('tanggal', 'desc')
                                     ->with(['mapel'])
                                     ->get();

        // Statistik absensi harian bulan ini (wali kelas)
        $absensiStats = [
            'hadir' => AbsensiHarian::where('siswa_id', $siswa->id)
                                   ->whereMonth('tanggal', Carbon::now()->month)
                                   ->where('status', 'hadir')
                                   ->count(),
            'sakit' => AbsensiHarian::where('siswa_id', $siswa->id)
                                   ->whereMonth('tanggal', Carbon::now()->month)
                                   ->where('status', 'sakit')
                                   ->count(),
            'izin' => AbsensiHarian::where('siswa_id', $siswa->id)
                                  ->whereMonth('tanggal', Carbon::now()->month)
                                  ->where('status', 'izin')
                                  ->count(),
            'alpha' => AbsensiHarian::where('siswa_id', $siswa->id)
                                   ->whereMonth('tanggal', Carbon::now()->month)
                                   ->where('status', 'alpha')
                                   ->count(),
        ];

        // Data keuangan
        $pembayaranList = Pembayaran::where('siswa_id', $siswa->id)
                                  ->orderBy('tanggal', 'desc')
                                  ->get();

        // Ambil tagihan yang berlaku untuk siswa ini
        $tagihanList = Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global
              ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas
              ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
        })->get();

        $pembayaranSiswa = $siswa->pembayaran;
        $detailTagihan = [];
        $totalTagihan = 0;
        $totalDibayar = 0;
        $totalTunggakan = 0;

        foreach ($tagihanList as $tagihan) {
            // Ambil semua pembayaran untuk tagihan ini
            $pembayaranTagihan = $pembayaranSiswa->where('tagihan_id', $tagihan->id);
            $jumlahDibayar = $pembayaranTagihan->sum('jumlah');
            $sisa = max(0, $tagihan->nominal - $jumlahDibayar);
            
            if ($jumlahDibayar >= $tagihan->nominal && $tagihan->nominal > 0) {
                $status = 'Lunas';
            } elseif ($jumlahDibayar > 0 && $jumlahDibayar < $tagihan->nominal) {
                $status = 'Sebagian';
            } else {
                $status = 'Belum Lunas';
            }

            $detailTagihan[] = [
                'id' => $tagihan->id,
                'nama_tagihan' => $tagihan->nama_tagihan,
                'nominal' => $tagihan->nominal,
                'total_dibayar' => $jumlahDibayar,
                'sisa' => $sisa,
                'status' => $status,
                'tanggal_jatuh_tempo' => $tagihan->tanggal_jatuh_tempo,
                'keterangan' => $tagihan->keterangan,
            ];

            $totalTagihan += $tagihan->nominal;
            $totalDibayar += $jumlahDibayar;
            $totalTunggakan += $sisa;
        }

        // Update data siswa dengan perhitungan yang benar
        $siswa->total_tagihan = $totalTagihan;
        $siswa->total_telah_dibayar = $totalDibayar;
        $siswa->tunggakan = $totalTunggakan;
        $siswa->status_keuangan = $totalTunggakan > 0 ? 'Belum Lunas' : 'Lunas';

        // Pembayaran bulan ini
        $pembayaranBulanIni = Pembayaran::where('siswa_id', $siswa->id)
                                       ->whereMonth('tanggal', Carbon::now()->month)
                                       ->whereYear('tanggal', Carbon::now()->year)
                                       ->get();

        return view('guru.wali-kelas.detail-siswa', compact(
            'siswa', 'kelas', 'riwayatAbsensiHarian', 'riwayatAbsensiMapel', 'absensiStats', 
            'pembayaranList', 'totalTagihan', 'totalDibayar', 'totalTunggakan',
            'pembayaranBulanIni', 'detailTagihan'
        ));
    }

    /**
     * Rekap Absensi Kelas
     */
    public function rekapAbsensi(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $siswaList = $kelas->siswa()->where('status', 'aktif')
                          ->orderBy('nama_lengkap')
                          ->get();

        $rekapData = [];
        foreach ($siswaList as $siswa) {
            // Gunakan AbsensiHarian untuk rekap absensi harian wali kelas
            $absensiHarian = AbsensiHarian::where('siswa_id', $siswa->id)
                                         ->whereMonth('tanggal', $bulan)
                                         ->whereYear('tanggal', $tahun)
                                         ->get();

            $rekapData[$siswa->id] = [
                'siswa' => $siswa,
                'hadir' => $absensiHarian->where('status', 'hadir')->count(),
                'sakit' => $absensiHarian->where('status', 'sakit')->count(),
                'izin' => $absensiHarian->where('status', 'izin')->count(),
                'alpha' => $absensiHarian->where('status', 'alpha')->count(),
                'total' => $absensiHarian->count()
            ];
        }

        return view('guru.wali-kelas.rekap-absensi', compact(
            'kelas', 'rekapData', 'bulan', 'tahun'
        ));
    }

    /**
     * Rekap Keuangan Kelas
     */
    public function rekapKeuangan(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);

        $siswaList = $kelas->siswa()->where('status', 'aktif')
                          ->orderBy('nama_lengkap')
                          ->get();

        $rekapKeuangan = [];
        foreach ($siswaList as $siswa) {
            // 1. Ambil semua tagihan yang berlaku untuk siswa ini
            $tagihanList = Tagihan::where(function($q) use ($siswa) {
                $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global (semua siswa)
                  ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas ini
                  ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa ini
            })
            ->whereMonth('created_at', $bulan)  // Filter berdasarkan bulan pembuatan tagihan
            ->whereYear('created_at', $tahun)   // Filter berdasarkan tahun pembuatan tagihan
            ->get();

            // 2. Hitung total tagihan
            $totalTagihan = $tagihanList->sum('nominal');

            // 3. Ambil pembayaran yang sudah dilakukan siswa untuk periode ini
            $pembayaranList = Pembayaran::where('siswa_id', $siswa->id)
                                       ->whereMonth('tanggal', $bulan)
                                       ->whereYear('tanggal', $tahun)
                                       ->get();

            // 4. Hitung total yang sudah dibayar
            $sudahBayar = $pembayaranList->sum('jumlah');

            // 5. Hitung sisa yang belum dibayar
            $belumBayar = max(0, $totalTagihan - $sudahBayar);

            $rekapKeuangan[$siswa->id] = [
                'siswa' => $siswa,
                'total_tagihan' => $totalTagihan,
                'sudah_bayar' => $sudahBayar,
                'belum_bayar' => $belumBayar,
                'persentase' => $totalTagihan > 0 ? ($sudahBayar / $totalTagihan) * 100 : 100,
                'status' => $belumBayar <= 0 ? 'Lunas' : 'Belum Lunas'
            ];
        }

        return view('guru.wali-kelas.rekap-keuangan', compact(
            'kelas', 'rekapKeuangan', 'bulan', 'tahun'
        ));
    }

    /**
     * Detail Keuangan Siswa
     */
    public function detailKeuangan($siswaId)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        // Pastikan siswa ada di kelas yang diampu wali kelas
        $siswa = Siswa::where('kelas_id', $kelas->id)
                     ->with(['kelas', 'pembayaran'])
                     ->findOrFail($siswaId);

        // Ambil tagihan yang berlaku untuk siswa ini
        $tagihanList = Tagihan::where(function($q) use ($siswa) {
            $q->whereNull('kelas_id')->whereNull('siswa_id')  // Tagihan global
              ->orWhere('kelas_id', $siswa->kelas_id)         // Tagihan untuk kelas
              ->orWhere('siswa_id', $siswa->id);              // Tagihan spesifik siswa
        })->get();

        $pembayaranSiswa = $siswa->pembayaran;
        $detailTagihan = [];

        foreach ($tagihanList as $tagihan) {
            // Ambil semua pembayaran untuk tagihan ini
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
                'tanggal_jatuh_tempo' => $tagihan->tanggal_jatuh_tempo,
                'keterangan' => $tagihan->keterangan,
                'pembayaran' => $pembayaranTagihan->sortByDesc('tanggal')
            ];
        }

        return view('guru.wali-kelas.detail-keuangan', compact('siswa', 'kelas', 'detailTagihan'));
    }

    /**
     * Data Siswa Kelas (Wali Kelas)
     */
    public function dataSiswa(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas');
        }

        // Filter dan search
        $search = $request->get('search');
        $status = $request->get('status', 'aktif');
        $jenis_kelamin = $request->get('jenis_kelamin');

        $query = $kelas->siswa()->with(['kelas', 'kelas.jurusan']);

        // Apply filters
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nisn', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($jenis_kelamin) {
            $query->where('jenis_kelamin', $jenis_kelamin);
        }

        $siswaList = $query->orderBy('nama_lengkap')->paginate(20)->appends($request->all());

        // Statistik kelas
        $totalSiswa = $kelas->siswa()->count();
        $siswaAktif = $kelas->siswa()->where('status', 'aktif')->count();
        $siswaLaki = $kelas->siswa()->where('jenis_kelamin', 'L')->count();
        $siswaPerempuan = $kelas->siswa()->where('jenis_kelamin', 'P')->count();

        // Statistik absensi hari ini
        $today = Carbon::today();
        $absensiToday = AbsensiHarian::where('kelas_id', $kelas->id)
                                    ->whereDate('tanggal', $today)
                                    ->get()
                                    ->keyBy('siswa_id');

        return view('guru.wali-kelas.data-siswa', compact(
            'kelas', 'siswaList', 'totalSiswa', 'siswaAktif', 'siswaLaki', 'siswaPerempuan',
            'absensiToday', 'search', 'status', 'jenis_kelamin'
        ));
    }

    /**
     * Set Ketua Kelas and Bendahara
     */
    public function setKmBendahara(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        $kelas = $guru->kelasWali;
        
        if (!$kelas) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda belum ditugaskan sebagai wali kelas');
        }

        $km_id = $request->input('km_id');
        $bendahara_id = $request->input('bendahara_id');
        
        // Reset semua ketua kelas dan bendahara di kelas ini
        Siswa::where('kelas_id', $kelas->id)
            ->update(['is_ketua_kelas' => false, 'is_bendahara' => false]);
            
        $messages = [];
        
        // Set ketua kelas baru jika dipilih
        if ($km_id) {
            $siswaKM = Siswa::where('id', $km_id)
                         ->where('kelas_id', $kelas->id)
                         ->first();
            
            if ($siswaKM) {
                $siswaKM->is_ketua_kelas = true;
                $siswaKM->save();
                $messages[] = $siswaKM->nama_lengkap . ' telah ditetapkan sebagai Ketua Kelas';
            }
        }
        
        // Set bendahara baru jika dipilih
        if ($bendahara_id) {
            $siswaBendahara = Siswa::where('id', $bendahara_id)
                               ->where('kelas_id', $kelas->id)
                               ->first();
            
            if ($siswaBendahara) {
                $siswaBendahara->is_bendahara = true;
                $siswaBendahara->save();
                $messages[] = $siswaBendahara->nama_lengkap . ' telah ditetapkan sebagai Bendahara Kelas';
            }
        }
        
        if (empty($messages)) {
            return redirect()->route('guru.wali-kelas.siswa.index')
                         ->with('info', 'Tidak ada perubahan yang dilakukan');
        }
        
        return redirect()->route('guru.wali-kelas.siswa.index')
                     ->with('success', implode(' dan ', $messages));
    }
}
