<?php

namespace App\Http\Controllers\Admin\Ujian;

use App\Http\Controllers\Controller;
use App\Models\JenisUjian;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JenisUjianController extends Controller
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
        $jenisUjian = JenisUjian::ordered()->get();
        
        return view('admin.ujian.pengaturan.jenis-ujian.index', compact('jenisUjian'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.ujian.pengaturan.jenis-ujian.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:jenis_ujian,nama',
            'kode' => 'required|string|max:10|unique:jenis_ujian,kode',
            'deskripsi' => 'nullable|string|max:500',
            'durasi_default' => 'nullable|integer|min:1|max:600',
            'urutan' => 'nullable|integer|min:1|max:999',
            'is_active' => 'nullable|boolean'
        ]);

        // Set default urutan jika tidak diisi
        if (!$request->urutan) {
            $maxUrutan = JenisUjian::max('urutan') ?? 0;
            $request->merge(['urutan' => $maxUrutan + 1]);
        }

        JenisUjian::create($request->all());

        return redirect()
            ->route('admin.ujian.pengaturan.jenis-ujian.index')
            ->with('success', 'Jenis ujian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisUjian $jenisUjian)
    {
        return view('admin.ujian.pengaturan.jenis-ujian.show', compact('jenisUjian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisUjian $jenisUjian)
    {
        return view('admin.ujian.pengaturan.jenis-ujian.edit', compact('jenisUjian'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisUjian $jenisUjian)
    {
        $request->validate([
            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('jenis_ujian')->ignore($jenisUjian->id)
            ],
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('jenis_ujian')->ignore($jenisUjian->id)
            ],
            'deskripsi' => 'nullable|string|max:500',
            'durasi_default' => 'nullable|integer|min:1|max:600',
            'urutan' => 'nullable|integer|min:1|max:999',
            'is_active' => 'nullable|boolean'
        ]);

        $jenisUjian->update($request->all());

        return redirect()
            ->route('admin.ujian.pengaturan.jenis-ujian.index')
            ->with('success', 'Jenis ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisUjian $jenisUjian)
    {
        // Check if jenis ujian is being used
        if ($jenisUjian->jadwalUjian()->exists()) {
            return redirect()
                ->route('admin.ujian.pengaturan.jenis-ujian.index')
                ->with('error', 'Jenis ujian tidak dapat dihapus karena masih digunakan.');
        }

        $jenisUjian->delete();

        return redirect()
            ->route('admin.ujian.pengaturan.jenis-ujian.index')
            ->with('success', 'Jenis ujian berhasil dihapus.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(JenisUjian $jenisUjian)
    {
        $jenisUjian->update([
            'is_active' => !$jenisUjian->is_active
        ]);

        $status = $jenisUjian->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.ujian.pengaturan.jenis-ujian.index')
            ->with('success', "Jenis ujian berhasil {$status}.");
    }
}
