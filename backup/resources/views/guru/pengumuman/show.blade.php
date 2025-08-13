@extends('layouts.guru')

@section('title', 'Detail Pengumuman')

@section('content')
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('guru.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                <li><a href="{{ route('guru.pengumuman.index') }}" class="hover:text-blue-600">Pengumuman</a></li>
                <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                <li class="text-gray-900 font-medium">Detail</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Detail Pengumuman</h1>
            <p class="text-gray-600">Informasi lengkap pengumuman dari sekolah</p>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header Pengumuman -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-bullhorn text-lg"></i>
                            </div>
                            <span class="text-blue-100 text-sm font-medium">Pengumuman Resmi</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl font-bold mb-3">{{ $pengumuman->judul }}</h1>
                        <div class="flex flex-wrap items-center gap-4 text-blue-100">
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span class="text-sm">{{ \Carbon\Carbon::parse($pengumuman->tanggal_mulai)->format('d F Y') }}</span>
                            </span>
                            @if($pengumuman->tanggal_selesai)
                            <span class="flex items-center">
                                <i class="fas fa-calendar-times mr-2"></i>
                                <span class="text-sm">Berlaku sampai {{ \Carbon\Carbon::parse($pengumuman->tanggal_selesai)->format('d F Y') }}</span>
                            </span>
                            @endif
                            <span class="flex items-center bg-white bg-opacity-20 px-3 py-1 rounded-lg">
                                <i class="fas fa-users mr-2"></i>
                                <span class="text-sm capitalize">{{ $pengumuman->target_role == 'all' ? 'Semua' : ucfirst($pengumuman->target_role) }}</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Isi Pengumuman -->
            <div class="p-6">
                <div class="prose max-w-none">
                    {!! nl2br(e($pengumuman->isi)) !!}
                </div>

                @if($pengumuman->file_lampiran)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-paperclip text-gray-500 mr-2"></i>
                        Lampiran
                    </h3>
                    <a href="{{ asset('storage/' . $pengumuman->file_lampiran) }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download Lampiran
                    </a>
                </div>
                @endif

                <!-- Info Tambahan -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Dipublikasikan:</span>
                            {{ \Carbon\Carbon::parse($pengumuman->created_at)->format('d F Y, H:i') }} WIB
                        </div>
                        @if($pengumuman->updated_at != $pengumuman->created_at)
                        <div>
                            <span class="font-medium">Terakhir diupdate:</span>
                            {{ \Carbon\Carbon::parse($pengumuman->updated_at)->format('d F Y, H:i') }} WIB
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('guru.pengumuman.index') }}" 
               class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Pengumuman
            </a>
            
            <button onclick="window.print()" 
                    class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-print mr-2"></i>
                Cetak Pengumuman
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .print\\:hidden {
        display: none !important;
    }
    
    body * {
        visibility: hidden;
    }
    
    .bg-white, .bg-white * {
        visibility: visible;
    }
    
    .bg-white {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endpush
@endsection
