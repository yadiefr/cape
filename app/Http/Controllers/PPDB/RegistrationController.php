<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Settings;

class RegistrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('ppdb.open');
    }

    public function showRegistrationForm()
    {
        return view('ppdb.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_telp' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create initial registration
            $pendaftaran = Pendaftaran::create([
                'nama_lengkap' => $request->nama_lengkap,
                'telepon' => $request->no_telp,
                'password' => Hash::make($request->password),
                'nomor_pendaftaran' => 'REG-' . date('Y') . Str::padLeft(Pendaftaran::count() + 1, 4, '0'),
                'status' => 'menunggu',
                'tahun_ajaran' => Settings::getValue('ppdb_year', date('Y').'/'.((int)date('Y')+1))
            ]);

            // Login the user
            auth('pendaftar')->login($pendaftaran);

            // Redirect to the form completion page
            return redirect()
                ->route('pendaftaran.form')
                ->with('success', 'Pendaftaran awal berhasil! Silakan lengkapi data pendaftaran Anda.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat melakukan pendaftaran. Silakan coba lagi.');
        }
    }
}
