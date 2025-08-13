<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class MataPelajaranTemplateExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    public function array(): array
    {
        return [
            [
                'MP001',
                'Matematika'
            ],
            [
                'MP002', 
                'Bahasa Indonesia'
            ],
            [
                'MP003',
                'Bahasa Inggris'
            ],
            [
                'MP004',
                'Fisika'
            ],
            [
                'MP005',
                'Kimia'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Kode Mata Pelajaran',
            'Nama Mata Pelajaran'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,  // Kode
            'B' => 40,  // Nama
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Add borders to all data
                $event->sheet->getDelegate()->getStyle('A1:B6')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Auto height for all rows
                for ($i = 1; $i <= 6; $i++) {
                    $event->sheet->getDelegate()->getRowDimension($i)->setRowHeight(-1);
                }
            }
        ];
    }
}
