@extends('layouts.siswa')

@section('title', 'Informasi Keuangan')

@section('main-content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Informasi Keuangan</h1>

    <!-- Summary Cards - Mobile First 2x2 Layout -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Tagihan -->
        <div class="bg-purple-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-purple-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-purple-800 text-xs sm:text-sm font-medium mb-1">Total Tagihan</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-purple-900">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</p>
                    <p class="text-xs text-purple-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Total Dibayar -->
        <div class="bg-green-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-green-800 text-xs sm:text-sm font-medium mb-1">Telah Dibayar</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-green-900">Rp {{ number_format($totalTelahDibayar, 0, ',', '.') }}</p>
                    <p class="text-xs text-green-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Sisa Tunggakan -->
        <div class="bg-red-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-red-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-100 text-red-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-red-800 text-xs sm:text-sm font-medium mb-1">Total Tunggakan</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-red-900">Rp {{ number_format($totalTunggakan, 0, ',', '.') }}</p>
                    <p class="text-xs text-red-600 mt-1">TA 2025/2026</p>
                </div>
            </div>
        </div>

        <!-- Status Pembayaran -->
        <div class="bg-blue-50 rounded-lg p-3 sm:p-4 lg:p-6 border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <h3 class="text-blue-800 text-xs sm:text-sm font-medium mb-1">Status</h3>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-blue-900">{{ $totalTunggakan <= 0 ? 'Lunas' : 'Belum Lunas' }}</p>
                    <p class="text-xs text-blue-600 mt-1">{{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian Tagihan -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Rincian Tagihan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($detailTagihan as $tagihan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $tagihan['nama'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['total_dibayar'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    Rp {{ number_format($tagihan['sisa'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full 
                                        {{ $tagihan['status'] === 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $tagihan['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Tidak ada tagihan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Pembayaran -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Pembayaran</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $allPembayaran = collect();
                            foreach($detailTagihan as $tagihan) {
                                $allPembayaran = $allPembayaran->concat($tagihan['pembayaran']);
                            }
                            $allPembayaran = $allPembayaran->sortByDesc('tanggal');
                        @endphp
                        
                        @forelse($allPembayaran as $pembayaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $pembayaran->keterangan }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs leading-5 font-semibold rounded-full 
                                        {{ $pembayaran->status === 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $pembayaran->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    Belum ada pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
