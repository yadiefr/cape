<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function indexSiswa()
    {
        $pengumuman = \App\Models\Pengumuman::where(function($q) {
            $q->where('target_role', 'siswa')
              ->orWhere('target_role', 'all');
        })->orderBy('tanggal_mulai', 'desc')->get();

        return view('siswa.pengumuman.index', compact('pengumuman'));
    }

    public function showSiswa(string $id)
    {
        $pengumuman = \App\Models\Pengumuman::findOrFail($id);

        // Check if the announcement is for siswa or all
        if (!in_array($pengumuman->target_role, ['siswa', 'all'])) {
            abort(403, 'Unauthorized - Pengumuman ini tidak untuk siswa');
        }

        return view('siswa.pengumuman.show', compact('pengumuman'));
    }

    public function indexGuru()
    {
        $pengumuman = \App\Models\Pengumuman::where(function($q) {
            $q->where('target_role', 'guru')
              ->orWhere('target_role', 'all');
        })->where('is_active', true)
          ->orderBy('tanggal_mulai', 'desc')
          ->get();

        return view('guru.pengumuman.index', compact('pengumuman'));
    }

    public function showGuru(string $id)
    {
        $pengumuman = \App\Models\Pengumuman::findOrFail($id);

        // Check if the announcement is for guru or all
        if (!in_array($pengumuman->target_role, ['guru', 'all'])) {
            abort(403, 'Unauthorized - Pengumuman ini tidak untuk guru');
        }

        return view('guru.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
