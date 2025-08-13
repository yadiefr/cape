@extends('layouts.admin')

@section('title', 'Tambah Jurusan Baru - SMK PGRI CIKAMPEK')

@section('main-content')
    <div class="container px-3 py-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Tambah Jurusan Baru</h1>
            <div class="text-sm breadcrumbs">
                <ul class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                    <li class="flex items-center space-x-2">
                        <span class="text-gray-400">/</span>
                        <a href="{{ route('admin.jurusan.index') }}" class="hover:text-blue-600">Jurusan</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-gray-400">/</span>
                        <span>Tambah Jurusan</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="font-semibold text-lg text-gray-700">Form Jurusan Baru</h3>
            </div>
            <div class="p-5">
                <form action="{{ route('admin.jurusan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="kode_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_jurusan" id="kode_jurusan" value="{{ old('kode_jurusan') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('kode_jurusan') border-red-500 @enderror" placeholder="Mis: TKR, RPL, TMI">
                            @error('kode_jurusan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Kode jurusan harus unik dan maksimal 10 karakter.</p>
                        </div>

                        <div>
                            <label for="nama_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_jurusan" id="nama_jurusan" value="{{ old('nama_jurusan') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nama_jurusan') border-red-500 @enderror" placeholder="Mis: Teknik Kendaraan Ringan">
                            @error('nama_jurusan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kepala_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kepala Jurusan <span class="text-red-500">*</span></label>
                            <select name="kepala_jurusan" id="kepala_jurusan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('kepala_jurusan') border-red-500 @enderror">
                                <option value="">-- Pilih Kepala Jurusan --</option>
                                @foreach($guru_list as $guru)
                                    <option value="{{ $guru->id }}" {{ old('kepala_jurusan') == $guru->id ? 'selected' : '' }}>
                                        {{ $guru->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kepala_jurusan')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Jurusan yang tidak aktif tidak akan ditampilkan di situs publik.</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('deskripsi') border-red-500 @enderror" placeholder="Tuliskan deskripsi program keahlian...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="visi" class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                            <textarea name="visi" id="visi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('visi') border-red-500 @enderror" placeholder="Visi jurusan...">{{ old('visi') }}</textarea>
                            @error('visi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="misi" class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                            <textarea name="misi" id="misi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('misi') border-red-500 @enderror" placeholder="Misi jurusan...">{{ old('misi') }}</textarea>
                            @error('misi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="prospek_karir" class="block text-sm font-medium text-gray-700 mb-1">Prospek Karir</label>
                        <textarea name="prospek_karir" id="prospek_karir" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('prospek_karir') border-red-500 @enderror" placeholder="Prospek karir lulusan...">{{ old('prospek_karir') }}</textarea>
                        @error('prospek_karir')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Logo Jurusan</label>
                            <input type="file" name="logo" id="logo" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('logo') border-red-500 @enderror">
                            @error('logo')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maks. 2MB.</p>
                        </div>

                        <div>
                            <label for="gambar_header" class="block text-sm font-medium text-gray-700 mb-1">Gambar Header</label>
                            <input type="file" name="gambar_header" id="gambar_header" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('gambar_header') border-red-500 @enderror">
                            @error('gambar_header')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, JPEG. Maks. 2MB. Ukuran optimal: 1200x400px.</p>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('admin.jurusan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                            Batal
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk preview gambar sebelum upload (jika diperlukan)
    // Tambahkan skrip tambahan jika diperlukan
</script>
@endpush
