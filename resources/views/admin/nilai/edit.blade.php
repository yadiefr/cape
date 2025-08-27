@extends('layouts.admin')

@section('title', 'Edit Nilai - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Edit Nilai</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.nilai.index') }}" class="hover:text-blue-600">Manajemen Nilai</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Edit Nilai</span>
                </li>
            </ul>
        </div>
    </div>
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-md" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Form Edit Nilai</h3>
        </div>
        <div class="p-4">
            <form action="{{ route('admin.nilai.update', $nilai->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa <span class="text-red-500">*</span></label>
                    <select name="siswa_id" id="siswa_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswa as $s)
                        <option value="{{ $s->id }}" {{ (old('siswa_id', $nilai->siswa_id) == $s->id) ? 'selected' : '' }}>
                            {{ $s->nama_lengkap }} ({{ $s->nis }})
                        </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                    <select name="mapel_id" id="mapel_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ (old('mapel_id', $nilai->mapel_id) == $m->id) ? 'selected' : '' }}>
                            {{ $m->nama }}
                        </option>
                        @endforeach
                    </select>
                    @error('mapel_id')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="nilai" class="block text-sm font-medium text-gray-700 mb-2">Nilai <span class="text-red-500">*</span></label>
                    <input type="number" name="nilai" id="nilai" min="0" max="100" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" value="{{ old('nilai', $nilai->nilai) }}" required>
                    @error('nilai')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-sm text-gray-500 mt-1">Nilai dari 0-100</p>
                </div>
                
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('keterangan', $nilai->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end space-x-2">
                    <a href="{{ route('admin.nilai.index') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded">
                        <i class="fas fa-times mr-1"></i> Batal
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
