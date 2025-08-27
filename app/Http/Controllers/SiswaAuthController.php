<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SiswaAuthController extends Controller
{
    public function showLoginForm()
    {
        // Cek semua guard yang mungkin aktif
        Log::info('Checking active guards', [
            'web' => Auth::guard('web')->check(),
            'guru' => Auth::guard('guru')->check(),
            'siswa' => Auth::guard('siswa')->check()
        ]);
        
        if (Auth::guard('web')->check() || 
            Auth::guard('guru')->check() || 
            Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }
        // Redirect to the main login page that handles all user types
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string',
        ], [
            'username.required' => 'NIS atau email wajib diisi',
        ]);

        if ($validator->fails()) {
            Log::warning('Student login validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $login = $request->input('username');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // Cek apakah input adalah email atau nis
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $password];
        } else {
            $credentials = ['nis' => $login, 'password' => $password];
        }

        Log::info('Student login attempt', [
            'credentials' => array_merge($credentials, ['password' => '[REDACTED]']),
            'remember' => $remember
        ]);

        if (Auth::guard('siswa')->attempt($credentials, $remember)) {
            $user = Auth::guard('siswa')->user();
            
            // Check if student is active
            if ($user->status !== 'aktif') {
                Auth::guard('siswa')->logout();
                Log::warning('Student login blocked - inactive status', [
                    'user_id' => $user->id,
                    'nis' => $user->nis,
                    'status' => $user->status
                ]);
                return redirect()->back()
                    ->with('error', 'Akun Anda dinonaktifkan. Silakan hubungi administrator.')
                    ->withInput($request->except('password'));
            }
            
            Log::info('Student login successful', [
                'user_id' => $user->id,
                'nis' => $user->nis,
                'name' => $user->nama_lengkap,
                'status' => $user->status
            ]);
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard'));
        }

        Log::warning('Student login failed', [
            'credentials' => array_merge($credentials, ['password' => '[REDACTED]'])
        ]);

        return redirect()->back()
            ->with('error', 'NIS/email atau password salah.')
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
