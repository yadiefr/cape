@extends('layouts.ujian')

@section('title', 'Detail Jenis Ujian')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.ujian.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Pengaturan</span>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.index') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600">Jenis Ujian</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Detail</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Jenis Ujian</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap jenis ujian: {{ $jenisUjian->nama }}</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.edit', $jenisUjian) }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nama Jenis Ujian</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $jenisUjian->nama }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kode</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $jenisUjian->kode }}
                                </span>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $jenisUjian->deskripsi ?: 'Tidak ada deskripsi' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Durasi Default</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($jenisUjian->durasi_default)
                                    <div class="flex items-center">
                                        <i class="fas fa-clock text-gray-400 mr-2"></i>
                                        {{ $jenisUjian->durasi_formatted }}
                                    </div>
                                @else
                                    <span class="text-gray-500">Tidak ditetapkan</span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Urutan</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $jenisUjian->urutan }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jenisUjian->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs"></i>
                                    {{ $jenisUjian->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar text-gray-400 mr-2"></i>
                                    {{ $jenisUjian->created_at->format('d/m/Y H:i') }}
                                </div>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <i class="fas fa-edit text-gray-400 mr-2"></i>
                                    {{ $jenisUjian->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Aksi</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Edit Button -->
                    <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.edit', $jenisUjian) }}" 
                       class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                        <i class="fas fa-edit"></i>
                        <span>Edit Jenis Ujian</span>
                    </a>
                    
                    <!-- Toggle Status Button -->
                    <form action="{{ route('admin.ujian.pengaturan.jenis-ujian.toggle-status', $jenisUjian) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="w-full {{ $jenisUjian->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                            <i class="fas {{ $jenisUjian->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                            <span>{{ $jenisUjian->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                        </button>
                    </form>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('admin.ujian.pengaturan.jenis-ujian.destroy', $jenisUjian) }}" 
                          method="POST" 
                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis ujian {{ $jenisUjian->nama }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center justify-center space-x-2 transition-colors">
                            <i class="fas fa-trash"></i>
                            <span>Hapus Jenis Ujian</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Statistics Card (if there are related data) -->
            <div class="bg-white rounded-lg shadow-md mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Statistik</h3>
                </div>
                
                <div class="p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-600">0</div>
                        <div class="text-sm text-gray-500 mt-1">Jadwal Ujian</div>
                    </div>
                    <p class="text-xs text-gray-500 text-center mt-2">
                        Total jadwal ujian yang menggunakan jenis ujian ini
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
