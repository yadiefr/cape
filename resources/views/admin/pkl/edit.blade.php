@extends('layouts.admin')

@section('title', 'Edit Data PKL - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Edit Data PKL</h2>
        <a href="{{ route('admin.pkl.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="{{ route('admin.pkl.update', $pkl->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Siswa</h3>
                <div class="mb-6">
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Siswa</label>
                    <select name="siswa_id" id="siswa_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('siswa_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswa as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id', $pkl->siswa_id) == $s->id ? 'selected' : '' }}>{{ $s->nama_lengkap }} - {{ $s->nis }} ({{ $s->kelas->nama_kelas }} - {{ $s->jurusan->nama_jurusan }})</option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Perusahaan/Instansi</h3>
                <div class="mb-6">
                    <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Nama Perusahaan/Instansi</label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $pkl->nama_perusahaan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_perusahaan') border-red-500 @enderror" required>
                    @error('nama_perusahaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="bidang_usaha" class="block text-sm font-medium text-gray-700 mb-2">Bidang Usaha/Jenis Instansi</label>
                    <input type="text" name="bidang_usaha" id="bidang_usaha" value="{{ old('bidang_usaha', $pkl->bidang_usaha) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bidang_usaha') border-red-500 @enderror" required>
                    @error('bidang_usaha')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="alamat_perusahaan" class="block text-sm font-medium text-gray-700 mb-2">Alamat Perusahaan/Instansi</label>
                    <textarea name="alamat_perusahaan" id="alamat_perusahaan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('alamat_perusahaan') border-red-500 @enderror" required>{{ old('alamat_perusahaan', $pkl->alamat_perusahaan) }}</textarea>
                    @error('alamat_perusahaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Informasi Pembimbing</h3>
                <div class="mb-6">
                    <label for="nama_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Nama Pembimbing</label>
                    <input type="text" name="nama_pembimbing" id="nama_pembimbing" value="{{ old('nama_pembimbing', $pkl->nama_pembimbing) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nama_pembimbing') border-red-500 @enderror" required>
                    @error('nama_pembimbing')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="telepon_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Telepon Pembimbing</label>
                        <input type="text" name="telepon_pembimbing" id="telepon_pembimbing" value="{{ old('telepon_pembimbing', $pkl->telepon_pembimbing) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telepon_pembimbing') border-red-500 @enderror">
                        @error('telepon_pembimbing')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email_pembimbing" class="block text-sm font-medium text-gray-700 mb-2">Email Pembimbing</label>
                        <input type="email" name="email_pembimbing" id="email_pembimbing" value="{{ old('email_pembimbing', $pkl->email_pembimbing) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email_pembimbing') border-red-500 @enderror">
                        @error('email_pembimbing')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Periode dan Status PKL</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $pkl->tanggal_mulai->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_mulai') border-red-500 @enderror" required>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $pkl->tanggal_selesai->format('Y-m-d')) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tanggal_selesai') border-red-500 @enderror" required>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status PKL</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                        <option value="pengajuan" {{ old('status', $pkl->status) == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="berlangsung" {{ old('status', $pkl->status) == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ old('status', $pkl->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="gagal" {{ old('status', $pkl->status) == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Penilaian</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label for="nilai_teknis" class="block text-sm font-medium text-gray-700 mb-2">Nilai Teknis</label>
                        <input type="number" name="nilai_teknis" id="nilai_teknis" value="{{ old('nilai_teknis', $pkl->nilai_teknis) }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nilai_teknis') border-red-500 @enderror">
                        @error('nilai_teknis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nilai_sikap" class="block text-sm font-medium text-gray-700 mb-2">Nilai Sikap</label>
                        <input type="number" name="nilai_sikap" id="nilai_sikap" value="{{ old('nilai_sikap', $pkl->nilai_sikap) }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nilai_sikap') border-red-500 @enderror">
                        @error('nilai_sikap')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nilai_laporan" class="block text-sm font-medium text-gray-700 mb-2">Nilai Laporan</label>
                        <input type="number" name="nilai_laporan" id="nilai_laporan" value="{{ old('nilai_laporan', $pkl->nilai_laporan) }}" min="0" max="100" step="0.01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nilai_laporan') border-red-500 @enderror">
                        @error('nilai_laporan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Dokumen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="dokumen_laporan" class="block text-sm font-medium text-gray-700 mb-2">Dokumen Laporan PKL</label>
                        <div class="flex items-center space-x-2">
                            <input type="file" name="dokumen_laporan" id="dokumen_laporan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('dokumen_laporan') border-red-500 @enderror">
                            @if($pkl->dokumen_laporan)
                                <a href="{{ route('admin.pkl.download.laporan', $pkl->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                        @if($pkl->dokumen_laporan)
                            <p class="mt-1 text-xs text-gray-600">File saat ini: {{ $pkl->dokumen_laporan }}</p>
                        @endif
                        @error('dokumen_laporan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="surat_keterangan" class="block text-sm font-medium text-gray-700 mb-2">Surat Keterangan</label>
                        <div class="flex items-center space-x-2">
                            <input type="file" name="surat_keterangan" id="surat_keterangan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('surat_keterangan') border-red-500 @enderror">
                            @if($pkl->surat_keterangan)
                                <a href="{{ route('admin.pkl.download.surat', $pkl->id) }}" class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                    <i class="fas fa-download text-xs"></i>
                                </a>
                            @endif
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Format: PDF, DOC, DOCX. Maksimal 10MB.</p>
                        @if($pkl->surat_keterangan)
                            <p class="mt-1 text-xs text-gray-600">File saat ini: {{ $pkl->surat_keterangan }}</p>
                        @endif
                        @error('surat_keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $pkl->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-2 rounded-lg hover:shadow-md transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
