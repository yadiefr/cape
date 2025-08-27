@extends('layouts.guru')

@section('title', 'Rekap Keuangan Kelas')

@section('content')
<div class="p-3 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Rekap Keuangan Kelas</h1>
        </div>
    </div>

    <!-- Info Kelas -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-green-200">
            <div class="bg-green-600 text-white px-4 sm:px-6 py-4 rounded-t-lg">
                <h5 class="text-base sm:text-lg font-semibold mb-0">
                    <i class="fas fa-money-bill-wave mr-2"></i> {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan }}
                </h5>
            </div>
            <div class="p-3 sm:p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <p class="mb-1 text-gray-700"><strong>Period:</strong> 
                            @if(request('periode_type') == 'tahun_ajaran')
                                Tahun Ajaran {{ request('tahun_ajaran', '2024/2025') }}
                            @else
                                {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}
                            @endif
                        </p>
                        <p class="mb-0 text-gray-500 text-sm">
                            @if(request('periode_type') == 'tahun_ajaran')
                                Status pembayaran siswa dalam satu tahun ajaran
                            @else
                                Status pembayaran siswa dalam satu bulan
                            @endif
                        </p>
                    </div>
                    <div class="w-full lg:w-auto">
                        <form method="GET" class="flex flex-col space-y-2 sm:space-y-0" id="filterForm">
                            <!-- Filter Type -->
                            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                                <select name="periode_type" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="togglePeriodeFilter()">
                                    <option value="bulanan" {{ request('periode_type', 'bulanan') == 'bulanan' ? 'selected' : '' }}>Filter Bulanan</option>
                                    <option value="tahun_ajaran" {{ request('periode_type') == 'tahun_ajaran' ? 'selected' : '' }}>Tahun Ajaran</option>
                                </select>
                            </div>
                            
                            <!-- Bulanan Filter -->
                            <div id="bulananFilter" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2" style="{{ request('periode_type') == 'tahun_ajaran' ? 'display: none;' : '' }}">
                                <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="submitFilter()">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                                <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="submitFilter()">
                                    @for($y = date('Y') - 2; $y <= date('Y') + 1; $y++)
                                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Tahun Ajaran Filter -->
                            <div id="tahunAjaranFilter" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2" style="{{ request('periode_type') != 'tahun_ajaran' ? 'display: none;' : '' }}">
                                <select name="tahun_ajaran" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="submitFilter()">
                                    <option value="2024/2025" {{ request('tahun_ajaran', '2024/2025') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                                    <option value="2023/2024" {{ request('tahun_ajaran') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                                    <option value="2025/2026" {{ request('tahun_ajaran') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-filter mr-1"></i> Filter
                                </button>
                            </div>
                        </form>
                        
                        <!-- Note untuk filter tahun ajaran -->
                        @if(request('periode_type') == 'tahun_ajaran')
                        <div class="mt-2 p-2 bg-green-50 rounded-lg border border-green-200">
                            <div class="text-xs text-green-700">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Info:</strong> Menampilkan rekap keuangan untuk keseluruhan tahun ajaran (Juli-Juni)
                            </div>
                        </div>
                        <div class="mt-1 p-2 bg-orange-50 rounded-lg border border-orange-200">
                            <div class="text-xs text-orange-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Note:</strong> Data tahun ajaran saat ini menggunakan simulasi. Perlu implementasi backend untuk data real.
                            </div>
                        </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Keuangan -->
    @if(count($rekapKeuangan) > 0)
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        @php
            if(request('periode_type') == 'tahun_ajaran') {
                // Logika untuk tahun ajaran - hitung total akumulatif dari semua siswa untuk keseluruhan tahun
                // Asumsi: data $rekapKeuangan berisi total akumulatif per siswa untuk tahun ajaran
                $totalTagihanTahunAjaran = collect($rekapKeuangan)->sum('total_tagihan_tahun_ajaran') ?: 
                                          collect($rekapKeuangan)->sum('total_tagihan') * 10; // fallback jika belum ada data tahun ajaran
                $totalSudahBayarTahunAjaran = collect($rekapKeuangan)->sum('sudah_bayar_tahun_ajaran') ?: 
                                             collect($rekapKeuangan)->sum('sudah_bayar') * 8; // simulasi pembayaran tahun ajaran
                $totalBelumBayarTahunAjaran = $totalTagihanTahunAjaran - $totalSudahBayarTahunAjaran;
                
                // Siswa yang persentase pembayaran tahun ajarannya < 90%
                $siswaBelumLunasTahunAjaran = collect($rekapKeuangan)->filter(function($data) {
                    // Hitung persentase pembayaran tahun ajaran per siswa
                    $totalTagihanSiswa = $data['total_tagihan'] * 10; // simulasi tagihan tahun ajaran
                    $sudahBayarSiswa = $data['sudah_bayar'] * 8; // simulasi pembayaran tahun ajaran
                    $persentaseTahunAjaran = $totalTagihanSiswa > 0 ? ($sudahBayarSiswa / $totalTagihanSiswa) * 100 : 0;
                    return $persentaseTahunAjaran < 90; // Kurang dari 90% dianggap perlu perhatian
                })->count();
                
            } else {
                // Logika untuk bulanan (tetap seperti sebelumnya)
                $totalTagihanTahunAjaran = collect($rekapKeuangan)->sum('total_tagihan');
                $totalSudahBayarTahunAjaran = collect($rekapKeuangan)->sum('sudah_bayar');
                $totalBelumBayarTahunAjaran = collect($rekapKeuangan)->sum('belum_bayar');
                $siswaBelumLunasTahunAjaran = collect($rekapKeuangan)->filter(function($data) { 
                    return $data['status'] == 'Belum Lunas'; 
                })->count();
            }
        @endphp
        
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="p-3 sm:p-6 text-center">
                <i class="fas fa-money-bill-wave text-2xl sm:text-3xl text-blue-600 mb-2 sm:mb-3"></i>
                <h4 class="text-lg sm:text-2xl font-bold text-blue-600 mb-1">Rp {{ number_format($totalTagihanTahunAjaran, 0, ',', '.') }}</h4>
                <p class="text-xs sm:text-sm text-gray-500">
                    @if(request('periode_type') == 'tahun_ajaran')
                        Total Tagihan/Tahun
                    @else
                        Total Tagihan
                    @endif
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-green-200">
            <div class="p-3 sm:p-6 text-center">
                <i class="fas fa-check-circle text-2xl sm:text-3xl text-green-600 mb-2 sm:mb-3"></i>
                <h4 class="text-lg sm:text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($totalSudahBayarTahunAjaran, 0, ',', '.') }}</h4>
                <p class="text-xs sm:text-sm text-gray-500">
                    @if(request('periode_type') == 'tahun_ajaran')
                        Terbayar/Tahun
                    @else
                        Sudah Dibayar
                    @endif
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-red-200">
            <div class="p-3 sm:p-6 text-center">
                <i class="fas fa-exclamation-triangle text-2xl sm:text-3xl text-red-600 mb-2 sm:mb-3"></i>
                <h4 class="text-lg sm:text-2xl font-bold text-red-600 mb-1">Rp {{ number_format($totalBelumBayarTahunAjaran, 0, ',', '.') }}</h4>
                <p class="text-xs sm:text-sm text-gray-500">
                    @if(request('periode_type') == 'tahun_ajaran')
                        Sisa Tagihan/Tahun
                    @else
                        Belum Dibayar
                    @endif
                </p>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-yellow-200">
            <div class="p-3 sm:p-6 text-center">
                <i class="fas fa-users text-2xl sm:text-3xl text-yellow-600 mb-2 sm:mb-3"></i>
                <h4 class="text-lg sm:text-2xl font-bold text-yellow-600 mb-1">{{ $siswaBelumLunasTahunAjaran }}</h4>
                <p class="text-xs sm:text-sm text-gray-500">
                    @if(request('periode_type') == 'tahun_ajaran')
                        Siswa Perlu Perhatian
                    @else
                        Siswa Menunggak
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Rekap Keuangan -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="bg-blue-600 text-white px-3 sm:px-6 py-3 sm:py-4 rounded-t-lg">
            <h6 class="text-base sm:text-lg font-semibold mb-0">
                <i class="fas fa-chart-bar mr-2"></i> Rekap Keuangan - 
                @if(request('periode_type') == 'tahun_ajaran')
                    {{ request('tahun_ajaran', '2024/2025') }}
                @else
                    {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}
                @endif
            </h6>
        </div>
        <div class="p-3 sm:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white" id="keuanganTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-6 sm:w-12">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 sm:w-20">Foto</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20 sm:w-24 hidden lg:table-cell">NISN</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 sm:w-32 hidden md:table-cell">Total Tagihan</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 sm:w-32 hidden md:table-cell">Sudah Dibayar</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24 sm:w-32 hidden md:table-cell">Belum Dibayar</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16 sm:w-24 hidden sm:table-cell">Status</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10 sm:w-16 hidden sm:table-cell">%</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-14 sm:w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($rekapKeuangan as $index => $data)
                        <tr class="hover:bg-gray-50">
                            <td class="px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <x-student-avatar :student="$data['siswa']" size="sm" />
                                </div>
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4">
                                <div>
                                    <div class="text-xs sm:text-sm font-medium text-gray-900">
                                        <a href="{{ route('guru.wali-kelas.siswa.detail', $data['siswa']->id) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $data['siswa']->nama_lengkap }}
                                        </a>
                                    </div>
                                    <div class="text-xs text-gray-500">{{ $data['siswa']->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    <!-- Mobile info: show key data on small screens -->
                                    <div class="sm:hidden mt-1 space-y-1">
                                        <div class="text-xs text-gray-600">
                                            Status: <span class="inline-flex px-1.5 py-0.5 text-xs font-semibold rounded-full {{ $data['status'] == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ $data['status'] }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            Tagihan: <span class="text-blue-600 font-medium">Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</span>
                                        </div>
                                        <div class="text-xs text-gray-600">
                                            Terbayar: <span class="text-green-600 font-medium">Rp {{ number_format($data['sudah_bayar'], 0, ',', '.') }}</span>
                                        </div>
                                        @if($data['belum_bayar'] > 0)
                                        <div class="text-xs text-gray-600">
                                            Sisa: <span class="text-red-600 font-medium">Rp {{ number_format($data['belum_bayar'], 0, ',', '.') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $data['siswa']->nisn ?? $data['siswa']->nis ?? '-' }}
                                </span>
                            </td>
                            <td class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-blue-600">
                                Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}
                            </td>
                            <td class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-green-600">
                                Rp {{ number_format($data['sudah_bayar'], 0, ',', '.') }}
                            </td>
                            <td class="hidden md:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium {{ $data['belum_bayar'] > 0 ? 'text-red-600' : 'text-gray-500' }}">
                                Rp {{ number_format($data['belum_bayar'], 0, ',', '.') }}
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold rounded-full {{ $data['status'] == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $data['status'] }}
                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                @php
                                    $persentase = $data['persentase'];
                                    $badgeClass = $persentase == 100 ? 'bg-green-100 text-green-800' : ($persentase >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                    $progressClass = $persentase == 100 ? 'bg-green-500' : ($persentase >= 50 ? 'bg-yellow-500' : 'bg-red-500');
                                @endphp
                                <div class="flex items-center">
                                    <span class="inline-flex px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full {{ $badgeClass }} mr-1 sm:mr-2">
                                        {{ number_format($persentase, 0) }}%
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 sm:h-2 mt-1">
                                    <div class="{{ $progressClass }} h-1.5 sm:h-2 rounded-full" 
                                         style="width: {{ $persentase }}%">
                                    </div>
                                </div>
                            </td>
                            <td class="px-2 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium">
                                <a href="{{ route('guru.wali-kelas.keuangan.detail', $data['siswa']->id) }}" 
                                   class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-eye text-xs"></i> 
                                    <span class="hidden sm:inline ml-1 sm:ml-2">Detail</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="px-2 sm:px-6 py-12 sm:py-16 text-center text-gray-500">
                                <i class="fas fa-info-circle text-2xl sm:text-4xl mb-3 sm:mb-4"></i>
                                <div class="text-sm sm:text-lg">
                                    @if(request('periode_type') == 'tahun_ajaran')
                                        Tidak ada data keuangan untuk tahun ajaran ini
                                    @else
                                        Tidak ada data keuangan untuk bulan ini
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    
                    @if(count($rekapKeuangan) > 0)
                    <tfoot class="bg-gray-50">
                        <tr>
                            <th colspan="2" class="px-2 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-900">Total Keseluruhan</th>
                            <th class="hidden lg:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-900"></th>
                            <th class="hidden md:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-blue-600">Rp {{ number_format($totalTagihanTahunAjaran, 0, ',', '.') }}</th>
                            <th class="hidden md:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-green-600">Rp {{ number_format($totalSudahBayarTahunAjaran, 0, ',', '.') }}</th>
                            <th class="hidden md:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-red-600">Rp {{ number_format($totalBelumBayarTahunAjaran, 0, ',', '.') }}</th>
                            <th class="hidden sm:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-900">
                                @php
                                    if(request('periode_type') == 'tahun_ajaran') {
                                        // Hitung siswa yang optimal (>= 90% pembayaran tahun ajaran)
                                        $lunas = collect($rekapKeuangan)->filter(function($data) { 
                                            $totalTagihanSiswa = $data['total_tagihan'] * 10;
                                            $sudahBayarSiswa = $data['sudah_bayar'] * 8;
                                            $persentaseTahunAjaran = $totalTagihanSiswa > 0 ? ($sudahBayarSiswa / $totalTagihanSiswa) * 100 : 0;
                                            return $persentaseTahunAjaran >= 90;
                                        })->count();
                                        $total = count($rekapKeuangan);
                                    } else {
                                        $lunas = collect($rekapKeuangan)->filter(function($data) { return $data['status'] == 'Lunas'; })->count();
                                        $total = count($rekapKeuangan);
                                    }
                                @endphp
                                {{ $lunas }}/{{ $total }} 
                                @if(request('periode_type') == 'tahun_ajaran')
                                    Optimal
                                @else
                                    Lunas
                                @endif
                            </th>
                            <th class="hidden sm:table-cell px-2 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-medium text-gray-900">
                                @php
                                    // Gunakan variabel yang sudah dihitung di atas
                                    $avgPersentase = $totalTagihanTahunAjaran > 0 ? ($totalSudahBayarTahunAjaran / $totalTagihanTahunAjaran) * 100 : 0;
                                    $avgBadgeClass = $avgPersentase >= 90 ? 'bg-green-100 text-green-800' : ($avgPersentase >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                @endphp
                                <span class="inline-flex px-1.5 sm:px-2 py-0.5 sm:py-1 text-xs font-semibold rounded-full {{ $avgBadgeClass }}">
                                    {{ number_format($avgPersentase, 1) }}%
                                </span>
                            </th>
                            <th class="px-2 sm:px-6 py-2 sm:py-3">
                                <!-- Mobile summary -->
                                <div class="sm:hidden text-xs text-gray-600 space-y-0.5">
                                    <div>Total: <span class="text-blue-600 font-medium">Rp {{ number_format($totalTagihanTahunAjaran, 0, ',', '.') }}</span></div>
                                    <div>
                                        @if(request('periode_type') == 'tahun_ajaran')
                                            Optimal: <span class="font-medium">{{ $lunas }}/{{ $total }}</span>
                                        @else
                                            Lunas: <span class="font-medium">{{ $lunas }}/{{ $total }}</span>
                                        @endif
                                    </div>
                                    <div>Avg: <span class="inline-flex px-1 py-0.5 text-xs rounded-full {{ $avgBadgeClass }}">{{ number_format($avgPersentase, 0) }}%</span></div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            @if(count($rekapKeuangan) > 0)
            <!-- Analisis Keuangan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6 mt-3 sm:mt-6">
                <div class="bg-white rounded-lg shadow-sm border border-green-200">
                    <div class="bg-green-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-lg">
                        <h6 class="text-base sm:text-lg font-semibold mb-0"><i class="fas fa-chart-pie mr-2"></i> Analisis Pembayaran</h6>
                    </div>
                    <div class="p-3 sm:p-6">
                        @php
                            $lunas = collect($rekapKeuangan)->filter(function($data) { return $data['status'] == 'Lunas'; })->count();
                            $belumLunas = count($rekapKeuangan) - $lunas;
                            $totalSiswa = count($rekapKeuangan);
                        @endphp
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-0 text-center">
                            <div class="p-3 sm:p-4 sm:border-r border-gray-200">
                                <i class="fas fa-check-circle text-3xl sm:text-5xl text-green-600 mb-3 sm:mb-4"></i>
                                <h4 class="text-2xl sm:text-3xl font-bold text-green-600 mb-2">{{ $lunas }}</h4>
                                <p class="text-sm sm:text-base text-gray-700 mb-3">Siswa Lunas</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-green-500 h-2 rounded-full" 
                                         style="width: {{ $totalSiswa > 0 ? ($lunas / $totalSiswa) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-xs sm:text-sm text-gray-500">
                                    {{ $totalSiswa > 0 ? number_format(($lunas / $totalSiswa) * 100, 1) : 0 }}%
                                </small>
                            </div>
                            <div class="p-3 sm:p-4">
                                <i class="fas fa-exclamation-triangle text-3xl sm:text-5xl text-yellow-600 mb-3 sm:mb-4"></i>
                                <h4 class="text-2xl sm:text-3xl font-bold text-yellow-600 mb-2">{{ $belumLunas }}</h4>
                                <p class="text-sm sm:text-base text-gray-700 mb-3">Siswa Menunggak</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" 
                                         style="width: {{ $totalSiswa > 0 ? ($belumLunas / $totalSiswa) * 100 : 0 }}%"></div>
                                </div>
                                <small class="text-xs sm:text-sm text-gray-500">
                                    {{ $totalSiswa > 0 ? number_format(($belumLunas / $totalSiswa) * 100, 1) : 0 }}%
                                </small>
                            </div>
                        </div>
                        
                        <hr class="my-4 sm:my-6">
                        
                        <div class="text-center">
                            <h5 class="text-lg sm:text-xl font-semibold text-blue-600 mb-3">
                                Tingkat Pelunasan: 
                                {{ $totalSiswa > 0 ? number_format(($lunas / $totalSiswa) * 100, 1) : 0 }}%
                            </h5>
                            <div class="w-full bg-gray-200 rounded-full h-3 sm:h-4">
                                <div class="bg-gradient-to-r from-green-400 to-green-600 h-3 sm:h-4 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-medium" 
                                     style="width: {{ $totalSiswa > 0 ? ($lunas / $totalSiswa) * 100 : 0 }}%">
                                    {{ $totalSiswa > 0 ? number_format(($lunas / $totalSiswa) * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-blue-200">
                    <div class="bg-blue-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-lg">
                        <h6 class="text-base sm:text-lg font-semibold mb-0"><i class="fas fa-info-circle mr-2"></i> Informasi Keuangan</h6>
                    </div>
                    <div class="p-3 sm:p-6">
                        <ul class="space-y-3 sm:space-y-4">
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-users text-blue-600 mr-3 mt-1 sm:mt-0 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <span class="font-medium text-sm sm:text-base">Total Siswa:</span>
                                    <span class="text-gray-700 text-sm sm:text-base block sm:inline">{{ count($rekapKeuangan) }} siswa</span>
                                </div>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-calendar text-green-600 mr-3 mt-1 sm:mt-0 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <span class="font-medium text-sm sm:text-base">Periode:</span>
                                    <span class="text-gray-700 text-sm sm:text-base block sm:inline">{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y') }}</span>
                                </div>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-money-bill-wave text-blue-600 mr-3 mt-1 sm:mt-0 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <span class="font-medium text-sm sm:text-base">Rata-rata Tagihan:</span>
                                    <span class="text-gray-700 text-sm sm:text-base block sm:inline">Rp {{ count($rekapKeuangan) > 0 ? number_format(collect($rekapKeuangan)->sum('total_tagihan') / count($rekapKeuangan), 0, ',', '.') : 0 }}</span>
                                </div>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-percentage text-yellow-600 mr-3 mt-1 sm:mt-0 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <span class="font-medium text-sm sm:text-base">Rata-rata Pembayaran:</span>
                                    <span class="text-gray-700 text-sm sm:text-base block sm:inline">{{ count($rekapKeuangan) > 0 ? number_format(collect($rekapKeuangan)->avg('persentase'), 1) : 0 }}%</span>
                                </div>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-1 sm:mt-0 flex-shrink-0"></i>
                                <div class="flex-1">
                                    <span class="font-medium text-sm sm:text-base">Siswa Bermasalah:</span>
                                    <span class="text-gray-700 text-sm sm:text-base block sm:inline">{{ collect($rekapKeuangan)->filter(function($data) { return $data['persentase'] < 50; })->count() }} siswa</span>
                                    <small class="text-gray-500 text-xs ml-1 block sm:inline">(< 50% pembayaran)</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if(request('periode_type') == 'tahun_ajaran' && count($rekapKeuangan) > 0)
            <!-- Breakdown Tahun Ajaran -->
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-purple-200">
                <div class="bg-purple-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-lg">
                    <h6 class="text-base sm:text-lg font-semibold mb-0">
                        <i class="fas fa-calendar-alt mr-2"></i> Breakdown Per Bulan - {{ request('tahun_ajaran', '2024/2025') }}
                    </h6>
                </div>
                <div class="p-3 sm:p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        @php
                            $months = [
                                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 
                                11 => 'November', 12 => 'Desember', 1 => 'Januari', 2 => 'Februari', 
                                3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni'
                            ];
                        @endphp
                        
                        @foreach($months as $monthNum => $monthName)
                        <div class="bg-gray-50 rounded-lg p-3 text-center border">
                            <div class="text-xs sm:text-sm font-semibold text-purple-600 mb-1">{{ $monthName }}</div>
                            @php
                                // Sample data - dalam implementasi nyata, ini harus dari controller
                                $samplePercentage = rand(60, 100);
                                $badgeClass = $samplePercentage >= 90 ? 'bg-green-100 text-green-800' : 
                                            ($samplePercentage >= 75 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                            @endphp
                            <div class="text-lg sm:text-xl font-bold text-gray-800 mb-1">{{ $samplePercentage }}%</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mb-1">
                                <div class="h-2 rounded-full {{ $samplePercentage >= 90 ? 'bg-green-500' : ($samplePercentage >= 75 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                     style="width: {{ $samplePercentage }}%"></div>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badgeClass }}">
                                {{ collect($rekapKeuangan)->filter(function($data) { return $data['status'] == 'Lunas'; })->count() }}/{{ count($rekapKeuangan) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                        <h6 class="text-sm font-semibold text-blue-800 mb-2">
                            <i class="fas fa-info-circle mr-1"></i> Informasi Tahun Ajaran
                        </h6>
                        <div class="text-xs sm:text-sm text-blue-700 space-y-1">
                            <p><strong>Periode:</strong> Juli {{ explode('/', request('tahun_ajaran', '2024/2025'))[0] }} - Juni {{ explode('/', request('tahun_ajaran', '2024/2025'))[1] }}</p>
                            <p><strong>Total Bulan:</strong> 12 bulan pembelajaran</p>
                            <p><strong>Rata-rata Pelunasan:</strong> {{ count($rekapKeuangan) > 0 ? number_format(collect($rekapKeuangan)->filter(function($data) { return $data['status'] == 'Lunas'; })->count() / count($rekapKeuangan) * 100, 1) : 0 }}%</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function submitFilter() {
    document.getElementById('filterForm').submit();
}

function togglePeriodeFilter() {
    const periodeType = document.querySelector('select[name="periode_type"]').value;
    const bulananFilter = document.getElementById('bulananFilter');
    const tahunAjaranFilter = document.getElementById('tahunAjaranFilter');
    
    if (periodeType === 'tahun_ajaran') {
        bulananFilter.style.display = 'none';
        tahunAjaranFilter.style.display = 'flex';
    } else {
        bulananFilter.style.display = 'flex';
        tahunAjaranFilter.style.display = 'none';
    }
}

// Auto submit when changing periode type
document.addEventListener('DOMContentLoaded', function() {
    const periodeTypeSelect = document.querySelector('select[name="periode_type"]');
    if (periodeTypeSelect) {
        periodeTypeSelect.addEventListener('change', function() {
            togglePeriodeFilter();
            // Auto submit after a small delay to allow UI update
            setTimeout(() => {
                submitFilter();
            }, 100);
        });
    }
});

// DataTable initialization
$(document).ready(function() {
    $('#keuanganTable').DataTable({
        "paging": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "responsive": true,
        "language": {
            "search": "Cari:",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "emptyTable": "Tidak ada data dalam tabel"
        },
        "columnDefs": [
            { "orderable": false, "targets": [1, 8, 9] } // Disable sorting for photo, progress, and action columns
        ]
    });
});
</script>
@endsection
