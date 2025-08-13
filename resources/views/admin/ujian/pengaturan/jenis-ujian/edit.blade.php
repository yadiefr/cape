@extends('layouts.ujian')

@section('title', 'Edit Jenis Ujian')

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
                    <span class="text-sm font-medium text-gray-500">Edit</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Jenis Ujian</h1>
            <p class="text-gray-600 mt-1">Perbarui informasi jenis ujian: {{ $jenisUjian->nama }}</p>
        </div>
        <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informasi Jenis Ujian</h3>
        </div>
        
        <form action="{{ route('admin.ujian.pengaturan.jenis-ujian.update', $jenisUjian) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="md:col-span-1">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Jenis Ujian <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $jenisUjian->nama) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Ujian Tengah Semester"
                           required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kode -->
                <div class="md:col-span-1">
                    <label for="kode" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode" 
                           name="kode" 
                           value="{{ old('kode', $jenisUjian->kode) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('kode') border-red-500 @enderror"
                           placeholder="Contoh: UTS"
                           maxlength="10"
                           required>
                    @error('kode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea id="deskripsi" 
                              name="deskripsi" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Deskripsi singkat tentang jenis ujian ini...">{{ old('deskripsi', $jenisUjian->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Durasi Default -->
                <div class="md:col-span-1">
                    <label for="durasi_default" class="block text-sm font-medium text-gray-700 mb-2">
                        Durasi Default (menit)
                    </label>
                    <input type="number" 
                           id="durasi_default" 
                           name="durasi_default" 
                           value="{{ old('durasi_default', $jenisUjian->durasi_default) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('durasi_default') border-red-500 @enderror"
                           placeholder="120"
                           min="1"
                           max="600">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ada durasi default</p>
                    @error('durasi_default')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Urutan -->
                <div class="md:col-span-1">
                    <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2">
                        Urutan
                    </label>
                    <input type="number" 
                           id="urutan" 
                           name="urutan" 
                           value="{{ old('urutan', $jenisUjian->urutan) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('urutan') border-red-500 @enderror"
                           placeholder="1"
                           min="1"
                           max="999">
                    <p class="mt-1 text-sm text-gray-500">Urutan tampilan dalam daftar</p>
                    @error('urutan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" 
                               id="is_active" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $jenisUjian->is_active) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-900">
                            Aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Jenis ujian yang aktif dapat digunakan dalam pembuatan jadwal ujian</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    Batal
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors">
                    <i class="fas fa-save"></i>
                    <span>Perbarui</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto uppercase kode
    document.getElementById('kode').addEventListener('input', function(e) {
        e.target.value = e.target.value.toUpperCase();
    });
</script>
@endpush
@endsection
