@extends('layouts.admin')

@section('title', 'Tambah Data PKL')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Tambah Data PKL
            </h1>
            <p class="text-gray-600 mt-1">Daftarkan siswa untuk program Praktek Kerja Lapangan</p>
        </div>
        <a href="{{ route('admin.pkl.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                <div>
                    <p class="font-medium mb-2">Terdapat kesalahan pada form:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-blue-600"></i>Form Data PKL
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.pkl.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Informasi Siswa -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-user-graduate mr-2 text-blue-600"></i>Informasi Siswa
                    </h4>
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa <span class="text-red-500">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('siswa_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswa as $s)
                                <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_lengkap }} - {{ $s->nis }} ({{ $s->kelas->nama_kelas }} - {{ $s->jurusan->nama_jurusan }})
                                </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Perusahaan -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-building mr-2 text-blue-600"></i>Informasi Perusahaan/Instansi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_perusahaan') border-red-500 @enderror" required>
                            @error('nama_perusahaan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="bidang_usaha" class="block text-sm font-medium text-gray-700 mb-2">Bidang Usaha/Jenis Instansi <span class="text-red-500">*</span></label>
                            <input type="text" name="bidang_usaha" id="bidang_usaha" value="{{ old('bidang_usaha') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_usaha') border-red-500 @enderror" required>
                            @error('bidang_usaha')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="alamat_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Alamat Perusahaan/Instansi <span class="text-red-500">*</span></label>
                        <textarea name="alamat_perusahaan" id="alamat_perusahaan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_perusahaan') border-red-500 @enderror" required>{{ old('alamat_perusahaan') }}</textarea>
                        @error('alamat_perusahaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Pembimbing -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-blue-600"></i>Informasi Pembimbing
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="nama_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Nama Pembimbing <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pembimbing" id="nama_pembimbing" value="{{ old('nama_pembimbing') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_pembimbing') border-red-500 @enderror" required>
                            @error('nama_pembimbing')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="telepon_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Telepon Pembimbing</label>
                            <input type="text" name="telepon_pembimbing" id="telepon_pembimbing" value="{{ old('telepon_pembimbing') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telepon_pembimbing') border-red-500 @enderror">
                            @error('telepon_pembimbing')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Email Pembimbing</label>
                            <input type="email" name="email_pembimbing" id="email_pembimbing" value="{{ old('email_pembimbing') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email_pembimbing') border-red-500 @enderror">
                            @error('email_pembimbing')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Periode dan Status PKL -->
                <div>
                    <h4 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>Periode dan Status PKL
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_mulai') border-red-500 @enderror" required>
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_selesai') border-red-500 @enderror" required>
                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status PKL <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                <option value="pengajuan" {{ old('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                                <option value="berlangsung" {{ old('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="gagal" {{ old('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror" placeholder="Tambahkan keterangan atau catatan khusus...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.pkl.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>Simpan Data PKL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection