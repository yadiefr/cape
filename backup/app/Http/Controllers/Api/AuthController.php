<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $request->validate([
                'nis' => 'required|string',
                'password' => 'required|string',
                'device_info' => 'nullable|string'
            ]);

            // Cari siswa berdasarkan NIS
            $siswa = Siswa::where('nis', $request->nis)
                          ->where('status', 'aktif')
                          ->first();

            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'NIS tidak ditemukan atau siswa tidak aktif'
                ], 401);
            }

            // Validasi password
            if (!Hash::check($request->password, $siswa->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password salah'
                ], 401);
            }

            // Buat token (dalam implementasi nyata, gunakan Laravel Sanctum)
            $token = base64_encode($siswa->id . '|' . time() . '|' . $request->ip());

            return response()->json([
                'success' => true,
                'message' => 'Login berhasil',
                'data' => [
                    'token' => $token,
                    'siswa' => [
                        'id' => $siswa->id,
                        'nis' => $siswa->nis,
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'kelas' => $siswa->kelas->nama_kelas ?? null,
                        'jurusan' => $siswa->jurusan->nama_jurusan ?? null,
                        'foto' => $siswa->foto ? asset('storage/' . $siswa->foto) : null,
                    ]
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        // Dalam implementasi nyata, invalidate token di database
        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ]);
    }

    public function profile(Request $request)
    {
        try {
            $siswa = $this->getSiswaFromToken($request);
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $siswa->id,
                    'nis' => $siswa->nis,
                    'nisn' => $siswa->nisn,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'jenis_kelamin' => $siswa->jenis_kelamin,
                    'tempat_lahir' => $siswa->tempat_lahir,
                    'tanggal_lahir' => $siswa->tanggal_lahir,
                    'agama' => $siswa->agama,
                    'alamat' => $siswa->alamat,
                    'telepon' => $siswa->telepon,
                    'email' => $siswa->email,
                    'kelas' => [
                        'id' => $siswa->kelas->id ?? null,
                        'nama' => $siswa->kelas->nama_kelas ?? null,
                        'tingkat' => $siswa->kelas->tingkat ?? null,
                    ],
                    'jurusan' => [
                        'id' => $siswa->jurusan->id ?? null,
                        'nama' => $siswa->jurusan->nama_jurusan ?? null,
                        'kode' => $siswa->jurusan->kode_jurusan ?? null,
                    ],
                    'tahun_masuk' => $siswa->tahun_masuk,
                    'status' => $siswa->status,
                    'foto' => $siswa->foto ? asset('storage/' . $siswa->foto) : null,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password_lama' => 'required|string',
                'password_baru' => 'required|string|min:8|confirmed',
            ]);

            $siswa = $this->getSiswaFromToken($request);
            
            if (!$siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token tidak valid'
                ], 401);
            }

            // Validasi password lama
            if (!Hash::check($request->password_lama, $siswa->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama salah'
                ], 400);
            }

            // Update password
            $siswa->update([
                'password' => Hash::make($request->password_baru)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getSiswaFromToken(Request $request)
    {
        $token = $request->bearerToken();
        
        if (!$token) {
            return null;
        }

        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);
            
            if (count($parts) !== 3) {
                return null;
            }

            $siswaId = $parts[0];
            $timestamp = $parts[1];
            $ip = $parts[2];

            // Cek apakah token sudah expired (24 jam)
            if (time() - $timestamp > 86400) {
                return null;
            }

            return Siswa::find($siswaId);
            
        } catch (\Exception $e) {
            return null;
        }
    }
}
