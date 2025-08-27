@extends('layouts.guru')

@section('title', 'Penilaian - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .nilai-card {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .nilai-card.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .nilai-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .header-section {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(-20px);
    }
    
    .header-section.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .empty-state {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .empty-state.animated {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <div class="header-section mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Penilaian Siswa</h1>
                <p class="mt-2 text-sm text-gray-600">Kelola nilai siswa untuk kelas dan mata pelajaran yang Anda ampu</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <div class="flex items-center px-3 py-2 bg-blue-50 rounded-lg">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    <span class="text-sm font-medium text-blue-700">{{ $kelas->count() }} Kelas</span>
                </div>
            </div>
        </div>
    </div>
    
    <div>
        <div class="flex items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Kelas</h2>
            <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                {{ $kelas->count() }} kelas
            </span>
        </div>
        
        @if($kelas->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($kelas as $k)
            <div class="nilai-card bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold text-gray-900 truncate">{{ $k->nama_kelas ?? 'Kelas Tidak Diketahui' }}</h3>
                        <p class="text-sm text-gray-600 mt-1 truncate">{{ $k->jurusan->nama_jurusan ?? $k->jurusan->nama ?? 'Jurusan Tidak Diketahui' }}</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium flex-shrink-0 ml-2">
                        <i class="fas fa-chart-line mr-1"></i>
                        Nilai
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-xl font-bold text-gray-900">{{ $k->siswa ? $k->siswa->count() : 0 }}</div>
                        <div class="text-xs text-gray-600">Siswa</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="text-xl font-bold text-gray-900">{{ $k->mapel_count ?? 0 }}</div>
                        <div class="text-xs text-gray-600">Mapel</div>
                    </div>
                </div>
                
                @if(isset($k->taught_mapels) && $k->taught_mapels->count() > 0)
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Mata Pelajaran:</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($k->taught_mapels as $mapel)
                                <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">
                                    {{ $mapel->nama ?? 'N/A' }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        @if($k->taught_mapels->count() == 1)
                            {{-- Jika hanya 1 mata pelajaran, langsung ke input nilai --}}
                            <a href="{{ route('guru.nilai.create', ['kelas_id' => $k->id, 'mapel_id' => $k->taught_mapels->first()->id]) }}" 
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition-colors text-sm font-medium block">
                                <i class="fas fa-edit mr-2"></i>
                                Input Nilai
                            </a>
                        @else
                            {{-- Jika lebih dari 1 mata pelajaran, pilih dulu mata pelajaran --}}
                            <a href="{{ route('guru.nilai.show', $k->id) }}" 
                               class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition-colors text-sm font-medium block">
                                <i class="fas fa-list mr-2"></i>
                                Lihat Mata Pelajaran di Kelas Ini
                            </a>
                        @endif
                    </div>
                @else
                    <div class="text-center py-4">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-book-open text-gray-400"></i>
                        </div>
                        <p class="text-gray-500 text-xs">Tidak ada mata pelajaran</p>
                    </div>
                @endif
            </div>
        @endforeach
        </div>
        @else
        <div class="empty-state text-center py-12 bg-gray-50 rounded-xl">
            <div class="max-w-md mx-auto">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Belum Ada Kelas</h3>
                <p class="text-sm text-gray-500 mb-4">Anda belum memiliki jadwal mengajar di kelas manapun.</p>
                <a href="{{ route('guru.jadwal.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Lihat Jadwal
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
// Add animation on page load
document.addEventListener('DOMContentLoaded', function() {
    // Animate header section
    const headerSection = document.querySelector('.header-section');
    if (headerSection) {
        setTimeout(() => {
            headerSection.classList.add('animated');
        }, 100);
    }
    
    // Animate nilai cards
    const nilaiCards = document.querySelectorAll('.nilai-card');
    nilaiCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animated');
        }, 300 + (index * 100)); // Start after header, with 100ms intervals
    });
    
    // Animate empty state if exists
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        setTimeout(() => {
            emptyState.classList.add('animated');
        }, 300);
    }
});
</script>
@endpush 