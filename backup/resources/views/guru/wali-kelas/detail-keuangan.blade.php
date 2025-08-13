@extends('layouts.guru')

@section('title', 'Detail Keuangan Siswa')

@push('styles')
<style>
    /* Mobile-first responsive design */
    .container-responsive {
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .container-responsive {
            padding: 1.5rem 2rem;
        }
    }

    /* Mobile responsive table */
    @media (max-width: 767px) {
        .desktop-table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        /* Mobile header adjustments */
        .mobile-header h1 {
            font-size: 1.5rem;
            line-height: 1.3;
        }

        /* Mobile stats grid */
        .mobile-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .mobile-stats .stat-card {
            padding: 0.75rem;
        }

        .mobile-stats .stat-card i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .mobile-stats .stat-card h4 {
            font-size: 1rem;
        }
    }

    @media (min-width: 768px) {
        .desktop-table {
            display: block;
        }

        .mobile-cards {
            display: none;
        }
    }

    /* Mobile financial card styling */
    .mobile-financial-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .financial-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f3f4f6;
    }

    .financial-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        line-height: 1.2;
    }

    .financial-amount {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e40af;
    }

    .financial-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .financial-detail-item {
        display: flex;
        flex-direction: column;
    }

    .financial-detail-label {
        font-size: 0.75rem;
        color: #6b7280;
        margin-bottom: 0.125rem;
    }

    .financial-detail-value {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .financial-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-badge-mobile {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
    }

    .riwayat-btn-mobile {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        background-color: #dbeafe;
        color: #1e40af;
        border: none;
        cursor: pointer;
    }

    /* Mobile student info */
    .mobile-student-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .mobile-student-info .student-avatar {
        width: 4rem;
        height: 4rem;
        align-self: center;
    }

    .mobile-student-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-responsive">
    <div class="mobile-header mb-4 md:mb-6">
        <div>
            <h1 class="text-xl md:text-3xl font-bold text-gray-900 mb-1">Detail Keuangan Siswa</h1>
        </div>
    </div>

    <!-- Info Siswa -->
    <div class="mb-4 md:mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-3 md:px-6 py-3 md:py-4 rounded-t-lg">
                <h5 class="text-base md:text-lg font-semibold mb-0">
                    <i class="fas fa-user mr-2"></i> Informasi Siswa
                </h5>
            </div>
            <div class="p-3 md:p-6">
                <div class="flex mobile-student-info md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-8">
                    <!-- Foto Siswa -->
                    <div class="flex-shrink-0">
                        <div class="student-avatar w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-blue-200 overflow-hidden bg-gray-100">
                            @if($siswa->foto && Storage::disk('public')->exists($siswa->foto))
                                <img src="{{ asset('storage/' . $siswa->foto) }}"
                                     alt="Foto {{ $siswa->nama_lengkap }}"
                                     class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama_lengkap) }}&background=3b82f6&color=ffffff&size=80"
                                     alt="Foto {{ $siswa->nama_lengkap }}"
                                     class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>

                    <!-- Info Siswa -->
                    <div class="grid mobile-student-grid md:grid-cols-3 gap-3 md:gap-4 flex-1">
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Nama Lengkap</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</p>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">NISN</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900">{{ $siswa->nisn ?? $siswa->nis ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm font-medium text-gray-500">Kelas</p>
                            <p class="text-sm md:text-lg font-semibold text-gray-900">{{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Keuangan -->
    @php
        $totalTagihan = collect($detailTagihan)->sum('nominal');
        $totalDibayar = collect($detailTagihan)->sum('total_dibayar');
        $totalSisa = collect($detailTagihan)->sum('sisa');
        $lunas = collect($detailTagihan)->where('status', 'Lunas')->count();
        $totalItem = count($detailTagihan);
    @endphp

    <div class="grid mobile-stats md:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="stat-card bg-white rounded-lg shadow-sm border border-blue-200 p-3 md:p-6 text-center">
            <i class="fas fa-money-bill-wave text-2xl md:text-3xl text-blue-600 mb-2 md:mb-3"></i>
            <h4 class="text-lg md:text-2xl font-bold text-blue-600 mb-1">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</h4>
            <p class="text-xs md:text-sm text-gray-500">Total Tagihan</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow-sm border border-green-200 p-3 md:p-6 text-center">
            <i class="fas fa-check-circle text-2xl md:text-3xl text-green-600 mb-2 md:mb-3"></i>
            <h4 class="text-lg md:text-2xl font-bold text-green-600 mb-1">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</h4>
            <p class="text-xs md:text-sm text-gray-500">Sudah Dibayar</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow-sm border border-red-200 p-3 md:p-6 text-center">
            <i class="fas fa-exclamation-triangle text-2xl md:text-3xl text-red-600 mb-2 md:mb-3"></i>
            <h4 class="text-lg md:text-2xl font-bold text-red-600 mb-1">Rp {{ number_format($totalSisa, 0, ',', '.') }}</h4>
            <p class="text-xs md:text-sm text-gray-500">Sisa Tagihan</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow-sm border border-purple-200 p-3 md:p-6 text-center">
            <i class="fas fa-chart-pie text-2xl md:text-3xl text-purple-600 mb-2 md:mb-3"></i>
            <h4 class="text-lg md:text-2xl font-bold text-purple-600 mb-1">{{ $lunas }}/{{ $totalItem }}</h4>
            <p class="text-xs md:text-sm text-gray-500">Item Lunas</p>
        </div>
    </div>

    <!-- Detail Tagihan -->
    <div class="bg-white rounded-lg shadow-sm border">
        <div class="bg-green-600 text-white px-3 md:px-6 py-3 md:py-4 rounded-t-lg">
            <h6 class="text-base md:text-lg font-semibold mb-0">
                <i class="fas fa-list mr-2"></i> Rincian Tagihan Siswa
            </h6>
        </div>

        <!-- Desktop Table -->
        <div class="desktop-table p-3 md:p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Tagihan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($detailTagihan as $tagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $tagihan['nama_tagihan'] }}</div>
                                @if($tagihan['keterangan'])
                                    <div class="text-sm text-gray-500">{{ $tagihan['keterangan'] }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                Rp {{ number_format($tagihan['total_dibayar'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $tagihan['sisa'] > 0 ? 'text-red-600' : 'text-gray-500' }}">
                                Rp {{ number_format($tagihan['sisa'], 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($tagihan['status'] == 'Lunas')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Lunas</span>
                                @elseif($tagihan['status'] == 'Sebagian')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Sebagian</span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $tagihan['tanggal_jatuh_tempo'] ? \Carbon\Carbon::parse($tagihan['tanggal_jatuh_tempo'])->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="toggleRiwayat('riwayat-{{ $tagihan['id'] }}')" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye mr-1"></i> Lihat Riwayat
                                </button>
                            </td>
                        </tr>
                        <!-- Riwayat Pembayaran (Hidden by default) -->
                        <tr id="riwayat-{{ $tagihan['id'] }}" class="hidden">
                            <td colspan="7" class="px-6 py-4 bg-gray-50">
                                @if($tagihan['pembayaran']->count() > 0)
                                    <div class="mb-3">
                                        <h6 class="font-medium text-gray-700 mb-2">Riwayat Pembayaran:</h6>
                                        <div class="space-y-2">
                                            @foreach($tagihan['pembayaran'] as $pembayaran)
                                                <div class="flex justify-between items-center bg-white p-3 rounded border">
                                                    <div>
                                                        <div class="font-medium text-sm">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
                                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y H:i') }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $pembayaran->status == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $pembayaran->status }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">Belum ada pembayaran untuk tagihan ini.</p>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center text-gray-500">
                                <i class="fas fa-info-circle text-4xl mb-4"></i>
                                <div class="text-lg">Tidak ada data tagihan untuk siswa ini</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards Layout -->
        <div class="mobile-cards p-3">
            @forelse($detailTagihan as $index => $tagihan)
            <div class="mobile-financial-card">
                <div class="financial-header">
                    <div>
                        <div class="financial-title">{{ $tagihan['nama_tagihan'] }}</div>
                        @if($tagihan['keterangan'])
                            <div class="text-xs text-gray-500 mt-1">{{ $tagihan['keterangan'] }}</div>
                        @endif
                    </div>
                    <div class="financial-amount">
                        Rp {{ number_format($tagihan['nominal'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="financial-details">
                    <div class="financial-detail-item">
                        <span class="financial-detail-label">Dibayar</span>
                        <span class="financial-detail-value text-green-600">
                            Rp {{ number_format($tagihan['total_dibayar'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="financial-detail-item">
                        <span class="financial-detail-label">Sisa</span>
                        <span class="financial-detail-value {{ $tagihan['sisa'] > 0 ? 'text-red-600' : 'text-gray-500' }}">
                            Rp {{ number_format($tagihan['sisa'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="financial-detail-item">
                        <span class="financial-detail-label">Jatuh Tempo</span>
                        <span class="financial-detail-value text-gray-700">
                            {{ $tagihan['tanggal_jatuh_tempo'] ? \Carbon\Carbon::parse($tagihan['tanggal_jatuh_tempo'])->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div class="financial-detail-item">
                        <span class="financial-detail-label">Status</span>
                        <div>
                            @if($tagihan['status'] == 'Lunas')
                                <span class="status-badge-mobile bg-green-100 text-green-800">Lunas</span>
                            @elseif($tagihan['status'] == 'Sebagian')
                                <span class="status-badge-mobile bg-yellow-100 text-yellow-800">Sebagian</span>
                            @else
                                <span class="status-badge-mobile bg-red-100 text-red-800">Belum Lunas</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="financial-actions">
                    <button onclick="toggleRiwayatMobile('riwayat-mobile-{{ $tagihan['id'] }}')"
                            class="riwayat-btn-mobile">
                        <i class="fas fa-eye mr-1"></i> Lihat Riwayat
                    </button>
                </div>

                <!-- Mobile Riwayat Pembayaran -->
                <div id="riwayat-mobile-{{ $tagihan['id'] }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                    @if($tagihan['pembayaran']->count() > 0)
                        <h6 class="text-xs font-medium text-gray-700 mb-2">Riwayat Pembayaran:</h6>
                        <div class="space-y-2">
                            @foreach($tagihan['pembayaran'] as $pembayaran)
                                <div class="bg-gray-50 p-2 rounded border">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <div class="text-xs font-medium">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
                                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <span class="text-xs px-2 py-1 rounded-full {{ $pembayaran->status == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $pembayaran->status }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500">Belum ada pembayaran untuk tagihan ini.</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="mobile-financial-card text-center py-8">
                <i class="fas fa-info-circle text-2xl text-gray-400 mb-2"></i>
                <div class="text-sm text-gray-500">Tidak ada data tagihan untuk siswa ini</div>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
function toggleRiwayat(id) {
    const element = document.getElementById(id);
    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
    } else {
        element.classList.add('hidden');
    }
}

function toggleRiwayatMobile(id) {
    const element = document.getElementById(id);
    const button = event.target.closest('button');

    if (element.classList.contains('hidden')) {
        element.classList.remove('hidden');
        button.innerHTML = '<i class="fas fa-eye-slash mr-1"></i> Sembunyikan';
    } else {
        element.classList.add('hidden');
        button.innerHTML = '<i class="fas fa-eye mr-1"></i> Lihat Riwayat';
    }
}

// Responsive behavior
function handleResize() {
    const isMobile = window.innerWidth < 768;
    const desktopTable = document.querySelector('.desktop-table');
    const mobileCards = document.querySelector('.mobile-cards');

    if (isMobile) {
        if (desktopTable) desktopTable.style.display = 'none';
        if (mobileCards) mobileCards.style.display = 'block';
    } else {
        if (desktopTable) desktopTable.style.display = 'block';
        if (mobileCards) mobileCards.style.display = 'none';
    }
}

// Initialize on load and resize
window.addEventListener('load', handleResize);
window.addEventListener('resize', handleResize);
</script>
@endsection
