@extends('layouts.admin')

@section('title', 'Edit Kelas - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="container mx-auto px-4 py-6 max-w-md">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Kelas</h1>
        <div class="text-sm breadcrumbs mb-4">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.kelas.index') }}" class="hover:text-blue-600">Manajemen Kelas</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Edit Kelas</span>
                </li>
            </ul>
        </div>
        
        @if($errors->any())
        <div class="bg-red-50 text-red-700 p-4 mb-4 rounded-md">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
            </div>

            <div>
                <label for="jurusan_id" class="block text-sm font-medium text-gray-700">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
                    @foreach($jurusan as $j)                        <option value="{{ $j->id }}" {{ $j->id == old('jurusan_id', $kelas->jurusan_id) ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="wali_kelas" class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                <select name="wali_kelas" id="wali_kelas" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3">
                    <option value="">-- Pilih Wali Kelas --</option>
                    @foreach($guru as $g)
                        @php
                            $isCurrentWali = $g->id == $kelas->wali_kelas;
                            $hasOtherClass = $g->kelas()->where('id', '!=', $kelas->id)->exists();
                            $isDisabled = $hasOtherClass && !$isCurrentWali;
                        @endphp
                        <option value="{{ $g->id }}" 
                            {{ $g->id == old('wali_kelas', $kelas->wali_kelas) ? 'selected' : '' }}
                            {{ $isDisabled ? 'disabled' : '' }}>
                            {{ $g->nama }} ({{ $g->nip }})
                            @if($isCurrentWali)
                                - Wali Kelas Saat Ini
                            @elseif($hasOtherClass)
                                - Sudah Menjadi Wali Kelas Lain
                            @elseif($g->is_wali_kelas)
                                - Dapat Menjadi Wali Kelas
                            @endif
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Guru yang sudah menjadi wali kelas lain tidak dapat dipilih</p>
            </div>

            <div>
                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="{{ old('tahun_ajaran', $kelas->tahun_ajaran) }}" 
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
                <p class="text-xs text-gray-500 mt-1">Format: 2023/2024</p>
            </div>

            <div class="flex justify-between pt-4">
                <a href="{{ route('admin.kelas.index') }}" class="bg-gray-300 text-gray-700 px-5 py-2 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-md shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
