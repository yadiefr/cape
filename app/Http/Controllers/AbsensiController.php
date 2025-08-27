<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now();
        
        if ($user->role === 'guru') {
            // Get base query with all necessary relationships
            $query = Absensi::with(['siswa.kelas', 'mapel'])
                ->where('guru_id', $user->id);

            // Apply filters if present
            if (request()->filled('kelas_id')) {
                $query->whereHas('siswa', function($q) {
                    $q->where('kelas_id', request('kelas_id'));
                });
            }
            
            if (request()->filled('mapel_id')) {
                $query->where('mapel_id', request('mapel_id'));
            }
            
            if (request()->filled('tanggal')) {
                $query->whereDate('tanggal', request('tanggal'));
            }

            // Get attendance records
            $absensi = $query->orderBy('tanggal', 'desc')->paginate(10);

            // Get unique classes taught by this teacher with proper eager loading
            $kelas = JadwalPelajaran::with('kelas')
                ->where('guru_id', $user->id)
                ->whereHas('kelas') // Ensure kelas exists
                ->get()
                ->pluck('kelas')
                ->filter() // Remove any null values
                ->unique('id')
                ->values(); // Reset array keys

            // Get subjects taught by this teacher with proper eager loading
            $mapel = JadwalPelajaran::with('mapel')
                ->where('guru_id', $user->id)
                ->whereHas('mapel') // Ensure mapel exists
                ->get()
                ->pluck('mapel')
                ->filter() // Remove any null values
                ->unique('id')
                ->values(); // Reset array keys

            // Get today's schedule
            $jadwal = JadwalPelajaran::with(['kelas', 'mapel'])
                ->where('guru_id', $user->id)
                ->where('hari', strtolower($today->format('l')))
                ->whereHas('kelas')
                ->whereHas('mapel')
                ->get();

            // Calculate statistics
            $stats = [
                'hadir' => Absensi::where('guru_id', $user->id)->where('status', 'hadir')->count(),
                'izin' => Absensi::where('guru_id', $user->id)->where('status', 'izin')->count(),
                'sakit' => Absensi::where('guru_id', $user->id)->where('status', 'sakit')->count(),
                'alpha' => Absensi::where('guru_id', $user->id)->where('status', 'alpha')->count(),
            ];
            
            return view('absensi.index', compact('absensi', 'kelas', 'mapel', 'jadwal', 'stats'));
            
        } else if ($user->role === 'siswa') {
            // For students: show attendance history
            $absensi = Absensi::with(['mapel', 'guru'])
                ->where('siswa_id', $user->id)
                ->orderBy('tanggal', 'desc')
                ->paginate(10);
            
            return view('absensi.siswa', compact('absensi'));
        } else {
            // For admin: show all attendance data
            return redirect()->route('guru.absensi.index');
        }
    }

    public function create()
    {
        if (Auth::user()->role !== 'guru') {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Unauthorized access');
        }

        $jadwal = JadwalPelajaran::with(['kelas', 'mapel'])
            ->where('guru_id', Auth::id())
            ->where('hari', strtolower(Carbon::now()->format('l')))
            ->get();

        return view('absensi.create', compact('jadwal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'status.*' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan.*' => 'nullable|string|max:255'
        ]);

        $siswaIds = $request->input('siswa_id');
        $statuses = $request->input('status');
        $keterangan = $request->input('keterangan');

        foreach ($siswaIds as $index => $siswaId) {
            Absensi::create([
                'siswa_id' => $siswaId,
                'mapel_id' => $request->mapel_id,
                'tanggal' => $request->tanggal,
                'status' => $statuses[$index],
                'keterangan' => $keterangan[$index] ?? null,
                'guru_id' => Auth::id()
            ]);
        }

        return redirect()->route('guru.absensi.index')
            ->with('success', 'Data absensi berhasil disimpan');
    }

    public function show($id)
    {
        $absensi = Absensi::with(['siswa', 'mapel', 'guru'])->findOrFail($id);
        return view('absensi.show', compact('absensi'));
    }

    public function edit($id)
    {
        if (Auth::user()->role !== 'guru') {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Unauthorized access');
        }

        $absensi = Absensi::with(['siswa', 'mapel'])->findOrFail($id);
        return view('absensi.edit', compact('absensi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|string|max:255'
        ]);

        $absensi = Absensi::findOrFail($id);
        $absensi->update([
            'status' => $request->status,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('guru.absensi.index')
            ->with('success', 'Data absensi berhasil diperbarui');
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('guru.absensi.index')
                ->with('error', 'Unauthorized access');
        }

        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('guru.absensi.index')
            ->with('success', 'Data absensi berhasil dihapus');
    }

    public function rekap(Request $request)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        $siswa = User::where('role', 'siswa')
            ->where('kelas_id', $request->kelas_id)
            ->with(['absensi' => function ($query) use ($request) {
                $query->whereBetween('tanggal', [
                    $request->tanggal_awal,
                    $request->tanggal_akhir
                ]);
            }])
            ->get();

        return view('absensi.rekap', compact('siswa'));
    }
}
