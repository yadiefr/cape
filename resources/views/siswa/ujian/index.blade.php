@extends('layouts.siswa')

@section('title', 'Ujian Online')

@section('content')
<div class="min-h-screen bg-gray-50/30">
    <div class="w-full px-1 py-1">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Ujian Online</h1>
                    <p class="mt-2 text-gray-600">Kelola dan ikuti ujian online yang tersedia</p>
                </div>
            </div>
        </div>

        <!-- Ujian Tersedia (Sedang Berlangsung) -->
        @if($ujianTersedia->count() > 0)
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                    <h2 class="text-lg font-semibold text-green-800 flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        Ujian Sedang Berlangsung
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4">
                        @foreach($ujianTersedia as $ujian)
                        <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $ujian->nama_ujian }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $ujian->mataPelajaran->nama_mata_pelajaran }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $ujian->guru->nama }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $ujian->waktu_mulai->format('d/m/Y H:i') }} - {{ $ujian->waktu_selesai->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Durasi: {{ $ujian->durasi }} menit
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <a href="{{ route('siswa.ujian.show', $ujian->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        <i class="fas fa-play mr-2"></i>
                                        Mulai Ujian
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Ujian Mendatang -->
        @if($ujianMendatang->count() > 0)
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                    <h2 class="text-lg font-semibold text-blue-800 flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Ujian Mendatang
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid gap-4">
                        @foreach($ujianMendatang as $ujian)
                        <div class="border border-blue-200 rounded-lg p-4 bg-blue-50">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $ujian->nama_ujian }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $ujian->mataPelajaran->nama_mata_pelajaran }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $ujian->guru->nama }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $ujian->waktu_mulai->format('d/m/Y H:i') }} - {{ $ujian->waktu_selesai->format('H:i') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-hourglass-half mr-1"></i>
                                        Durasi: {{ $ujian->durasi }} menit
                                    </p>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-lg">
                                        <i class="fas fa-calendar mr-2"></i>
                                        Belum Dimulai
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Ujian Selesai & Hasil -->
        @if($ujianSelesai->count() > 0 || $hasilUjian->count() > 0)
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Ujian Selesai & Hasil
                    </h2>
                </div>
                <div class="p-6">
                    @if($hasilUjian->count() > 0)
                    <div class="grid gap-4">
                        @foreach($hasilUjian as $hasil)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900">{{ $hasil->bankSoal->nama ?? 'Ujian' }}</h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $hasil->catatan }}</p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $hasil->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="ml-4 text-right">
                                    <div class="text-2xl font-bold {{ $hasil->nilai >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($hasil->nilai, 1) }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $hasil->nilai >= 70 ? 'Lulus' : 'Tidak Lulus' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <i class="fas fa-clipboard-list text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Belum ada hasil ujian</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Empty State -->
        @if($ujianTersedia->count() == 0 && $ujianMendatang->count() == 0 && $ujianSelesai->count() == 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-12 text-center">
                <i class="fas fa-clipboard-list text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Ujian</h3>
                <p class="text-gray-600 mb-6">Saat ini belum ada ujian yang tersedia untuk kelas Anda.</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
