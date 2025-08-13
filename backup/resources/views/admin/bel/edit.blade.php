@extends('layouts.admin')

@section('title', 'Edit Jadwal Bel')

@section('main-content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Edit Jadwal Bel</h2>
        <a href="{{ route('admin.bel.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md shadow-sm">
        <div class="flex">
            <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
            <div>
                <p class="font-bold">Terjadi kesalahan:</p>
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
        <div class="mb-6">
            <h3 class="text-lg font-medium text-gray-800 mb-2">Informasi Bel</h3>
            <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                <div class="w-12 h-12 rounded-lg bg-{{ str_replace('#', '', $bel->kode_warna) }}-100 flex items-center justify-center mr-4">
                    <i class="fas fa-{{ $bel->ikon }} text-{{ str_replace('#', '', $bel->kode_warna) }}-500 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-lg">{{ $bel->nama }}</p>
                    <p class="text-gray-600">{{ date('H:i', strtotime($bel->waktu)) }} - 
                    @if ($bel->hari)
                        {{ $daftarHari[$bel->hari] }}
                    @else
                        Setiap Hari
                    @endif
                    </p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.bel.update', $bel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Bel -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Bel <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $bel->nama) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        placeholder="Contoh: Bel Masuk Jam Pertama">
                </div>

                <!-- Waktu -->
                <div>
                    <label for="waktu" class="block text-sm font-medium text-gray-700 mb-1">Waktu <span class="text-red-500">*</span></label>
                    <input type="time" name="waktu" id="waktu" value="{{ old('waktu', date('H:i', strtotime($bel->waktu))) }}" required
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                </div>

                <!-- Hari -->
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" id="hari" 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Setiap Hari</option>
                        @foreach ($daftarHari as $day => $dayName)
                        <option value="{{ $day }}" {{ old('hari', $bel->hari) == $day ? 'selected' : '' }}>{{ $dayName }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika bel berlaku setiap hari</p>
                </div>

                <!-- Tipe Bel -->
                <div>
                    <label for="tipe" class="block text-sm font-medium text-gray-700 mb-1">Tipe Bel <span class="text-red-500">*</span></label>
                    <select name="tipe" id="tipe" required 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @foreach ($tipeBel as $value => $label)
                        <option value="{{ $value }}" {{ old('tipe', $bel->tipe) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Icon -->
                <div>
                    <label for="ikon" class="block text-sm font-medium text-gray-700 mb-1">Ikon <span class="text-red-500">*</span></label>
                    <select name="ikon" id="ikon" required 
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        @foreach ($pilihanIkon as $value => $label)
                        <option value="{{ $value }}" {{ old('ikon', $bel->ikon) == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Warna -->
                <div>
                    <label for="kode_warna" class="block text-sm font-medium text-gray-700 mb-1">Kode Warna</label>
                    <div class="flex space-x-2">
                        <input type="color" name="kode_warna" id="kode_warna" value="{{ old('kode_warna', $bel->kode_warna) }}"
                            class="h-10 w-10 rounded border-gray-300 shadow-sm">
                        <input type="text" id="kode_warna_text" value="{{ old('kode_warna', $bel->kode_warna) }}" readonly
                            class="w-full rounded-lg border-gray-300 shadow-sm bg-gray-50">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pilih warna untuk tampilan bel</p>
                </div>

                <!-- File Suara -->
                <div>
                    <label for="file_suara" class="block text-sm font-medium text-gray-700 mb-1">File Suara</label>
                    <input type="file" name="file_suara" id="file_suara" accept="audio/*"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @if ($bel->file_suara)
                    <div class="mt-2 flex items-center text-sm">
                        <i class="fas fa-music text-blue-500 mr-1"></i>
                        <a href="{{ asset($bel->file_suara) }}" target="_blank" class="text-blue-600 hover:underline">File saat ini</a>
                        <audio controls class="ml-3 h-8" style="width: 200px">
                            <source src="{{ asset($bel->file_suara) }}" type="audio/mpeg">
                            Browser Anda tidak mendukung pemutaran audio.
                        </audio>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Unggah file baru untuk mengganti</p>
                    @else
                    <p class="text-xs text-gray-500 mt-1">Tidak ada file audio. Format: MP3, WAV (maks. 2MB)</p>
                    @endif
                </div>

                <!-- Status Aktif -->
                <div>
                    <div class="flex items-center mt-6">
                        <input type="checkbox" name="aktif" id="aktif" {{ old('aktif', $bel->aktif) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 h-5 w-5">
                        <label for="aktif" class="ml-2 block text-sm text-gray-700">Aktifkan jadwal bel ini</label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="md:col-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3"
                        class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        placeholder="Deskripsi opsional untuk bel ini...">{{ old('deskripsi', $bel->deskripsi) }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.bel.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-6 rounded-lg mr-2">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg flex items-center">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('kode_warna');
        const colorText = document.getElementById('kode_warna_text');

        colorPicker.addEventListener('input', function() {
            colorText.value = this.value;
        });
    });
</script>
@endpush
@endsection
