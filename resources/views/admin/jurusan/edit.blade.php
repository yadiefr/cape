@extends('layouts.admin')

@section('title', 'Edit Jurusan - SMK PGRI CIKAMPEK')

@section('main-content')
    <div class="container px-3 py-4">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Jurusan</h1>
                    <div class="text-sm breadcrumbs">
                        <ul class="flex items-center space-x-2 text-gray-500">
                            <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                            <li class="flex items-center space-x-2">
                                <span class="text-gray-400">/</span>
                                <a href="{{ route('admin.jurusan.index') }}" class="hover:text-blue-600 transition-colors">Jurusan</a>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-gray-400">/</span>
                                <span>Edit Jurusan</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('admin.jurusan.show', $jurusan->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center">
                    @if($jurusan->logo)
                        <img src="{{ Storage::url($jurusan->logo) }}" alt="Logo {{ $jurusan->nama_jurusan }}" class="h-10 w-10 object-contain rounded mr-3">
                    @else
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                    @endif
                    <h3 class="font-semibold text-lg text-gray-800">Edit {{ $jurusan->nama_jurusan }}</h3>
                </div>
            </div>

            <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Kolom Kiri - Informasi Dasar -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="kode_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-code text-gray-400"></i>
                                            </div>
                                            <input type="text" name="kode_jurusan" id="kode_jurusan" value="{{ old('kode_jurusan', $jurusan->kode_jurusan) }}" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('kode_jurusan') border-red-500 @enderror" placeholder="Mis: TKR, RPL, TMI">
                                        </div>
                                        @error('kode_jurusan')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-1">Kode jurusan harus unik dan maksimal 10 karakter.</p>
                                    </div>

                                    <div>
                                        <label for="nama_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-graduation-cap text-gray-400"></i>
                                            </div>
                                            <input type="text" name="nama_jurusan" id="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nama_jurusan') border-red-500 @enderror" placeholder="Mis: Teknik Kendaraan Ringan">
                                        </div>
                                        @error('nama_jurusan')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="kepala_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kepala Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-tie text-gray-400"></i>
                                            </div>
                                            <select name="kepala_jurusan" id="kepala_jurusan" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('kepala_jurusan') border-red-500 @enderror">
                                                <option value="">-- Pilih Kepala Jurusan --</option>
                                                @foreach($guru_list as $guru)
                                                    <option value="{{ $guru->id }}" {{ old('kepala_jurusan', $jurusan->kepala_jurusan) == $guru->id ? 'selected' : '' }}>
                                                        {{ $guru->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('kepala_jurusan')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center p-2 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_active', $jurusan->is_active) ? 'checked' : '' }}>
                                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1">Jurusan yang tidak aktif tidak akan ditampilkan di situs publik.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Akademis</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('deskripsi') border-red-500 @enderror" placeholder="Tuliskan deskripsi program keahlian...">{{ old('deskripsi', $jurusan->deskripsi) }}</textarea>
                                        @error('deskripsi')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="visi" class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                                            <textarea name="visi" id="visi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('visi') border-red-500 @enderror" placeholder="Visi jurusan...">{{ old('visi', $jurusan->visi) }}</textarea>
                                            @error('visi')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="misi" class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                                            <textarea name="misi" id="misi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('misi') border-red-500 @enderror" placeholder="Misi jurusan...">{{ old('misi', $jurusan->misi) }}</textarea>
                                            @error('misi')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="prospek_karir" class="block text-sm font-medium text-gray-700 mb-1">Prospek Karir</label>
                                        <textarea name="prospek_karir" id="prospek_karir" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('prospek_karir') border-red-500 @enderror" placeholder="Prospek karir lulusan...">{{ old('prospek_karir', $jurusan->prospek_karir) }}</textarea>
                                        @error('prospek_karir')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan - Media -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Media & Gambar</h4>
                                
                                <!-- Logo -->
                                <div class="mb-6">
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo Jurusan</label>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-dashed border-gray-300">
                                        @if($jurusan->logo)
                                            <div class="mb-4 flex justify-center">
                                                <img src="{{ Storage::url($jurusan->logo) }}" alt="Logo {{ $jurusan->nama_jurusan }}" class="h-28 w-auto object-contain border p-2 rounded-lg bg-white shadow-sm">
                                            </div>
                                        @else
                                            <div class="mb-4 flex justify-center">
                                                <div class="h-28 w-28 flex items-center justify-center rounded-lg bg-gray-200 text-gray-500">
                                                    <i class="fas fa-image text-3xl"></i>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <label class="cursor-pointer">
                                            <div class="relative">
                                                <input type="file" name="logo" id="logo" class="hidden">
                                                <div class="py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg inline-flex items-center transition-colors cursor-pointer">
                                                    <i class="fas fa-upload mr-2"></i> Upload Logo
                                                </div>
                                            </div>
                                        </label>
                                        
                                        @error('logo')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maks. 2MB.</p>
                                    </div>
                                </div>
                                
                                <!-- Gambar Header -->
                                <div>
                                    <label for="gambar_header" class="block text-sm font-medium text-gray-700 mb-2">Gambar Header</label>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-dashed border-gray-300">
                                        @if($jurusan->gambar_header)
                                            <div class="mb-4 flex justify-center">
                                                <img src="{{ Storage::url($jurusan->gambar_header) }}" alt="Header {{ $jurusan->nama_jurusan }}" class="h-28 w-full object-cover rounded-lg shadow-sm">
                                            </div>
                                        @else
                                            <div class="mb-4 flex justify-center">
                                                <div class="h-28 w-full flex items-center justify-center rounded-lg bg-gray-200 text-gray-500">
                                                    <i class="fas fa-panorama text-3xl"></i>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <label class="cursor-pointer">
                                            <div class="relative">
                                                <input type="file" name="gambar_header" id="gambar_header" class="hidden">
                                                <div class="py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg inline-flex items-center transition-colors cursor-pointer">
                                                    <i class="fas fa-upload mr-2"></i> Upload Header
                                                </div>
                                            </div>
                                        </label>
                                        
                                        @error('gambar_header')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maks. 2MB. Ukuran optimal: 1200x400px.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Statistik Jurusan</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mr-3">
                                                <i class="fas fa-door-open"></i>
                                            </span>
                                            <span class="text-sm text-blue-800">Kelas</span>
                                        </div>
                                        <span class="font-bold text-blue-800">{{ $jurusan->kelas ? $jurusan->kelas->count() : 0 }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 rounded-full mr-3">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            <span class="text-sm text-green-800">Siswa</span>
                                        </div>
                                        <span class="font-bold text-green-800">{{ $jurusan->siswa ? $jurusan->siswa->count() : 0 }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mr-3">
                                                <i class="fas fa-book"></i>
                                            </span>
                                            <span class="text-sm text-purple-800">Mata Pelajaran</span>
                                        </div>
                                        <span class="font-bold text-purple-800">{{ $jurusan->mata_pelajaran ? $jurusan->mata_pelajaran->count() : 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <a href="{{ route('admin.jurusan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-save mr-2"></i> Perbarui Jurusan
                    </button>                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fungsi untuk preview gambar sebelum upload (jika diperlukan)
    // Tambahkan skrip tambahan jika diperlukan
</script>
@endpush
