<?php

namespace App\Imports;

use App\Models\Guru;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Hash;

class GuruImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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

            // Pre-process boolean fields to handle string representations
            if (isset($cleanedRow['is_wali_kelas'])) {
                $cleanedRow['is_wali_kelas'] = $this->convertToBoolean($cleanedRow['is_wali_kelas']);
            }
            if (isset($cleanedRow['is_active'])) {
                $cleanedRow['is_active'] = $this->convertToBoolean($cleanedRow['is_active']);
            }

            // Validasi data
            $validator = Validator::make($cleanedRow, [
                'nip' => 'nullable|unique:guru,nip',
                'nama' => 'required|string|max:255',
                'email' => 'nullable|email|unique:guru,email',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string',
                'is_wali_kelas' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                $identifier = $cleanedRow['nip'] ?: $cleanedRow['nama'] ?? 'tidak diketahui';
                throw new \Exception('Data tidak valid pada baris dengan identifier ' . $identifier . ': ' . $validator->errors()->first());
            }

            // Buat data guru
            $guruData = [
                'nip' => $cleanedRow['nip'] ?: null,
                'nama' => $cleanedRow['nama'],
                'email' => $cleanedRow['email'],
                'password' => Hash::make($cleanedRow['password'] ?? $cleanedRow['nip'] ?? strtolower(str_replace(' ', '', $cleanedRow['nama']))), // Default password adalah NIP atau nama
                'jenis_kelamin' => $cleanedRow['jenis_kelamin'] ?? null,
                'alamat' => $cleanedRow['alamat'] ?? null,
                'no_hp' => $cleanedRow['no_hp'] ?? null,
                'is_wali_kelas' => $cleanedRow['is_wali_kelas'] ?? false,
                'is_active' => $cleanedRow['is_active'] ?? true,
                'foto' => null, // Foto akan diupload terpisah
            ];

            try {
                Guru::create($guruData);
            } catch (\Exception $e) {
                $identifier = $cleanedRow['nip'] ?: $cleanedRow['nama'] ?? 'tidak diketahui';
                throw new \Exception('Gagal menyimpan data guru dengan identifier ' . $identifier . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Mendapatkan template header untuk export
     */
    public static function getTemplateHeaders()
    {
        return [
            'nip',
            'nama', 
            'email',
            'password',
            'jenis_kelamin',
            'alamat',
            'no_hp',
            'is_wali_kelas',
            'is_active'
        ];
    }

    /**
     * Mendapatkan contoh data untuk template
     */
    public static function getTemplateExample()
    {
        return [
            [
                'nip' => '123456789',
                'nama' => 'Ahmad Susanto, S.Pd',
                'email' => 'ahmad.susanto@smk.sch.id',
                'password' => '123456789',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Pendidikan No. 123, Jakarta',
                'no_hp' => '081234567890',
                'is_wali_kelas' => 'true',
                'is_active' => 'true'
            ],
            [
                'nip' => '987654321',
                'nama' => 'Siti Rahayu, S.Pd',
                'email' => 'siti.rahayu@smk.sch.id',
                'password' => '987654321',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Guru No. 456, Jakarta',
                'no_hp' => '081987654321',
                'is_wali_kelas' => 'false',
                'is_active' => 'true'
            ]
        ];
    }

    /**
     * Convert string representations to boolean
     */
    private function convertToBoolean($value)
    {
        if ($value === null || $value === '') {
            return false;
        }

        // Handle various string representations of boolean
        $value = strtolower(trim($value));
        
        // True values
        if (in_array($value, ['true', '1', 'yes', 'ya', 'on', 'aktif'])) {
            return true;
        }
        
        // False values
        if (in_array($value, ['false', '0', 'no', 'tidak', 'off', 'non-aktif', 'nonaktif'])) {
            return false;
        }
        
        // If it's already boolean, return as is
        if (is_bool($value)) {
            return $value;
        }
        
        // Default to false for unrecognized values
        return false;
    }
}
