<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\KasKelas;
use App\Exports\KasKelasExport;
use App\Exports\DataSiswaExport;
use App\Exports\RekapKeuanganExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class BendaharaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
        $this->middleware(function ($request, $next) {
            $siswa = Auth::guard('siswa')->user();
            if (!$siswa->is_bendahara) {
                return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki akses sebagai Bendahara');
            }
            return $next($request);
        });
    }

    /**
     * Dashboard Bendahara - Kas Kelas
     */
    public function dashboard()
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Statistik kas kelas dari database
        $totalKasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;
        $jumlahTransaksi = KasKelas::byKelas($kelas->id)->byMonth(Carbon::now()->month, Carbon::now()->year)->count();

        // Transaksi kas bulan ini - dikelompokkan per siswa untuk kas masuk
        $transaksiKasMasukBulanIni = KasKelas::byKelas($kelas->id)
                                            ->tipeMasuk()
                                            ->byMonth(Carbon::now()->month, Carbon::now()->year)
                                            ->with('siswa')
                                            ->whereNotNull('siswa_id')
                                            ->get()
                                            ->groupBy('siswa_id')
                                            ->map(function ($transaksiGroup) {
                                                $siswa = $transaksiGroup->first()->siswa;
                                                $totalNominal = $transaksiGroup->sum('nominal');
                                                $jumlahTransaksi = $transaksiGroup->count();
                                                $tanggalTerakhir = $transaksiGroup->max('tanggal');
                                                $keteranganAsli = $transaksiGroup->first()->keterangan;
                                                
                                                // Bersihkan keterangan dari nama siswa jika ada
                                                if ($siswa && str_contains($keteranganAsli, ' - ' . $siswa->nama_lengkap)) {
                                                    $keteranganAsli = str_replace(' - ' . $siswa->nama_lengkap, '', $keteranganAsli);
                                                }
                                                
                                                return [
                                                    'siswa' => [
                                                        'nama_lengkap' => $siswa->nama_lengkap,
                                                        'nis' => $siswa->nis
                                                    ],
                                                    'total_nominal' => $totalNominal,
                                                    'jumlah_transaksi' => $jumlahTransaksi,
                                                    'tanggal_terakhir' => $tanggalTerakhir,
                                                    'tipe' => 'masuk',
                                                    'keterangan' => $keteranganAsli // Gunakan keterangan asli
                                                ];
                                            });

        // Kas lainnya masuk bulan ini
        $kasLainnyaMasukBulanIni = KasKelas::byKelas($kelas->id)
                                          ->tipeMasuk()
                                          ->byMonth(Carbon::now()->month, Carbon::now()->year)
                                          ->whereNull('siswa_id')
                                          ->orderBy('tanggal', 'desc')
                                          ->get()
                                          ->map(function ($transaksi) {
                                              return [
                                                  'tanggal' => $transaksi->tanggal,
                                                  'keterangan' => $transaksi->keterangan,
                                                  'nominal' => $transaksi->nominal,
                                                  'tipe' => 'masuk'
                                              ];
                                          });

        // Transaksi kas keluar bulan ini
        $transaksiKasKeluarBulanIni = KasKelas::byKelas($kelas->id)
                                             ->tipeKeluar()
                                             ->byMonth(Carbon::now()->month, Carbon::now()->year)
                                             ->orderBy('tanggal', 'desc')
                                             ->get()
                                             ->map(function ($transaksi) {
                                                 return [
                                                     'tanggal' => $transaksi->tanggal,
                                                     'keterangan' => $transaksi->keterangan,
                                                     'nominal' => $transaksi->nominal,
                                                     'tipe' => 'keluar'
                                                 ];
                                             });

        // Gabungkan semua transaksi
        $transaksiKasBulanIni = $transaksiKasMasukBulanIni->values()
                                                          ->concat($kasLainnyaMasukBulanIni)
                                                          ->concat($transaksiKasKeluarBulanIni)
                                                          ->sortByDesc(function ($item) {
                                                              return $item['tanggal_terakhir'] ?? $item['tanggal'];
                                                          });

        // Kategori pengeluaran bulan ini
        $kategoriPengeluaran = KasKelas::byKelas($kelas->id)
                                      ->tipeKeluar()
                                      ->byMonth(Carbon::now()->month, Carbon::now()->year)
                                      ->select('kategori', DB::raw('SUM(nominal) as total'))
                                      ->groupBy('kategori')
                                      ->orderBy('total', 'desc')
                                      ->get();

        // Chart kas per bulan (6 bulan terakhir)
        $chartKas = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $kasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->byMonth($bulan->month, $bulan->year)->sum('nominal');
            $kasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->byMonth($bulan->month, $bulan->year)->sum('nominal');
            
            $chartKas->push([
                'bulan' => $bulan->format('M Y'),
                'masuk' => $kasMasuk,
                'keluar' => $kasKeluar,
                'saldo' => $kasMasuk - $kasKeluar
            ]);
        }

        return view('siswa.bendahara.dashboard', compact(
            'siswa', 'kelas', 'totalKasMasuk', 'totalKasKeluar', 'saldoKas',
            'jumlahTransaksi', 'transaksiKasBulanIni', 'kategoriPengeluaran',
            'chartKas'
        ));
    }

    /**
     * Kas Kelas - Transaksi Masuk (Tabel Harian)
     */
    public function kasMasuk(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Ambil bulan dan tahun dari request atau default bulan ini
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        
        // Validasi bulan dan tahun
        $bulan = max(1, min(12, (int)$bulan));
        $tahun = max(2020, min(2030, (int)$tahun));
        
        // Buat tanggal awal dan akhir bulan
        $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1);
        $tanggalAkhir = $tanggalAwal->copy()->endOfMonth();
        
        // Ambil semua hari dalam bulan tersebut
        $hariDalamBulan = [];
        $currentDate = $tanggalAwal->copy();
        
        while ($currentDate <= $tanggalAkhir) {
            $tanggal = $currentDate->format('Y-m-d');
            
            // Ambil data kas masuk untuk tanggal ini
            $kasMasukHari = KasKelas::byKelas($kelas->id)
                                   ->tipeMasuk()
                                   ->whereDate('tanggal', $tanggal)
                                   ->with(['siswa', 'createdBy'])
                                   ->orderBy('created_at', 'desc')
                                   ->get();
            
            $totalHari = $kasMasukHari->sum('nominal');
            
            $hariDalamBulan[] = [
                'tanggal' => $tanggal,
                'tanggal_formatted' => $currentDate->format('d'),
                'hari_nama' => $currentDate->locale('id')->dayName,
                'transaksi' => $kasMasukHari,
                'total' => $totalHari,
                'jumlah_transaksi' => $kasMasukHari->count()
            ];
            
            $currentDate->addDay();
        }
        
        // Statistik bulan ini
        $totalKasMasukBulan = KasKelas::byKelas($kelas->id)
                                     ->tipeMasuk()
                                     ->byMonth($bulan, $tahun)
                                     ->sum('nominal');
        
        $totalTransaksiBulan = KasKelas::byKelas($kelas->id)
                                      ->tipeMasuk()
                                      ->byMonth($bulan, $tahun)
                                      ->count();

        // Total kas masuk keseluruhan (semua bulan)
        $totalKasMasukKeseluruhan = KasKelas::byKelas($kelas->id)
                                           ->tipeMasuk()
                                           ->sum('nominal');

        // Total transaksi keseluruhan (semua bulan)
        $totalTransaksiKeseluruhan = KasKelas::byKelas($kelas->id)
                                            ->tipeMasuk()
                                            ->count();

        // Ambil daftar siswa untuk form input cepat
        $siswaKelas = $kelas->siswa()->orderBy('nama_lengkap')->get();

        // Data untuk navigasi bulan
        $bulanSebelumnya = $tanggalAwal->copy()->subMonth();
        $bulanSelanjutnya = $tanggalAwal->copy()->addMonth();

        return view('siswa.bendahara.kas-masuk', compact(
            'siswa', 'kelas', 'hariDalamBulan', 'bulan', 'tahun',
            'totalKasMasukBulan', 'totalTransaksiBulan', 'siswaKelas',
            'totalKasMasukKeseluruhan', 'totalTransaksiKeseluruhan',
            'bulanSebelumnya', 'bulanSelanjutnya', 'tanggalAwal'
        ));
    }

    /**
     * Halaman Input Kas Masuk untuk tanggal tertentu
     */
    public function inputKasMasuk(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Ambil tanggal dari request, default hari ini
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $tanggalObj = \Carbon\Carbon::parse($tanggal);
        
        // Cek apakah sudah ada transaksi kas masuk untuk tanggal ini
        $transaksiHariIni = KasKelas::byKelas($kelas->id)
                                   ->tipeMasuk()
                                   ->whereDate('tanggal', $tanggal)
                                   ->get();

        // Jika sudah ada transaksi, redirect ke halaman edit
        if ($transaksiHariIni->count() > 0) {
            return redirect()->route('siswa.bendahara.edit-kas-masuk', ['tanggal' => $tanggal])
                           ->with('info', 'Sudah ada transaksi untuk tanggal ini. Gunakan halaman edit untuk mengubah data.');
        }

        // Ambil daftar siswa di kelas
        $siswaKelas = $kelas->siswa()->orderBy('nama_lengkap')->get();

        return view('siswa.bendahara.input-kas-masuk', compact(
            'siswa', 'kelas', 'tanggal', 'tanggalObj', 'transaksiHariIni', 'siswaKelas'
        ));
    }

    /**
     * Halaman Edit Kas Masuk untuk tanggal tertentu
     */
    public function editKasMasuk(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Ambil tanggal dari request
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $tanggalObj = \Carbon\Carbon::parse($tanggal);
        
        // Ambil semua siswa di kelas
        $siswaKelas = $kelas->siswa()->orderBy('nama_lengkap')->get();
        
        // Ambil transaksi kas masuk untuk tanggal ini
        $transaksiHariIni = KasKelas::byKelas($kelas->id)
                                   ->tipeMasuk()
                                   ->whereDate('tanggal', $tanggal)
                                   ->with('siswa')
                                   ->get();
        
        if ($transaksiHariIni->count() === 0) {
            return redirect()->route('siswa.bendahara.input-kas-masuk', ['tanggal' => $tanggal])
                           ->with('info', 'Tidak ada transaksi untuk diedit. Silakan tambah transaksi baru.');
        }
        
        // Buat array siswa yang sudah bayar dengan nominal
        $siswaSudahBayar = [];
        $nominalSiswa = [];
        foreach ($transaksiHariIni->where('kategori', 'iuran_bulanan') as $transaksi) {
            if ($transaksi->siswa_id) {
                $siswaSudahBayar[] = $transaksi->siswa_id;
                $nominalSiswa[$transaksi->siswa_id] = $transaksi->nominal;
            }
        }
        
        // Ambil kas lainnya
        $kasLainnya = $transaksiHariIni->where('kategori', '!=', 'iuran_bulanan')->values();

        return view('siswa.bendahara.edit-kas-masuk', compact(
            'siswa', 'kelas', 'siswaKelas', 'tanggal', 'tanggalObj',
            'transaksiHariIni', 'siswaSudahBayar', 'nominalSiswa', 'kasLainnya'
        ));
    }

    /**
     * Update Transaksi Kas Masuk
     */
    public function updateKasMasuk(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;

        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Validasi data dasar
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'keterangan_umum' => 'nullable|string|max:500',
            'nominal' => 'nullable|array',
            'nominal.*' => 'nullable|numeric|min:0',
            'kas_lainnya' => 'nullable|array',
            'kas_lainnya.*.id' => 'nullable|exists:kas_kelas,id',
            'kas_lainnya.*.nominal' => 'required_with:kas_lainnya|numeric|min:1000'
        ]);

        try {
            DB::beginTransaction();

            // 1. Hapus semua transaksi iuran_bulanan untuk tanggal ini
            KasKelas::byKelas($kelas->id)
                    ->tipeMasuk()
                    ->where('kategori', 'iuran_bulanan')
                    ->whereDate('tanggal', $request->tanggal)
                    ->delete();

            $totalTransaksi = 0;
            $totalNominal = 0;

            // 2. Simpan ulang iuran bulanan siswa
            if ($request->has('nominal') && is_array($request->nominal)) {
                foreach ($request->nominal as $siswaId => $nominal) {
                    $nominal = (int) $nominal;
                    
                    if ($nominal > 0 && $nominal >= 1000) {
                        $keterangan = $request->keterangan_umum ? 
                                        $request->keterangan_umum :
                                        "Kas masuk - " . date('d/m/Y');

                        KasKelas::create([
                            'kelas_id' => $kelas->id,
                            'siswa_id' => $siswaId,
                            'tipe' => 'masuk',
                            'kategori' => 'iuran_bulanan',
                            'nominal' => $nominal,
                            'keterangan' => $keterangan,
                            'tanggal' => $request->tanggal,
                            'bukti_transaksi' => null,
                            'diinput_oleh' => $siswa->id
                        ]);

                        $totalTransaksi++;
                        $totalNominal += $nominal;
                    }
                }
            }

            // 3. Update kas lainnya
            if ($request->has('kas_lainnya') && is_array($request->kas_lainnya)) {
                foreach ($request->kas_lainnya as $kas) {
                    if (empty($kas['nominal'])) {
                        continue;
                    }

                    $keterangan = $request->keterangan_umum ? 
                                    $request->keterangan_umum :
                                    "Kas masuk - " . date('d/m/Y');

                    if (isset($kas['id']) && !empty($kas['id'])) {
                        // Update existing
                        $existingKas = KasKelas::find($kas['id']);
                        if ($existingKas && $existingKas->kelas_id == $kelas->id) {
                            $existingKas->update([
                                'kategori' => 'lainnya',
                                'nominal' => $kas['nominal'],
                                'keterangan' => $keterangan,
                                'diinput_oleh' => $siswa->id
                            ]);
                            $totalTransaksi++;
                            $totalNominal += $kas['nominal'];
                        }
                    } else {
                        // Create new
                        KasKelas::create([
                            'kelas_id' => $kelas->id,
                            'siswa_id' => null,
                            'tipe' => 'masuk',
                            'kategori' => 'lainnya',
                            'nominal' => $kas['nominal'],
                            'keterangan' => $keterangan,
                            'tanggal' => $request->tanggal,
                            'bukti_transaksi' => null,
                            'diinput_oleh' => $siswa->id
                        ]);
                        $totalTransaksi++;
                        $totalNominal += $kas['nominal'];
                    }
                }
            }

            DB::commit();

            $message = "Berhasil mengupdate transaksi kas masuk dengan total Rp " . number_format($totalNominal, 0, ',', '.');
            
            return redirect()->route('siswa.bendahara.kas-masuk')
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Laporan Kas Kelas
     */
    public function laporanKas(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }
        
        // Ambil parameter filter
        $bulan = $request->get('bulan', Carbon::now()->month);
        $tahun = $request->get('tahun', Carbon::now()->year);
        $periode = $request->get('periode', 'bulan'); // bulan, semester, tahun
        
        // Validasi input
        $bulan = max(1, min(12, (int)$bulan));
        $tahun = max(2020, min(2030, (int)$tahun));
        
        // Hitung periode berdasarkan pilihan
        if ($periode === 'tahun') {
            $tanggalMulai = Carbon::createFromDate($tahun, 1, 1);
            $tanggalSelesai = Carbon::createFromDate($tahun, 12, 31);
        } elseif ($periode === 'semester') {
            $semester = $request->get('semester', 1);
            if ($semester == 1) {
                $tanggalMulai = Carbon::createFromDate($tahun, 7, 1); // Juli
                $tanggalSelesai = Carbon::createFromDate($tahun, 12, 31); // Desember
            } else {
                $tanggalMulai = Carbon::createFromDate($tahun, 1, 1); // Januari
                $tanggalSelesai = Carbon::createFromDate($tahun, 6, 30); // Juni
            }
        } else {
            // Default: bulan
            $tanggalMulai = Carbon::createFromDate($tahun, $bulan, 1);
            $tanggalSelesai = $tanggalMulai->copy()->endOfMonth();
        }
        
        // Data kas masuk
        $kasMasuk = KasKelas::byKelas($kelas->id)
                           ->tipeMasuk()
                           ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                           ->orderBy('tanggal', 'desc')
                           ->with(['siswa', 'createdBy'])
                           ->get();
        
        // Data kas keluar
        $kasKeluar = KasKelas::byKelas($kelas->id)
                            ->tipeKeluar()
                            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
                            ->orderBy('tanggal', 'desc')
                            ->with(['siswa', 'createdBy'])
                            ->get();
        
        // Statistik periode
        $totalKasMasukPeriode = $kasMasuk->sum('nominal');
        $totalKasKeluarPeriode = $kasKeluar->sum('nominal');
        $saldoPeriode = $totalKasMasukPeriode - $totalKasKeluarPeriode;
        
        // Statistik keseluruhan (saldo awal)
        $totalKasMasukSemua = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluarSemua = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKeseluruhan = $totalKasMasukSemua - $totalKasKeluarSemua;
        
        // Saldo awal periode (transaksi sebelum periode ini)
        $saldoAwal = KasKelas::byKelas($kelas->id)
                            ->where('tanggal', '<', $tanggalMulai)
                            ->selectRaw('SUM(CASE WHEN tipe = "masuk" THEN nominal ELSE 0 END) - SUM(CASE WHEN tipe = "keluar" THEN nominal ELSE 0 END) as saldo')
                            ->value('saldo') ?? 0;
        
        // Kas masuk per kategori
        $kasMasukPerKategori = $kasMasuk->groupBy('kategori')->map(function ($items, $kategori) {
            return [
                'kategori' => $kategori === 'iuran_bulanan' ? 'Iuran Bulanan' : 'Kas Lainnya',
                'total' => $items->sum('nominal'),
                'jumlah' => $items->count(),
                'transaksi' => $items
            ];
        });
        
        // Kas masuk per siswa - logika pembagian kas kelas
        $allSiswaKelas = $kelas->siswa()->orderBy('nama_lengkap')->get();
        $jumlahSiswaKelas = $allSiswaKelas->count();
        
        // Ambil kas masuk per kategori untuk pembagian
        $kasIuranBulanan = $kasMasuk->where('kategori', 'iuran_bulanan');
        $kasLainnya = $kasMasuk->where('kategori', '!=', 'iuran_bulanan');
        
        $kasMasukPerSiswa = $allSiswaKelas->map(function ($siswa) use ($kasIuranBulanan, $kasLainnya, $jumlahSiswaKelas) {
            // Untuk iuran bulanan, setiap siswa berkontribusi sama
            $totalIuranSiswa = $kasIuranBulanan->sum('nominal') / max(1, $jumlahSiswaKelas);
            
            // Untuk kas lainnya, lihat apakah siswa spesifik yang input
            $kasLainnyaSiswa = $kasLainnya->filter(function($kas) use ($siswa) {
                return $kas->siswa_id == $siswa->id || $kas->diinput_oleh == $siswa->id;
            });
            
            $totalKasLainnya = $kasLainnyaSiswa->sum('nominal');
            
            // Total kas siswa
            $totalKasSiswa = $totalIuranSiswa + $totalKasLainnya;
            
            // Jumlah transaksi yang melibatkan siswa
            $jumlahTransaksi = 0;
            
            // Untuk iuran bulanan: hitung hari unik yang ada transaksi (bukan total transaksi)
            if ($kasIuranBulanan->count() > 0) {
                $hariUnikIuran = $kasIuranBulanan->groupBy(function($kas) {
                    return $kas->tanggal->format('Y-m-d');
                })->count();
                $jumlahTransaksi += $hariUnikIuran;
            }
            
            // Plus kas lainnya yang spesifik untuk siswa ini
            $jumlahTransaksi += $kasLainnyaSiswa->count();
            
            // Debug: Log untuk setiap siswa
            \Log::info("Debug Per Siswa - {$siswa->nama_lengkap}: Iuran: {$totalIuranSiswa}, Lainnya: {$totalKasLainnya}, Total: {$totalKasSiswa}, Transaksi: {$jumlahTransaksi}");
            
            return [
                'siswa' => $siswa,
                'total' => $totalKasSiswa,
                'jumlah' => $jumlahTransaksi,
                'transaksi' => $kasIuranBulanan->merge($kasLainnyaSiswa)
            ];
        });
        
        // Chart data untuk grafik (per hari/minggu/bulan tergantung periode)
        $chartData = $this->generateChartData($kelas->id, $tanggalMulai, $tanggalSelesai, $periode);
        
        return view('siswa.bendahara.laporan-kas', compact(
            'siswa', 'kelas', 'bulan', 'tahun', 'periode',
            'tanggalMulai', 'tanggalSelesai',
            'kasMasuk', 'kasKeluar',
            'totalKasMasukPeriode', 'totalKasKeluarPeriode', 'saldoPeriode',
            'saldoAwal', 'saldoKeseluruhan',
            'kasMasukPerKategori', 'kasMasukPerSiswa',
            'chartData'
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
                
                $masuk = KasKelas::byKelas($kelasId)
                               ->tipeMasuk()
                               ->byMonth($i, $tanggalMulai->year)
                               ->sum('nominal');
                               
                $keluar = KasKelas::byKelas($kelasId)
                                ->tipeKeluar()
                                ->byMonth($i, $tanggalMulai->year)
                                ->sum('nominal');
                                
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
            }
        } elseif ($periode === 'semester') {
            // Data per bulan dalam semester
            $startMonth = $tanggalMulai->month;
            $endMonth = $tanggalSelesai->month;
            
            for ($i = $startMonth; $i <= $endMonth; $i++) {
                $labels[] = Carbon::create()->month($i)->locale('id')->monthName;
                
                $masuk = KasKelas::byKelas($kelasId)
                               ->tipeMasuk()
                               ->byMonth($i, $tanggalMulai->year)
                               ->sum('nominal');
                               
                $keluar = KasKelas::byKelas($kelasId)
                                ->tipeKeluar()
                                ->byMonth($i, $tanggalMulai->year)
                                ->sum('nominal');
                                
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
            }
        } else {
            // Data per minggu dalam bulan
            $currentDate = $tanggalMulai->copy();
            $weekNumber = 1;
            
            while ($currentDate <= $tanggalSelesai) {
                $weekStart = $currentDate->copy();
                $weekEnd = $currentDate->copy()->addDays(6);
                
                if ($weekEnd > $tanggalSelesai) {
                    $weekEnd = $tanggalSelesai->copy();
                }
                
                $labels[] = "Minggu {$weekNumber}";
                
                $masuk = KasKelas::byKelas($kelasId)
                               ->tipeMasuk()
                               ->whereBetween('tanggal', [$weekStart, $weekEnd])
                               ->sum('nominal');
                               
                $keluar = KasKelas::byKelas($kelasId)
                                ->tipeKeluar()
                                ->whereBetween('tanggal', [$weekStart, $weekEnd])
                                ->sum('nominal');
                                
                $dataMasuk[] = $masuk;
                $dataKeluar[] = $keluar;
                
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
     * Simpan Transaksi Kas Masuk (Batch)
     */
    public function simpanKasMasuk(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;

        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Validasi data dasar
        $request->validate([
            'tanggal' => 'required|date|before_or_equal:' . date('Y-m-d'),
            'keterangan_umum' => 'nullable|string|max:500',
            'nominal' => 'required|array',
            'nominal.*' => 'nullable|numeric|min:0',
        ], [
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'nominal.required' => 'Data nominal siswa harus ada',
            'nominal.array' => 'Data nominal tidak valid'
        ]);

        $totalTransaksi = 0;
        $totalNominal = 0;

        try {
            DB::beginTransaction();

            // Debug: Cek data yang diterima
            if (!$request->has('nominal') || !is_array($request->nominal)) {
                return back()->withErrors(['error' => 'Data nominal tidak valid'])
                            ->withInput();
            }

            // Simpan iuran bulanan siswa
            foreach ($request->nominal as $siswaId => $nominal) {
                $nominal = (int) $nominal;
                
                if ($nominal > 0 && $nominal >= 1000) {
                    $keterangan = $request->keterangan_umum ? 
                                    $request->keterangan_umum :
                                    "Kas masuk - " . date('d/m/Y');

                    $result = KasKelas::create([
                        'kelas_id' => $kelas->id,
                        'siswa_id' => $siswaId,
                        'tipe' => 'masuk',
                        'kategori' => 'iuran_bulanan',
                        'nominal' => $nominal,
                        'keterangan' => $keterangan,
                        'tanggal' => $request->tanggal,
                        'bukti_transaksi' => null,
                        'diinput_oleh' => $siswa->id
                    ]);

                    if ($result) {
                        $totalTransaksi++;
                        $totalNominal += $nominal;
                    }
                }
            }

            if ($totalTransaksi === 0) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Tidak ada transaksi yang valid untuk disimpan. Pastikan ada siswa dengan nominal minimal Rp 1.000.'])
                            ->withInput();
            }

            DB::commit();

            $message = "Berhasil menyimpan {$totalTransaksi} transaksi kas masuk dengan total Rp " . number_format($totalNominal, 0, ',', '.');
            
            return redirect()->route('siswa.bendahara.kas-masuk')
                           ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Simpan Transaksi Kas Keluar
     */
    public function simpanKasKeluar(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;

        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Hitung saldo saat ini
        $totalKasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Validasi data
        $validated = $request->validate([
            'tanggal' => 'required|date|before_or_equal:today',
            'nominal' => 'required|numeric|min:1000|max:' . $saldoKas,
            'keterangan' => 'required|string|max:500',
            'bukti_transaksi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ], [
            'nominal.min' => 'Nominal minimal Rp 1.000',
            'nominal.max' => 'Nominal tidak boleh melebihi saldo kas kelas (Rp ' . number_format($saldoKas, 0, ',', '.') . ')',
        ]);

        // Upload bukti transaksi (wajib)
        $buktiPath = null;
        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $fileName = time() . '_kas_keluar_' . $file->getClientOriginalName();
            $buktiPath = $file->storeAs('kas-kelas/bukti-transaksi', $fileName, 'public');
        }

        try {
            // Simpan ke database
            $kasKelas = KasKelas::create([
                'kelas_id' => $kelas->id,
                'siswa_id' => null, // Kas keluar tidak terkait siswa tertentu
                'tipe' => 'keluar',
                'kategori' => 'lainnya', // Default kategori untuk kas keluar
                'nominal' => $validated['nominal'],
                'keterangan' => $validated['keterangan'],
                'tanggal' => $validated['tanggal'],
                'bukti_transaksi' => $buktiPath,
                'diinput_oleh' => $siswa->id
            ]);

            return redirect()->route('siswa.bendahara.kas-keluar')
                           ->with('success', 'Kas keluar berhasil dicatat! Nominal: Rp ' . number_format($validated['nominal'], 0, ',', '.'));

        } catch (\Exception $e) {
            // Hapus file jika ada error
            if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * Halaman Kas Keluar
     */
    public function kasKeluar()
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Hitung saldo kas saat ini
        $totalKasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Ambil data kas keluar bulan ini
        $bulan = Carbon::now()->month;
        $tahun = Carbon::now()->year;
        
        $kasKeluarBulanIni = KasKelas::byKelas($kelas->id)
                                    ->tipeKeluar()
                                    ->byMonth($bulan, $tahun)
                                    ->orderBy('tanggal', 'desc')
                                    ->with('siswa')
                                    ->get();

        // Statistik kas keluar bulan ini
        $totalKasKeluarBulan = $kasKeluarBulanIni->sum('nominal');
        $jumlahTransaksiBulan = $kasKeluarBulanIni->count();

        return view('siswa.bendahara.kas-keluar-list', compact(
            'siswa', 'kelas', 'saldoKas', 'kasKeluarBulanIni', 
            'totalKasKeluarBulan', 'jumlahTransaksiBulan',
            'bulan', 'tahun'
        ));
    }

    /**
     * Form Input Kas Keluar
     */
    public function inputKasKeluar()
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        
        if (!$kelas) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data kelas tidak ditemukan');
        }

        // Hitung saldo kas saat ini
        $totalKasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        return view('siswa.bendahara.kas-keluar', compact('siswa', 'kelas', 'saldoKas'));
    }

    /**
     * Export Laporan Kas
     */
    public function exportKas(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        $format = $request->get('format', 'excel');
        $periode = $request->get('periode', 'bulan');
        
        // Determine date range based on periode
        $now = Carbon::now();
        if ($periode == 'bulan') {
            $bulan = $request->get('bulan', $now->month);
            $tahun = $request->get('tahun', $now->year);
            $tanggalMulai = Carbon::create($tahun, $bulan, 1);
            $tanggalSelesai = $tanggalMulai->copy()->endOfMonth();
        } elseif ($periode == 'semester') {
            $semester = $request->get('semester', 1);
            $tahun = $request->get('tahun', $now->year);
            if ($semester == 1) {
                $tanggalMulai = Carbon::create($tahun, 7, 1);
                $tanggalSelesai = Carbon::create($tahun, 12, 31);
            } else {
                $tanggalMulai = Carbon::create($tahun, 1, 1);
                $tanggalSelesai = Carbon::create($tahun, 6, 30);
            }
        } else { // tahun
            $tahun = $request->get('tahun', $now->year);
            $tanggalMulai = Carbon::create($tahun, 1, 1);
            $tanggalSelesai = Carbon::create($tahun, 12, 31);
        }

        // Get kas data
        $kasData = KasKelas::byKelas($kelas->id)
            ->with('siswa')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'desc')
            ->get();

        if ($format == 'pdf') {
            return $this->exportKasPdf($kasData, $kelas, $tanggalMulai, $tanggalSelesai, $periode);
        } else {
            return $this->exportKasExcel($kasData, $kelas, $tanggalMulai, $tanggalSelesai, $periode);
        }
    }

    /**
     * Export Data Siswa
     */
    public function exportDataSiswa(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        $format = $request->get('format', 'excel');

        $dataSiswa = Siswa::where('kelas_id', $kelas->id)->orderBy('nama_lengkap')->get();

        if ($format == 'pdf') {
            return $this->exportDataSiswaPdf($dataSiswa, $kelas);
        } else {
            return $this->exportDataSiswaExcel($dataSiswa, $kelas);
        }
    }

    /**
     * Export Rekap Keuangan
     */
    public function exportRekap(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        $kelas = $siswa->kelas;
        $format = $request->get('format', 'excel');

        // Get financial summary data
        $totalKasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->sum('nominal');
        $totalKasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->sum('nominal');
        $saldoKas = $totalKasMasuk - $totalKasKeluar;

        // Monthly breakdown
        $rekapBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $kasMasuk = KasKelas::byKelas($kelas->id)->tipeMasuk()->byMonth($i, Carbon::now()->year)->sum('nominal');
            $kasKeluar = KasKelas::byKelas($kelas->id)->tipeKeluar()->byMonth($i, Carbon::now()->year)->sum('nominal');
            
            $rekapBulanan[] = [
                'bulan' => Carbon::create()->month($i)->locale('id')->monthName,
                'kas_masuk' => $kasMasuk,
                'kas_keluar' => $kasKeluar,
                'saldo' => $kasMasuk - $kasKeluar
            ];
        }

        if ($format == 'pdf') {
            return $this->exportRekapPdf($rekapBulanan, $kelas, $totalKasMasuk, $totalKasKeluar, $saldoKas);
        } else {
            return $this->exportRekapExcel($rekapBulanan, $kelas, $totalKasMasuk, $totalKasKeluar, $saldoKas);
        }
    }

    private function exportKasExcel($kasData, $kelas, $tanggalMulai, $tanggalSelesai, $periode)
    {
        $filename = 'laporan-kas-' . str_replace(' ', '-', strtolower($kelas->nama_kelas)) . '-' . $periode . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new KasKelasExport($kasData, $kelas, $tanggalMulai, $tanggalSelesai, $periode), $filename);
    }

    private function exportDataSiswaExcel($dataSiswa, $kelas)
    {
        $filename = 'data-siswa-' . str_replace(' ', '-', strtolower($kelas->nama_kelas)) . '-' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new DataSiswaExport($dataSiswa, $kelas), $filename);
    }

    private function exportRekapExcel($rekapBulanan, $kelas, $totalKasMasuk, $totalKasKeluar, $saldoKas)
    {
        $filename = 'rekap-keuangan-' . str_replace(' ', '-', strtolower($kelas->nama_kelas)) . '-' . date('Y') . '.xlsx';
        
        return Excel::download(new RekapKeuanganExport($rekapBulanan, $kelas, $totalKasMasuk, $totalKasKeluar, $saldoKas), $filename);
    }
}
