@extends('layouts.admin')

@section('title', 'Edit Pendaftaran PPDB - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="container px-3 py-4">    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Edit Data Pendaftaran PPDB</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.ppdb.dashboard') }}" class="hover:text-blue-600">PPDB</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.ppdb.index') }}" class="hover:text-blue-600">Data Pendaftaran</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Edit</span>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3"></i>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <p>{{ session('error') }}</p>
        </div>
    @endif    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <!-- Header Actions -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Edit Data Pendaftaran
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">{{ $pendaftaran->nomor_pendaftaran }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.ppdb.update', $pendaftaran->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <!-- Data Pribadi -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-address-card text-blue-600 mr-2"></i>
                        Data Pribadi
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap) }}" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nama_lengkap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="Laki-laki" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $pendaftaran->nisn) }}" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nisn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('tempat_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir ? $pendaftaran->tanggal_lahir->format('Y-m-d') : '') }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('tanggal_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <input type="text" name="agama" id="agama" value="{{ old('agama', $pendaftaran->agama) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('agama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $pendaftaran->telepon) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('telepon')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $pendaftaran->email) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('alamat', $pendaftaran->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" id="asal_sekolah" value="{{ old('asal_sekolah', $pendaftaran->asal_sekolah) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('asal_sekolah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>            <!-- Data Orang Tua -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Data Orang Tua
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">
                    <div>
                        <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                        <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah', $pendaftaran->nama_ayah) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nama_ayah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                        <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nama_ibu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('pekerjaan_ayah')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('pekerjaan_ibu')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="telepon_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Telepon Orang Tua</label>
                        <input type="text" name="telepon_orangtua" id="telepon_orangtua" value="{{ old('telepon_orangtua', $pendaftaran->telepon_orangtua) }}"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('telepon_orangtua')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Alamat Orang Tua</label>
                        <textarea name="alamat_orangtua" id="alamat_orangtua" rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('alamat_orangtua', $pendaftaran->alamat_orangtua) }}</textarea>
                        <div class="mt-1 text-sm text-gray-500">Kosongkan jika sama dengan alamat siswa</div>
                        @error('alamat_orangtua')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>            <!-- Data Akademik -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                        Data Akademik
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mb-6 bg-white border border-gray-100 rounded-lg p-4">                    <div>
                        <label for="pilihan_jurusan_1" class="block text-sm font-medium text-gray-700 mb-1">Pilihan Jurusan</label>
                        <select name="pilihan_jurusan_1" id="pilihan_jurusan_1" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Pilih Jurusan</option>
                            @foreach($jurusan as $j)
                                <option value="{{ $j->id }}" {{ old('pilihan_jurusan_1', $pendaftaran->pilihan_jurusan_1) == $j->id ? 'selected' : '' }}>
                                    {{ $j->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                        @error('pilihan_jurusan_1')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_matematika" class="block text-sm font-medium text-gray-700 mb-1">Nilai Matematika</label>
                        <input type="number" name="nilai_matematika" id="nilai_matematika" value="{{ old('nilai_matematika', $pendaftaran->nilai_matematika) }}" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nilai_matematika')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_indonesia" class="block text-sm font-medium text-gray-700 mb-1">Nilai B. Indonesia</label>
                        <input type="number" name="nilai_indonesia" id="nilai_indonesia" value="{{ old('nilai_indonesia', $pendaftaran->nilai_indonesia) }}" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nilai_indonesia')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nilai_inggris" class="block text-sm font-medium text-gray-700 mb-1">Nilai B. Inggris</label>
                        <input type="number" name="nilai_inggris" id="nilai_inggris" value="{{ old('nilai_inggris', $pendaftaran->nilai_inggris) }}" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        @error('nilai_inggris')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>            <!-- Status Pendaftaran -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                        Status Pendaftaran
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50
                            @if(old('status', $pendaftaran->status) == 'diterima') 
                                bg-green-50 text-green-700 border-green-300
                            @elseif(old('status', $pendaftaran->status) == 'ditolak')
                                bg-red-50 text-red-700 border-red-300
                            @elseif(old('status', $pendaftaran->status) == 'menunggu')
                                bg-yellow-50 text-yellow-700 border-yellow-300
                            @else
                                bg-purple-50 text-purple-700 border-purple-300
                            @endif">
                            <option value="menunggu" {{ old('status', $pendaftaran->status) == 'menunggu' ? 'selected' : '' }}>⌛ Menunggu</option>
                            <option value="diterima" {{ old('status', $pendaftaran->status) == 'diterima' ? 'selected' : '' }}>✅ Diterima</option>
                            <option value="ditolak" {{ old('status', $pendaftaran->status) == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
                            <option value="cadangan" {{ old('status', $pendaftaran->status) == 'cadangan' ? 'selected' : '' }}>⭐ Cadangan</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('keterangan', $pendaftaran->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>            <!-- Submit Button -->
            <div class="mt-8 bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.ppdb.show', $pendaftaran->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg border border-gray-200 shadow-sm transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
