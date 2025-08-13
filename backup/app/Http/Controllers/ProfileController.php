<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Check guards in order and get the current user
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('guru')->check()) {
            $user = Auth::guard('guru')->user();
        } elseif (Auth::guard('siswa')->check()) {
            $user = Auth::guard('siswa')->user();
        } else {
            return redirect()->route('login');
        }

        // Return the appropriate view based on user role
        switch($user->role) {
            case 'admin':
                return view('admin.profile.index', ['user' => $user]);
            case 'guru':
                // Get additional data for guru profile
                $totalKelas = \App\Models\JadwalPelajaran::where('guru_id', $user->id)
                    ->distinct('kelas_id')
                    ->count('kelas_id');
                $totalMapel = \App\Models\JadwalPelajaran::where('guru_id', $user->id)
                    ->distinct('mapel_id')
                    ->count('mapel_id');
                    
                return view('guru.profile.index', [
                    'user' => $user,
                    'totalKelas' => $totalKelas,
                    'totalMapel' => $totalMapel
                ]);
            case 'siswa':
                return view('siswa.profile.index', ['siswa' => $user->siswa]);
            default:
                return view('profile.index', ['user' => $user]);
        }
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Check guards in order and get the current user
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('guru')->check()) {
            $user = Auth::guard('guru')->user();
        } elseif (Auth::guard('siswa')->check()) {
            $user = Auth::guard('siswa')->user();
        } else {
            return redirect()->route('login');
        }

        // Return the appropriate view based on user role
        switch($user->role) {
            case 'admin':
                return view('admin.profile.edit', ['user' => $user]);
            case 'guru':
                return view('guru.profile.edit', ['user' => $user]);
            case 'siswa':
                return view('siswa.profile.edit', ['siswa' => $user->siswa]);
            default:
                return view('profile.edit', ['user' => $user]);
        }
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Check guards in order and get the current user
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
        } elseif (Auth::guard('guru')->check()) {
            $user = Auth::guard('guru')->user();
        } elseif (Auth::guard('siswa')->check()) {
            $user = Auth::guard('siswa')->user();
        } else {
            return redirect()->route('login');
        }
        
        if ($user->role === 'siswa') {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('siswa')->ignore($user->siswa->id)],
                'telepon' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string'],
                'foto' => ['nullable', 'image', 'max:2048'], // Max 2MB
                'password' => ['nullable', 'min:8', 'confirmed'],
            ]);
            
            // Update student info
            $siswa = $user->siswa;
            $siswa->email = $validated['email'];
            $siswa->telepon = $validated['telepon'];
            $siswa->alamat = $validated['alamat'];
            
            // Handle photo upload for students
            if ($request->hasFile('foto')) {
                if ($siswa->foto) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $path = $request->file('foto')->store('siswa', 'public');
                $siswa->foto = $path;
            }
            
            // Update student password if provided
            if ($request->filled('password')) {
                $siswa->password = Hash::make($validated['password']);
            }
            
            $siswa->save();
            
            return redirect()->route('siswa.profile.index')->with('success', 'Profile updated successfully.');
        }
        
        // Handle different user types with proper field mapping
        if ($user->role === 'guru') {
            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('guru')->ignore($user->id)],
                'no_hp' => ['nullable', 'string', 'max:20'],
                'alamat' => ['nullable', 'string'],
                'jenis_kelamin' => ['nullable', 'string', 'in:L,P'],
                'foto' => ['nullable', 'image', 'max:2048'], // Max 2MB
                'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                    if ($value && !Hash::check($value, $user->password)) {
                        $fail('The current password is incorrect.');
                    }
                }],
                'password' => ['nullable', 'min:8', 'confirmed'],
            ]);
            
            // Update guru info with correct field names
            $user->nama = $validated['nama'];
            $user->email = $validated['email'];
            $user->no_hp = $validated['no_hp'] ?? $user->no_hp;
            $user->alamat = $validated['alamat'] ?? $user->alamat;
            $user->jenis_kelamin = $validated['jenis_kelamin'] ?? $user->jenis_kelamin;
            
            // Handle profile photo for guru
            if ($request->hasFile('foto')) {
                if ($user->foto) {
                    Storage::disk('public')->delete($user->foto);
                }
                $path = $request->file('foto')->store('guru', 'public');
                $user->foto = $path;
            }
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            
            return redirect()->route('guru.profile.index')->with('success', 'Profile updated successfully.');
        }
        
        // For admin
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'current_password' => ['nullable', 'required_with:new_password', function ($attribute, $value, $fail) use ($user) {
                if ($value && !Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);
        
        // Update basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->address = $validated['address'] ?? $user->address;
        
        // Handle profile photo
        if ($request->hasFile('photo')) {
            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }
        
        // Update password if provided
        if ($request->filled('new_password')) {
            $user->password = Hash::make($validated['new_password']);
        }
        
        $user->save();
        
        $route = match($user->role) {
            'admin' => 'admin.profile.index',
            'guru' => 'guru.profile.index',
            'siswa' => 'siswa.profile.index',
            default => 'profile.index',
        };
        return redirect()->route($route)->with('success', 'Profile updated successfully.');
    }
}
