@extends('layouts.admin')

@section('title', 'Detail Jadwal Bel')

@section('main-content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Detail Jadwal Bel</h2>
        <a href="{{ route('admin.bel.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex flex-col md:flex-row md:items-start">
            <!-- Icon dan Info Utama -->
            <div class="md:w-1/3 flex flex-col items-center text-center mb-6 md:mb-0">
                <div class="w-32 h-32 rounded-full bg-{{ str_replace('#', '', $bel->kode_warna) }}-100 flex items-center justify-center mb-4">
                    <i class="fas fa-{{ $bel->ikon }} text-{{ str_replace('#', '', $bel->kode_warna) }}-500 text-5xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $bel->nama }}</h3>
                <p class="text-lg text-gray-600">{{ date('H:i', strtotime($bel->waktu)) }}</p>
                
                @if ($bel->hari)
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm mt-2">{{ $bel->hari }}</span>
                @else
                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm mt-2">Setiap Hari</span>
                @endif
                
                <div class="mt-4">
                    @if ($bel->aktif)
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">Aktif</span>
                    @else
                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">Tidak Aktif</span>
                    @endif
                </div>
                
                <div class="mt-6 flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="{{ route('admin.bel.edit', $bel->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white py-2 px-6 rounded-lg flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </a>
                    <form action="{{ route('admin.bel.bunyikan', $bel->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg flex items-center justify-center w-full">
                            <i class="fas fa-bell mr-2"></i> Bunyikan Bel
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Informasi Detail -->
            <div class="md:w-2/3 md:pl-8 md:border-l border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Detail</h3>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">ID Bel</p>
                            <p class="text-lg">{{ $bel->id }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Tipe Bel</p>
                            <p class="text-lg">
                                @if ($bel->tipe === 'reguler')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-sm">Reguler</span>
                                @elseif ($bel->tipe === 'istirahat')
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-sm">Istirahat</span>
                                @elseif ($bel->tipe === 'ujian')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-md text-sm">Ujian</span>
                                @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-md text-sm">Khusus</span>
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Waktu Dibuat</p>
                            <p class="text-lg">{{ $bel->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Terakhir Diperbarui</p>
                            <p class="text-lg">{{ $bel->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kode Warna</p>
                            <div class="flex items-center">
                                <div class="w-6 h-6 rounded mr-2" style="background-color: {{ $bel->kode_warna }};"></div>
                                <p class="text-lg">{{ $bel->kode_warna }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500">Deskripsi</p>
                        <p class="text-lg bg-gray-50 p-3 rounded-lg mt-1">
                            {{ $bel->deskripsi ?? 'Tidak ada deskripsi' }}
                        </p>
                    </div>
                    
                    @if ($bel->file_suara)
                    <div>
                        <p class="text-sm font-medium text-gray-500">File Suara</p>
                        <div class="bg-gray-50 p-3 rounded-lg mt-1">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <i class="fas fa-music text-blue-500 mr-2"></i>
                                    <a href="{{ asset($bel->file_suara) }}" target="_blank" class="text-blue-600 hover:underline">
                                        {{ basename($bel->file_suara) }}
                                    </a>
                                </div>
                                <audio controls class="h-8 w-48 md:w-64">
                                    <source src="{{ asset($bel->file_suara) }}" type="audio/mpeg">
                                    Browser Anda tidak mendukung pemutaran audio.
                                </audio>
                            </div>
                        </div>
                    </div>
                    @else
                    <div>
                        <p class="text-sm font-medium text-gray-500">File Suara</p>
                        <p class="text-lg bg-gray-50 p-3 rounded-lg mt-1 text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i> Tidak ada file suara yang diunggah
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">
            <i class="fas fa-history mr-2 text-indigo-500"></i>
            Log Aktivitas Bel
        </h3>
        
        <!-- Contoh log aktivitas -->
        <div class="bg-gray-50 p-4 rounded-lg text-center">
            <i class="fas fa-bell-slash text-gray-400 text-2xl mb-2"></i>
            <p class="text-gray-500">Belum ada riwayat aktivitas untuk bel ini.</p>
        </div>
    </div>
</div>
@endsection
