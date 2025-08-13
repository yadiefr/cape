<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Storage;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumuman = Pengumuman::orderBy('tanggal_mulai', 'desc')->get();
        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_role' => 'required|in:all,admin,guru,siswa',
            'show_on_homepage' => 'boolean',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Get the author ID and type based on the authenticated guard
        $authorId = null;
        $authorType = null;
        
        if (auth()->guard('web')->check()) {
            $authorId = auth()->guard('web')->id();
            $authorType = 'App\Models\Admin';
        } elseif (auth()->guard('guru')->check()) {
            $authorId = auth()->guard('guru')->id();
            $authorType = 'App\Models\Guru';
        }

        if (!$authorId || !$authorType) {
            return redirect()->back()->with('error', 'Anda harus login sebagai admin atau guru untuk membuat pengumuman.');
        }

        // Validasi target role berdasarkan user yang login
        if (auth()->guard('guru')->check() && $request->target_role === 'admin') {
            return redirect()->back()
                ->with('error', 'Guru tidak dapat membuat pengumuman untuk admin.')
                ->withInput();
        }

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_role' => $request->target_role,
            'is_active' => true,
            'author_id' => $authorId,
            'author_type' => $authorType,
            'show_on_homepage' => $request->boolean('show_on_homepage')
        ];

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $data['lampiran'] = $request->file('foto')->store('pengumuman', 'public');
        }

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'target_role' => 'required|in:all,admin,guru,siswa',
            'is_active' => 'boolean',
            'show_on_homepage' => 'boolean',
            'foto' => 'nullable|image|max:2048',
        ]);

        // Validasi target role berdasarkan user yang login
        if (auth()->guard('guru')->check() && $request->target_role === 'admin') {
            return redirect()->back()
                ->with('error', 'Guru tidak dapat membuat pengumuman untuk admin.')
                ->withInput();
        }

        $pengumuman = Pengumuman::findOrFail($id);

        $data = [
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'target_role' => $request->target_role,
            'is_active' => $request->boolean('is_active'),
            'show_on_homepage' => $request->boolean('show_on_homepage')
        ];

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($pengumuman->lampiran) {
                Storage::disk('public')->delete($pengumuman->lampiran);
            }
            $data['lampiran'] = $request->file('foto')->store('pengumuman', 'public');
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus');
    }
} 