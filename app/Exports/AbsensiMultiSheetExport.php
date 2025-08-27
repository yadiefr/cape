<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AbsensiMultiSheetExport implements WithMultipleSheets
{
    protected $sheets;

    public function __construct($sheets)
    {
        $this->sheets = $sheets;
    }

    public function sheets(): array
    {
        $result = [];
        
        foreach ($this->sheets as $kelasId => $data) {
            $kelasName = "Kelas {$kelasId}";
            
            // Try to get actual kelas name if possible
            try {
                $kelas = \App\Models\Kelas::find($kelasId);
                if ($kelas) {
                    $kelasName = $kelas->nama_kelas;
                }
            } catch (\Exception $e) {
                // Use default name
            }
            
            $result[] = new AbsensiSheetExport($data, $kelasName);
        }
        
        return $result;
    }
}

class AbsensiSheetExport implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    protected $data;
    protected $title;

    public function __construct($data, $title)
    {
        $this->data = $data;
        $this->title = $title;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function styles(Worksheet $sheet)
    {
        // Get total columns
        $totalColumns = count($this->data[0] ?? []);
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);

        // Style for header row
        $sheet->getStyle("A1:{$columnLetter}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Style for data rows
        $dataRows = count($this->data);
        if ($dataRows > 1) {
            $sheet->getStyle("A2:{$columnLetter}{$dataRows}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);

            // Center align numeric columns
            $numericColumns = ['B', 'C','D', 'E', 'F', 'G', 'H', 'I']; // Hadir, Izin, Sakit, Alpha, Total, Persentase
            foreach ($numericColumns as $col) {
                if (isset($this->data[0]) && count($this->data[0]) >= array_search($col, range('A', 'Z')) + 1) {
                    $sheet->getStyle("{$col}2:{$col}{$dataRows}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            }
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40, // Nama
            'B' => 12, // NIS
            'C' => 15, // Kelas
            'D' => 8,  // Hadir
            'E' => 8,  // Izin
            'F' => 8,  // Sakit
            'G' => 8,  // Alpha
            'H' => 8,  // Total
            'I' => 12, // Persentase
        ];
    }
}
