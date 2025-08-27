@extends('layouts.siswa')

@section('title', 'Detail Kehadiran - SMK PGRI CIKAMPEK')

@section('content')
<div class="w-full px-6 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Kehadiran</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('siswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('siswa.absensi') }}" class="hover:text-blue-600">Kehadiran</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Detail</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Header Detail -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-blue-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-check text-white text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $absensi->mapel->nama ?? 'Mata Pelajaran Tidak Ditemukan' }}</h2>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($absensi->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                        @if($absensi->status == 'hadir')
                            bg-green-100 text-green-800
                        @elseif($absensi->status == 'izin')
                            bg-blue-100 text-blue-800
                        @elseif($absensi->status == 'sakit')
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-red-100 text-red-800
                        @endif">
                        @if($absensi->status == 'hadir')
                            <i class="fas fa-check mr-1"></i>
                        @elseif($absensi->status == 'izin')
                            <i class="fas fa-clock mr-1"></i>
                        @elseif($absensi->status == 'sakit')
                            <i class="fas fa-procedures mr-1"></i>
                        @else
                            <i class="fas fa-times mr-1"></i>
                        @endif
                        {{ ucfirst($absensi->status) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6">
            <!-- Informasi Mata Pelajaran -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-book text-blue-500 mr-2"></i>
                    Informasi Mata Pelajaran
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600">Mata Pelajaran:</span>
                            <span class="font-semibold">{{ $absensi->mapel->nama ?? 'Mata Pelajaran Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600">Guru Pengajar:</span>
                            <span class="font-semibold">{{ $absensi->guru->nama ?? 'Guru Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600">Kelas:</span>
                            <span class="font-semibold">{{ $absensi->kelas->nama_kelas ?? 'Tidak ada kelas' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600">Tanggal:</span>
                            <span class="font-semibold">{{ \Carbon\Carbon::parse($absensi->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Kehadiran -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-user-check text-green-500 mr-2"></i>
                    Status Kehadiran
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600">Status Kehadiran:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                @if($absensi->status == 'hadir')
                                    bg-green-100 text-green-800
                                @elseif($absensi->status == 'izin')
                                    bg-blue-100 text-blue-800
                                @elseif($absensi->status == 'sakit')
                                    bg-yellow-100 text-yellow-800
                                @else
                                    bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($absensi->status) }}
                            </span>
                        </div>
                        @if($absensi->keterangan)
                        <div class="border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600 block mb-1">Keterangan:</span>
                            <span class="text-gray-800 bg-yellow-50 p-2 rounded border-l-4 border-yellow-400 block">{{ $absensi->keterangan }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                            <span class="font-medium text-gray-600">Waktu Input:</span>
                            <span class="font-semibold">{{ $absensi->created_at->locale('id')->translatedFormat('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-gray-600">Terakhir Update:</span>
                            <span class="font-semibold">{{ $absensi->updated_at->locale('id')->translatedFormat('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        @if($absensi->status == 'alpha')
        <div class="px-6 pb-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-red-500 text-lg mr-3 mt-1"></i>
                    <div>
                        <h4 class="text-red-800 font-semibold mb-1">Perhatian - Status Alpha</h4>
                        <p class="text-red-700 text-sm">Anda tercatat tidak hadir tanpa keterangan pada mata pelajaran ini. Pastikan untuk memberikan izin atau keterangan yang valid jika tidak bisa mengikuti pelajaran.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-xl">
            <div class="flex justify-between items-center">
                <a href="{{ route('siswa.absensi') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
                <div class="space-x-2">
                    <button onclick="printDetail()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-print mr-2"></i>
                        Cetak Detail
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printDetail() {
    const printWindow = window.open('', '_blank');
    const content = `
        <html>
            <head>
                <title>Detail Kehadiran - {{ $absensi->mapel->nama ?? 'Mata Pelajaran Tidak Ditemukan' }}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin: 20px 0; }
                    .info-box { border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
                    .info-row { display: flex; justify-content: space-between; margin: 5px 0; padding: 5px 0; border-bottom: 1px solid #eee; }
                    .label { font-weight: bold; color: #555; }
                    .value { color: #333; }
                    .status { padding: 5px 10px; border-radius: 15px; font-weight: bold; }
                    .status-hadir { background: #dcfce7; color: #166534; }
                    .status-izin { background: #dbeafe; color: #1e40af; }
                    .status-sakit { background: #fef3c7; color: #92400e; }
                    .status-alpha { background: #fee2e2; color: #dc2626; }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>DETAIL KEHADIRAN SISWA</h2>
                    <p><strong>SMK PGRI CIKAMPEK</strong></p>
                </div>
                
                <div class="info-grid">
                    <div class="info-box">
                        <h3>Informasi Siswa</h3>
                        <div class="info-row">
                            <span class="label">Nama Siswa:</span>
                            <span class="value">{{ Auth::guard('siswa')->user()->nama_lengkap }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Kelas:</span>
                            <span class="value">{{ Auth::guard('siswa')->user()->kelas->nama_kelas ?? 'Tidak ada kelas' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">NIS:</span>
                            <span class="value">{{ Auth::guard('siswa')->user()->nis }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box">
                        <h3>Informasi Mata Pelajaran</h3>
                        <div class="info-row">
                            <span class="label">Mata Pelajaran:</span>
                            <span class="value">{{ $absensi->mapel->nama ?? 'Mata Pelajaran Tidak Ditemukan' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Guru Pengajar:</span>
                            <span class="value">{{ $absensi->guru->nama ?? 'Guru Tidak Ditemukan' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label">Tanggal:</span>
                            <span class="value">{{ \Carbon\Carbon::parse($absensi->tanggal)->locale('id')->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="info-box">
                    <h3>Status Kehadiran</h3>
                    <div class="info-row">
                        <span class="label">Status:</span>
                        <span class="status status-{{ $absensi->status }}">{{ ucfirst($absensi->status) }}</span>
                    </div>
                    @if($absensi->keterangan)
                    <div class="info-row">
                        <span class="label">Keterangan:</span>
                        <span class="value">{{ $absensi->keterangan }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="label">Waktu Input:</span>
                        <span class="value">{{ $absensi->created_at->locale('id')->translatedFormat('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                <p style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
                    Dicetak pada: {{ date('d/m/Y H:i') }}
                </p>
            </body>
        </html>
    `;
    
    printWindow.document.write(content);
    printWindow.document.close();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
@endsection
