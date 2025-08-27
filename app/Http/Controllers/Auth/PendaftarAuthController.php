<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PendaftarAuthController extends Controller
{    public function __construct()
    {
        $this->middleware('ppdb.open')->only(['showRegistrationForm', 'register']);
        $this->middleware('guest:pendaftar,web,guru,siswa')->only(['showLoginForm', 'login', 'showRegistrationForm', 'register']);
        $this->middleware('auth:pendaftar')->only('logout');
    }public function showRegistrationForm()
    {
        // Check if already logged in as pendaftar
        if (Auth::guard('pendaftar')->check()) {
            return redirect()->route('pendaftaran.check');
        }
        
        return view('auth.ppdb.register');
    }public function register(Request $request)
    {
        $request->validate([            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'nisn' => 'required|string|max:20|unique:users,nisn',
            'whatsapp' => 'required|string|regex:/^[0-9]{10,15}$/|unique:users,whatsapp',
        ], [            'username.unique' => 'Username sudah digunakan',
            'nisn.unique' => 'NISN sudah terdaftar',
            'whatsapp.unique' => 'Nomor WhatsApp sudah terdaftar',
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Masukkan antara 10-15 digit angka (contoh: 081234567890)',
        ]);
        
        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->nisn = $request->nisn;
        $user->whatsapp = $request->whatsapp;
        $user->password = $request->nisn; // Use NISN as password
        $user->role = 'pendaftar';
        $user->is_active = true;
        $user->save();

        Auth::guard('pendaftar')->login($user);

        return redirect()
            ->route('pendaftaran.form')
            ->with('success', 'Akun berhasil dibuat. Silakan lengkapi formulir pendaftaran PPDB.');
    }    public function showLoginForm()
    {
        // Check if already logged in as pendaftar
        if (Auth::guard('pendaftar')->check()) {
            return redirect()->route('pendaftaran.check');
        }
        
        return view('auth.ppdb.login');
    }public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Masukkan minimal 10 digit dan maksimal 15 digit, diawali dengan 0. misal: 08123456789',
        ]);        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];

        if (Auth::guard('pendaftar')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('pendaftar')->user();
            $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)->first();

            if (!$pendaftaran) {
                return redirect()->route('pendaftaran.form');
            }

            return redirect()->route('pendaftaran.check');
        }

        return back()
            ->withErrors([                'username' => 'Username atau password salah.',
            ])
            ->withInput($request->only('username'));
    }    public function logout(Request $request)
    {
        Auth::guard('pendaftar')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pendaftar.login')
            ->with('success', 'Anda telah berhasil logout dari sistem PPDB.');
    }
}
