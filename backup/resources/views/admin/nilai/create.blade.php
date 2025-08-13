@extends('layouts.admin')

@section('title', 'Tambah Nilai')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-600"></i>
                Tambah Nilai
            </h1>
            <p class="text-gray-600 mt-1">Tambahkan nilai siswa untuk mata pelajaran tertentu</p>
        </div>
        <a href="{{ route('admin.nilai.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit mr-2 text-blue-600"></i>Form Tambah Nilai
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.nilai.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Filter Kelas -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <label for="kelas_filter" class="block text-sm font-medium text-blue-800 mb-2">
                        <i class="fas fa-filter mr-1"></i>Filter Kelas (Opsional)
                    </label>
                    <select id="kelas_filter" class="w-full px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <p class="text-sm text-blue-600 mt-1">Pilih kelas untuk memfilter daftar siswa</p>
                </div>

                <!-- Form Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-2">Siswa <span class="text-red-500">*</span></label>
                        <select name="siswa_id" id="siswa_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('siswa_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswa as $s)
                            <option value="{{ $s->id }}" data-kelas="{{ $s->kelas_id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama_lengkap }} ({{ $s->nis }}) - {{ $s->kelas->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                        @error('siswa_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                        <select name="mapel_id" id="mapel_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('mapel_id') border-red-500 @enderror" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapel as $m)
                            <option value="{{ $m->id }}" {{ old('mapel_id') == $m->id ? 'selected' : '' }}>{{ $m->nama }}</option>
                            @endforeach
                        </select>
                        @error('mapel_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nilai" class="block text-sm font-medium text-gray-700 mb-2">Nilai <span class="text-red-500">*</span></label>
                        <input type="number" name="nilai" id="nilai" min="0" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nilai') border-red-500 @enderror"
                               value="{{ old('nilai') }}" placeholder="0-100" required>
                        @error('nilai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 mt-1">Masukkan nilai dari 0-100</p>
                    </div>

                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan (Opsional)</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('keterangan') border-red-500 @enderror"
                                  placeholder="Tambahkan keterangan atau catatan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.nilai.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors flex items-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kelasFilter = document.getElementById('kelas_filter');
        const siswaSelect = document.getElementById('siswa_id');

        // Filter siswa berdasarkan kelas
        kelasFilter.addEventListener('change', function() {
            const selectedKelasId = this.value;
            const siswaOptions = siswaSelect.querySelectorAll('option');

            // Reset pilihan siswa
            siswaSelect.value = '';

            // Show/hide options based on kelas filter
            siswaOptions.forEach(function(option) {
                const kelasId = option.getAttribute('data-kelas');

                if (selectedKelasId === '' || option.value === '' || kelasId === selectedKelasId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });

        // Nilai input validation
        const nilaiInput = document.getElementById('nilai');
        nilaiInput.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value < 0) this.value = 0;
            if (value > 100) this.value = 100;
        });
    });
</script>
@endpush
@endsection
