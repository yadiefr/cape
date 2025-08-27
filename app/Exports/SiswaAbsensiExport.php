<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;

class SiswaAbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithProperties
{
    protected $absensi;
    protected $siswa;

    public function __construct($absensi, $siswa)
    {
        $this->absensi = $absensi;
        $this->siswa = $siswa;
    }

    public function collection()
    {
        return $this->absensi;
    }

    public function headings(): array
    {
        return [
            ['RIWAYAT KEHADIRAN SISWA'],
            ['SMK PGRI CIKAMPEK'],
            [],
            ['Nama Siswa', $this->siswa->nama_lengkap],
            ['NIS', $this->siswa->nis],
            ['Kelas', $this->siswa->kelas->nama_kelas ?? 'Tidak ada kelas'],
            ['Tanggal Export', Carbon::now()->locale('id')->translatedFormat('d F Y H:i')],
            [],
            ['No', 'Tanggal', 'Mata Pelajaran', 'Guru', 'Status', 'Keterangan']
        ];
    }

    public function map($absensi): array
    {
        static $no = 1;
        
        return [
            $no++,
            Carbon::parse($absensi->tanggal)->locale('id')->translatedFormat('d F Y'),
            $absensi->mapel->nama ?? 'Tidak ada mata pelajaran',
            $absensi->guru->nama ?? 'Tidak ada guru',
            ucfirst($absensi->status),
            $absensi->keterangan ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge cells untuk header
        $sheet->mergeCells('A1:F1');
        $sheet->mergeCells('A2:F2');
        
        // Style untuk header utama
        $sheet->getStyle('A1:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk informasi siswa
        $sheet->getStyle('A4:B7')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        // Style untuk header tabel
        $sheet->getStyle('A9:F9')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4F46E5'],
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

        // Style untuk data tabel
        $lastRow = 9 + $this->absensi->count();
        $sheet->getStyle('A10:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center alignment untuk nomor dan status
        $sheet->getStyle('A10:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E10:E' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // Tanggal
            'C' => 25,  // Mata Pelajaran
            'D' => 20,  // Guru
            'E' => 12,  // Status
            'F' => 30,  // Keterangan
        ];
    }

    public function title(): string
    {
        return 'Riwayat Kehadiran';
    }

    public function properties(): array
    {
        return [
            'creator'        => 'SMK PGRI CIKAMPEK',
            'lastModifiedBy' => 'SMK PGRI CIKAMPEK',
            'title'          => 'Riwayat Kehadiran - ' . $this->siswa->nama_lengkap,
            'description'    => 'Riwayat kehadiran siswa ' . $this->siswa->nama_lengkap,
            'subject'        => 'Absensi Siswa',
            'keywords'       => 'absensi,kehadiran,siswa',
            'category'       => 'Laporan',
            'manager'        => 'SMK PGRI CIKAMPEK',
            'company'        => 'SMK PGRI CIKAMPEK',
        ];
    }
}
