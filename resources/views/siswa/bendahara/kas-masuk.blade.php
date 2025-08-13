@extends('layouts.siswa')

@section('title', 'Kas Masuk Harian - Bendahara')

@section('content')
<div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kas Masuk Harian</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Kas Masuk Bulan -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6 border border-emerald-200 hover:shadow-md transition-all h-full">
            <div class="flex flex-col h-full">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="p-2 bg-emerald-500 rounded-full mr-2 sm:mr-3 flex-shrink-0">
                        <i class="fas fa-calendar-alt text-white text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <p class="text-emerald-700 text-xs sm:text-sm font-medium truncate">Kas Masuk Bulan</p>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="text-base sm:text-lg lg:text-2xl font-bold text-emerald-800 truncate">Rp {{ number_format($totalKasMasukBulan, 0, ',', '.') }}</p>
                    <p class="text-emerald-600 text-xs mt-1">{{ $tanggalAwal->locale('id')->monthName }} {{ $tahun }}</p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi Bulan -->
        <div class="bg-gradient-to-br from-sky-50 to-sky-100 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6 border border-sky-200 hover:shadow-md transition-all h-full">
            <div class="flex flex-col h-full">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="p-2 bg-sky-500 rounded-full mr-2 sm:mr-3 flex-shrink-0">
                        <i class="fas fa-list text-white text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <p class="text-sky-700 text-xs sm:text-sm font-medium truncate">Transaksi Bulan</p>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="text-base sm:text-lg lg:text-2xl font-bold text-sky-800">{{ $totalTransaksiBulan }}</p>
                    <p class="text-sky-600 text-xs mt-1">{{ $tanggalAwal->locale('id')->monthName }} {{ $tahun }}</p>
                </div>
            </div>
        </div>

        <!-- Total Kas Masuk Keseluruhan -->
        <div class="bg-gradient-to-br from-violet-50 to-violet-100 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6 border border-violet-200 hover:shadow-md transition-all h-full">
            <div class="flex flex-col h-full">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="p-2 bg-violet-500 rounded-full mr-2 sm:mr-3 flex-shrink-0">
                        <i class="fas fa-coins text-white text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <p class="text-violet-700 text-xs sm:text-sm font-medium truncate">Total Kas Masuk</p>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="text-base sm:text-lg lg:text-2xl font-bold text-violet-800 truncate">Rp {{ number_format($totalKasMasukKeseluruhan, 0, ',', '.') }}</p>
                    <p class="text-violet-600 text-xs mt-1">Keseluruhan</p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi Keseluruhan -->
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-lg shadow-sm p-3 sm:p-4 lg:p-6 border border-amber-200 hover:shadow-md transition-all h-full">
            <div class="flex flex-col h-full">
                <div class="flex items-center mb-2 sm:mb-3">
                    <div class="p-2 bg-amber-500 rounded-full mr-2 sm:mr-3 flex-shrink-0">
                        <i class="fas fa-chart-line text-white text-sm sm:text-base lg:text-lg"></i>
                    </div>
                    <p class="text-amber-700 text-xs sm:text-sm font-medium truncate">Total Transaksi</p>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="text-base sm:text-lg lg:text-2xl font-bold text-amber-800">{{ $totalTransaksiKeseluruhan }}</p>
                    <p class="text-amber-600 text-xs mt-1">Keseluruhan</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <!-- Navigasi Bulan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <a href="{{ route('siswa.bendahara.kas-masuk', ['bulan' => $bulanSebelumnya->month, 'tahun' => $bulanSebelumnya->year]) }}" 
               class="flex items-center text-gray-600 hover:text-blue-600 transition duration-200">
                <i class="fas fa-chevron-left mr-2"></i>
                {{ $bulanSebelumnya->locale('id')->monthName }} {{ $bulanSebelumnya->year }}
            </a>
            
            <h2 class="text-xl font-bold text-gray-800">
                {{ $tanggalAwal->locale('id')->monthName }} {{ $tahun }}
            </h2>
            
            <a href="{{ route('siswa.bendahara.kas-masuk', ['bulan' => $bulanSelanjutnya->month, 'tahun' => $bulanSelanjutnya->year]) }}" 
               class="flex items-center text-gray-600 hover:text-blue-600 transition duration-200">
                {{ $bulanSelanjutnya->locale('id')->monthName }} {{ $bulanSelanjutnya->year }}
                <i class="fas fa-chevron-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Tabel Kas Masuk Harian -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800">
                Kas Masuk Harian - {{ $tanggalAwal->locale('id')->monthName }} {{ $tahun }}
            </h3>
        </div>

        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="desktop-table hidden lg:block overflow-x-auto" style="display: block !important;">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Masuk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($hariDalamBulan as $hari)
                    <tr class="hover:bg-gray-50 {{ $hari['total'] > 0 ? 'bg-green-50' : '' }}">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $hari['tanggal_formatted'] }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($hari['tanggal'])->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700">{{ $hari['hari_nama'] }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($hari['jumlah_transaksi'] > 0)
                                <div class="bg-gray-100 rounded p-2 text-xs">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <span class="font-medium text-green-700">{{ $hari['jumlah_transaksi'] }} transaksi</span>
                                            <div class="text-gray-600 mt-1">Total kas masuk hari ini</div>
                                        </div>
                                        <span class="text-green-700 font-medium ml-2">
                                            Rp {{ number_format($hari['total'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-400 text-sm italic">Tidak ada transaksi</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center">
                            <div class="flex space-x-2 justify-center">
                                @if($hari['jumlah_transaksi'] > 0)
                                    <a href="{{ route('siswa.bendahara.edit-kas-masuk', ['tanggal' => $hari['tanggal']]) }}"
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded text-xs transition duration-200">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </a>
                                @else
                                    <a href="{{ route('siswa.bendahara.input-kas-masuk', ['tanggal' => $hari['tanggal']]) }}"
                                       class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded text-xs transition duration-200">
                                        <i class="fas fa-plus mr-1"></i>Tambah
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (Hidden on Desktop) -->
        <div class="mobile-cards lg:hidden p-4 space-y-4">
            @foreach($hariDalamBulan as $hari)
            <div class="border border-gray-200 rounded-lg p-4 {{ $hari['total'] > 0 ? 'bg-green-50 border-green-200' : 'bg-white' }}">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $hari['tanggal_formatted'] }}</p>
                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($hari['tanggal'])->format('d/m/Y') }} - {{ $hari['hari_nama'] }}</p>
                    </div>
                    @if($hari['total'] > 0)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ $hari['jumlah_transaksi'] }} transaksi
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            Belum ada
                        </span>
                    @endif
                </div>

                @if($hari['jumlah_transaksi'] > 0)
                    <div class="mb-3 p-3 bg-white rounded border">
                        <p class="text-sm text-gray-700 mb-1">Total kas masuk hari ini</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($hari['total'], 0, ',', '.') }}</p>
                    </div>
                @else
                    <div class="mb-3 p-3 bg-gray-50 rounded border">
                        <p class="text-sm text-gray-500 italic">Tidak ada transaksi</p>
                    </div>
                @endif
                
                <div class="flex justify-end">
                    @if($hari['jumlah_transaksi'] > 0)
                        <a href="{{ route('siswa.bendahara.edit-kas-masuk', ['tanggal' => $hari['tanggal']]) }}"
                           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200 flex items-center">
                            <i class="fas fa-edit mr-2"></i>Edit Transaksi
                        </a>
                    @else
                        <a href="{{ route('siswa.bendahara.input-kas-masuk', ['tanggal' => $hari['tanggal']]) }}"
                           class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200 flex items-center">
                            <i class="fas fa-plus mr-2"></i>Tambah Transaksi
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
/* Mobile Responsive Styles for Kas Masuk */
@media (max-width: 1023px) {
    /* COMPLETELY REMOVE desktop tables from layout on mobile/tablet */
    .desktop-table {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
    }

    .hidden.lg\\:block {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* FORCE SHOW mobile cards on mobile/tablet */
    .mobile-cards {
        display: block !important;
        visibility: visible !important;
    }

    .lg\\:hidden {
        display: block !important;
    }
}

/* Desktop styles */
@media (min-width: 1024px) {
    /* FORCE SHOW desktop tables */
    .desktop-table {
        display: block !important;
        visibility: visible !important;
    }

    .hidden.lg\\:block {
        display: block !important;
    }

    /* FORCE HIDE mobile cards */
    .mobile-cards {
        display: none !important;
        visibility: hidden !important;
    }

    .lg\\:hidden {
        display: none !important;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .space-y-4 > * + * {
        margin-top: 1rem;
    }

    /* Ensure mobile cards are visible and properly styled */
    .mobile-cards > div {
        display: block !important;
        margin-bottom: 1rem;
    }

    /* Additional mobile-specific styling */
    .mobile-cards .bg-white {
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    /* Remove any empty space from hidden desktop table */
    .mobile-cards {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* Ensure no spacing issues between desktop table and mobile cards */
    .desktop-table + .mobile-cards {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
}

/* Mobile 2x2 Grid Layout for Statistics Cards */
@media (max-width: 640px) {
    /* Ensure 2x2 grid layout on mobile */
    .grid-cols-2 {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    /* Ensure equal height for all cards */
    .grid-cols-2 > div {
        min-height: 120px;
        display: flex;
        flex-direction: column;
    }

    /* Responsive text sizing for mobile */
    .text-base {
        font-size: 0.875rem !important;
    }

    .text-lg {
        font-size: 1rem !important;
    }

    /* Ensure proper spacing in mobile cards */
    .p-3 {
        padding: 0.75rem !important;
    }

    /* Icon sizing for mobile */
    .text-sm {
        font-size: 0.75rem !important;
    }
}

/* Tablet optimizations */
@media (min-width: 641px) and (max-width: 1023px) {
    .grid-cols-2 > div {
        min-height: 140px;
    }
}

/* Desktop optimizations */
@media (min-width: 1024px) {
    .lg\\:grid-cols-4 > div {
        min-height: 160px;
    }
}
</style>
@endsection