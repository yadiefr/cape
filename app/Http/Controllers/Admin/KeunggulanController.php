<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keunggulan;
use Illuminate\Http\Request;

class KeunggulanController extends Controller
{
    public function index()
    {
        $keunggulan = Keunggulan::latest()->get();
        return view('admin.keunggulan.index', compact('keunggulan'));
    }

    public function create()
    {
        return view('admin.keunggulan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'ikon' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        Keunggulan::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'ikon' => $request->ikon,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.keunggulan.index')->with('success', 'Keunggulan berhasil ditambahkan.');
    }

    public function edit(Keunggulan $keunggulan)
    {
        return view('admin.keunggulan.edit', compact('keunggulan'));
    }

    public function update(Request $request, Keunggulan $keunggulan)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'ikon' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $keunggulan->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'ikon' => $request->ikon,
            'is_active' => $request->has('is_active'),
        ]);
        return redirect()->route('admin.keunggulan.index')->with('success', 'Keunggulan berhasil diupdate.');
    }

    public function destroy(Keunggulan $keunggulan)
    {
        $keunggulan->delete();
        return redirect()->route('admin.keunggulan.index')->with('success', 'Keunggulan berhasil dihapus.');
    }

    public function toggleStatus(Keunggulan $keunggulan)
    {
        $keunggulan->is_active = !$keunggulan->is_active;
        $keunggulan->save();
        return redirect()->route('admin.keunggulan.index')->with('success', 'Status keunggulan berhasil diubah.');
    }
} 