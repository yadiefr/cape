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

class RekapAbsensiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithProperties
{
    protected $siswa;
    protected $periode;
    protected $dataAbsensi;
    protected $rekapData;
    protected $kelas;
    protected $mapel;
    protected $tanggalAwal;
    protected $tanggalAkhir;

    public function __construct($siswa, $periode, $dataAbsensi, $rekapData, $kelas, $mapel, $tanggalAwal, $tanggalAkhir)
    {
        $this->siswa = $siswa;
        $this->periode = $periode;
        $this->dataAbsensi = $dataAbsensi;
        $this->rekapData = $rekapData;
        $this->kelas = $kelas;
        $this->mapel = $mapel;
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
    }

    public function collection()
    {
        return $this->siswa;
    }

    public function headings(): array
    {
        $schoolName = setting('nama_sekolah', 'SMK PGRI CIKAMPEK');
        
        // Build the header with school info
        $headers = [
            ['REKAP ABSENSI SISWA'],
            [strtoupper($schoolName)],
            [],
            ['Kelas', $this->kelas->nama_kelas],
            ['Mata Pelajaran', $this->mapel->nama],
            ['Periode', Carbon::parse($this->tanggalAwal)->locale('id')->translatedFormat('d F Y') . ' - ' . Carbon::parse($this->tanggalAkhir)->locale('id')->translatedFormat('d F Y')],
            ['Tanggal Export', Carbon::now()->locale('id')->translatedFormat('d F Y H:i')],
            []
        ];

        // Build the column headers
        $columnHeaders = ['No', 'NIS', 'Nama Siswa'];
        
        // Add date columns
        foreach ($this->periode as $tgl) {
            $columnHeaders[] = Carbon::parse($tgl)->format('d/m');
        }
        
        // Add summary columns
        $columnHeaders = array_merge($columnHeaders, ['Hadir', 'Izin', 'Sakit', 'Alpha']);
        
        $headers[] = $columnHeaders;
        
        return $headers;
    }

    public function map($siswa): array
    {
        static $no = 1;
        
        $row = [
            $no++,
            $siswa->nis,
            $siswa->nama_lengkap
        ];
        
        // Add attendance status for each date
        foreach ($this->periode as $tgl) {
            if (isset($this->dataAbsensi[$siswa->id][$tgl])) {
                $status = $this->dataAbsensi[$siswa->id][$tgl];
                switch ($status) {
                    case 'hadir':
                        $row[] = 'H';
                        break;
                    case 'izin':
                        $row[] = 'I';
                        break;
                    case 'sakit':
                        $row[] = 'S';
                        break;
                    case 'alpha':
                        $row[] = 'A';
                        break;
                    default:
                        $row[] = '-';
                }
            } else {
                $row[] = '-';
            }
        }
        
        // Add summary data
        $row[] = isset($this->rekapData[$siswa->id]['hadir']) ? $this->rekapData[$siswa->id]['hadir'] : 0;
        $row[] = isset($this->rekapData[$siswa->id]['izin']) ? $this->rekapData[$siswa->id]['izin'] : 0;
        $row[] = isset($this->rekapData[$siswa->id]['sakit']) ? $this->rekapData[$siswa->id]['sakit'] : 0;
        $row[] = isset($this->rekapData[$siswa->id]['alpha']) ? $this->rekapData[$siswa->id]['alpha'] : 0;
        
        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        $totalColumns = 3 + count($this->periode) + 4; // No, NIS, Nama + dates + summary
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColumns);
        
        // Merge cells untuk header utama
        $sheet->mergeCells("A1:{$columnLetter}1");
        $sheet->mergeCells("A2:{$columnLetter}2");
        
        // Style untuk header utama
        $sheet->getStyle("A1:{$columnLetter}2")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Style untuk info kelas dan periode
        $sheet->getStyle('A4:A7')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Style untuk header tabel
        $headerRow = 9;
        $sheet->getStyle("A{$headerRow}:{$columnLetter}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E5E7EB'],
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

        // Style untuk data
        $dataStartRow = $headerRow + 1;
        $dataEndRow = $dataStartRow + count($this->siswa) - 1;
        
        $sheet->getStyle("A{$dataStartRow}:{$columnLetter}{$dataEndRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center align untuk kolom tanggal dan summary
        $dateStartCol = 4; // After No, NIS, Nama
        $summaryStartCol = $dateStartCol + count($this->periode);
        
        for ($col = $dateStartCol; $col <= $totalColumns; $col++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $sheet->getStyle("{$colLetter}{$dataStartRow}:{$colLetter}{$dataEndRow}")
                  ->getAlignment()
                  ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        return $sheet;
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 5,   // No
            'B' => 12,  // NIS
            'C' => 25,  // Nama
        ];
        
        // Width untuk kolom tanggal
        $col = 4;
        foreach ($this->periode as $tgl) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $widths[$colLetter] = 8;
            $col++;
        }
        
        // Width untuk kolom summary
        $summaryColumns = ['H', 'I', 'S', 'A'];
        for ($i = 0; $i < 4; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
            $widths[$colLetter] = 8;
            $col++;
        }
        
        return $widths;
    }

    public function title(): string
    {
        return 'Rekap Absensi ' . $this->kelas->nama_kelas;
    }

    public function properties(): array
    {
        return [
            'creator' => 'SMK System',
            'lastModifiedBy' => 'SMK System',
            'title' => 'Rekap Absensi ' . $this->kelas->nama_kelas,
            'description' => 'Rekap absensi siswa periode ' . $this->tanggalAwal . ' - ' . $this->tanggalAkhir,
            'subject' => 'Rekap Absensi',
            'keywords' => 'absensi,rekap,siswa',
            'category' => 'Laporan',
        ];
    }
}
