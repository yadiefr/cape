@extends('layouts.guru')

@section('title', 'Tambah Tugas - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-orange-600 mr-3"></i>
                    Tambah Tugas Siswa
                </h1>
                <p class="text-gray-600 mt-1">Buat tugas baru untuk siswa</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('guru.materi.store-tugas') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6">
                <div class="grid lg:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Judul Tugas -->
                        <div>
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('judul') border-red-500 @enderror"
                                   placeholder="Masukkan judul tugas">
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelas -->
                        <div>
                            <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select id="kelas_id" name="kelas_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('kelas_id') border-red-500 @enderror">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ old('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Mata Pelajaran -->
                        <div>
                            <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select id="mapel_id" name="mapel_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('mapel_id') border-red-500 @enderror">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mapelList as $mapel)
                                    <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mapel_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Deadline -->
                        <div>
                            <label for="tanggal_deadline" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal_deadline" name="tanggal_deadline" value="{{ old('tanggal_deadline') }}" required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('tanggal_deadline') border-red-500 @enderror">
                            @error('tanggal_deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Minimal H+1 dari hari ini
                            </p>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                                File Soal (Opsional)
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-400 transition-colors">
                                <input type="file" id="file" name="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                       class="hidden" onchange="updateFileName(this)">
                                <label for="file" class="cursor-pointer">
                                    <div class="text-gray-400 mb-2">
                                        <i class="fas fa-cloud-upload-alt text-3xl"></i>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <span class="text-orange-600 hover:text-orange-700">Klik untuk upload</span> atau drag & drop file
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        PDF, DOC, DOCX, JPG, PNG (Maks. 10MB)
                                    </p>
                                </label>
                                <div id="fileName" class="mt-2 text-sm text-green-600 hidden"></div>
                            </div>
                            @error('file')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Tugas <span class="text-red-500">*</span>
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="12" required
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 @error('deskripsi') border-red-500 @enderror"
                                      placeholder="Jelaskan tugas yang harus dikerjakan siswa...

Contoh:
1. Bacalah materi tentang...
2. Kerjakan soal nomor 1-10
3. Buatlah resume dengan minimal 500 kata
4. Upload jawaban dalam format PDF">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview Section -->
                        <div class="bg-orange-50 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-orange-800 mb-2">
                                <i class="fas fa-info-circle mr-1"></i> Tips Membuat Tugas Efektif
                            </h4>
                            <ul class="text-sm text-orange-700 space-y-1">
                                <li>• Berikan instruksi yang jelas dan spesifik</li>
                                <li>• Tentukan deadline yang realistic</li>
                                <li>• Sertakan file soal jika diperlukan</li>
                                <li>• Jelaskan format pengumpulan tugas</li>
                                <li>• Berikan rubrik penilaian yang jelas</li>
                            </ul>
                        </div>

                        <!-- Deadline Warning -->
                        <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-0.5 mr-2"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800 mb-1">Perhatian Deadline</h4>
                                    <p class="text-sm text-yellow-700">
                                        Pastikan memberikan waktu yang cukup untuk siswa mengerjakan tugas. 
                                        Disarankan minimal 3-7 hari untuk tugas biasa dan 1-2 minggu untuk tugas besar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('guru.materi.index') }}" 
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Tugas
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateFileName(input) {
    const fileName = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        fileName.textContent = `File dipilih: ${input.files[0].name}`;
        fileName.classList.remove('hidden');
    } else {
        fileName.classList.add('hidden');
    }
}

// Set minimum date to tomorrow
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const deadlineInput = document.getElementById('tanggal_deadline');
    deadlineInput.min = tomorrow.toISOString().split('T')[0];
});
</script>
@endsection
