@extends('layouts.siswa')

@section('title', 'Detail Absensi Siswa')

@section('content')
<div class="p-3 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Detail Absensi Siswa</h1>
            <nav class="text-sm" aria-label="breadcrumb">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('siswa.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ route('siswa.ketua-kelas.dashboard') }}" class="text-blue-600 hover:text-blue-800">Ketua Kelas</a></li>
                    <li class="text-gray-500">/</li>
                    <li><a href="{{ route('siswa.ketua-kelas.rekap-absensi') }}" class="text-blue-600 hover:text-blue-800">Rekap Absensi</a></li>
                    <li class="text-gray-500">/</li>
                    <li class="text-gray-700">Detail Siswa</li>
                </ol>
            </nav>
        </div>
        
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
            <a href="{{ route('siswa.ketua-kelas.rekap-absensi') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Info Siswa -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-4 sm:px-6 py-4 rounded-t-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <h5 class="text-base sm:text-lg font-semibold mb-0">
                        <i class="fas fa-user mr-2"></i> {{ $targetSiswa->nama_lengkap }}
                    </h5>
                    <div class="text-sm">
                        <span class="bg-blue-500 px-3 py-1 rounded-full">NIS: {{ $targetSiswa->nis }}</span>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $statistik['hadir'] }}</div>
                        <div class="text-sm text-gray-600">Hadir</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $statistik['sakit'] }}</div>
                        <div class="text-sm text-gray-600">Sakit</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $statistik['izin'] }}</div>
                        <div class="text-sm text-gray-600">Izin</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $statistik['alpha'] }}</div>
                        <div class="text-sm text-gray-600">Alpha</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800">{{ array_sum($statistik) }}</div>
                        <div class="text-sm text-gray-600">Total Hari</div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <div class="text-lg font-semibold">
                        Persentase Kehadiran: 
                        <span class="{{ $persentaseKehadiran >= 90 ? 'text-green-600' : ($persentaseKehadiran >= 75 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ number_format($persentaseKehadiran, 1) }}%
                        </span>
                        @if(array_sum($statistik) > 0)
                            <span class="text-sm text-gray-500 block">
                                ({{ $statistik['hadir'] }} hadir dari {{ array_sum($statistik) }} hari tercatat)
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
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

    <!-- Detail Absensi Harian -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
            <h6 class="text-base sm:text-lg font-semibold text-gray-900 mb-0">
                <i class="fas fa-calendar-alt mr-2"></i> Detail Absensi - {{ $monthName }} {{ $year }}
            </h6>
        </div>
        <div class="p-4 sm:p-6">
            @if($absensiDetail->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($absensiDetail as $absensi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($absensi->tanggal)->locale('id')->isoFormat('dddd') }}
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                @if($absensi->status == 'hadir')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                @elseif($absensi->status == 'sakit')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                @elseif($absensi->status == 'izin')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                @elseif($absensi->status == 'alpha')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Alpha</span>
                                @endif
                            </td>
                            <td class="px-3 sm:px-6 py-4 text-sm text-gray-900">
                                {{ $absensi->keterangan ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500">Tidak ada data absensi untuk bulan {{ $monthName }} {{ $year }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
