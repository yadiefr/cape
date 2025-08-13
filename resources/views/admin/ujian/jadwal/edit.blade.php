@extends('layouts.ujian')

@section('title', 'Edit Jadwal Ujian')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.ujian.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-home w-4 h-4"></i>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <a href="{{ route('admin.ujian.jadwal.index') }}" class="text-gray-600 hover:text-gray-900">Jadwal Ujian</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <span class="text-gray-500">Edit Jadwal</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Jadwal Ujian</h1>
            <p class="text-gray-600 mt-1">Ubah informasi jadwal ujian</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.ujian.jadwal.index') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Informasi Jadwal Ujian</h3>
                </div>
                
                <form action="{{ route('admin.ujian.jadwal.update', $jadwal->id) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Ujian <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_ujian" required
                                   value="{{ old('nama_ujian', $jadwal->nama_ujian) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Ujian Tengah Semester">
                            @error('nama_ujian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Ujian <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_ujian" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Jenis Ujian</option>
                                <option value="uts" {{ old('jenis_ujian', $jadwal->jenis_ujian) == 'uts' ? 'selected' : '' }}>Ujian Tengah Semester</option>
                                <option value="uas" {{ old('jenis_ujian', $jadwal->jenis_ujian) == 'uas' ? 'selected' : '' }}>Ujian Akhir Semester</option>
                                <option value="quiz" {{ old('jenis_ujian', $jadwal->jenis_ujian) == 'quiz' ? 'selected' : '' }}>Quiz/Kuis</option>
                                <option value="tugas" {{ old('jenis_ujian', $jadwal->jenis_ujian) == 'tugas' ? 'selected' : '' }}>Tugas</option>
                                <option value="praktek" {{ old('jenis_ujian', $jadwal->jenis_ujian) == 'praktek' ? 'selected' : '' }}>Ujian Praktek</option>
                            </select>
                            @error('jenis_ujian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mata Pelajaran <span class="text-red-500">*</span>
                            </label>
                            <select name="mata_pelajaran_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Mata Pelajaran</option>
                                @foreach($mataPelajaran as $mapel)
                                    <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id', $jadwal->mata_pelajaran_id) == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mata_pelajaran_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="kelas_id" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Kelas</option>
                                @foreach($kelas as $kelasItem)
                                    <option value="{{ $kelasItem->id }}" {{ old('kelas_id', $jadwal->kelas_id) == $kelasItem->id ? 'selected' : '' }}>
                                        {{ $kelasItem->nama_kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Waktu Pelaksanaan</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Tanggal <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal" required
                                       value="{{ $jadwal->tanggal }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       min="{{ date('Y-m-d') }}">
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="waktu_mulai" required
                                       value="{{ $jadwal->waktu_mulai }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('waktu_mulai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Durasi (menit) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="durasi" required min="1" max="480"
                                       value="{{ $jadwal->durasi }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="60">
                                @error('durasi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Exam Settings -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Pengaturan Ujian</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Bank Soal
                                </label>
                                <select name="bank_soal_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Bank Soal (Opsional)</option>
                                    <option value="1" selected>Matematika - Aljabar Linear</option>
                                    <option value="2">Pemrograman - Algoritma Dasar</option>
                                    <option value="3">Bahasa Indonesia - Teks Argumentasi</option>
                                </select>
                                @error('bank_soal_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Ruangan
                                </label>
                                <select name="ruangan_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih Ruangan (Opsional)</option>
                                    <option value="1" selected>Lab Komputer 1</option>
                                    <option value="2">Lab Komputer 2</option>
                                    <option value="3">Ruang Kelas 10A</option>
                                    <option value="4">Ruang Kelas 10B</option>
                                </select>
                                @error('ruangan_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Status Jadwal</h4>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="draft" {{ $jadwal->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="scheduled" {{ $jadwal->status == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="active" {{ $jadwal->status == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ $jadwal->status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="cancelled" {{ $jadwal->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Additional Settings -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="text-md font-semibold text-gray-800 mb-4">Pengaturan Tambahan</h4>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" name="acak_soal" id="acak_soal" value="1" {{ $jadwal->acak_soal ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="acak_soal" class="ml-2 text-sm text-gray-700">
                                    Acak urutan soal
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="acak_jawaban" id="acak_jawaban" value="1" {{ $jadwal->acak_jawaban ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="acak_jawaban" class="ml-2 text-sm text-gray-700">
                                    Acak urutan jawaban
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" name="tampilkan_hasil" id="tampilkan_hasil" value="1" {{ $jadwal->tampilkan_hasil ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="tampilkan_hasil" class="ml-2 text-sm text-gray-700">
                                    Tampilkan hasil setelah ujian selesai
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi/Catatan
                        </label>
                        <textarea name="deskripsi" rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Tambahkan catatan atau instruksi khusus untuk ujian ini...">{{ $jadwal->deskripsi }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.ujian.jadwal.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                            Update Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Current Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Status Saat Ini</h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                Terjadwal
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Peserta:</span>
                            <span class="text-sm font-medium text-gray-800">32 siswa</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Waktu tersisa:</span>
                            <span class="text-sm font-medium text-gray-800">5 hari</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-lg p-6 border border-yellow-200">
                    <div class="flex items-center mb-3">
                        <div class="p-2 bg-yellow-600 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-white w-4 h-4"></i>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-800 ml-3">Peringatan</h4>
                    </div>
                    <ul class="text-xs text-gray-600 space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Jadwal akan mulai dalam 5 hari
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Pastikan bank soal sudah siap
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-500 w-3 h-3 mt-0.5 mr-2"></i>
                            Informasikan siswa H-1
                        </li>
                    </ul>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Aktivitas Terakhir</h4>
                    <div class="space-y-3">
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">10 Jan 2025, 14:30</div>
                            <div class="text-gray-500">Jadwal dibuat</div>
                        </div>
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">11 Jan 2025, 09:15</div>
                            <div class="text-gray-500">Bank soal ditambahkan</div>
                        </div>
                        <div class="text-xs">
                            <div class="font-medium text-gray-700">12 Jan 2025, 16:45</div>
                            <div class="text-gray-500">Pengaturan diperbarui</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto calculate end time
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const waktuMulaiInput = document.querySelector('input[name="waktu_mulai"]');
    const durasiInput = document.querySelector('input[name="durasi"]');
    
    function updateEndTime() {
        if (waktuMulaiInput.value && durasiInput.value) {
            const [hours, minutes] = waktuMulaiInput.value.split(':').map(Number);
            const durasi = parseInt(durasiInput.value);
            
            const startTime = new Date();
            startTime.setHours(hours, minutes, 0, 0);
            
            const endTime = new Date(startTime.getTime() + durasi * 60000);
            const endHours = String(endTime.getHours()).padStart(2, '0');
            const endMinutes = String(endTime.getMinutes()).padStart(2, '0');
            
            // Display end time info (you can add a display element for this)
            console.log(`Ujian berakhir pada: ${endHours}:${endMinutes}`);
        }
    }
    
    waktuMulaiInput?.addEventListener('change', updateEndTime);
    durasiInput?.addEventListener('input', updateEndTime);
    
    // Initial calculation
    updateEndTime();
});
</script>
@endpush
@endsection
