<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Helpers\HostingStorageHelper;

class AuthController extends Controller
{    /**
     * Tampilkan halaman login
     */    public function showLoginForm()
    {
        // Cek semua guard yang mungkin aktif
        if (\Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        } elseif (\Auth::guard('guru')->check()) {
            return redirect()->route('guru.dashboard');
        } elseif (\Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }
        return view('auth.login');
    }    /**
     * Proses login user (admin)
     */    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string',
        ], [
            'username.required' => 'Email/NIS/NIP wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $login = $request->input('username');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // Admin login attempt (email only)
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            if (Auth::guard('web')->attempt(['email' => $login, 'password' => $password], $remember)) {
                $user = Auth::guard('web')->user();
                if (!$user->is_active) {
                    Auth::guard('web')->logout();
                    return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
                }
                $request->session()->regenerate();
                return redirect()->intended(route('admin.dashboard'));
            }
        }

        // Guru login attempt
        if (Auth::guard('guru')->attempt(['nip' => $login, 'password' => $password], $remember) || 
            Auth::guard('guru')->attempt(['email' => $login, 'password' => $password], $remember)) {
            $user = Auth::guard('guru')->user();
            if (!$user->is_active) {
                Auth::guard('guru')->logout();
                return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('guru.dashboard'));
        }

        // Siswa login attempt
        if (Auth::guard('siswa')->attempt(['nis' => $login, 'password' => $password], $remember) || 
            Auth::guard('siswa')->attempt(['email' => $login, 'password' => $password], $remember)) {
            $user = Auth::guard('siswa')->user();
            if ($user->status !== 'aktif') {
                Auth::guard('siswa')->logout();
                return redirect()->route('login')->with('error', 'Akun Anda dinonaktifkan.');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard'));
        }

        // Log failed login attempt
        \Log::warning('Login failed for all guards', [
            'login' => $login,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->back()
            ->with('error', 'Email/NIS/NIP atau password yang Anda masukkan salah.')
            ->withInput($request->except('password'));
    }    /**
     * Logout semua guard (admin, guru, siswa)
     */
    public function logout(Request $request)
    {
        $guards = ['web', 'guru', 'siswa'];
        $loggedOutFromAnyGuard = false;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout(); // This handles remember token invalidation in DB
                $loggedOutFromAnyGuard = true;
            }
        }

        if ($loggedOutFromAnyGuard) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        
        $response = redirect()->route('login')->with('success', 'Anda berhasil logout.');
        $response->withCookie(\Illuminate\Support\Facades\Cookie::forget(config('session.cookie')));
        
        return $response;
    }

    /**
     * Tampilkan halaman lupa password
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses reset password
     */
    public function processForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Proses reset password akan diimplementasikan lebih lanjut
        // dengan email notification

        return redirect()->route('login')
            ->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Tampilkan halaman profil
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Update profil user
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->photo) {
                Storage::disk('public')->delete('profiles/' . $user->photo);
                // Also delete from hosting paths
                if (HostingStorageHelper::isHostingEnvironment()) {
                    $paths = HostingStorageHelper::getHostingPaths();
                    $hostingFile = $paths['public_storage'] . '/profiles/' . $user->photo;
                    if (file_exists($hostingFile)) {
                        @unlink($hostingFile);
                    }
                }
            }
            
            $file = $request->file('photo');
            $photoPath = HostingStorageHelper::uploadFile($file, 'profiles');
            
            if (!$photoPath) {
                return redirect()->back()->with('error', 'Gagal mengupload foto profil. Silakan coba lagi.');
            }
            
            $user->photo = basename($photoPath);
        }

        $user->save();

        return redirect()->back()
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Tampilkan halaman ubah password
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Proses ubah password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak cocok.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()
            ->with('success', 'Password berhasil diubah.');
    }
}
