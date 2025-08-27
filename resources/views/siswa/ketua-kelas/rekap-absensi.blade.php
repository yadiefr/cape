@extends('layouts.siswa')

@section('title', 'Rekap Absensi Kelas')

@section('content')
<div class="p-3 sm:p-6" x-data="rekapAbsensi()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Rekap Absensi Kelas</h1>
            <nav class="text-sm" aria-label="breadcrumb">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('siswa.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ route('siswa.ketua-kelas.dashboard') }}" class="text-blue-600 hover:text-blue-800">Ketua Kelas</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-gray-700">Rekap Absensi</li>
                </ol>
            </nav>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('siswa.ketua-kelas.dashboard') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Kelas & Summary -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-4 sm:px-6 py-4 rounded-t-lg">
                <h5 class="text-base sm:text-lg font-semibold mb-0">
                    <i class="fas fa-chart-bar mr-2"></i> {{ $kelas->nama_kelas }} - {{ $monthName }} {{ $year }}
                </h5>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $summary['total_hadir'] }}</div>
                        <div class="text-sm text-gray-600">Total Hadir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $summary['total_sakit'] }}</div>
                        <div class="text-sm text-gray-600">Total Sakit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $summary['total_izin'] }}</div>
                        <div class="text-sm text-gray-600">Total Izin</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $summary['total_alpha'] }}</div>
                        <div class="text-sm text-gray-600">Total Alpha</div>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-800">
                                <strong>Cara Perhitungan Persentase:</strong><br>
                                Persentase kehadiran dihitung berdasarkan hari yang tercatat absensinya. 
                                Rumus: (Jumlah Hadir ÷ Total Hari Tercatat Absensi) × 100%
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Filter Periode</h3>
                <p class="text-sm text-gray-600">Pilih bulan dan tahun untuk melihat rekap absensi</p>
            </div>
            <form method="GET" class="flex flex-wrap items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="month" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="year" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        @for($i = date('Y') - 2; $i <= date('Y') + 1; $i++)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Rekap -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                <div>
                    <h6 class="text-base sm:text-lg font-semibold text-gray-900 mb-1">
                        <i class="fas fa-users mr-2"></i> Rekap per Siswa
                    </h6>
                    <p class="text-xs text-gray-500">
                        Persentase = (Jumlah Hadir ÷ Total Hari Tercatat) × 100%
                    </p>
                </div>
                <button @click="exportData" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </div>
        </div>
        <div class="p-4 sm:p-6">
            @if(count($attendanceData) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 sm:w-16">No</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">NIS</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Total</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($attendanceData as $index => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-3 sm:px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $data['nama_lengkap'] }}</div>
                                <div class="text-xs sm:text-sm text-gray-500 sm:hidden">{{ $data['nis'] }}</div>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $data['nis'] }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-green-600 font-medium">{{ $data['hadir'] }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-yellow-600 font-medium">{{ $data['sakit'] }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-blue-600 font-medium">{{ $data['izin'] }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-red-600 font-medium">{{ $data['alpha'] }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center hidden lg:table-cell">
                                <span class="text-gray-800 font-medium">{{ $data['total_absensi'] ?? ($data['hadir'] + $data['sakit'] + $data['izin'] + $data['alpha']) }}</span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                @if(($data['total_absensi'] ?? ($data['hadir'] + $data['sakit'] + $data['izin'] + $data['alpha'])) > 0)
                                    <span class="font-semibold {{ $data['persentase'] >= 90 ? 'text-green-600' : ($data['persentase'] >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($data['persentase'], 1) }}%
                                    </span>
                                    <div class="text-xs text-gray-500">
                                        {{ $data['hadir'] }}/{{ $data['total_absensi'] ?? ($data['hadir'] + $data['sakit'] + $data['izin'] + $data['alpha']) }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Belum ada data</span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $data['persentase'] >= 90 ? 'bg-green-100 text-green-800' : 
                                       ($data['persentase'] >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $data['persentase'] >= 90 ? 'Baik' : ($data['persentase'] >= 75 ? 'Cukup' : 'Buruk') }}
                                </span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <a href="{{ route('siswa.ketua-kelas.detail-siswa', $data['id']) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 pt-4 border-t border-gray-200 space-y-4 sm:space-y-0">
                <div class="text-gray-600 text-center sm:text-left">
                    Total: {{ count($attendanceData) }} siswa
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-users-slash text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Tidak ada data absensi untuk periode {{ $monthName }} {{ $year }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function rekapAbsensi() {
    return {
        init() {
            console.log('Rekap Absensi loaded');
        },
        
        exportData() {
            // Implementasi export Excel
            alert('Fitur export akan segera tersedia');
        }
    }
}
</script>
@endsection
