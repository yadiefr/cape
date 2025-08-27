@extends('layouts.guru')

@section('title', 'Detail Penilaian - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .header-section {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .header-section.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .content-card {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .content-card.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .mapel-card {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .mapel-card.animated {
        opacity: 1;
        transform: translateY(0);
    }
    
    .mapel-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <div class="header-section flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
                Penilaian Kelas {{ $kelas->nama_kelas ?? 'Tidak Diketahui' }}
            </h1>
            <p class="text-sm sm:text-base text-gray-600">
                {{ $kelas->jurusan->nama_jurusan ?? $kelas->jurusan->nama ?? 'Belum ada jurusan' }}
            </p>
        </div>
        <a href="{{ route('guru.nilai.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors text-center sm:text-left">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="content-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Pilih Mata Pelajaran</h2>
        </div>

        <div class="p-5">
            <p class="text-gray-600 mb-4">Pilih mata pelajaran yang ingin Anda input nilainya:</p>
                        
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($mapel as $m)
                <div class="mapel-card border border-gray-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-md transition-all">
                    <h3 class="font-medium text-gray-800">{{ $m->nama ?? 'Mata Pelajaran Tidak Diketahui' }}</h3>
                    <p class="text-sm text-gray-500 mb-3">{{ $m->kode_mapel ?? $m->kode ?? 'Tidak ada kode' }}</p>
                    
                    <div class="space-y-3">
                        {{-- Tombol input nilai langsung --}}
                        <a href="{{ route('guru.nilai.create', ['kelas_id' => $kelas->id, 'mapel_id' => $m->id]) }}"
                           class="block w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg text-center transition-colors">
                            <i class="fas fa-edit mr-1"></i> Input Nilai
                        </a>
                        
                        <a href="{{ route('guru.nilai.show', $kelas->id) }}?action=detail&mapel_id={{ $m->id }}" 
                            class="block w-full bg-green-50 hover:bg-green-100 text-green-700 text-center py-2 px-3 rounded-lg transition-colors text-xs font-medium">
                            <i class="fas fa-chart-bar mr-1"></i>
                            Lihat Detail Nilai
                        </a>
                    </div>

                </div>
                @empty
                <div class="col-span-full text-center p-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-4">
                        <i class="fas fa-book text-blue-400 text-xl"></i>
                    </div>
                    <p class="text-gray-600">Anda tidak mengajar mata pelajaran di kelas ini</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>



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
    
    // Animate content card
    const contentCard = document.querySelector('.content-card');
    if (contentCard) {
        setTimeout(() => {
            contentCard.classList.add('animated');
        }, 200);
    }
    
    // Animate mapel cards
    const mapelCards = document.querySelectorAll('.mapel-card');
    mapelCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('animated');
        }, 400 + (index * 100)); // Start after content card, with 100ms intervals
    });
});
</script>
@endpush
@endsection
