@extends('layouts.siswa')

@section('content')
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6" x-data="laporanKas()">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 mb-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div class="flex-1">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Laporan Kas Kelas</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">{{ $kelas->nama_kelas }} - {{ $kelas->tahun_ajaran }}</p>
            </div>

            <!-- Filter Periode -->
            <div class="w-full lg:w-auto">
                <form method="GET" action="{{ route('siswa.bendahara.laporan-kas') }}" class="flex flex-col sm:flex-row flex-wrap gap-2">
                    <select name="periode" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                        <option value="bulan" {{ $periode == 'bulan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="semester" {{ $periode == 'semester' ? 'selected' : '' }}>Semester</option>
                        <option value="tahun" {{ $periode == 'tahun' ? 'selected' : '' }}>Tahunan</option>
                    </select>

                    @if($periode == 'bulan')
                        <select name="bulan" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month($i)->locale('id')->monthName }}
                                </option>
                            @endfor
                        </select>
                    @endif

                    @if($periode == 'semester')
                        <select name="semester" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                            <option value="1" {{ request('semester', 1) == 1 ? 'selected' : '' }}>Semester 1 (Jul-Des)</option>
                            <option value="2" {{ request('semester', 1) == 2 ? 'selected' : '' }}>Semester 2 (Jan-Jun)</option>
                        </select>
                    @endif

                    <select name="tahun" onchange="this.form.submit()" class="border border-gray-300 rounded-md px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-auto">
                        @for($i = 2020; $i <= 2030; $i++)
                            <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </form>
            </div>
        </div>

        <!-- Periode Info -->
        <div class="mt-4 text-xs sm:text-sm text-gray-600">
            <span class="font-medium">Periode:</span>
            <span class="block sm:inline mt-1 sm:mt-0">{{ $tanggalMulai->locale('id')->format('d F Y') }} - {{ $tanggalSelesai->locale('id')->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
        <!-- Saldo Awal -->
        <div class="bg-yellow-50 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-yellow-200 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-yellow-600 truncate">Saldo Awal Periode</p>
                    <p class="text-lg sm:text-2xl font-semibold text-gray-900 truncate">
                        Rp {{ number_format($saldoAwal, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Kas Masuk -->
        <div class="bg-green-50 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-200 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-green-600 truncate">Kas Masuk</p>
                    <p class="text-lg sm:text-2xl font-semibold text-green-900 truncate">
                        Rp {{ number_format($totalKasMasukPeriode, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-green-600 truncate">{{ $kasMasuk->count() }} transaksi</p>
                </div>
            </div>
        </div>

        <!-- Kas Keluar -->
        <div class="bg-red-50 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-200 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-red-600 truncate">Kas Keluar</p>
                    <p class="text-lg sm:text-2xl font-semibold text-red-900 truncate">
                        Rp {{ number_format($totalKasKeluarPeriode, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-red-600 truncate">{{ $kasKeluar->count() }} transaksi</p>
                </div>
            </div>
        </div>

        <!-- Saldo Akhir -->
        <div class="bg-blue-50 rounded-lg p-4 sm:p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-200 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4 min-w-0 flex-1">
                    <p class="text-xs sm:text-sm font-medium text-blue-600 truncate">Saldo Akhir</p>
                    <p class="text-lg sm:text-2xl font-semibold text-blue-900 truncate">
                        Rp {{ number_format($saldoKeseluruhan, 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-blue-600 truncate">Keseluruhan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 mb-6">
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Grafik Kas Masuk vs Kas Keluar</h3>
        <div class="relative h-48 sm:h-64">
            <canvas id="kasChart"></canvas>
        </div>
    </div>

    <!-- Tabs untuk detail -->
    <div class="bg-white shadow rounded-lg">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex overflow-x-auto space-x-4 sm:space-x-8 px-4 sm:px-0" aria-label="Tabs">
                <button @click="activeTab = 'ringkasan'"
                        :class="activeTab === 'ringkasan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0">
                    Ringkasan
                </button>
                <button @click="activeTab = 'kas-masuk'"
                        :class="activeTab === 'kas-masuk' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0">
                    <span class="hidden sm:inline">Detail </span>Kas Masuk
                </button>
                <button @click="activeTab = 'kas-keluar'"
                        :class="activeTab === 'kas-keluar' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0">
                    <span class="hidden sm:inline">Detail </span>Kas Keluar
                </button>
                <button @click="activeTab = 'per-siswa'"
                        :class="activeTab === 'per-siswa' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-xs sm:text-sm flex-shrink-0">
                    Per Siswa
                </button>
            </nav>
        </div>

        <div class="p-4 sm:p-6">
            <!-- Tab Ringkasan -->
            <div x-show="activeTab === 'ringkasan'">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Kas Masuk per Kategori -->
                    <div>
                        <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Kas Masuk per Kategori</h4>
                        <div class="space-y-3">
                            @foreach($kasMasukPerKategori as $kategori => $data)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 text-sm sm:text-base truncate">{{ $data['kategori'] }}</p>
                                    <p class="text-xs sm:text-sm text-gray-600">{{ $data['jumlah'] }} transaksi</p>
                                </div>
                                <p class="font-semibold text-green-600 text-sm sm:text-base ml-2">
                                    Rp {{ number_format($data['total'], 0, ',', '.') }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Summary Periode -->
                    <div>
                        <h4 class="text-base sm:text-lg font-medium text-gray-900 mb-4">Ringkasan Periode</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="font-medium text-gray-700 text-sm sm:text-base">Saldo Awal</span>
                                <span class="font-semibold text-gray-900 text-sm sm:text-base">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="font-medium text-green-700 text-sm sm:text-base">Total Kas Masuk</span>
                                <span class="font-semibold text-green-900 text-sm sm:text-base">Rp {{ number_format($totalKasMasukPeriode, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-red-50 rounded-lg">
                                <span class="font-medium text-red-700 text-sm sm:text-base">Total Kas Keluar</span>
                                <span class="font-semibold text-red-900 text-sm sm:text-base">Rp {{ number_format($totalKasKeluarPeriode, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg border-t-2 border-blue-200">
                                <span class="font-medium text-blue-700 text-sm sm:text-base">Perubahan Saldo</span>
                                <span class="font-semibold {{ $saldoPeriode >= 0 ? 'text-green-900' : 'text-red-900' }} text-sm sm:text-base">
                                    {{ $saldoPeriode >= 0 ? '+' : '' }}Rp {{ number_format($saldoPeriode, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Kas Masuk -->
            <div x-show="activeTab === 'kas-masuk'">
                <!-- Desktop Table -->
                <div class="desktop-table hidden lg:block overflow-x-auto" style="display: none !important;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Siswa
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nominal
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kasMasuk as $kas)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ Carbon\Carbon::parse($kas->tanggal)->locale('id')->format('d M Y') }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $kas->siswa ? $kas->siswa->nama_lengkap : '-' }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        {{ $kas->kategori === 'iuran_bulanan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $kas->kategori === 'iuran_bulanan' ? 'Iuran Bulanan' : 'Lainnya' }}
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">
                                    {{ $kas->keterangan }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-green-600">
                                    Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 lg:px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-gray-400 text-2xl mb-2 block"></i>
                                    Tidak ada data kas masuk pada periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards lg:hidden space-y-4" style="display: block !important;">
                    @forelse($kasMasuk as $kas)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $kas->siswa ? $kas->siswa->nama_lengkap : 'System' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ Carbon\Carbon::parse($kas->tanggal)->locale('id')->format('d M Y') }}
                                </p>
                            </div>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ml-2
                                {{ $kas->kategori === 'iuran_bulanan' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                {{ $kas->kategori === 'iuran_bulanan' ? 'Iuran Bulanan' : 'Lainnya' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <p class="text-sm text-gray-700">{{ $kas->keterangan }}</p>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500 font-medium">Nominal</span>
                            <span class="text-lg font-bold text-green-600">
                                Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data kas masuk pada periode ini</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Kas Keluar -->
            <div x-show="activeTab === 'kas-keluar'">
                <!-- Desktop Table -->
                <div class="desktop-table hidden lg:block overflow-x-auto" style="display: none !important;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nominal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bukti
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kasKeluar as $kas)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ Carbon\Carbon::parse($kas->tanggal)->locale('id')->format('d M Y') }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">
                                    {{ $kas->keterangan }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-right text-red-600">
                                    Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center">
                                    @if($kas->bukti_transaksi)
                                        <button @click="showBukti('{{ asset('storage/' . $kas->bukti_transaksi) }}', '{{ pathinfo($kas->bukti_transaksi, PATHINFO_EXTENSION) }}')"
                                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors">
                                            <i class="fas fa-eye mr-1"></i>Lihat
                                        </button>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 lg:px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-receipt text-gray-400 text-2xl mb-2 block"></i>
                                    Tidak ada data kas keluar pada periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards lg:hidden space-y-4" style="display: block !important;">
                    @forelse($kasKeluar as $kas)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">
                                    {{ Carbon\Carbon::parse($kas->tanggal)->locale('id')->format('d M Y') }}
                                </p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $kas->keterangan }}
                                </p>
                            </div>
                            @if($kas->bukti_transaksi)
                                <button @click="showBukti('{{ asset('storage/' . $kas->bukti_transaksi) }}', '{{ pathinfo($kas->bukti_transaksi, PATHINFO_EXTENSION) }}')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs ml-2 flex-shrink-0">
                                    <i class="fas fa-eye"></i>
                                </button>
                            @endif
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500 font-medium">Nominal</span>
                            <span class="text-lg font-bold text-red-600">
                                Rp {{ number_format($kas->nominal, 0, ',', '.') }}
                            </span>
                        </div>
                        @if($kas->bukti_transaksi)
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <span class="text-xs text-green-600 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>
                                Bukti tersedia
                            </span>
                        </div>
                        @else
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-400 flex items-center">
                                <i class="fas fa-times-circle mr-1"></i>
                                Tidak ada bukti
                            </span>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data kas keluar pada periode ini</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tab Per Siswa -->
            <div x-show="activeTab === 'per-siswa'">
                <div class="mb-4 p-3 sm:p-4 bg-blue-50 rounded-lg">
                    <p class="text-xs sm:text-sm text-blue-800">
                        <strong>Catatan:</strong> Iuran bulanan dibagi rata ke semua siswa. Jumlah transaksi menunjukkan hari kontribusi iuran + transaksi kas lainnya yang spesifik.
                    </p>
                </div>

                <!-- Desktop Table -->
                <div class="desktop-table hidden lg:block overflow-x-auto" style="display: none !important;">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama Siswa
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Hari Kontribusi
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Kas Masuk
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($kasMasukPerSiswa as $siswaId => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $data['siswa']->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $data['siswa']->nis }}</div>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $data['jumlah'] }} hari
                                    </span>
                                </td>
                                <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600">
                                    Rp {{ number_format($data['total'], 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 lg:px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users text-gray-400 text-2xl mb-2 block"></i>
                                    Tidak ada data kas masuk per siswa pada periode ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards lg:hidden space-y-3" style="display: block !important;">
                    @forelse($kasMasukPerSiswa as $siswaId => $data)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $data['siswa']->nama_lengkap }}</p>
                                <p class="text-xs text-gray-500">{{ $data['siswa']->nis }}</p>
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 ml-2">
                                {{ $data['jumlah'] }} hari
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500 font-medium">Total Kas Masuk</span>
                            <span class="text-lg font-bold text-green-600">
                                Rp {{ number_format($data['total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <i class="fas fa-users text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data kas masuk per siswa pada periode ini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lihat Bukti Transaksi -->
    <div x-show="showBuktiModal" 
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showBuktiModal = false"></div>

            <div x-show="showBuktiModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                                Bukti Transaksi
                            </h3>
                            
                            <div class="max-h-96 overflow-auto">
                                <!-- Image Preview -->
                                <div x-show="buktiType === 'image'" class="text-center">
                                    <img :src="buktiUrl" 
                                         alt="Bukti Transaksi" 
                                         class="max-w-full h-auto rounded-lg shadow-lg mx-auto"
                                         style="max-height: 400px;">
                                </div>
                                
                                <!-- PDF Preview -->
                                <div x-show="buktiType === 'pdf'" class="text-center">
                                    <div class="bg-gray-100 p-8 rounded-lg">
                                        <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                        <p class="text-gray-600 mb-4">File PDF - Klik tombol di bawah untuk membuka</p>
                                        <a :href="buktiUrl" target="_blank" 
                                           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                                            Buka PDF
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showBuktiModal = false"
                            class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('laporanKas', () => ({
        activeTab: 'ringkasan',
        showBuktiModal: false,
        buktiUrl: '',
        buktiType: '',
        
        init() {
            this.initChart();
        },
        
        showBukti(url, extension) {
            this.buktiUrl = url;
            
            // Determine file type
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (imageExtensions.includes(extension.toLowerCase())) {
                this.buktiType = 'image';
            } else if (extension.toLowerCase() === 'pdf') {
                this.buktiType = 'pdf';
            } else {
                this.buktiType = 'other';
            }
            
            this.showBuktiModal = true;
        },
        
        initChart() {
            const ctx = document.getElementById('kasChart').getContext('2d');
            const chartData = @json($chartData);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Kas Masuk',
                            data: chartData.dataMasuk,
                            backgroundColor: 'rgba(34, 197, 94, 0.6)',
                            borderColor: 'rgba(34, 197, 94, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Kas Keluar',
                            data: chartData.dataKeluar,
                            backgroundColor: 'rgba(239, 68, 68, 0.6)',
                            borderColor: 'rgba(239, 68, 68, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        }
    }));
});
</script>

<style>
/* Mobile Responsive Styles for Siswa Bendahara Laporan Kas */
@media (max-width: 1023px) {
    /* Force hide desktop tables */
    .desktop-table {
        display: none !important;
        visibility: hidden !important;
    }

    /* Force show mobile cards */
    .mobile-cards {
        display: block !important;
        visibility: visible !important;
    }

    /* Hide scrollbar on mobile for tabs */
    .overflow-x-auto::-webkit-scrollbar {
        display: none;
    }

    .overflow-x-auto {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
}

/* Desktop styles */
@media (min-width: 1024px) {
    .desktop-table {
        display: block !important;
        visibility: visible !important;
    }

    .mobile-cards {
        display: none !important;
        visibility: hidden !important;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .space-y-3 > * + * {
        margin-top: 0.75rem;
    }

    .space-y-4 > * + * {
        margin-top: 1rem;
    }

    /* Ensure text doesn't break layout */
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}

/* Print styles */
@media print {
    .no-print {
        display: none !important;
    }

    .bg-gray-50, .bg-green-50, .bg-red-50, .bg-blue-50, .bg-yellow-50 {
        background: white !important;
        border: 1px solid #e5e7eb !important;
    }

    .shadow {
        box-shadow: none !important;
    }
}
</style>
@endsection
