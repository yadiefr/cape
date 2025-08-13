<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ruangan = Ruangan::ordered()->get();
        
        return view('admin.ujian.pengaturan.ruangan.index', compact('ruangan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ujian.pengaturan.ruangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kode_ruangan' => 'required|string|max:20|unique:ruangan,kode_ruangan',
            'kapasitas' => 'required|integer|min:1|max:1000',
            'lokasi' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,terpakai,maintenance'
        ]);

        $validated['is_active'] = true;

        Ruangan::create($validated);

        return redirect()->route('admin.ruangan.index')
                        ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ruangan $ruangan)
    {
        $ruangan->load('kelas');
        return view('admin.ujian.pengaturan.ruangan.show', compact('ruangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ruangan $ruangan)
    {
        return view('admin.ujian.pengaturan.ruangan.edit', compact('ruangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kode_ruangan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ruangan', 'kode_ruangan')->ignore($ruangan->id)
            ],
            'kapasitas' => 'required|integer|min:1|max:1000',
            'lokasi' => 'nullable|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:tersedia,terpakai,maintenance'
        ]);

        $ruangan->update($validated);

        return redirect()->route('admin.ruangan.index')
                        ->with('success', 'Ruangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ruangan $ruangan)
    {
        // Check if room is being used
        if ($ruangan->kelas()->count() > 0) {
            return redirect()->route('admin.ruangan.index')
                            ->with('error', 'Ruangan tidak dapat dihapus karena masih digunakan oleh kelas.');
        }

        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')
                        ->with('success', 'Ruangan berhasil dihapus.');
    }

    /**
     * Toggle room status
     */
    public function toggle(Ruangan $ruangan)
    {
        $ruangan->update([
            'is_active' => !$ruangan->is_active
        ]);

        $status = $ruangan->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return response()->json([
            'success' => true,
            'message' => "Ruangan berhasil {$status}.",
            'is_active' => $ruangan->is_active
        ]);
    }

    /**
     * Get room data for AJAX
     */
    public function getRoomData(Ruangan $ruangan)
    {
        return response()->json([
            'success' => true,
            'data' => $ruangan->load('kelas')
        ]);
    }

    /**
     * Manage room-class assignments
     */
    public function manageKelas(Ruangan $ruangan)
    {
        $ruangan->load('kelas');
        $availableKelas = Kelas::whereNotIn('id', $ruangan->kelas->pluck('id'))->get();
        
        return view('admin.ujian.pengaturan.ruangan.manage-kelas', compact('ruangan', 'availableKelas'));
    }

    /**
     * Assign class to room
     */
    public function assignKelas(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'kapasitas_maksimal' => 'nullable|integer|min:1|max:' . $ruangan->kapasitas
        ]);

        // Check if class is already assigned
        if ($ruangan->kelas()->where('kelas_id', $validated['kelas_id'])->exists()) {
            return redirect()->back()->with('error', 'Kelas sudah ditugaskan ke ruangan ini.');
        }

        $ruangan->kelas()->attach($validated['kelas_id'], [
            'kapasitas_maksimal' => $validated['kapasitas_maksimal'] ?? $ruangan->kapasitas,
            'is_active' => true
        ]);

        return redirect()->back()->with('success', 'Kelas berhasil ditugaskan ke ruangan.');
    }

    /**
     * Remove class from room
     */
    public function removeKelas(Ruangan $ruangan, Kelas $kelas)
    {
        $ruangan->kelas()->detach($kelas->id);

        return redirect()->back()->with('success', 'Kelas berhasil dihapus dari ruangan.');
    }

    /**
     * Update room-class assignment
     */
    public function updateKelas(Request $request, Ruangan $ruangan, Kelas $kelas)
    {
        $validated = $request->validate([
            'kapasitas_maksimal' => 'nullable|integer|min:1|max:' . $ruangan->kapasitas
        ]);

        $ruangan->kelas()->updateExistingPivot($kelas->id, [
            'kapasitas_maksimal' => $validated['kapasitas_maksimal'] ?? $ruangan->kapasitas
        ]);

        return redirect()->back()->with('success', 'Pengaturan kelas berhasil diperbarui.');
    }

    /**
     * Toggle room-class assignment status
     */
    public function toggleKelas(Ruangan $ruangan, Kelas $kelas)
    {
        $pivot = $ruangan->kelas()->where('kelas_id', $kelas->id)->first()->pivot;
        
        $ruangan->kelas()->updateExistingPivot($kelas->id, [
            'is_active' => !$pivot->is_active
        ]);

        $status = !$pivot->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Penugasan kelas berhasil {$status}."
        ]);
    }
}
