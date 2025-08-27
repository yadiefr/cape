<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GuruAuthController extends Controller
{    public function showLoginForm()
    {
        // Cek semua guard yang mungkin aktif
        if (Auth::guard('web')->check() || 
            Auth::guard('guru')->check() || 
            Auth::guard('siswa')->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::guard('guru')->attempt($credentials, $remember)) {
            $user = Auth::guard('guru')->user();
            if (!$user->is_active) {
                Auth::guard('guru')->logout();
                return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'));
        }

        return redirect()->back()
            ->with('error', 'Email atau password salah.')
            ->withInput($request->except('password'));
    }    public function logout(Request $request)
    {
        // Logout dari semua guard yang mungkin aktif
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        
        if (Auth::guard('guru')->check()) {
            Auth::guard('guru')->logout();
        }
        
        if (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
        }
        
        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Start fresh session
        $request->session()->start();
        
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
