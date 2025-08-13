<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PendaftarAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('ppdb.open')->except(['showLoginForm', 'login', 'showForgotPasswordForm', 'forgotPassword', 'logout']);
    }

    public function showRegistrationForm()
    {
        return view('auth.pendaftar-register');
    }

    public function showLoginForm()
    {
        return view('auth.pendaftar-login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nisn' => 'required|string|size:10|unique:users',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nisn' => $request->nisn,
            'role' => 'pendaftar'
        ]);

        Auth::guard('pendaftar')->login($user);

        // After registration, redirect to the PPDB form
        return redirect()
            ->route('pendaftaran.form')
            ->with('success', 'Akun berhasil dibuat. Silakan lengkapi formulir pendaftaran PPDB.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        if (Auth::guard('pendaftar')->attempt($validator->validated())) {
            $request->session()->regenerate();

            // Check if user has submitted PPDB form
            $user = Auth::guard('pendaftar')->user();
            $pendaftaran = \App\Models\Pendaftaran::where('user_id', $user->id)->first();

            if (!$pendaftaran) {
                return redirect()->route('pendaftaran.form');
            }

            return redirect()->route('pendaftar.dashboard');
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput($request->except('password'));
    }    public function logout(Request $request)
    {
        Auth::guard('pendaftar')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('pendaftar.login');
    }
}
