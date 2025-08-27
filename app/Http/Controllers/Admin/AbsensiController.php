<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Exports\AbsensiExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::where('is_active', true)->get();
        $mapel = MataPelajaran::all();

        $query = Absensi::with(['mapel', 'guru', 'kelas']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('mapel_id')) {
            $query->where('mapel_id', $request->mapel_id);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        // Get the unique dates and mapel combinations
        $absensi = $query->select('tanggal', 'mapel_id', 'guru_id', 'kelas_id')
            ->groupBy('tanggal', 'mapel_id', 'guru_id', 'kelas_id')
            ->orderBy('tanggal', 'desc')
            ->paginate(15)
            ->through(function($record) {
            $counts = Absensi::where('tanggal', $record->tanggal)
                ->where('mapel_id', $record->mapel_id)
                ->where('kelas_id', $record->kelas_id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Load the related models
            $guru = \App\Models\Guru::find($record->guru_id);
            $mapel = \App\Models\MataPelajaran::find($record->mapel_id);
            $kelas = \App\Models\Kelas::find($record->kelas_id);
            
            // Get any ID from the attendance records for this group
            $sampleAbsensi = Absensi::where('tanggal', $record->tanggal)
                ->where('mapel_id', $record->mapel_id)
                ->where('kelas_id', $record->kelas_id)
                ->first();

            return (object) [
                'id' => $sampleAbsensi->id ?? null,
                'tanggal' => $record->tanggal,
                'guru' => $guru,
                'mapel' => $mapel,
                'kelas' => $kelas,
                'composite_key' => base64_encode($record->tanggal . '|' . $record->mapel_id . '|' . $record->kelas_id),
                'counts' => [
                    'hadir' => $counts['hadir'] ?? 0,
                    'izin' => $counts['izin'] ?? 0,
                    'sakit' => $counts['sakit'] ?? 0,
                    'alpha' => $counts['alpha'] ?? 0
                ]
            ];
            });

        try {
            // Calculate overall statistics
            $stats = [
                'hadir' => (clone $query)->where('status', 'hadir')->count(),
                'izin' => (clone $query)->where('status', 'izin')->count(),
                'sakit' => (clone $query)->where('status', 'sakit')->count(),
                'alpha' => (clone $query)->where('status', 'alpha')->count(),
                'total' => (clone $query)->count(),
            ];
        } catch (\Exception $e) {
            \Log::error('Error calculating attendance statistics: ' . $e->getMessage());
            $stats = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
                'total' => 0,
            ];
        }

        return view('admin.absensi.index', compact('absensi', 'kelas', 'mapel', 'stats'));
    }

    public function show($id)
    {
        // Get the reference absensi record
        $referenceAbsensi = Absensi::with(['kelas', 'mapel', 'guru'])->findOrFail($id);
        
        // Get all attendance records for the same class, subject and date
        $absensi = Absensi::with(['siswa', 'kelas', 'mapel', 'guru'])
            ->whereDate('tanggal', $referenceAbsensi->tanggal)
            ->where('mapel_id', $referenceAbsensi->mapel_id)
            ->where('kelas_id', $referenceAbsensi->kelas_id)
            ->get();

        if ($absensi->isEmpty()) {
            return redirect()->route('admin.absensi.index')
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

        return view('admin.absensi.show', compact('absensi', 'stats'));
    }    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            // Check if it's a composite key (base64 encoded)
            if (strlen($id) > 10 && base64_decode($id, true) !== false) {
                $decoded = base64_decode($id);
                $parts = explode('|', $decoded);
                
                if (count($parts) === 3) {
                    [$tanggal, $mapel_id, $kelas_id] = $parts;
                    
                    $deletedCount = Absensi::where('tanggal', $tanggal)
                        ->where('mapel_id', $mapel_id)
                        ->where('kelas_id', $kelas_id)
                        ->delete();
                        
                    if ($deletedCount > 0) {
                        DB::commit();
                        return redirect()->route('admin.absensi.index')
                            ->with('success', "Data absensi berhasil dihapus ({$deletedCount} record)");
                    } else {
                        return redirect()->route('admin.absensi.index')
                            ->with('error', 'Data absensi tidak ditemukan');
                    }
                }
            }
            
            // Fallback to original method using ID
            $absensi = Absensi::find($id);
            
            if (!$absensi) {
                return redirect()->route('admin.absensi.index')
                    ->with('error', 'Data absensi tidak ditemukan');
            }
            
            // Hapus semua absensi dengan tanggal, mapel, dan kelas yang sama
            $deletedCount = Absensi::where('tanggal', $absensi->tanggal)
                ->where('mapel_id', $absensi->mapel_id)
                ->where('kelas_id', $absensi->kelas_id)
                ->delete();
                
            DB::commit();
            
            return redirect()->route('admin.absensi.index')
                ->with('success', "Data absensi berhasil dihapus ({$deletedCount} record)");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error deleting attendance: ' . $e->getMessage());
            return redirect()->route('admin.absensi.index')
                ->with('error', 'Gagal menghapus data absensi: ' . $e->getMessage());
        }
    }

    public function rekap(Request $request)
    {
        $kelas = Kelas::where('is_active', true)->get();
        $kelas_info = null;
        
        $query = Absensi::with(['siswa', 'kelas', 'mapel', 'guru']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
            $kelas_info = Kelas::find($request->kelas_id);
        }

        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->whereMonth('tanggal', $request->bulan)
                  ->whereYear('tanggal', $request->tahun);
        }

        $absensi = $query->get();

        // Group data by student
        $rekapData = $absensi->groupBy('siswa_id')
            ->map(function ($items) {
                $siswa = $items->first()->siswa;
                return [
                    'siswa' => $siswa,
                    'nama' => $siswa->nama_lengkap ?? $siswa->nama ?? 'Unknown',
                    'nis' => $siswa->nis ?? '-',
                    'hadir' => $items->where('status', 'hadir')->count(),
                    'izin' => $items->where('status', 'izin')->count(),
                    'sakit' => $items->where('status', 'sakit')->count(),
                    'alpha' => $items->where('status', 'alpha')->count(),
                    'total' => $items->count()
                ];
            })
            ->map(function ($data) {
                $data['persentase'] = $data['total'] > 0 ? ($data['hadir'] / $data['total']) * 100 : 0;
                return $data;
            });

        return view('admin.absensi.rekap', compact('rekapData', 'kelas', 'kelas_info'))->with('rekap', $rekapData);
    }    public function export(Request $request)
    {
        try {
            $query = Absensi::with(['siswa', 'kelas', 'mapel', 'guru']);

            // Filter berdasarkan kelas
            if ($request->filled('kelas_id')) {
                $query->where('kelas_id', $request->kelas_id);
            }

            if ($request->filled('mapel_id')) {
                $query->where('mapel_id', $request->mapel_id);
            }
            
            // Filter berdasarkan rentang tanggal
            if ($request->filled('tanggal_mulai') && $request->filled('tanggal_akhir')) {
                $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_akhir]);
            } elseif ($request->filled('tanggal')) {
                $query->whereDate('tanggal', $request->tanggal);
            }
            
            if ($request->filled('bulan') && $request->filled('tahun')) {
                $query->whereMonth('tanggal', $request->bulan)
                      ->whereYear('tanggal', $request->tahun);
            }

            $absensi = $query->orderBy('tanggal', 'desc')->get();
            if ($absensi->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data absensi pada rentang tanggal yang dipilih.');
            }

            // Group by kelas
            $dataPerKelas = $absensi->groupBy(function($item) {
                return optional($item->kelas)->id;
            });

            $sheets = [];
            foreach ($dataPerKelas as $kelasId => $items) {
                if (!$kelasId) continue; // Skip if kelas_id is null
                
                $header = ['Nama', 'NIS', 'Kelas', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Total', 'Persentase'];
                $rekap = $items->groupBy('siswa_id')->map(function($absensiSiswa) {
                    $siswa = $absensiSiswa->first()->siswa;
                    $kelas = $absensiSiswa->first()->kelas;
                    $total = $absensiSiswa->count();
                    $hadir = $absensiSiswa->where('status', 'hadir')->count();
                    return [
                        $siswa->nama_lengkap ?? $siswa->nama ?? '-',
                        $siswa->nis ?? '-',
                        optional($kelas)->nama_kelas ?? '-',
                        $hadir,
                        $absensiSiswa->where('status', 'izin')->count(),
                        $absensiSiswa->where('status', 'sakit')->count(),
                        $absensiSiswa->where('status', 'alpha')->count(),
                        $total,
                        $total > 0 ? round(($hadir / $total) * 100, 2) . '%' : '0%'
                    ];
                })->values()->toArray();
                array_unshift($rekap, $header);
                $sheets[$kelasId] = $rekap;
            }

            if (empty($sheets)) {
                return redirect()->back()->with('error', 'Tidak ada data yang dapat diekspor.');
            }

            $filename = 'rekap_absensi_' . 
                       ($request->filled('kelas_id') ? 'kelas_' . $request->kelas_id . '_' : 'semua_kelas_') .
                       ($request->filled('bulan') && $request->filled('tahun') ? 
                        $request->bulan . '_' . $request->tahun . '_' : '') .
                       date('Y-m-d_His') . '.xlsx';
                       
            return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AbsensiMultiSheetExport($sheets), $filename);
        } catch (\Exception $e) {
            \Log::error('Error exporting attendance data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        try {
            $imported = 0;
            $file = $request->file('file');
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            $rows = $data[0];
            // Asumsi baris pertama header
            foreach ($rows as $i => $row) {
                if ($i === 0) continue; // skip header
                // Sesuaikan kolom: [tanggal, siswa_id, kelas_id, mapel_id, status, keterangan, guru_id]
                if (count($row) < 7) continue;
                Absensi::create([
                    'tanggal' => $row[0],
                    'siswa_id' => $row[1],
                    'kelas_id' => $row[2],
                    'mapel_id' => $row[3],
                    'status' => $row[4],
                    'keterangan' => $row[5],
                    'guru_id' => $row[6],
                ]);
                $imported++;
            }
            return back()->with('success', 'Berhasil mengimpor ' . $imported . ' data absensi.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $header = ['tanggal', 'siswa_id', 'kelas_id', 'mapel_id', 'status', 'keterangan', 'guru_id'];
        $rows = [
            $header,
            ['2024-05-01', '1', '1', '1', 'hadir', '', '2'],
            ['2024-05-01', '2', '1', '1', 'izin', 'Sakit', '2'],
        ];
        $filename = 'template_import_absensi.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TemplateExport($rows), $filename);
    }
}
