<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pengumuman;
use App\Models\Nilai;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalGuru = \App\Models\Guru::count();
        $totalSiswa = \App\Models\Siswa::count();
        $totalAdmin = \App\Models\Admin::count();
        $totalPengumuman = Pengumuman::count();
        $totalNilai = Nilai::count();

        return view('admin.dashboard', compact('totalGuru', 'totalSiswa', 'totalAdmin', 'totalPengumuman', 'totalNilai'));
    }

    public function manageUsers()
    {
        $gurus = \App\Models\Guru::all();
        $siswas = \App\Models\Siswa::all();
        $admins = \App\Models\Admin::all();
        return view('admin.manage-users', compact('gurus', 'siswas', 'admins'));
    }

    public function managePengumuman()
    {
        $pengumuman = Pengumuman::all();
        return view('admin.manage-pengumuman', compact('pengumuman'));
    }

    public function manageNilai()
    {
        $nilai = Nilai::all();
        return view('admin.manage-nilai', compact('nilai'));
    }

    public function createPengumuman()
    {
        return view('admin.create-pengumuman');
    }

    public function storePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
        ]);

        return redirect()->route('admin.pengumuman')->with('success', 'Pengumuman berhasil ditambahkan!');
    }
}
