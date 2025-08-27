<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Traits\GuruAccessTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    use GuruAccessTrait;

    public function index()
    {
        $guru = Auth::guard('guru')->user();
        
        // Get kelas dan mata pelajaran yang diampu guru using trait methods
        $kelasIds = $this->getGuruKelasIds();
        $mapelIds = $this->getGuruMapelIds();

        // Get materi yang dibuat oleh guru
        $materi = Materi::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->whereIn('kelas_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get tugas yang dibuat oleh guru
        $tugas = Tugas::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->whereIn('kelas_id', $kelasIds)
            ->whereIn('mapel_id', $mapelIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get data untuk form - ordered alphabetically
        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();

        return view('guru.materi.index', compact('materi', 'tugas', 'kelasList', 'mapelList'));
    }

    public function createMateri()
    {
        $guru = Auth::guard('guru')->user();
        
        // Get kelas dan mapel yang diampu guru using trait methods
        $kelasIds = $this->getGuruKelasIds();
        $mapelIds = $this->getGuruMapelIds();

        // Debug logging
        \Log::info('CreateMateri Debug:', [
            'guru_id' => $guru->id,
            'guru_nama' => $guru->nama,
            'kelasIds' => $kelasIds->toArray(),
            'mapelIds' => $mapelIds->toArray()
        ]);

        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();

        // Debug logging untuk hasil query
        \Log::info('CreateMateri Query Results:', [
            'kelasList_count' => $kelasList->count(),
            'mapelList_count' => $mapelList->count(),
            'kelasList' => $kelasList->pluck('nama_kelas', 'id')->toArray(),
            'mapelList' => $mapelList->pluck('nama', 'id')->toArray()
        ]);

        return view('guru.materi.create-materi', compact('kelasList', 'mapelList'));
    }

    public function storeMateri(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:10240', // Max 10MB
            'link_video' => 'nullable|url',
        ]);

        // Verify guru mengajar di kelas dan mapel tersebut using trait method
        if (!$this->hasAccessToKelasMapel($request->kelas_id, $request->mapel_id)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membuat materi di kelas dan mata pelajaran tersebut.');
        }

        // Prepare data manually to avoid any issues with mass assignment
        $materiData = [
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'),
            'kelas_id' => (int) $request->input('kelas_id'),
            'mapel_id' => (int) $request->input('mapel_id'),
            'mata_pelajaran_id' => (int) $request->input('mapel_id'), // For backward compatibility
            'link_video' => $request->input('link_video'),
            'guru_id' => $guru->id,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $materiData['file_path'] = $file->storeAs('materi', $fileName, 'public');
            $materiData['file_name'] = $file->getClientOriginalName();
        }

        try {
            $materi = new Materi();
            $materi->fill($materiData);
            $materi->save();
            
            return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating materi:', [
                'error' => $e->getMessage(),
                'data' => $materiData,
                'guru_id' => $guru->id
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan materi: ' . $e->getMessage());
        }
    }

    public function createTugas()
    {
        $guru = Auth::guard('guru')->user();
        
        // Get kelas dan mapel yang diampu guru using trait methods
        $kelasIds = $this->getGuruKelasIds();
        $mapelIds = $this->getGuruMapelIds();

        // Debug logging
        \Log::info('CreateTugas Debug:', [
            'guru_id' => $guru->id,
            'guru_nama' => $guru->nama,
            'kelasIds' => $kelasIds->toArray(),
            'mapelIds' => $mapelIds->toArray()
        ]);

        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();

        // Debug logging untuk hasil query
        \Log::info('CreateTugas Query Results:', [
            'kelasList_count' => $kelasList->count(),
            'mapelList_count' => $mapelList->count(),
            'kelasList' => $kelasList->pluck('nama_kelas', 'id')->toArray(),
            'mapelList' => $mapelList->pluck('nama', 'id')->toArray()
        ]);

        return view('guru.materi.create-tugas', compact('kelasList', 'mapelList'));
    }

    public function storeTugas(Request $request)
    {
        $guru = Auth::guard('guru')->user();
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tanggal_deadline' => 'required|date|after:today',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // Max 10MB
        ]);

        // Verify guru mengajar di kelas dan mapel tersebut using trait method
        if (!$this->hasAccessToKelasMapel($request->kelas_id, $request->mapel_id)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membuat tugas di kelas dan mata pelajaran tersebut.');
        }

        // Cari jadwal_id berdasarkan guru, kelas, dan mapel
        $jadwal = JadwalPelajaran::where('guru_id', $guru->id)
                                ->where('kelas_id', $request->input('kelas_id'))
                                ->where('mapel_id', $request->input('mapel_id'))
                                ->where('is_active', true)
                                ->first();

        if (!$jadwal) {
            return back()->withInput()->with('error', 'Jadwal pelajaran tidak ditemukan untuk kombinasi kelas dan mata pelajaran tersebut.');
        }

        // Prepare data manually to avoid any issues with mass assignment
        $tugasData = [
            'jadwal_id' => $jadwal->id, // Required field
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'), 
            'kelas_id' => (int) $request->input('kelas_id'),
            'mapel_id' => (int) $request->input('mapel_id'),
            'deadline' => $request->input('tanggal_deadline') . ' 23:59:59', // Required timestamp field
            'tanggal_deadline' => $request->input('tanggal_deadline'),
            'guru_id' => $guru->id,
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $tugasData['file_path'] = $file->storeAs('tugas', $fileName, 'public');
            $tugasData['file_name'] = $file->getClientOriginalName();
        }

        try {
            $tugas = new Tugas();
            $tugas->fill($tugasData);
            $tugas->save();
            
            return redirect()->route('guru.materi.index')->with('success', 'Tugas berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating tugas:', [
                'error' => $e->getMessage(),
                'data' => $tugasData,
                'guru_id' => $guru->id
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan tugas: ' . $e->getMessage());
        }
    }

    public function editMateri($id)
    {
        $guru = Auth::guard('guru')->user();
        $materi = Materi::where('guru_id', $guru->id)->findOrFail($id);
        
        // Get kelas dan mapel yang diampu guru using trait methods
        $kelasIds = $this->getGuruKelasIds();
        $mapelIds = $this->getGuruMapelIds();

        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();

        return view('guru.materi.edit-materi', compact('materi', 'kelasList', 'mapelList'));
    }

    public function updateMateri(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $materi = Materi::where('guru_id', $guru->id)->findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,jpg,jpeg,png|max:10240',
            'link_video' => 'nullable|url',
        ]);

        $data = $request->all();

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['file_path'] = $file->storeAs('materi', $fileName, 'public');
            $data['file_name'] = $file->getClientOriginalName();
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function editTugas($id)
    {
        $guru = Auth::guard('guru')->user();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);
        
        // Get kelas dan mapel yang diampu guru using trait methods
        $kelasIds = $this->getGuruKelasIds();
        $mapelIds = $this->getGuruMapelIds();

        $kelasList = Kelas::whereIn('id', $kelasIds)->orderBy('nama_kelas', 'asc')->get();
        $mapelList = MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama', 'asc')->get();

        return view('guru.materi.edit-tugas', compact('tugas', 'kelasList', 'mapelList'));
    }

    public function updateTugas(Request $request, $id)
    {
        $guru = Auth::guard('guru')->user();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);
        
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'tanggal_deadline' => 'required|date|after:today',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        // Verify guru mengajar di kelas dan mapel tersebut using trait method
        if (!$this->hasAccessToKelasMapel($request->kelas_id, $request->mapel_id)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit tugas di kelas dan mata pelajaran tersebut.');
        }

        // Prepare data
        $tugasData = [
            'judul' => $request->input('judul'),
            'deskripsi' => $request->input('deskripsi'), 
            'kelas_id' => (int) $request->input('kelas_id'),
            'mapel_id' => (int) $request->input('mapel_id'),
            'deadline' => $request->input('tanggal_deadline') . ' 23:59:59',
            'tanggal_deadline' => $request->input('tanggal_deadline'),
        ];

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
                Storage::disk('public')->delete($tugas->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $tugasData['file_path'] = $file->storeAs('tugas', $fileName, 'public');
            $tugasData['file_name'] = $file->getClientOriginalName();
        }

        try {
            $tugas->update($tugasData);
            return redirect()->route('guru.materi.index')->with('success', 'Tugas berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('Error updating tugas:', [
                'error' => $e->getMessage(),
                'data' => $tugasData,
                'tugas_id' => $tugas->id
            ]);
            return back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui tugas: ' . $e->getMessage());
        }
    }

    public function showMateri($id)
    {
        $guru = Auth::guard('guru')->user();
        $materi = Materi::with(['mapel', 'kelas', 'guru'])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);
        
        return view('guru.materi.show-materi', compact('materi'));
    }

    public function showTugas($id)
    {
        $guru = Auth::guard('guru')->user();
        $tugas = Tugas::with(['mapel', 'kelas', 'guru'])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);
        
        // Get submission count
        $submissionCount = \App\Models\TugasSiswa::where('tugas_id', $tugas->id)->count();
        
        // Get total students in the class
        $totalStudents = \App\Models\Siswa::where('kelas_id', $tugas->kelas_id)
            ->where('status', 'aktif')
            ->count();
        
        return view('guru.materi.show-tugas', compact('tugas', 'submissionCount', 'totalStudents'));
    }

    public function deleteMateri($id)
    {
        $guru = Auth::guard('guru')->user();
        $materi = Materi::where('guru_id', $guru->id)->findOrFail($id);

        // Delete file if exists
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }

    public function deleteTugas($id)
    {
        $guru = Auth::guard('guru')->user();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);

        // Delete file if exists
        if ($tugas->file_path && Storage::disk('public')->exists($tugas->file_path)) {
            Storage::disk('public')->delete($tugas->file_path);
        }

        $tugas->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function showTugasSubmissions($id)
    {
        $guru = Auth::guard('guru')->user();
        
        // Get tugas yang dibuat oleh guru ini
        $tugas = Tugas::with(['mapel', 'kelas'])
            ->where('guru_id', $guru->id)
            ->findOrFail($id);

        // Get pengumpulan tugas dengan data siswa
        $submissions = \App\Models\TugasSiswa::with(['siswa'])
            ->where('tugas_id', $tugas->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get siswa yang belum mengumpulkan
        $submittedSiswaIds = $submissions->pluck('siswa_id')->toArray();
        
        $siswaKelas = \App\Models\Siswa::where('kelas_id', $tugas->kelas_id)
            ->where('status', 'aktif')
            ->whereNotIn('id', $submittedSiswaIds)
            ->get();

        return view('guru.materi.submissions', compact('tugas', 'submissions', 'siswaKelas'));
    }
}
