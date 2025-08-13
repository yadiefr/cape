@extends('layouts.admin')

@section('title', 'Edit Pengumuman - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Pengumuman</h2>
        <a href="{{ route('admin.pengumuman.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengumuman
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="mt-2 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.pengumuman.update', $pengumuman->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('judul') border-red-500 @enderror" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}" required>
                @error('judul')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="isi" class="block text-sm font-medium text-gray-700 mb-2">Isi Pengumuman</label>
                <textarea class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('isi') border-red-500 @enderror" id="isi" name="isi" rows="6" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                @error('isi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto (Opsional)</label>
                @if($pengumuman->lampiran)
                    <div class="mb-3">
                        <p class="text-sm text-gray-600 mb-1">Foto Saat Ini:</p>
                        <img src="{{ Storage::url($pengumuman->lampiran) }}" alt="Foto Pengumuman" class="w-40 h-auto object-cover rounded-lg border border-gray-200">
                    </div>
                @endif
                <input type="file" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('foto') border-red-500 @enderror" id="foto" name="foto" accept="image/*">
                <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB.</p>
                @error('foto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_mulai') border-red-500 @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pengumuman->tanggal_mulai ? $pengumuman->tanggal_mulai->format('Y-m-d') : '') }}" required>
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai (Opsional)</label>
                    <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_selesai') border-red-500 @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pengumuman->tanggal_selesai ? $pengumuman->tanggal_selesai->format('Y-m-d') : '') }}">
                    <p class="mt-1 text-sm text-gray-500">Kosongkan jika pengumuman tidak memiliki tanggal berakhir.</p>
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="target_role" class="block text-sm font-medium text-gray-700 mb-2">Target Pengguna</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('target_role') border-red-500 @enderror" id="target_role" name="target_role" required>
                    <option value="">Pilih Target Pengguna</option>
                    <option value="all" {{ old('target_role', $pengumuman->target_role) == 'all' ? 'selected' : '' }}>Semua Pengguna</option>
                    <option value="guru" {{ old('target_role', $pengumuman->target_role) == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ old('target_role', $pengumuman->target_role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                @error('target_role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5 mt-1">
                        <input id="show_on_homepage" name="show_on_homepage" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('show_on_homepage') border-red-500 @enderror" value="1" {{ old('show_on_homepage', $pengumuman->show_on_homepage) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="show_on_homepage" class="font-medium text-gray-700">Tampilkan di Halaman Utama Website</label>
                        <p class="text-gray-500">Centang jika pengumuman ini ingin ditampilkan di bagian pengumuman halaman utama website.</p>
                    </div>
                </div>
                 @error('show_on_homepage')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <div class="relative flex items-start">
                    <div class="flex items-center h-5 mt-1">
                        <input id="is_active" name="is_active" type="checkbox" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded @error('is_active') border-red-500 @enderror" value="1" {{ old('is_active', $pengumuman->is_active) ? 'checked' : '' }}>
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Status Aktif</label>
                        <p class="text-gray-500">Centang untuk mengaktifkan pengumuman ini agar dapat dilihat oleh pengguna.</p>
                    </div>
                </div>
                 @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:shadow-md transition-all flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
