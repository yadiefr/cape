<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Exports\SiswaAbsensiExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Pastikan siswa ada
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }
        
        $query = Absensi::with(['kelas', 'mapel', 'guru'])
            ->where('siswa_id', $siswa->id);

        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan jika ada
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter berdasarkan tahun jika ada (default tahun ini)
        $tahun = $request->filled('tahun') ? $request->tahun : date('Y');
        $query->whereYear('tanggal', $tahun);

        // Pencarian mata pelajaran
        if ($request->filled('search')) {
            $query->whereHas('mapel', function($q) use ($request) {
                $q->where('nama_mapel', 'like', '%' . $request->search . '%');
            });
        }

        // Jika request export Excel
        if ($request->has('export') && $request->export === 'excel') {
            return $this->exportExcel($query->get(), $siswa);
        }

        $absensi = $query->orderBy('tanggal', 'desc')->paginate(10);

        // Statistik tambahan
        $statistik = [
            'total_hari_ini' => Absensi::where('siswa_id', $siswa->id)
                               ->whereDate('tanggal', Carbon::today())
                               ->count(),
            'total_minggu_ini' => Absensi::where('siswa_id', $siswa->id)
                                 ->whereBetween('tanggal', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                                 ->count(),
            'total_bulan_ini' => Absensi::where('siswa_id', $siswa->id)
                                ->whereMonth('tanggal', Carbon::now()->month)
                                ->whereYear('tanggal', Carbon::now()->year)
                                ->count(),
        ];
        
        return view('siswa.absensi.index', compact('absensi', 'statistik'));
    }

    private function exportExcel($absensi, $siswa)
    {
        $filename = 'absensi_' . str_replace(' ', '_', strtolower($siswa->nama_lengkap)) . '_' . date('Y-m-d') . '.xlsx';
        
        return Excel::download(new SiswaAbsensiExport($absensi, $siswa), $filename);
    }

    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        
        // Pastikan siswa ada
        if (!$siswa) {
            return redirect()->route('siswa.dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }
        
        $absensi = Absensi::with(['kelas', 'mapel', 'guru'])
            ->where('siswa_id', $siswa->id)
            ->findOrFail($id);
        
        return view('siswa.absensi.show', compact('absensi'));
    }
} 