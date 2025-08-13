<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class JadwalTemplateExport implements FromArray, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 15,  // hari
            'B' => 12,  // jam_mulai
            'C' => 12,  // jam_selesai
            'D' => 15,  // kelas
            'E' => 25,  // mata_pelajaran
            'F' => 25,  // guru
            'G' => 15,  // ruangan
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $styles = [];
        
        // Find header row (contains 'hari', 'jam_mulai', etc.)
        $headerRow = 0;
        foreach ($this->data as $index => $row) {
            if (isset($row[0]) && $row[0] === 'hari') {
                $headerRow = $index + 1;
                break;
            }
        }
        
        // Style title and instructions (blue background)
        for ($i = 1; $i < $headerRow; $i++) {
            if ($i == 1) {
                // Title row - bold and larger
                $styles[$i] = [
                    'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'ffffff']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1f2937']
                    ]
                ];
            } else {
                // Instruction rows
                $styles[$i] = [
                    'font' => ['bold' => false, 'size' => 10, 'color' => ['rgb' => '374151']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'dbeafe']
                    ]
                ];
            }
        }
        
        // Style header row (bold, green background)
        if ($headerRow > 0) {
            $styles[$headerRow] = [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'ffffff']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '059669']
                ]
            ];
        }
        
        // Style example rows (light green background)
        for ($i = $headerRow + 1; $i <= count($this->data); $i++) {
            $styles[$i] = [
                'font' => ['bold' => false, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'f0fdf4']
                ]
            ];
        }
        
        return $styles;
    }
}
