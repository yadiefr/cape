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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Comment;
use App\Models\Kelas;
use App\Models\Jurusan;

class TemplateSiswaExport implements FromArray, WithHeadings, WithColumnWidths, WithStyles, WithEvents
{
    public function array(): array
    {
        return [
            [
                '2024001',
                '1234567890',
                'Contoh Nama Siswa',
                'L',
                'Jakarta',
                '01-01-2005', // Format: DD-MM-YYYY (akan jadi password: 01012005)
                'Islam',
                'Jl. Contoh No. 123',
                '081234567890',
                'contoh@email.com',
                '1', // Contoh ID Kelas
                '1', // Contoh ID Jurusan
                'aktif',
                'Nama Ayah',
                'Nama Ibu',
                'Swasta',
                'Ibu Rumah Tangga',
                '081234567891',
                '081234567892',
                'Jl. Alamat Orangtua No. 456',
                '',
                '* Password'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '- Jika tanggal lahir di isi maka password menggunakan tanggal lahir. Misal 01-01-2001 maka passwordnya adalah 01012001'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '- Jika tanggal lahir di kosongkan maka default password adalah = Password'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'NIS* (Required)',
            'NISN* (Required)',
            'Nama Lengkap* (Required)',
            'Jenis Kelamin* (L/P) (Required)',
            'Tempat Lahir',
            'Tanggal Lahir (Format: DD-MM-YYYY)',
            'Agama',
            'Alamat',
            'Telepon',
            'Email',
            'ID Kelas',
            'ID Jurusan',
            'Status (aktif/tidak_aktif)',
            'Nama Ayah',
            'Nama Ibu',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'No. Telp Ayah',
            'No. Telp Ibu',
            'Alamat Orangtua',
            '',
            'CATATAN'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // NIS
            'B' => 15, // NISN
            'C' => 25, // Nama Lengkap
            'D' => 15, // Jenis Kelamin
            'E' => 11, // Tempat Lahir
            'F' => 18, // Tanggal Lahir
            'G' => 7, // Agama
            'H' => 30, // Alamat
            'I' => 14, // Telepon
            'J' => 25, // Email
            'K' => 7, // ID Kelas (lihat daftar di bawah)
            'L' => 7, // ID Jurusan (lihat daftar di bawah)
            'M' => 15, // Status
            'N' => 20, // Nama Ayah
            'O' => 20, // Nama Ibu
            'P' => 20, // Pekerjaan Ayah
            'Q' => 20, // Pekerjaan Ibu
            'R' => 14, // No. Telp Ayah
            'S' => 14, // No. Telp Ibu
            'T' => 30, // Alamat Orangtua
            'U' => 2, // 
            'V' => 110, // Catatan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as a header
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 10,
                    'color' => ['argb' => 'FFFFFFFF'], // White text
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF4472C4', // Blue background
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true, // Enable text wrapping
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Set kolom F (Tanggal Lahir) sebagai format TEXT
                $sheet->getStyle('F:F')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set kolom A (NIS) sebagai format TEXT juga untuk menghindari scientific notation
                $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set kolom B (NISN) sebagai format TEXT juga
                $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set kolom I (Telepon) sebagai format TEXT
                $sheet->getStyle('I:I')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set kolom R (No. Telp Ayah) sebagai format TEXT
                $sheet->getStyle('R:R')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set kolom S (No. Telp Ibu) sebagai format TEXT
                $sheet->getStyle('S:S')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                
                // Set row height untuk header agar komentar terlihat
                $sheet->getRowDimension(1)->setRowHeight(40);
                
                // Set alignment center untuk semua cell
                $sheet->getStyle('A:V')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A:V')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
                
                // Tambahkan komentar popup untuk kolom ID Kelas (K1) dan ID Jurusan (L1)
                $kelasData = Kelas::with('jurusan')->orderBy('nama_kelas', 'asc')->get();
                $jurusanData = Jurusan::orderBy('id', 'asc')->get();
                
                // Buat string komentar untuk kelas (sorted by nama_kelas)
                $kelasComment = "Daftar ID Kelas:\n";
                foreach ($kelasData as $kelas) {
                    $kelasComment .= "ID {$kelas->id}: {$kelas->nama_kelas}";
                    if ($kelas->jurusan) {
                        $kelasComment .= " ({$kelas->jurusan->nama_jurusan})";
                    }
                    $kelasComment .= "\n";
                }
                
                // Buat string komentar untuk jurusan (sorted by id)
                $jurusanComment = "Daftar ID Jurusan:\n";
                foreach ($jurusanData as $jurusan) {
                    $jurusanComment .= "ID {$jurusan->id}: {$jurusan->nama_jurusan}\n";
                }
                
                // Tambahkan komentar pada kolom ID Kelas (K1)
                $sheet->getComment('K1')->getText()->createTextRun($kelasComment);
                $kelasSize = $this->calculateCommentSize($kelasComment);
                $sheet->getComment('K1')->setWidth($kelasSize['width']);
                $sheet->getComment('K1')->setHeight($kelasSize['height']);
                
                // Tambahkan komentar pada kolom ID Jurusan (L1)
                $sheet->getComment('L1')->getText()->createTextRun($jurusanComment);
                $jurusanSize = $this->calculateCommentSize($jurusanComment);
                $sheet->getComment('L1')->setWidth($jurusanSize['width']);
                $sheet->getComment('L1')->setHeight($jurusanSize['height']);
                
                // Set styling khusus untuk cell U1 (tanpa warna) dan V1 (hijau)
                $sheet->getStyle('U1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['argb' => 'FF000000'], // Black text
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_NONE, // No background color
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                
                $sheet->getStyle('V1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => ['argb' => 'FFFFFFFF'], // White text
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'argb' => 'FF70AD47', // Green background
                        ],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                ]);
                
                // Auto-fit column heights
                $sheet->getDefaultRowDimension()->setRowHeight(20);
            },
        ];
    }

    /**
     * Calculate optimal comment size based on content
     */
    private function calculateCommentSize($content, $minWidth = 200, $maxWidth = 500, $minHeight = 100, $maxHeight = 400)
    {
        $lines = substr_count($content, "\n") + 1;
        $maxLineLength = max(array_map('strlen', explode("\n", $content)));
        
        $width = min(max($maxLineLength * 8, $minWidth), $maxWidth) . 'px';
        $height = min(max($lines * 20, $minHeight), $maxHeight) . 'px';
        
        return ['width' => $width, 'height' => $height];
    }
}
