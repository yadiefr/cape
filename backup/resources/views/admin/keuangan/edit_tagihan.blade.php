@extends('layouts.admin')

@section('title', 'Edit Tagihan')

@push('styles')
<style>
    .peer:checked ~ label .fas {
        color: #8b5cf6 !important;
    }
</style>
@endpush

@section('main-content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">
                <i class="fas fa-edit text-purple-600 mr-3"></i>Edit Tagihan
            </h1>
            <p class="text-lg text-gray-600">Perbarui informasi tagihan siswa</p>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>
                    <h3 class="text-red-800 font-semibold">Terdapat kesalahan:</h3>
                </div>
                <ul class="list-disc pl-6 text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <form action="{{ route('admin.keuangan.tagihan.update', $tagihan->id) }}" method="POST" id="editTagihanForm">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-3"></i>Informasi Tagihan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Tagihan -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-tag mr-2 text-gray-500"></i>Nama Tagihan
                            </label>
                            <input type="text" name="nama_tagihan"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                   value="{{ old('nama_tagihan', $tagihan->nama_tagihan) }}"
                                   placeholder="Contoh: SPP Bulan Januari 2025" required>
                        </div>

                        <!-- Nominal -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-money-bill-wave mr-2 text-gray-500"></i>Nominal (Rp)
                            </label>
                            <input type="number" name="nominal"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                   value="{{ old('nominal', $tagihan->nominal) }}"
                                   placeholder="150000" required min="1">
                        </div>

                        <!-- Periode -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Periode
                            </label>
                            <select name="periode" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200" required>
                                <option value="">-- Pilih Periode --</option>
                                <option value="Bulanan" {{ old('periode', $tagihan->periode) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                <option value="Semesteran" {{ old('periode', $tagihan->periode) == 'Semesteran' ? 'selected' : '' }}>Semesteran</option>
                                <option value="Tahunan" {{ old('periode', $tagihan->periode) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                <option value="Sekali" {{ old('periode', $tagihan->periode) == 'Sekali' ? 'selected' : '' }}>Sekali</option>
                            </select>
                        </div>

                        <!-- Tanggal Jatuh Tempo -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-clock mr-2 text-gray-500"></i>Tanggal Jatuh Tempo
                            </label>
                            <input type="date" name="tanggal_jatuh_tempo"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                   value="{{ old('tanggal_jatuh_tempo', $tagihan->tanggal_jatuh_tempo) }}">
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-sticky-note mr-2 text-gray-500"></i>Keterangan
                            </label>
                            <textarea name="keterangan" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                      placeholder="Keterangan tambahan (opsional)">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- Target Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-users mr-2 text-gray-500"></i>Tagihan Untuk
                    </label>

                    @php
                        // Detect target type based on keterangan
                        $targetType = 'siswa'; // default
                        if (strpos($tagihan->keterangan, '[Semua Siswa]') !== false) {
                            $targetType = 'semua';
                        } elseif (strpos($tagihan->keterangan, '[Angkatan') !== false) {
                            $targetType = 'tahun';
                        } elseif (strpos($tagihan->keterangan, '[Kelas ') !== false) {
                            $targetType = 'kelas';
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="relative">
                            <input type="radio" name="target" value="semua" id="target-semua" class="sr-only peer"
                                   {{ $targetType == 'semua' ? 'checked' : '' }} required>
                            <label for="target-semua" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                <i class="fas fa-globe text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                                <span class="font-medium text-gray-700 peer-checked:text-purple-700">Semua Siswa</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Seluruh siswa aktif</span>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" name="target" value="tahun" id="target-tahun" class="sr-only peer"
                                   {{ $targetType == 'tahun' ? 'checked' : '' }}>
                            <label for="target-tahun" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                <i class="fas fa-calendar-check text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                                <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Angkatan</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Berdasarkan tahun masuk</span>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" name="target" value="kelas" id="target-kelas" class="sr-only peer"
                                   {{ $targetType == 'kelas' ? 'checked' : '' }}>
                            <label for="target-kelas" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                <i class="fas fa-school text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                                <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Kelas</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Kelas tertentu</span>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" name="target" value="siswa" id="target-siswa" class="sr-only peer"
                                   {{ $targetType == 'siswa' ? 'checked' : '' }}>
                            <label for="target-siswa" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                                <i class="fas fa-user text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                                <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Siswa</span>
                                <span class="text-xs text-gray-500 text-center mt-1">Siswa individual</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Year/Angkatan Selection -->
                <div class="mb-6 {{ $targetType != 'tahun' ? 'hidden' : '' }}" id="tahun-select-wrapper">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-graduation-cap mr-2 text-gray-500"></i>Pilih Angkatan (Tahun Masuk)
                    </label>

                    @php
                        // Extract year from keterangan if it's angkatan
                        $selectedYear = null;
                        if ($targetType == 'tahun' && preg_match('/\[Angkatan (\d{4})/', $tagihan->keterangan, $matches)) {
                            $selectedYear = $matches[1];
                        }
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($availableYears as $year)
                        <div class="relative">
                            <input type="radio" name="tahun_masuk" value="{{ $year['year'] }}" id="tahun-{{ $year['year'] }}"
                                   class="sr-only peer tahun-radio" {{ $selectedYear == $year['year'] ? 'checked' : '' }}>
                            <label for="tahun-{{ $year['year'] }}" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                                <i class="fas fa-calendar text-xl text-gray-400 peer-checked:text-blue-500 mb-2"></i>
                                <span class="font-bold text-gray-700 peer-checked:text-blue-700">{{ $year['label'] }}</span>
                                <span class="text-sm text-gray-600 peer-checked:text-blue-600">Kelas {{ $year['tingkat'] }}</span>
                                <span class="text-xs text-gray-500 mt-1">Tahun Masuk {{ $year['year'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Class Selection -->
                <div class="mb-6 {{ $targetType != 'kelas' ? 'hidden' : '' }}" id="kelas-select-wrapper">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-school mr-2 text-gray-500"></i>Pilih Kelas
                    </label>
                    <select name="kelas_id" id="kelas-select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ old('kelas_id', $tagihan->kelas_id) == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Student Selection -->
                <div class="mb-6 {{ $targetType != 'siswa' ? 'hidden' : '' }}" id="siswa-select-wrapper">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        <i class="fas fa-user mr-2 text-gray-500"></i>Pilih Siswa
                    </label>
                    <select name="siswa_id" id="siswa-select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        <option value="">-- Pilih Siswa --</option>
                        @if(isset($siswaList) && $siswaList->count() > 0)
                            @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id }}" {{ old('siswa_id', $tagihan->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                    {{ $siswa->nama_lengkap ?? 'Nama tidak tersedia' }}
                                    @if($siswa->nis)
                                        ({{ $siswa->nis }})
                                    @endif
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Tidak ada data siswa</option>
                        @endif
                    </select>
                    <div class="text-xs text-gray-500 mt-1">
                        Total siswa: {{ isset($siswaList) ? $siswaList->count() : 0 }}
                        @if(isset($siswaList) && $siswaList->count() > 0)
                            <br>Sample: {{ $siswaList->first()->nama_lengkap }}
                        @endif
                    </div>
                </div>
            </div>
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Update Tagihan
                </button>
                <a href="{{ route('admin.keuangan.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl shadow-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const targetRadios = document.querySelectorAll('input[name="target"]');
    const tahunWrapper = document.getElementById('tahun-select-wrapper');
    const kelasWrapper = document.getElementById('kelas-select-wrapper');
    const siswaWrapper = document.getElementById('siswa-select-wrapper');

    // Handle target selection
    targetRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            hideAllWrappers();

            if (this.value === 'tahun') {
                tahunWrapper.classList.remove('hidden');
            } else if (this.value === 'kelas') {
                kelasWrapper.classList.remove('hidden');
            } else if (this.value === 'siswa') {
                siswaWrapper.classList.remove('hidden');
            }
        });
    });

    function hideAllWrappers() {
        tahunWrapper.classList.add('hidden');
        kelasWrapper.classList.add('hidden');
        siswaWrapper.classList.add('hidden');
    }

    // Form validation
    document.getElementById('editTagihanForm').addEventListener('submit', function(e) {
        const selectedTarget = document.querySelector('input[name="target"]:checked');

        if (!selectedTarget) {
            e.preventDefault();
            showNotification('Silakan pilih target tagihan', 'error');
            return;
        }

        if (selectedTarget.value === 'tahun') {
            const selectedTahun = document.querySelector('input[name="tahun_masuk"]:checked');
            if (!selectedTahun) {
                e.preventDefault();
                showNotification('Silakan pilih tahun masuk', 'error');
                return;
            }
        } else if (selectedTarget.value === 'kelas') {
            const selectedKelas = document.getElementById('kelas-select').value;
            if (!selectedKelas) {
                e.preventDefault();
                showNotification('Silakan pilih kelas', 'error');
                return;
            }
        } else if (selectedTarget.value === 'siswa') {
            const selectedSiswa = document.getElementById('siswa-select').value;
            if (!selectedSiswa) {
                e.preventDefault();
                showNotification('Silakan pilih siswa', 'error');
                return;
            }
        }
    });

    // Show notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-20 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' :
            type === 'error' ? 'bg-red-500 text-white' :
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
});
</script>
@endpush
