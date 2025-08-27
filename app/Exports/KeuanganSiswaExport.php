<?php

namespace App\Exports;

use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KeuanganSiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        try {
            // Build query with filters (same logic as controller)
            $query = Siswa::with(['kelas.jurusan'])->where('status', 'aktif');

        // Apply filters
        if (isset($this->filters['tingkat']) && $this->filters['tingkat']) {
            $tingkat = $this->filters['tingkat'];
            $query->whereHas('kelas', function($q) use ($tingkat) {
                if ($tingkat == 'XI') {
                    $q->where('nama_kelas', 'LIKE', 'XI %')
                      ->orWhere('nama_kelas', '=', 'XI');
                } else {
                    $q->where('nama_kelas', 'LIKE', $tingkat . ' %')
                      ->orWhere('nama_kelas', '=', $tingkat);
                }
            });
        }

        if (isset($this->filters['kelas']) && $this->filters['kelas']) {
            $query->where('kelas_id', $this->filters['kelas']);
        }

        if (isset($this->filters['search']) && $this->filters['search']) {
            $search = $this->filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        if (isset($this->filters['tahun_ajaran']) && $this->filters['tahun_ajaran']) {
            $tahunAjaran = explode('/', $this->filters['tahun_ajaran'])[0];
            $query->where('tahun_masuk', $tahunAjaran);
        }

        $siswaList = $query->orderBy('nama_lengkap', 'asc')->get();

        // Calculate financial data for each student (simplified)
        foreach ($siswaList as $siswa) {
            try {
                $tagihanSiswa = Tagihan::where(function($q) use ($siswa) {
                    $q->whereNull('kelas_id')->whereNull('siswa_id')
                      ->orWhere('kelas_id', $siswa->kelas_id)
                      ->orWhere('siswa_id', $siswa->id);
                })->get();

                $totalTagihan = $tagihanSiswa->sum('nominal');
                $totalTelahDibayar = Pembayaran::where('siswa_id', $siswa->id)->sum('jumlah');

                $siswa->total_tagihan = $totalTagihan;
                $siswa->total_telah_dibayar = $totalTelahDibayar;
                $siswa->tunggakan = max(0, $totalTagihan - $totalTelahDibayar);
                $siswa->status_keuangan = $siswa->tunggakan <= 0 ? 'Lunas' : 'Belum Lunas';
            } catch (\Exception $e) {
                $siswa->total_tagihan = 0;
                $siswa->total_telah_dibayar = 0;
                $siswa->tunggakan = 0;
                $siswa->status_keuangan = 'Error';
            }
        }

        return $siswaList;
        } catch (\Exception $e) {
            // Return empty collection if error occurs
            return collect();
        }
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Lengkap',
            'Kelas',
            'Jurusan',
            'Total Tagihan',
            'Total Dibayar',
            'Tunggakan',
            'Status Keuangan',
            'Tanggal Export'
        ];
    }

    public function map($siswa): array
    {
        static $no = 1;
        
        return [
            $no++,
            $siswa->nis ?? '-',
            $siswa->nama_lengkap ?? 'Nama tidak tersedia',
            $siswa->kelas->nama_kelas ?? 'Tidak ada kelas',
            $siswa->kelas->jurusan->nama_jurusan ?? 'Tidak ada jurusan',
            $siswa->total_tagihan,
            $siswa->total_telah_dibayar,
            $siswa->tunggakan,
            $siswa->status_keuangan,
            now()->format('d/m/Y H:i:s')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
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
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],
            // Data rows styling
            'A:J' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
            // Number columns alignment
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            'F:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]],
            'I:I' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 12,  // NIS
            'C' => 25,  // Nama Lengkap
            'D' => 15,  // Kelas
            'E' => 20,  // Jurusan
            'F' => 15,  // Total Tagihan
            'G' => 15,  // Total Dibayar
            'H' => 15,  // Tunggakan
            'I' => 15,  // Status Keuangan
            'J' => 18,  // Tanggal Export
        ];
    }

    public function title(): string
    {
        $title = 'Data Keuangan Siswa';
        
        if (isset($this->filters['tingkat']) && $this->filters['tingkat']) {
            $title .= ' - Kelas ' . $this->filters['tingkat'];
        } elseif (isset($this->filters['show_all']) && $this->filters['show_all']) {
            $title .= ' - Semua Siswa';
        }
        
        return $title;
    }
}
