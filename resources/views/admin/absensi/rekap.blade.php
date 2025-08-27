@extends('layouts.admin')

@section('title', 'Rekap Absensi')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-purple-600"></i>
                Rekap Absensi
            </h1>
            <p class="text-gray-600 mt-1">Laporan rekapitulasi kehadiran siswa per kelas dan periode</p>
        </div>
        <a href="{{ route('admin.absensi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Filter Rekap -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <form action="{{ route('admin.absensi.rekap') }}" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('bulan', date('n')) == $m ? 'selected' : '' }}>
                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    @foreach(range(date('Y'), 2020) as $y)
                    <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>
                        {{ $y }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i> Tampilkan
                </button>
                @if(isset($rekap) && count($rekap) > 0)            <a href="{{ route('admin.absensi.rekap.export', request()->all()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-excel mr-2"></i> Export
                </a>
                @endif
            </div>
        </form>
    </div>

    @if(isset($rekap) && count($rekap) > 0)
    <!-- Tabel Rekap -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">
                Rekap Absensi 
                @if($kelas_info)
                    Kelas {{ $kelas_info->nama_kelas }} - 
                @endif
                {{ date('F Y', mktime(0, 0, 0, request('bulan', date('n')), 1, request('tahun', date('Y')))) }}
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kehadiran</th>
                        <th rowspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase Hadir</th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">H</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">I</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">S</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">A</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($rekap as $index => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $data['nama'] }}</div>
                            <div class="text-xs text-gray-500">NIS: {{ $data['nis'] }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $data['hadir'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                {{ $data['izin'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                {{ $data['sakit'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                {{ $data['alpha'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $data['persentase'] >= 90 ? 'bg-green-100 text-green-800' :
                                   ($data['persentase'] >= 80 ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800') }}">
                                {{ number_format($data['persentase'], 1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @elseif(request()->has('kelas_id'))
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Tidak ada data absensi untuk periode yang dipilih.
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
