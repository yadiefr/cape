<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Carbon\Carbon;

class SiswaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Persiapkan data dengan cleaning untuk handle empty values
            $cleanedRow = [];
            foreach ($row->toArray() as $key => $value) {
                // Convert empty strings to null
                $cleanedRow[$key] = trim($value) === '' ? null : trim($value);
            }

            // Validasi data yang lebih fleksibel
            $validator = Validator::make($cleanedRow, [
                'nis' => 'required|unique:siswa,nis',
                'nisn' => 'required|unique:siswa,nisn',
                'nama_lengkap' => 'required',
                'jenis_kelamin' => 'nullable|in:L,P',
                'tempat_lahir' => 'nullable',
                'tanggal_lahir' => 'nullable|date', // Changed to nullable
                'agama' => 'nullable',
                'alamat' => 'nullable',
                'telepon' => 'nullable',
                'email' => 'nullable|email|unique:siswa,email',
                'kelas_id' => 'nullable|exists:kelas,id',
                'jurusan_id' => 'nullable|exists:jurusan,id',
                'status' => 'nullable|in:aktif,tidak_aktif',
                'nama_ayah' => 'nullable',
                'nama_ibu' => 'nullable',
                'pekerjaan_ayah' => 'nullable',
                'pekerjaan_ibu' => 'nullable',
                'telepon_orangtua' => 'nullable',
                'alamat_orangtua' => 'nullable',
            ]);

            if ($validator->fails()) {
                throw new \Exception('Data tidak valid: ' . $validator->errors()->first());
            }

            // Cek apakah siswa sudah ada
            $siswa = Siswa::where('nis', $cleanedRow['nis'])
                         ->orWhere('nisn', $cleanedRow['nisn'])
                         ->first();

            if ($siswa) {
                throw new \Exception('Siswa dengan NIS ' . $cleanedRow['nis'] . ' atau NISN ' . $cleanedRow['nisn'] . ' sudah terdaftar');
            }

            // Parse tanggal lahir untuk database (convert ke YYYY-MM-DD)
            $tanggal_lahir_db = null;
            if (!empty($cleanedRow['tanggal_lahir']) && $cleanedRow['tanggal_lahir'] !== null) {
                try {
                    $dateString = trim($cleanedRow['tanggal_lahir']);
                    
                    // Try different date formats
                    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateString)) {
                        // DD-MM-YYYY format
                        $tanggal_lahir_db = Carbon::createFromFormat('d-m-Y', $dateString)->format('Y-m-d');
                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                        // YYYY-MM-DD format
                        $tanggal_lahir_db = Carbon::parse($dateString)->format('Y-m-d');
                    } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateString)) {
                        // DD/MM/YYYY format
                        $tanggal_lahir_db = Carbon::createFromFormat('d/m/Y', $dateString)->format('Y-m-d');
                    } else {
                        // Try to parse with Carbon (fallback)
                        $tanggal_lahir_db = Carbon::parse($dateString)->format('Y-m-d');
                    }
                } catch (\Exception $e) {
                    $tanggal_lahir_db = null; // Set to null if invalid
                }
            }

            // Tentukan password berdasarkan tanggal lahir atau default
            $password = 'password'; // Default password
            if (!empty($cleanedRow['tanggal_lahir']) && $cleanedRow['tanggal_lahir'] !== null) {
                try {
                    $dateString = trim($cleanedRow['tanggal_lahir']);
                    $tanggal = null;
                    
                    // Try different date formats
                    if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $dateString)) {
                        // DD-MM-YYYY format
                        $tanggal = Carbon::createFromFormat('d-m-Y', $dateString);
                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateString)) {
                        // YYYY-MM-DD format
                        $tanggal = Carbon::parse($dateString);
                    } elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dateString)) {
                        // DD/MM/YYYY format
                        $tanggal = Carbon::createFromFormat('d/m/Y', $dateString);
                    } else {
                        // Try to parse with Carbon (fallback)
                        $tanggal = Carbon::parse($dateString);
                    }
                    
                    // Cek apakah tanggal adalah default 01/01/2000
                    if ($tanggal && $tanggal->format('Y-m-d') !== '2000-01-01') {
                        $password = $tanggal->format('dmY'); // Format: DDMMYYYY
                    }
                    // Jika tanggal adalah 01/01/2000, tetap gunakan password default
                } catch (\Exception $e) {
                    $password = 'password'; // Fallback jika tanggal tidak valid
                }
            }

            // Buat data siswa
            Siswa::create([
                'nis' => $cleanedRow['nis'],
                'nisn' => $cleanedRow['nisn'],
                'nama_lengkap' => $cleanedRow['nama_lengkap'],
                'jenis_kelamin' => $cleanedRow['jenis_kelamin'],
                'tempat_lahir' => $cleanedRow['tempat_lahir'],
                'tanggal_lahir' => $tanggal_lahir_db, // Use parsed date for database
                'agama' => $cleanedRow['agama'],
                'alamat' => $cleanedRow['alamat'],
                'telepon' => $cleanedRow['telepon'],
                'email' => $cleanedRow['email'] ?: null,
                'kelas_id' => $cleanedRow['kelas_id'],
                'jurusan_id' => $cleanedRow['jurusan_id'],
                'status' => $cleanedRow['status'] ?: 'aktif', // Default to aktif
                'nama_ayah' => $cleanedRow['nama_ayah'],
                'nama_ibu' => $cleanedRow['nama_ibu'],
                'pekerjaan_ayah' => $cleanedRow['pekerjaan_ayah'],
                'pekerjaan_ibu' => $cleanedRow['pekerjaan_ibu'],
                'telepon_orangtua' => $cleanedRow['telepon_orangtua'],
                'alamat_orangtua' => $cleanedRow['alamat_orangtua'],
                'password' => bcrypt($password), // Hash password
            ]);
        }
    }
}
