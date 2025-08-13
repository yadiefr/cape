<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\TugasSiswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class TugasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:siswa');
    }

    public function index()
    {
        Log::info('TugasController::index called', [
            'auth_check' => Auth::guard('siswa')->check(),
            'guard' => 'siswa',
            'user' => Auth::guard('siswa')->user()
        ]);

        $siswa = Auth::guard('siswa')->user();
        
        if (!$siswa) {
            Log::error('Siswa data not found', ['guard_check' => Auth::guard('siswa')->check()]);
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }
        
        Log::info('Student info', [
            'siswa_id' => $siswa->id,
            'kelas_id' => $siswa->kelas_id,
            'name' => $siswa->nama_lengkap
        ]);

        // Use new structure with direct kelas_id and mapel_id
        $tugas = Tugas::where('kelas_id', $siswa->kelas_id)
            ->with(['mapel', 'guru', 'kelas', 'pengumpulanTugas' => function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }])
            ->where('is_active', true)
            ->orderByDesc('created_at')
            ->paginate(10);

        Log::info('Tasks fetched', [
            'count' => $tugas->count(),
            'total' => $tugas->total(),
            'kelas_id_used' => $siswa->kelas_id
        ]);

        return view('siswa.tugas.index', compact('tugas'));
    }

    public function show($id)
    {
        $siswa = Auth::guard('siswa')->user();
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }
        
        if (!$siswa->kelas_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki kelas. Silahkan hubungi administrator.');
        }

        // Try new structure first (direct kelas_id and mapel_id)
        $tugas = Tugas::with(['mapel', 'guru', 'kelas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->find($id);

        // If not found with new structure, try legacy structure
        if (!$tugas) {
            $tugas = Tugas::with(['jadwal.mapel', 'jadwal.guru', 'jadwal.kelas'])
                ->whereHas('jadwal', function($q) use ($siswa) {
                    $q->where('kelas_id', $siswa->kelas_id);
                })
                ->find($id);
        }

        // If still not found, check if tugas exists but without kelas restriction
        if (!$tugas) {
            $tugas = Tugas::with(['mapel', 'guru', 'kelas', 'jadwal.mapel', 'jadwal.guru', 'jadwal.kelas'])
                ->find($id);
            
            if ($tugas) {
                // Tugas exists but not for this student's class
                Log::warning('Student trying to access tugas from different class', [
                    'siswa_id' => $siswa->id,
                    'siswa_kelas_id' => $siswa->kelas_id,
                    'tugas_id' => $id,
                    'tugas_kelas_id' => $tugas->kelas_id
                ]);
                abort(403, 'Anda tidak memiliki akses ke tugas ini.');
            }
            
            abort(404, 'Tugas tidak ditemukan.');
        }

        $pengumpulan = TugasSiswa::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:10240' // max 10MB
        ]);

        $siswa = Auth::guard('siswa')->user();
        
        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }
        
        if (!$siswa->kelas_id) {
            return redirect()->back()->with('error', 'Anda belum memiliki kelas. Silahkan hubungi administrator.');
        }

        // Try new structure first (direct kelas_id)
        $tugas = Tugas::where('kelas_id', $siswa->kelas_id)->find($id);

        // If not found, try legacy structure (jadwal relationship)
        if (!$tugas) {
            $tugas = Tugas::whereHas('jadwal', function($q) use ($siswa) {
                    $q->where('kelas_id', $siswa->kelas_id);
                })
                ->find($id);
        }

        if (!$tugas) {
            return redirect()->back()->with('error', 'Tugas tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Check if deadline has passed
        if($tugas->deadline < now()) {
            return redirect()->back()->with('error', 'Batas waktu pengumpulan tugas telah berakhir');
        }

        // Store file
        $file = $request->file('file');
        $filePath = $file->store('tugas_submissions/' . $tugas->id, 'public');

        // Delete previous submission if exists
        $existingSubmission = TugasSiswa::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if($existingSubmission) {
            if($existingSubmission->file_path) {
                Storage::disk('public')->delete($existingSubmission->file_path);
            }
            $existingSubmission->update([
                'file_path' => $filePath,
                'tanggal_submit' => now(),
                'status' => 'submitted'
            ]);
        } else {
            TugasSiswa::create([
                'tugas_id' => $tugas->id,
                'siswa_id' => $siswa->id,
                'file_path' => $filePath,
                'tanggal_submit' => now(),
                'status' => 'submitted'
            ]);
        }

        return redirect()->back()->with('success', 'Tugas berhasil dikumpulkan');
    }
}
