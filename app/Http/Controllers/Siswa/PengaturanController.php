<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PengaturanController extends Controller
{
    public function index()
    {
        $siswa = Auth::guard('siswa')->user();
        return view('siswa.pengaturan.index', compact('siswa'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'password_lama' => 'required_with:password_baru',
            'password_baru' => 'nullable|min:6|confirmed',
            'email' => 'nullable|email',
        ]);

        $siswa = Auth::guard('siswa')->user();

        // Update password if provided
        if ($request->filled('password_baru')) {
            if (!Hash::check($request->password_lama, $siswa->password)) {
                return back()->withErrors(['password_lama' => 'Password lama tidak sesuai']);
            }
            
            $siswa->password = Hash::make($request->password_baru);
        }

        // Update other fields
        if ($request->filled('email')) {
            $siswa->email = $request->email;
        }

        $siswa->save();

        return back()->with('success', 'Pengaturan berhasil diperbarui');
    }
}
