@extends('layouts.admin')

@section('title', 'Manajemen Keuangan Siswa')

@section('main-content')
<!-- Enhanced Header Section -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg mb-8 overflow-hidden">
    <div class="px-8 py-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-4 lg:mb-0">
                <h1 class="text-3xl font-bold text-white mb-2">
                    <i class="fas fa-wallet mr-3"></i>Manajemen Keuangan Siswa
                </h1>
                <p class="text-blue-100 text-lg">Kelola tagihan dan pembayaran siswa dengan mudah</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.keuangan.tagihan.create') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-xl shadow-lg hover:bg-blue-50 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Tagihan
                </a>
                <button onclick="exportData()"
                        class="inline-flex items-center justify-center px-6 py-3 bg-green-500 text-white font-semibold rounded-xl shadow-lg hover:bg-green-600 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <i class="fas fa-download mr-2"></i>
                    Export Data
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Section with Enhanced Design -->
@if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-gradient-to-r from-red-50 to-pink-50 border border-red-200 shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
            </div>
            <div class="ml-4">
                @if($errors->count() == 1)
                    <p class="text-red-800 font-medium">{{ $errors->first() }}</p>
                @else
                    <p class="text-red-800 font-medium mb-2">Terjadi beberapa kesalahan:</p>
                    <ul class="text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="flex items-center">
                                <i class="fas fa-circle text-xs mr-2"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
@endif

<!-- Enhanced Filter Section -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
    <div class="flex items-center mb-6">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
            <i class="fas fa-filter text-blue-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900">Filter & Pencarian</h2>
            <p class="text-gray-600">Temukan data siswa dengan mudah</p>
        </div>
    </div>

    <form method="GET" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Enhanced Search -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-search mr-2 text-gray-500"></i>Cari Siswa
                </label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Masukkan nama atau NIS siswa..."
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filter Kelas -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-school mr-2 text-gray-500"></i>Kelas
                </label>
                <div class="relative">
                    <select name="kelas" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-graduation-cap text-gray-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Filter Tahun Ajaran -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Tahun Ajaran
                </label>
                <div class="relative">
                    <select name="tahun_ajaran" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-gray-50 focus:bg-white appearance-none">
                        <option value="">Pilih Tahun Ajaran</option>
                        <option value="2025/2026" {{ request('tahun_ajaran') == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
                        <option value="2024/2025" {{ request('tahun_ajaran') == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                        <option value="2023/2024" {{ request('tahun_ajaran') == '2023/2024' ? 'selected' : '' }}>2023/2024</option>
                        <option value="2022/2023" {{ request('tahun_ajaran') == '2022/2023' ? 'selected' : '' }}>2022/2023</option>
                    </select>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-clock text-gray-400"></i>
                    </div>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
            </div>

            <!-- Enhanced Action Buttons -->
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700 opacity-0">Actions</label>
                <div class="flex flex-col space-y-2">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>
                        Cari Data
                    </button>
                    <a href="{{ route('admin.keuangan.index') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                        <i class="fas fa-refresh mr-2"></i>
                        Reset
                    </a>
                </div>
            </div>
        </div>

        <!-- Hidden sorting inputs -->
        <input type="hidden" name="sort_by" value="{{ request('sort_by', 'kelas_id') }}">
        <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
    </form>
</div>

<!-- Enhanced Bills Management Section -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-file-invoice-dollar text-purple-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Daftar Tagihan</h3>
                    <p class="text-sm text-gray-600">Kelola semua tagihan siswa</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                    {{ $tagihanList->count() }} Tagihan
                </span>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-tag text-purple-500"></i>
                            <span>Nama Tagihan</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-money-bill text-green-500"></i>
                            <span>Nominal</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                            <span>Periode</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock text-orange-500"></i>
                            <span>Jatuh Tempo</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-users text-indigo-500"></i>
                            <span>Untuk</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-info-circle text-gray-500"></i>
                            <span>Keterangan</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog"></i>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($tagihanList as $tagihan)
                <tr class="hover:bg-purple-50 transition-colors duration-200 group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-invoice text-white text-xs"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">
                                    {{ $tagihan->nama_tagihan }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm font-bold text-green-700">
                                Rp {{ number_format($tagihan->nominal,0,',','.') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $tagihan->periode }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                            <i class="fas fa-clock mr-1"></i>
                            {{ $tagihan->tanggal_jatuh_tempo }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">
                            @if($tagihan->siswa_id)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $tagihan->siswa->nama_lengkap ?? $tagihan->siswa->nama ?? '-' }}
                                </span>
                            @elseif($tagihan->kelas_id)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-graduation-cap mr-1"></i>
                                    {{ $tagihan->kelas->nama_kelas ?? '-' }}
                                </span>
                                @if($tagihan->jumlah_siswa > 1)
                                    <div class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $tagihan->jumlah_siswa }} siswa terkena
                                    </div>
                                @endif
                            @else
                                @php
                                    // Check tagihan type based on keterangan
                                    $isYearBased = strpos($tagihan->keterangan, '[Angkatan') !== false;
                                    $isKelasSpecific = strpos($tagihan->keterangan, '[Kelas ') !== false;
                                    $isSemua = strpos($tagihan->keterangan, '[Semua Siswa]') !== false;

                                    $displayInfo = '';
                                    $badgeClass = 'bg-gray-100 text-gray-800';
                                    $icon = 'fas fa-users';

                                    if ($isYearBased) {
                                        // Extract year from keterangan
                                        preg_match('/\[Angkatan (\d{4})(.*?)\]/', $tagihan->keterangan, $matches);
                                        if (isset($matches[1])) {
                                            $tahunMasuk = $matches[1];
                                            $currentYear = date('Y');
                                            $tingkat = $currentYear - $tahunMasuk + 1;
                                            $tingkatRomawi = ['', 'X', 'XI', 'XII'][$tingkat] ?? 'XIII';
                                            $displayInfo = "Angkatan {$tahunMasuk}/" . ($tahunMasuk + 1) . " (Kelas {$tingkatRomawi})";

                                            // Check if it's specific class within year
                                            if (strpos($matches[2], 'Kelas') !== false) {
                                                $displayInfo .= $matches[2];
                                            }

                                            $badgeClass = 'bg-green-100 text-green-800';
                                            $icon = 'fas fa-calendar-check';
                                        }
                                    } elseif ($isKelasSpecific) {
                                        // Extract class name from keterangan
                                        preg_match('/\[Kelas (.*?)\]/', $tagihan->keterangan, $matches);
                                        if (isset($matches[1])) {
                                            $displayInfo = "Kelas {$matches[1]}";
                                            $badgeClass = 'bg-blue-100 text-blue-800';
                                            $icon = 'fas fa-graduation-cap';
                                        }
                                    } elseif ($isSemua) {
                                        $displayInfo = 'Semua Siswa';
                                        $badgeClass = 'bg-purple-100 text-purple-800';
                                        $icon = 'fas fa-globe';
                                    } else {
                                        $displayInfo = 'Semua Siswa';
                                    }
                                @endphp

                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                    <i class="{{ $icon }} mr-1"></i>
                                    {{ $displayInfo }}
                                </span>

                                <div class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ $tagihan->jumlah_siswa }} siswa terkena
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $tagihan->keterangan }}">
                            {{ $tagihan->keterangan ?: '-' }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('admin.keuangan.tagihan.edit', $tagihan->id) }}"
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>

                            <form action="{{ route('admin.keuangan.tagihan.delete', $tagihan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tagihan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-file-invoice-dollar text-purple-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada tagihan</h3>
                            <p class="text-gray-500 mb-4">Mulai dengan membuat tagihan baru untuk siswa</p>
                            <a href="{{ route('admin.keuangan.tagihan.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Tagihan
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


<!-- Enhanced Data Table Section -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Data Keuangan Siswa</h3>
                    <p class="text-sm text-gray-600">Total {{ $siswaList->count() }} siswa ditemukan</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="toggleView('table')" id="tableViewBtn" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-table mr-1"></i> Tabel
                </button>
                <button onclick="toggleView('card')" id="cardViewBtn" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-th-large mr-1"></i> Kartu
                </button>
            </div>
        </div>
    </div>

    <!-- Table View -->
    <div id="tableView" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <!-- Enhanced Sortable Headers -->
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <button type="button" onclick="sortTable('nama_lengkap')" class="flex items-center space-x-2 hover:text-blue-600 focus:outline-none transition-colors group">
                            <i class="fas fa-user text-gray-500 group-hover:text-blue-600"></i>
                            <span>Nama Siswa</span>
                            @if(request('sort_by') == 'nama_lengkap')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                            @else
                                <i class="fas fa-sort text-gray-400 group-hover:text-blue-600"></i>
                            @endif
                        </button>
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <button type="button" onclick="sortTable('kelas_id')" class="flex items-center space-x-2 hover:text-blue-600 focus:outline-none transition-colors group">
                            <i class="fas fa-graduation-cap text-gray-500 group-hover:text-blue-600"></i>
                            <span>Kelas</span>
                            @if(request('sort_by') == 'kelas_id')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                            @else
                                <i class="fas fa-sort text-gray-400 group-hover:text-blue-600"></i>
                            @endif
                        </button>
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <button type="button" onclick="sortTable('tahun_masuk')" class="flex items-center space-x-2 hover:text-blue-600 focus:outline-none transition-colors group">
                            <i class="fas fa-calendar text-gray-500 group-hover:text-blue-600"></i>
                            <span>Tahun Masuk</span>
                            @if(request('sort_by') == 'tahun_masuk')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }} text-blue-600"></i>
                            @else
                                <i class="fas fa-sort text-gray-400 group-hover:text-blue-600"></i>
                            @endif
                        </button>
                    </th>

                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-money-bill-wave text-purple-500"></i>
                            <span>Total Tagihan</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span>Total Dibayar</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <span>Tunggakan</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span>Status</span>
                        </div>
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-cog"></i>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($siswaList as $siswa)
                <tr class="hover:bg-blue-50 transition-colors duration-200 group">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                <span class="text-white font-bold text-sm">{{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama, 0, 1)) }}</span>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $siswa->nama_lengkap ?? $siswa->nama }}
                                </div>
                                <div class="text-xs text-gray-500 flex items-center">
                                    <i class="fas fa-id-card mr-1"></i>
                                    {{ $siswa->nis }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $siswa->tahun_masuk }}/{{ $siswa->tahun_masuk + 1 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                            <span class="text-sm font-bold text-purple-700">
                                Rp {{ number_format($siswa->total_tagihan ?? 0,0,',','.') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm font-bold text-green-700">
                                Rp {{ number_format($siswa->total_telah_dibayar ?? 0,0,',','.') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm font-bold text-red-700">
                                Rp {{ number_format($siswa->tunggakan ?? 0,0,',','.') }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if(($siswa->status_keuangan ?? 'Belum Lunas') == 'Lunas')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i>
                                Lunas
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Belum Lunas
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('admin.keuangan.riwayat', $siswa->id) }}"
                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold rounded-lg hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-lg">
                            <i class="fas fa-eye mr-1"></i>
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-users text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data siswa</h3>
                            <p class="text-gray-500 mb-4">Coba ubah filter pencarian Anda atau tambah data siswa baru</p>
                            <a href="{{ route('admin.keuangan.tagihan.create') }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Tagihan
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Card View (Hidden by default) -->
    <div id="cardView" class="hidden p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($siswaList as $siswa)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">{{ strtoupper(substr($siswa->nama_lengkap ?? $siswa->nama, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 text-sm">{{ $siswa->nama_lengkap ?? $siswa->nama }}</h3>
                            <p class="text-xs text-gray-500">{{ $siswa->nis }}</p>
                        </div>
                        @if(($siswa->status_keuangan ?? 'Belum Lunas') == 'Lunas')
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        @else
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        @endif
                    </div>

                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Kelas:</span>
                            <span class="text-xs font-medium text-blue-600">{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Total Tagihan:</span>
                            <span class="text-xs font-bold text-purple-600">Rp {{ number_format($siswa->total_tagihan ?? 0,0,',','.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Dibayar:</span>
                            <span class="text-xs font-bold text-green-600">Rp {{ number_format($siswa->total_telah_dibayar ?? 0,0,',','.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-600">Tunggakan:</span>
                            <span class="text-xs font-bold text-red-600">Rp {{ number_format($siswa->tunggakan ?? 0,0,',','.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.keuangan.riwayat', $siswa->id) }}"
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada data siswa</h3>
                <p class="text-gray-500">Coba ubah filter pencarian Anda</p>
            </div>
            @endforelse
        </div>
    </div>
</div>



<script>
// Enhanced JavaScript for better user experience
function sortTable(column) {
    try {
        const form = document.querySelector('form');
        if (!form) {
            console.error('Form not found for sorting');
            return;
        }

        const currentSortBy = document.querySelector('input[name="sort_by"]');
        const currentSortOrder = document.querySelector('input[name="sort_order"]');

        if (!currentSortBy || !currentSortOrder) {
            console.error('Sort input fields not found');
            return;
        }

        let newSortOrder = 'asc';

        // If clicking the same column, toggle the order
        if (currentSortBy.value === column) {
            newSortOrder = currentSortOrder.value === 'asc' ? 'desc' : 'asc';
        }

        // Update hidden inputs
        currentSortBy.value = column;
        currentSortOrder.value = newSortOrder;

        // Show loading state
        showLoadingState();

        // Create a new URL with parameters instead of submitting form
        const url = new URL(window.location.href);
        url.searchParams.set('sort_by', column);
        url.searchParams.set('sort_order', newSortOrder);

        // Preserve existing filters
        const searchInput = document.querySelector('input[name="search"]');
        const kelasSelect = document.querySelector('select[name="kelas"]');
        const tahunSelect = document.querySelector('select[name="tahun_ajaran"]');

        if (searchInput && searchInput.value) {
            url.searchParams.set('search', searchInput.value);
        }
        if (kelasSelect && kelasSelect.value) {
            url.searchParams.set('kelas', kelasSelect.value);
        }
        if (tahunSelect && tahunSelect.value) {
            url.searchParams.set('tahun_ajaran', tahunSelect.value);
        }

        // Navigate to the new URL
        window.location.href = url.toString();

    } catch (error) {
        console.error('Error in sortTable:', error);
        showNotification('Terjadi kesalahan saat mengurutkan data', 'error');
    }
}

// Toggle between table and card view
function toggleView(viewType) {
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    const tableBtn = document.getElementById('tableViewBtn');
    const cardBtn = document.getElementById('cardViewBtn');

    if (viewType === 'table') {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        tableBtn.classList.add('bg-blue-600', 'text-white');
        tableBtn.classList.remove('bg-gray-200', 'text-gray-700');
        cardBtn.classList.add('bg-gray-200', 'text-gray-700');
        cardBtn.classList.remove('bg-blue-600', 'text-white');
        localStorage.setItem('preferredView', 'table');
    } else {
        tableView.classList.add('hidden');
        cardView.classList.remove('hidden');
        cardBtn.classList.add('bg-blue-600', 'text-white');
        cardBtn.classList.remove('bg-gray-200', 'text-gray-700');
        tableBtn.classList.add('bg-gray-200', 'text-gray-700');
        tableBtn.classList.remove('bg-blue-600', 'text-white');
        localStorage.setItem('preferredView', 'card');
    }
}

// Export data function
function exportData() {
    showNotification('Fitur export sedang dalam pengembangan', 'info');
}

// Show loading state
function showLoadingState() {
    const loadingOverlay = document.createElement('div');
    loadingOverlay.id = 'loadingOverlay';
    loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    loadingOverlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Memuat data...</span>
        </div>
    `;
    document.body.appendChild(loadingOverlay);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';

    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);

    // Remove after 3 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Enhanced form handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const searchInput = document.querySelector('input[name="search"]');

    // Load preferred view
    const preferredView = localStorage.getItem('preferredView') || 'table';
    toggleView(preferredView);

    // Enhanced search with debounce
    if (searchInput) {
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const searchIcon = this.previousElementSibling.querySelector('i');
            searchIcon.className = 'fas fa-spinner fa-spin text-gray-400';

            searchTimeout = setTimeout(() => {
                searchIcon.className = 'fas fa-user text-gray-400';
            }, 500);
        });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                showLoadingState();
                form.submit();
            }
        });
    }

    // Add smooth transitions to all interactive elements
    const interactiveElements = document.querySelectorAll('button, a, input, select');
    interactiveElements.forEach(element => {
        element.style.transition = 'all 0.2s ease';
    });

    console.log('Enhanced financial management interface initialized');
});

// Remove loading overlay when page loads
window.addEventListener('load', function() {
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.remove();
    }
});
</script>
@endsection