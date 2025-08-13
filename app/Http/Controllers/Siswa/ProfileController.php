<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display student's profile
     */    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.profile.index', compact('siswa'));
    }

    /**
     * Show the form for editing student's profile
     */    public function edit()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.profile.edit', compact('siswa'));
    }

    /**
     * Update student's profile
     */    public function update(Request $request)
    {
        $siswa = Auth::guard('siswa')->user();

        $request->validate([
            'email' => 'required|email|unique:siswa,email,'.$siswa->id,
            'telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'password' => 'nullable|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Handle foto upload if provided
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $fotoPath = $request->file('foto')->store('siswa/foto', 'public');
                $siswa->foto = $fotoPath;
            }

            $siswa->email = $request->email;
            $siswa->telepon = $request->telepon;
            $siswa->alamat = $request->alamat;

            if ($request->filled('password')) {
                $siswa->password = Hash::make($request->password);
            }

            $siswa->save();

            return redirect()->route('siswa.profile.index')
                ->with('success', 'Profil berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage())
                ->withInput();
        }
    }
}
