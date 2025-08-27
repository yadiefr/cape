<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjian;
use App\Models\JadwalPengawas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PengawasController extends Controller
{
    public function index(Request $request)
    {
        $query = JadwalUjian::with([
            'mataPelajaran', 
            'jenisUjian',
            'kelas', 
            'ruangan',
            'pengawas.guru'
        ])->withCount('pengawas');

        // Apply filters
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }

        if ($request->filled('status')) {
            if ($request->status == 'perlu_pengawas') {
                $query->having('pengawas_count', '<', 2);
            } elseif ($request->status == 'lengkap') {
                $query->having('pengawas_count', '>=', 2);
            }
        }

        $jadwalUjian = $query->orderBy('tanggal', 'asc')
                           ->orderBy('waktu_mulai', 'asc')
                           ->paginate(15);

        // Get filter options
        $mataPelajaran = MataPelajaran::orderBy('nama')->get();
        $guru = Guru::orderBy('nama')->get();

        // Calculate total pengawas
        $totalPengawas = JadwalPengawas::where('is_active', true)->count();

        return view('admin.ujian.pengawas.index', compact(
            'jadwalUjian',
            'mataPelajaran',
            'guru',
            'totalPengawas'
        ));
    }

    public function show($id)
    {
        $jadwal = JadwalUjian::with([
            'mataPelajaran',
            'jenisUjian',
            'kelas',
            'ruangan',
            'pengawas.guru'
        ])->findOrFail($id);

        // Get all guru and available guru
        $allGuru = Guru::orderBy('nama')->get();
        
        // Get guru yang tersedia (tidak ada konflik jadwal)
        $guruTersedia = Guru::whereDoesntHave('jadwalPengawas', function($query) use ($jadwal) {
            $query->whereHas('jadwalUjian', function($subQuery) use ($jadwal) {
                $subQuery->where('tanggal', $jadwal->tanggal)
                        ->where(function($timeQuery) use ($jadwal) {
                            $timeQuery->whereBetween('waktu_mulai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                    ->orWhereBetween('waktu_selesai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                    ->orWhere(function($overlapQuery) use ($jadwal) {
                                        $overlapQuery->where('waktu_mulai', '<=', $jadwal->waktu_mulai)
                                                   ->where('waktu_selesai', '>=', $jadwal->waktu_selesai);
                                    });
                        });
            });
        })->whereNotIn('id', $jadwal->pengawas->pluck('guru_id'))
          ->orderBy('nama')
          ->get();

        return view('admin.ujian.pengawas.show', compact('jadwal', 'allGuru', 'guruTersedia'));
    }

    public function assign(Request $request, $jadwalId)
    {
        $validator = Validator::make($request->all(), [
            'guru_id' => 'required|exists:guru,id',
            'jenis_pengawas' => 'required|in:utama,pendamping,cadangan',
            'catatan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $jadwal = JadwalUjian::findOrFail($jadwalId);

        // Check if guru already assigned to this jadwal
        $exists = JadwalPengawas::where('jadwal_ujian_id', $jadwalId)
                               ->where('guru_id', $request->guru_id)
                               ->exists();

        if ($exists) {
            return redirect()->back()
                           ->with('error', 'Guru sudah ditugaskan sebagai pengawas untuk jadwal ini.');
        }

        // Check for time conflicts
        $conflict = JadwalPengawas::where('guru_id', $request->guru_id)
                                 ->whereHas('jadwalUjian', function($query) use ($jadwal) {
                                     $query->where('tanggal', $jadwal->tanggal)
                                           ->where(function($timeQuery) use ($jadwal) {
                                               $timeQuery->whereBetween('waktu_mulai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                                       ->orWhereBetween('waktu_selesai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                                       ->orWhere(function($overlapQuery) use ($jadwal) {
                                                           $overlapQuery->where('waktu_mulai', '<=', $jadwal->waktu_mulai)
                                                                      ->where('waktu_selesai', '>=', $jadwal->waktu_selesai);
                                                       });
                                           });
                                 })
                                 ->exists();

        if ($conflict) {
            return redirect()->back()
                           ->with('error', 'Guru memiliki konflik jadwal pada waktu yang sama.');
        }

        JadwalPengawas::create([
            'jadwal_ujian_id' => $jadwalId,
            'guru_id' => $request->guru_id,
            'jenis_pengawas' => $request->jenis_pengawas,
            'catatan' => $request->catatan,
            'is_active' => true
        ]);

        return redirect()->back()
                       ->with('success', 'Pengawas berhasil ditugaskan.');
    }

    public function remove($pengawasId)
    {
        $pengawas = JadwalPengawas::findOrFail($pengawasId);
        $pengawas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pengawas berhasil dihapus.'
        ]);
    }

    public function updateKehadiran(Request $request, $pengawasId)
    {
        $validator = Validator::make($request->all(), [
            'kehadiran' => 'required|in:belum_dicek,hadir,tidak_hadir,sakit,izin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $pengawas = JadwalPengawas::findOrFail($pengawasId);
        
        // Map kehadiran status to is_hadir boolean and keterangan
        $isHadir = false;
        $keterangan = null;
        
        switch ($request->kehadiran) {
            case 'hadir':
                $isHadir = true;
                $pengawas->waktu_hadir = now();
                break;
            case 'tidak_hadir':
                $keterangan = 'Tidak hadir tanpa keterangan';
                break;
            case 'sakit':
                $keterangan = 'Sakit';
                break;
            case 'izin':
                $keterangan = 'Izin';
                break;
            default:
                // belum_dicek - reset to default
                $pengawas->waktu_hadir = null;
                break;
        }

        $pengawas->update([
            'is_hadir' => $isHadir,
            'keterangan_tidak_hadir' => $keterangan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status kehadiran berhasil diupdate.'
        ]);
    }

    public function updateCatatan(Request $request, $pengawasId)
    {
        $validator = Validator::make($request->all(), [
            'catatan' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator);
        }

        $pengawas = JadwalPengawas::findOrFail($pengawasId);
        $pengawas->update([
            'catatan' => $request->catatan
        ]);

        return redirect()->back()
                       ->with('success', 'Catatan berhasil diupdate.');
    }

    public function autoAssign(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'strategy' => 'required|in:random,balanced,expertise'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        $tanggal = $request->tanggal;
        $strategy = $request->strategy;

        try {
            DB::beginTransaction();

            // Get jadwal ujian yang belum memiliki pengawas lengkap pada tanggal tersebut
            $jadwalList = JadwalUjian::whereDate('tanggal', $tanggal)
                                    ->withCount('pengawas')
                                    ->having('pengawas_count', '<', 2)
                                    ->get();

            if ($jadwalList->isEmpty()) {
                return redirect()->back()
                               ->with('info', 'Tidak ada jadwal ujian yang memerlukan pengawas pada tanggal tersebut.');
            }

            $assignedCount = 0;

            foreach ($jadwalList as $jadwal) {
                $neededCount = 2 - $jadwal->pengawas_count;
                
                // Get available guru for this time slot
                $availableGuru = $this->getAvailableGuru($jadwal, $strategy);
                
                $toAssign = $availableGuru->take($neededCount);
                
                foreach ($toAssign as $index => $guru) {
                    JadwalPengawas::create([
                        'jadwal_ujian_id' => $jadwal->id,
                        'guru_id' => $guru->id,
                        'jenis_pengawas' => $index === 0 ? 'utama' : 'pendamping',
                        'catatan' => 'Auto-assigned (' . $strategy . ')',
                        'is_active' => true
                    ]);
                    $assignedCount++;
                }
            }

            DB::commit();

            return redirect()->back()
                           ->with('success', "Berhasil melakukan auto-assign untuk {$assignedCount} pengawas.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat melakukan auto-assign: ' . $e->getMessage());
        }
    }

    private function getAvailableGuru($jadwal, $strategy = 'random')
    {
        $query = Guru::whereDoesntHave('jadwalPengawas', function($query) use ($jadwal) {
            $query->whereHas('jadwalUjian', function($subQuery) use ($jadwal) {
                $subQuery->where('tanggal', $jadwal->tanggal)
                        ->where(function($timeQuery) use ($jadwal) {
                            $timeQuery->whereBetween('waktu_mulai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                    ->orWhereBetween('waktu_selesai', [$jadwal->waktu_mulai, $jadwal->waktu_selesai])
                                    ->orWhere(function($overlapQuery) use ($jadwal) {
                                        $overlapQuery->where('waktu_mulai', '<=', $jadwal->waktu_mulai)
                                                   ->where('waktu_selesai', '>=', $jadwal->waktu_selesai);
                                    });
                        });
            });
        })->whereNotIn('id', $jadwal->pengawas->pluck('guru_id'));

        switch ($strategy) {
            case 'balanced':
                // Prioritize guru with fewer assignments
                $query->withCount('jadwalPengawas')
                      ->orderBy('jadwal_pengawas_count', 'asc');
                break;
            case 'expertise':
                // Prioritize guru with same mata pelajaran
                $query->where('mata_pelajaran', $jadwal->mataPelajaran->nama)
                      ->orWhere('mata_pelajaran', 'like', '%' . $jadwal->mataPelajaran->nama . '%');
                break;
            default:
                // Random assignment
                $query->inRandomOrder();
                break;
        }

        return $query->get();
    }
}
