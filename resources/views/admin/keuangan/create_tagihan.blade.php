@extends('layouts.admin')

@section('title', 'Tambah Tagihan Baru')

@section('main-content')
<!-- Enhanced Header -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-lg mb-8 overflow-hidden">
    <div class="px-8 py-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-plus-circle text-white text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Tambah Tagihan Baru</h1>
                <p class="text-purple-100">Buat tagihan untuk siswa berdasarkan target yang dipilih</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden max-w-4xl mx-auto">
    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <div class="flex items-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-3">
                <i class="fas fa-file-invoice-dollar text-purple-600"></i>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Form Tagihan</h2>
                <p class="text-gray-600">Lengkapi informasi tagihan di bawah ini</p>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.keuangan.tagihan.store') }}" method="POST" class="p-8">
        @csrf

        <!-- Basic Information Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                Informasi Dasar Tagihan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-gray-500"></i>Nama Tagihan
                    </label>
                    <input type="text" name="nama_tagihan"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                           placeholder="Contoh: SPP Bulan Januari 2025" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-money-bill-wave mr-2 text-gray-500"></i>Nominal (Rp)
                    </label>
                    <input type="number" name="nominal"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                           placeholder="Masukkan nominal tagihan" required min="1">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-gray-500"></i>Periode
                    </label>
                    <select name="periode" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                        <option value="">-- Pilih Periode --</option>
                        <option value="Bulanan">Bulanan</option>
                        <option value="Semesteran">Semesteran</option>
                        <option value="Tahunan">Tahunan</option>
                        <option value="Sekali">Sekali</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clock mr-2 text-gray-500"></i>Tanggal Jatuh Tempo
                    </label>
                    <input type="date" name="tanggal_jatuh_tempo"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-2 text-gray-500"></i>Keterangan
                </label>
                <textarea name="keterangan" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                          placeholder="Keterangan tambahan (opsional)"></textarea>
            </div>
        </div>
        <!-- Target Selection Section -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bullseye text-green-500 mr-2"></i>
                Target Tagihan
            </h3>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-users mr-2 text-gray-500"></i>Tagihan Untuk
                </label>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <input type="radio" name="target" value="semua" id="target-semua" class="sr-only peer" required>
                        <label for="target-semua" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <i class="fas fa-globe text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                            <span class="font-medium text-gray-700 peer-checked:text-purple-700">Semua Siswa</span>
                            <span class="text-xs text-gray-500 text-center mt-1">Seluruh siswa aktif</span>
                        </label>
                    </div>

                    <div class="relative">
                        <input type="radio" name="target" value="tahun" id="target-tahun" class="sr-only peer">
                        <label for="target-tahun" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <i class="fas fa-calendar-check text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                            <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Angkatan</span>
                            <span class="text-xs text-gray-500 text-center mt-1">Berdasarkan tahun masuk</span>
                        </label>
                    </div>

                    <div class="relative">
                        <input type="radio" name="target" value="kelas" id="target-kelas" class="sr-only peer">
                        <label for="target-kelas" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <i class="fas fa-school text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                            <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Kelas</span>
                            <span class="text-xs text-gray-500 text-center mt-1">Kelas tertentu</span>
                        </label>
                    </div>

                    <div class="relative">
                        <input type="radio" name="target" value="siswa" id="target-siswa" class="sr-only peer">
                        <label for="target-siswa" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-purple-300 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all duration-200">
                            <i class="fas fa-user text-2xl text-gray-400 peer-checked:text-purple-500 mb-2"></i>
                            <span class="font-medium text-gray-700 peer-checked:text-purple-700">Per Siswa</span>
                            <span class="text-xs text-gray-500 text-center mt-1">Siswa individual</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Year Selection -->
            <div class="mb-6 hidden" id="tahun-select-wrapper">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-graduation-cap mr-2 text-gray-500"></i>Pilih Angkatan (Tahun Masuk)
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($availableYears as $year)
                    <div class="relative">
                        <input type="radio" name="tahun_masuk" value="{{ $year['year'] }}" id="tahun-{{ $year['year'] }}" class="sr-only peer tahun-radio">
                        <label for="tahun-{{ $year['year'] }}" class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-300 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200">
                            <i class="fas fa-calendar text-xl text-gray-400 peer-checked:text-blue-500 mb-2"></i>
                            <span class="font-bold text-gray-700 peer-checked:text-blue-700">{{ $year['label'] }}</span>
                            <span class="text-sm text-gray-600 peer-checked:text-blue-600">Kelas {{ $year['tingkat'] }}</span>
                            <span class="text-xs text-gray-500 mt-1">Tahun Masuk {{ $year['year'] }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>

                <!-- Option for all classes in selected year -->
                <div class="mt-4 p-4 bg-blue-50 rounded-xl border border-blue-200" id="tahun-options" style="display: none;">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-semibold text-blue-900">Opsi Target dalam Angkatan</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="relative">
                            <input type="radio" name="tahun_target_type" value="all" id="tahun-all" class="sr-only peer" checked>
                            <label for="tahun-all" class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:border-blue-400 peer-checked:border-blue-500 peer-checked:bg-blue-100 transition-all duration-200">
                                <i class="fas fa-users text-blue-500 mr-3"></i>
                                <div>
                                    <span class="font-medium text-blue-900">Semua Kelas</span>
                                    <p class="text-xs text-blue-600">Seluruh siswa dalam angkatan</p>
                                </div>
                            </label>
                        </div>
                        <div class="relative">
                            <input type="radio" name="tahun_target_type" value="specific" id="tahun-specific" class="sr-only peer">
                            <label for="tahun-specific" class="flex items-center p-3 border border-blue-200 rounded-lg cursor-pointer hover:border-blue-400 peer-checked:border-blue-500 peer-checked:bg-blue-100 transition-all duration-200">
                                <i class="fas fa-filter text-blue-500 mr-3"></i>
                                <div>
                                    <span class="font-medium text-blue-900">Kelas Tertentu</span>
                                    <p class="text-xs text-blue-600">Pilih kelas spesifik</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Class Selection for Year -->
            <div class="mb-6 hidden" id="tahun-kelas-select-wrapper">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-school mr-2 text-gray-500"></i>Pilih Kelas dalam Angkatan
                </label>
                <select name="tahun_kelas_id" id="tahun-kelas-select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <option value="">-- Pilih Kelas --</option>
                </select>
            </div>

            <!-- Regular Class Selection -->
            <div class="mb-6 hidden" id="kelas-select-wrapper">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-school mr-2 text-gray-500"></i>Pilih Kelas
                </label>
                <select name="kelas_id" id="kelas-select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Student Selection -->
            <div class="mb-6 hidden" id="siswa-select-wrapper">
                <label class="block text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-user mr-2 text-gray-500"></i>Pilih Siswa
                </label>
                <select name="siswa_id" id="siswa-select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200">
                    <option value="">-- Pilih Siswa --</option>
                    @if(isset($siswaList) && $siswaList->count() > 0)
                        @foreach($siswaList as $siswa)
                            <option value="{{ $siswa->id }}">
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
            </div>
        </div>
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            <button type="submit" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-save mr-2"></i>
                Simpan Tagihan
            </button>
            <a href="{{ route('admin.keuangan.index') }}" class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all elements
    const targetRadios = document.querySelectorAll('input[name="target"]');
    const tahunRadios = document.querySelectorAll('.tahun-radio');
    const tahunTargetRadios = document.querySelectorAll('input[name="tahun_target_type"]');

    const tahunWrapper = document.getElementById('tahun-select-wrapper');
    const tahunOptions = document.getElementById('tahun-options');
    const tahunKelasWrapper = document.getElementById('tahun-kelas-select-wrapper');
    const kelasWrapper = document.getElementById('kelas-select-wrapper');
    const siswaWrapper = document.getElementById('siswa-select-wrapper');

    const tahunKelasSelect = document.getElementById('tahun-kelas-select');
    const siswaSelect = document.getElementById('siswa-select');

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
                // Load all siswa data when siswa target is selected
                loadAllSiswa();
            }
        });
    });

    // Handle year selection
    tahunRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                tahunOptions.style.display = 'block';

                // Load kelas for the selected year
                loadKelasForYear(this.value);

                // If "specific" is already selected, show the kelas wrapper
                const specificRadio = document.getElementById('tahun-specific');
                if (specificRadio && specificRadio.checked) {
                    tahunKelasWrapper.classList.remove('hidden');
                }
            }
        });
    });

    // Handle year target type selection using event delegation
    document.addEventListener('change', function(e) {
        if (e.target.name === 'tahun_target_type') {
            if (e.target.value === 'specific') {
                tahunKelasWrapper.classList.remove('hidden');
                // Load kelas for currently selected year if any
                const selectedYear = document.querySelector('input[name="tahun_masuk"]:checked');
                if (selectedYear) {
                    loadKelasForYear(selectedYear.value);
                }
            } else {
                tahunKelasWrapper.classList.add('hidden');
            }
        }
    });

    // Load students when class changes (for regular class selection)
    document.getElementById('kelas-select').addEventListener('change', function() {
        if (this.value) {
            loadSiswaForKelas(this.value);
        } else {
            clearSiswaOptions();
        }
    });

    function hideAllWrappers() {
        tahunWrapper.classList.add('hidden');
        tahunOptions.style.display = 'none';
        tahunKelasWrapper.classList.add('hidden');
        kelasWrapper.classList.add('hidden');
        siswaWrapper.classList.add('hidden');

        // Clear selections
        tahunRadios.forEach(radio => radio.checked = false);
        document.getElementById('tahun-all').checked = true;
        clearKelasOptions();
        clearSiswaOptions();
    }

    function loadKelasForYear(year) {
        // Show loading state
        tahunKelasSelect.innerHTML = '<option value="">Loading...</option>';

        fetch(`/api/tahun-masuk/${year}/kelas`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                clearKelasOptions();

                if (data && data.length > 0) {
                    data.forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas.id;
                        option.textContent = kelas.nama_kelas;
                        tahunKelasSelect.appendChild(option);
                    });
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Tidak ada kelas tersedia';
                    tahunKelasSelect.appendChild(option);
                }
            })
            .catch(error => {
                console.error('Error loading kelas:', error);
                clearKelasOptions();
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Error loading kelas';
                tahunKelasSelect.appendChild(option);
                showNotification('Gagal memuat data kelas: ' + error.message, 'error');
            });
    }

    function loadSiswaForKelas(kelasId) {
        fetch(`/api/kelas/${kelasId}/siswa`)
            .then(response => response.json())
            .then(data => {
                clearSiswaOptions();
                data.forEach(siswa => {
                    const option = document.createElement('option');
                    option.value = siswa.id;
                    option.textContent = `${siswa.nama} (${siswa.nis})`;
                    siswaSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading siswa:', error);
                showNotification('Gagal memuat data siswa', 'error');
            });
    }

    function loadAllSiswa() {        
        // Use the siswa data that was already passed from the controller
        @if(isset($siswaList) && $siswaList->count() > 0)
            const siswaData = @json($siswaList);
            
            clearSiswaOptions();
            siswaData.forEach(siswa => {
                const option = document.createElement('option');
                option.value = siswa.id;
                option.textContent = `${siswa.nama_lengkap || siswa.nama || 'Nama tidak tersedia'}${siswa.nis ? ' (' + siswa.nis + ')' : ''}`;
                siswaSelect.appendChild(option);
            });
        @else
            showNotification('Data siswa tidak tersedia', 'error');
        @endif
    }

    function clearKelasOptions() {
        tahunKelasSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
    }

    function clearSiswaOptions() {
        siswaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';

        notification.className = `fixed top-20 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
        notification.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i>
                <span>${message}</span>
            </div>
        `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const selectedTarget = document.querySelector('input[name="target"]:checked');

        if (!selectedTarget) {
            e.preventDefault();
            showNotification('Silakan pilih target tagihan', 'error');
            return;
        }

        if (selectedTarget.value === 'tahun') {
            const selectedYear = document.querySelector('input[name="tahun_masuk"]:checked');
            if (!selectedYear) {
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
});
</script>
@endpush
@endsection 