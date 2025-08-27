@extends('layouts.guru')

@section('title', 'Input Nilai - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .nilai-header {
        opacity: 0;
        transform: translateY(-20px);
        animation: slideInFromTop 0.6s ease-out forwards;
    }
    
    .nilai-form-card {
        opacity: 0;
        transform: translateY(20px);
        animation: slideInFromBottom 0.8s ease-out 0.2s forwards;
    }
    
    .form-row {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.4s ease-out;
    }
    
    .siswa-row {
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease-out;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInFromBottom {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive control for desktop/mobile views */
    @media (min-width: 768px) {
        #desktop-view {
            display: block !important;
        }
        #mobile-view {
            display: none !important;
        }
    }

    @media (max-width: 767px) {
        #desktop-view {
            display: none !important;
        }
        #mobile-view {
            display: block !important;
        }
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 nilai-header gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Input Nilai</h1>
            <div class="mt-2 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Semester {{ $currentSemester }} ({{ $currentSemester == 1 ? 'Ganjil' : 'Genap' }})
                </span>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class="fas fa-graduation-cap mr-1"></i>
                    {{ $academicYear }}/{{ $academicYear + 1 }}
                </span>
            </div>
        </div>
        <a href="{{ route('guru.nilai.show', $kelas->id) }}?action=detail&mapel_id={{ $mapel->id }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors text-center sm:text-left">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6 nilai-form-card">
        <div class="p-2 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Form Input Nilai</h2>
            <strong><p class="text-blue-600">{{ $kelas->nama_kelas ?? 'Kelas Tidak Diketahui' }} - {{ $mapel->nama ?? 'Mata Pelajaran Tidak Diketahui' }}</p></strong>
        </div>

        <div class="p-5">
            <form action="{{ route('guru.nilai.store') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6 form-row">
                    <div>
                        <label for="jenis_nilai" class="block text-gray-700 font-medium mb-2">Jenis Nilai *</label>
                        <select id="jenis_nilai" name="jenis_nilai" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Jenis Nilai</option>
                            <option value="tugas" {{ $jenisNilai === 'tugas' ? 'selected' : '' }}>Tugas Harian</option>
                            <option value="ulangan" {{ $jenisNilai === 'ulangan' ? 'selected' : '' }}>Ulangan Harian</option>
                            <option value="uts" {{ $jenisNilai === 'uts' ? 'selected' : '' }}>UTS (Ujian Tengah Semester)</option>
                            <option value="uas" {{ $jenisNilai === 'uas' ? 'selected' : '' }}>UAS (Ujian Akhir Semester)</option>
                            <option value="praktik" {{ $jenisNilai === 'praktik' ? 'selected' : '' }}>Praktik/Praktek</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="semester" class="block text-gray-700 font-medium mb-2">Semester *</label>
                        <select id="semester" name="semester" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">Pilih Semester</option>
                            <option value="1" {{ $currentSemester == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ $currentSemester == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Semester dipilih otomatis berdasarkan bulan saat ini
                        </p>
                    </div>
                    
                    <div>
                        <label for="tahun_ajaran" class="block text-gray-700 font-medium mb-2">Tahun Ajaran *</label>
                        <select id="tahun_ajaran" name="tahun_ajaran" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="{{ $academicYear }}" selected>{{ $academicYear }}/{{ $academicYear + 1 }}</option>
                            <option value="{{ $academicYear - 1 }}">{{ $academicYear - 1 }}/{{ $academicYear }}</option>
                            <option value="{{ $academicYear + 1 }}">{{ $academicYear + 1 }}/{{ $academicYear + 2 }}</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            Tahun ajaran saat ini: {{ $academicYear }}/{{ $academicYear + 1 }}
                        </p>
                    </div>
                </div>
                
                <div class="mb-4 form-row">
                    <label for="deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi/Keterangan Nilai</label>
                    <input type="text" id="deskripsi" name="deskripsi" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Tugas Matematika Bab 1, UTS Genap 2025, dll">
                </div>
                
                <!-- Desktop Table View -->
                <div id="desktop-view" class="border rounded-lg overflow-hidden mb-6 form-row">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="py-3 px-4 text-left font-medium text-gray-700">No</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">NIS</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">Nama Siswa</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">Nilai</th>
                                <th class="py-3 px-4 text-left font-medium text-gray-700">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $index => $s)
                            <tr class="border-b last:border-b-0 hover:bg-gray-50 siswa-row">
                                <td class="py-3 px-4 text-gray-800">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-gray-800">{{ $s->nis }}</td>
                                <td class="py-3 px-4 text-gray-800">{{ $s->nama_lengkap }}</td>
                                <td class="py-3 px-4">
                                    <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                                    <input type="number" name="nilai[]" min="0" max="100" value="0"
                                           class="w-24 rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </td>
                                <td class="py-3 px-4">
                                    <input type="text" name="keterangan[]"
                                           class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="Opsional">
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada siswa di kelas ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div id="mobile-view" class="hidden mb-6 form-row">
                    <div class="space-y-3">
                        @forelse($siswa as $index => $s)
                        <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm siswa-row">
                            <!-- Compact header dengan nama dan input nilai dalam satu baris -->
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center flex-1 min-w-0">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-2 flex-shrink-0">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $s->nama_lengkap }}
                                        </h3>
                                        <p class="text-xs text-gray-500">{{ $s->nis }}</p>
                                        <input type="hidden" name="siswa_id[]" value="{{ $s->id }}">
                                    </div>
                                </div>
                                <!-- Input nilai di sebelah kanan -->
                                <div class="flex-shrink-0 ml-3">
                                    <input type="number" name="nilai[]" min="0" max="100" value="0"
                                           class="w-16 text-center rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 font-semibold"
                                           placeholder="0" required>
                                </div>
                            </div>

                            <!-- Input Keterangan (compact) -->
                            <input type="text" name="keterangan[]"
                                   class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                   placeholder="Keterangan (opsional)">
                        </div>
                        @empty
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-users text-2xl mb-2"></i>
                            <p class="text-sm">Tidak ada siswa di kelas ini</p>
                        </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors text-center">
                        <i class="fas fa-save mr-2"></i> Simpan Nilai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Responsive view control
    function handleResponsiveView() {
        const desktopView = document.getElementById('desktop-view');
        const mobileView = document.getElementById('mobile-view');

        if (window.innerWidth >= 768) {
            // Desktop view
            if (desktopView) desktopView.style.display = 'block';
            if (mobileView) mobileView.style.display = 'none';

            // Enable desktop inputs, disable mobile inputs
            enableInputs('#desktop-view');
            disableInputs('#mobile-view');
        } else {
            // Mobile view
            if (desktopView) desktopView.style.display = 'none';
            if (mobileView) mobileView.style.display = 'block';

            // Enable mobile inputs, disable desktop inputs
            enableInputs('#mobile-view');
            disableInputs('#desktop-view');
        }
    }

    // Function to enable inputs in a container
    function enableInputs(container) {
        const inputs = document.querySelectorAll(container + ' input');
        inputs.forEach(input => {
            input.disabled = false;
        });
    }

    // Function to disable inputs in a container
    function disableInputs(container) {
        const inputs = document.querySelectorAll(container + ' input');
        inputs.forEach(input => {
            input.disabled = true;
        });
    }

    // Initial call and resize listener
    handleResponsiveView();
    window.addEventListener('resize', handleResponsiveView);

    // Animate form rows with staggered timing
    const formRows = document.querySelectorAll('.form-row');
    formRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateX(0)';
        }, 300 + (index * 100));
    });

    // Animate student rows with staggered timing
    const siswaRows = document.querySelectorAll('.siswa-row');
    siswaRows.forEach((row, index) => {
        setTimeout(() => {
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, 600 + (index * 50));
    });
    
    // Display semester information in console for debugging
    console.log('Semester Detection System:');
    console.log('Current Month:', {{ date('n') }});
    console.log('Selected Semester:', {{ $currentSemester }});
    console.log('Academic Year:', '{{ $academicYear }}/{{ $academicYear + 1 }}');
    console.log('Semester 1: Juli-Desember (Month 7-12)');
    console.log('Semester 2: Januari-Juni (Month 1-6)');
    
    // Add semester change notification
    const semesterSelect = document.getElementById('semester');
    if (semesterSelect) {
        semesterSelect.addEventListener('change', function() {
            const selectedSemester = this.value;
            const semesterInfo = selectedSemester == '1' ? 'Juli - Desember' : 'Januari - Juni';
            console.log('Semester changed to:', selectedSemester, '(' + semesterInfo + ')');
        });
    }

    // Form submit handler to ensure only active inputs are submitted
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Re-run responsive view control to ensure correct inputs are enabled
            handleResponsiveView();

            // Log which view is active for debugging
            const isDesktop = window.innerWidth >= 768;
            console.log('Form submitted from:', isDesktop ? 'Desktop' : 'Mobile', 'view');
        });
    }
});
</script>
@endpush
@endsection
