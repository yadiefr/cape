<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('siswa.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::where('nis', $request->nis)
                      ->where('status', 'aktif')
                      ->first();

        if (!$siswa) {
            return back()->withErrors([
                'nis' => 'NIS tidak ditemukan atau siswa tidak aktif'
            ])->withInput();
        }

        // Validasi password
        if (!Hash::check($request->password, $siswa->password)) {
            return back()->withErrors([
                'password' => 'Password salah'
            ])->withInput();
        }

        // Login siswa
        Auth::guard('siswa')->login($siswa);

        return redirect()->route('siswa.dashboard')
                        ->with('success', 'Login berhasil! Selamat datang ' . $siswa->nama_lengkap);
    }

    public function logout()
    {
        Auth::guard('siswa')->logout();
        return redirect()->route('siswa.login')
                        ->with('success', 'Logout berhasil');
    }
}
