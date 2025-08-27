<?php

namespace App\Imports;

use App\Models\MataPelajaran;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MataPelajaranImport implements ToCollection, WithHeadingRow
{
    private $imported = 0;
    private $updated = 0;
    private $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows
                if (empty($row['kode_mata_pelajaran']) && empty($row['nama_mata_pelajaran'])) {
                    continue;
                }

                // Clean data
                $cleanedRow = [
                    'kode' => trim($row['kode_mata_pelajaran'] ?? ''),
                    'nama' => trim($row['nama_mata_pelajaran'] ?? ''),
                ];

                // Validate required fields
                $validator = Validator::make($cleanedRow, [
                    'kode' => 'required|string|max:10',
                    'nama' => 'required|string|max:100',
                ]);

                if ($validator->fails()) {
                    $this->errors[] = "Baris " . ($index + 2) . ": " . implode(', ', $validator->errors()->all());
                    continue;
                }

                // Check if mata pelajaran already exists
                $existing = MataPelajaran::where('kode', $cleanedRow['kode'])->first();

                // Create or update mata pelajaran
                $mapel = MataPelajaran::updateOrCreate(
                    ['kode' => $cleanedRow['kode']],
                    [
                        'nama' => $cleanedRow['nama'],
                        'kelas' => '',
                        'jenis' => 'Wajib',
                        'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
                        'kkm' => 75,
                        'deskripsi' => null,
                        'jurusan_id' => null,
                        'materi_pokok' => null,
                        'is_unggulan' => false,
                    ]
                );

                if ($existing) {
                    $this->updated++;
                    Log::info('Import Mata Pelajaran - Updated', ['kode' => $cleanedRow['kode']]);
                } else {
                    $this->imported++;
                    Log::info('Import Mata Pelajaran - Created', ['kode' => $cleanedRow['kode'], 'id' => $mapel->id]);
                }

            } catch (\Exception $e) {
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                Log::error('Import Mata Pelajaran - Error', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray()
                ]);
            }
        }
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
