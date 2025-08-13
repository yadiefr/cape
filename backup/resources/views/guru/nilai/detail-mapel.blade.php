@extends('layouts.guru')

@section('title', 'Detail Nilai - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .detail-header {
        opacity: 0;
        transform: translateY(-20px);
        animation: slideInFromTop 0.6s ease-out forwards;
    }
    
    .mapel-card {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }
    
    .empty-state {
        opacity: 0;
        transform: scale(0.9);
        animation: scaleIn 0.8s ease-out 0.3s forwards;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive control */
    @media (min-width: 768px) {
        #desktop-view {
            display: block !important;
        }
        #mobile-view {
            display: none !important;
        }
    }

    @media (max-width: 767px) {
        #desktop-view {
            display: none !important;
        }
        #mobile-view {
            display: block !important;
        }
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 detail-header gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Detail Nilai Kelas {{ $kelas->nama_kelas ?? 'Tidak Diketahui' }}
            </h1>
            <p class="text-sm sm:text-base text-gray-600">
                {{ $kelas->jurusan->nama_jurusan ?? $kelas->jurusan->nama ?? 'Belum ada jurusan' }}
            </p>
        </div>
        <a href="{{ route('guru.nilai.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors text-center sm:text-left">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <!-- Desktop Table View -->
    <div id="desktop-view">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800">Pilih Mata Pelajaran</h2>
                <p class="text-sm text-gray-600">Klik mata pelajaran untuk melihat detail nilai siswa</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mapel as $index => $m)
                        <tr class="hover:bg-gray-50 mapel-card">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $m->nama }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $m->kode_mapel ?? $m->kode ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('guru.nilai.show', $kelas->id) }}?action=detail&mapel_id={{ $m->id }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500 empty-state">
                                <i class="fas fa-book text-4xl mb-3"></i>
                                <p>Tidak ada mata pelajaran yang diampu di kelas ini</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Mobile Card View -->
    <div id="mobile-view" class="hidden">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 mb-4">
            <h2 class="text-lg font-semibold text-gray-800 mb-2">Pilih Mata Pelajaran</h2>
            <p class="text-sm text-gray-600">Tap mata pelajaran untuk melihat detail nilai siswa</p>
        </div>
        
        <div class="space-y-3">
            @forelse($mapel as $index => $m)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm mapel-card">
                <div class="flex items-center justify-between">
                    <div class="flex items-center flex-1 min-w-0">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3 flex-shrink-0">
                            {{ $index + 1 }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 truncate">
                                {{ $m->nama }}
                            </h3>
                            <p class="text-xs text-gray-500">
                                Kode: {{ $m->kode_mapel ?? $m->kode ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-3">
                        <a href="{{ route('guru.nilai.show', $kelas->id) }}?action=detail&mapel_id={{ $m->id }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition-colors text-xs">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500 empty-state">
                <i class="fas fa-book text-3xl mb-3"></i>
                <p class="text-sm">Tidak ada mata pelajaran yang diampu di kelas ini</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Responsive view control
    function handleResponsiveView() {
        const desktopView = document.getElementById('desktop-view');
        const mobileView = document.getElementById('mobile-view');
        
        if (window.innerWidth >= 768) {
            if (desktopView) desktopView.style.display = 'block';
            if (mobileView) mobileView.style.display = 'none';
        } else {
            if (desktopView) desktopView.style.display = 'none';
            if (mobileView) mobileView.style.display = 'block';
        }
    }
    
    // Initial call and resize listener
    handleResponsiveView();
    window.addEventListener('resize', handleResponsiveView);
    
    // Animate mapel cards with staggered timing
    const mapelCards = document.querySelectorAll('.mapel-card');
    mapelCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 300 + (index * 100));
    });
});
</script>
@endpush
