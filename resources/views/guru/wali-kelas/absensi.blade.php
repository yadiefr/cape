@extends('layouts.guru')

@section('title', 'Absensi Harian')

@push('styles')
<style>
    /* Ensure status text is visible on desktop */
    @media (min-width: 640px) {
        .status-text-desktop {
            display: block !important;
        }

        .status-text-mobile {
            display: none !important;
        }

        /* Ensure proper spacing for desktop status buttons */
        .status-button-desktop {
            min-width: 80px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    @media (max-width: 639px) {
        .status-text-desktop {
            display: none !important;
        }

        .status-text-mobile {
            display: block !important;
        }
    }

    /* Improve button visibility */
    .absensi-button {
        border: 2px solid transparent;
        transition: all 0.2s ease;
    }

    .absensi-button:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* Ensure Alpine.js doesn't interfere with visibility */
    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="p-3 sm:p-6" x-data="absensiManager()">
    <!-- Toast Notification -->
    <div x-show="showNotification" 
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50">
        <div class="bg-white rounded-lg shadow-lg border-l-4 p-4 max-w-sm"
             :class="{
                'border-green-400': notificationType === 'success',
                'border-red-400': notificationType === 'error',
                'border-yellow-400': notificationType === 'warning'
             }">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="text-lg"
                       :class="{
                          'fas fa-check-circle text-green-400': notificationType === 'success',
                          'fas fa-exclamation-circle text-red-400': notificationType === 'error',
                          'fas fa-exclamation-triangle text-yellow-400': notificationType === 'warning'
                       }"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900" x-text="notificationMessage"></p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="showNotification = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Absensi Harian</h1>
        </div>
    </div>

    <!-- Info Kelas & Filter -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-4 sm:px-6 py-4 rounded-t-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
                    <h5 class="text-base sm:text-lg font-semibold mb-0">
                        <i class="fas fa-users mr-2"></i> {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan }}
                    </h5>
                    <!-- Filter Tanggal -->
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-white">Tanggal:</label>
                        <div class="relative">
                            <input type="date" 
                                   id="filter-tanggal"
                                   value="{{ $tanggal }}" 
                                   class="px-3 py-1 text-sm border border-blue-300 rounded-md text-gray-900 focus:ring-2 focus:ring-white focus:border-white"
                                   :disabled="isLoading"
                                   :class="{ 'opacity-50 cursor-not-allowed': isLoading }"
                                   @change="filterByDate($event.target.value)">
                            <div x-show="isLoading" class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-spinner fa-spin text-gray-400"></i>
                            </div>
                            <div x-show="showSuccessIndicator" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-75"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-75"
                                 class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <i class="fas fa-check-circle text-green-500"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <p class="mb-1 text-gray-700"><strong>Total Siswa:</strong> {{ $siswaList->count() }} siswa</p>
                        <p class="mb-0 text-gray-500 text-sm">
                            Tanggal: <span class="font-medium" x-text="formatDate('{{ $tanggal }}')"></span> 
                            <div class="text">Jangan lupa klik simpan di bawah setelah melakukan perubahan</div>
                        </p>
                    </div>
                    <div class="w-full lg:w-auto">
                        <div class="grid grid-cols-2 sm:flex gap-2">
                            <button type="button" class="inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors" @click="setAllStatus('hadir')">
                                <i class="fas fa-check mr-1 sm:mr-2"></i> 
                                <span class="text-xs sm:text-sm">Semua Hadir</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Absensi -->
    <form method="POST" action="{{ route('guru.wali-kelas.absensi.simpan') }}" @submit="onSubmit">
        @csrf
        
        <!-- Hidden field untuk tanggal yang akan dikirim saat submit form -->
        <input type="hidden" name="tanggal" x-model="selectedDate">

        <!-- Daftar Siswa -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                <h6 class="text-base sm:text-lg font-semibold text-gray-900 mb-0">
                    <i class="fas fa-clipboard-check mr-2"></i> Daftar Absensi - <span x-text="formatDate(selectedDate)"></span>
                </h6>
            </div>
            <div class="p-4 sm:p-6 relative">
                <!-- Loading Overlay -->
                <div x-show="isLoading" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center z-10 rounded-lg">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-blue-600 mb-2"></i>
                        <p class="text-gray-600 font-medium">Memuat data absensi...</p>
                    </div>
                </div>
                
                <div class="overflow-x-auto transition-opacity duration-300" :class="{ 'opacity-30': isLoading }">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 sm:w-16">No</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">NISN</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Absensi</th>
                                <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($siswaList as $index => $siswa)
                            <tr class="hover:bg-gray-50">
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <x-student-avatar :student="$siswa" size="md" />
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $siswa->nama_lengkap }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500 sm:hidden">{{ $siswa->nisn ?? $siswa->nis ?? '-' }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500 hidden sm:block">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $siswa->nisn ?? $siswa->nis ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-4">
                                    <div class="grid grid-cols-2 sm:flex sm:space-x-2 gap-1 sm:gap-0">
                                        <label class="inline-flex items-center">
                                            <input type="radio"
                                                   name="absensi[{{ $siswa->id }}]"
                                                   value="hadir"
                                                   x-model="absensiData[{{ $siswa->id }}]"
                                                   class="sr-only"
                                                   {{ (isset($absensiData[$siswa->id]) && $absensiData[$siswa->id]->status == 'hadir') ? 'checked' : '' }}>
                                            <span class="absensi-button px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full cursor-pointer transition-colors w-full sm:w-auto text-center status-button-desktop"
                                                  :class="absensiData[{{ $siswa->id }}] === 'hadir' ? 'bg-green-100 text-green-800 border-2 border-green-500' : 'bg-gray-100 text-gray-600 border-2 border-gray-300 hover:bg-green-50'"
                                                  @click="setStatus({{ $siswa->id }}, 'hadir')">
                                                <i class="fas fa-check mr-1 hidden sm:inline"></i>
                                                <span class="status-text-mobile">H</span>
                                                <span class="status-text-desktop">Hadir</span>
                                            </span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="radio"
                                                   name="absensi[{{ $siswa->id }}]"
                                                   value="sakit"
                                                   x-model="absensiData[{{ $siswa->id }}]"
                                                   class="sr-only"
                                                   {{ (isset($absensiData[$siswa->id]) && $absensiData[$siswa->id]->status == 'sakit') ? 'checked' : '' }}>
                                            <span class="absensi-button px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full cursor-pointer transition-colors w-full sm:w-auto text-center status-button-desktop"
                                                  :class="absensiData[{{ $siswa->id }}] === 'sakit' ? 'bg-yellow-100 text-yellow-800 border-2 border-yellow-500' : 'bg-gray-100 text-gray-600 border-2 border-gray-300 hover:bg-yellow-50'"
                                                  @click="setStatus({{ $siswa->id }}, 'sakit')">
                                                <i class="fas fa-thermometer-half mr-1 hidden sm:inline"></i>
                                                <span class="status-text-mobile">S</span>
                                                <span class="status-text-desktop">Sakit</span>
                                            </span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="radio"
                                                   name="absensi[{{ $siswa->id }}]"
                                                   value="izin"
                                                   x-model="absensiData[{{ $siswa->id }}]"
                                                   class="sr-only"
                                                   {{ (isset($absensiData[$siswa->id]) && $absensiData[$siswa->id]->status == 'izin') ? 'checked' : '' }}>
                                            <span class="absensi-button px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full cursor-pointer transition-colors w-full sm:w-auto text-center status-button-desktop"
                                                  :class="absensiData[{{ $siswa->id }}] === 'izin' ? 'bg-blue-100 text-blue-800 border-2 border-blue-500' : 'bg-gray-100 text-gray-600 border-2 border-gray-300 hover:bg-blue-50'"
                                                  @click="setStatus({{ $siswa->id }}, 'izin')">
                                                <i class="fas fa-file-alt mr-1 hidden sm:inline"></i>
                                                <span class="status-text-mobile">I</span>
                                                <span class="status-text-desktop">Izin</span>
                                            </span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="radio"
                                                   name="absensi[{{ $siswa->id }}]"
                                                   value="alpha"
                                                   x-model="absensiData[{{ $siswa->id }}]"
                                                   class="sr-only"
                                                   {{ (isset($absensiData[$siswa->id]) && $absensiData[$siswa->id]->status == 'alpha') ? 'checked' : '' }}>
                                            <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm font-medium rounded-full cursor-pointer transition-colors w-full sm:w-auto text-center"
                                                  :class="absensiData[{{ $siswa->id }}] === 'alpha' ? 'bg-red-100 text-red-800 border-2 border-red-500' : 'bg-gray-100 text-gray-600 border-2 border-gray-300 hover:bg-red-50'"
                                                  @click="setStatus({{ $siswa->id }}, 'alpha')">
                                                <i class="fas fa-times mr-1 hidden sm:inline"></i>
                                                <span class="block sm:hidden">A</span>
                                                <span class="hidden sm:block">Alpha</span>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td class="px-3 sm:px-6 py-4 hidden lg:table-cell">
                                    <input type="text" 
                                           name="keterangan[{{ $siswa->id }}]" 
                                           class="w-full px-3 py-1 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                           placeholder="Keterangan..."
                                           value="{{ isset($absensiData[$siswa->id]) ? $absensiData[$siswa->id]->keterangan : '' }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 pt-4 border-t border-gray-200 space-y-4 sm:space-y-0">
                    <div class="w-full sm:w-auto">
                        <button type="submit" 
                                class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors"
                                :disabled="isSubmitting"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }">
                            <i class="fas fa-save mr-2" :class="{ 'fa-spinner fa-spin': isSubmitting }"></i> 
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Absensi'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function absensiManager() {
    return {
        selectedDate: '{{ $tanggal }}',
        isSubmitting: false,
        isLoading: false,
        showSuccessIndicator: false,
        showNotification: false,
        notificationMessage: '',
        notificationType: 'success', // success, error, warning
        absensiData: @json($absensiData->mapWithKeys(function($item, $key) {
            return [$item->siswa_id => $item->status];
        })->toArray()),
        
        // Filter berdasarkan tanggal (SPA - No Refresh)
        async filterByDate(date) {
            this.selectedDate = date;
            this.isLoading = true;
            
            try {
                const response = await fetch(`{{ route("guru.wali-kelas.absensi") }}?tanggal=${date}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    
                    // Update absensi data
                    this.absensiData = data.absensiData || {};
                    
                    // Update form dengan data baru
                    this.updateFormData(data.siswaList, data.absensiData);
                    
                    // Update URL tanpa refresh
                    window.history.pushState({}, '', `{{ route("guru.wali-kelas.absensi") }}?tanggal=${date}`);
                    
                    // Show success indicator
                    this.showSuccessIndicator = true;
                    setTimeout(() => {
                        this.showSuccessIndicator = false;
                    }, 2000);
                    
                } else {
                    console.error('Error loading data:', response.statusText);
                    this.showNotificationMessage('Gagal memuat data absensi', 'error');
                    // Fallback ke reload jika error
                    setTimeout(() => {
                        window.location.href = `{{ route("guru.wali-kelas.absensi") }}?tanggal=${date}`;
                    }, 2000);
                }
            } catch (error) {
                console.error('Network error:', error);
                this.showNotificationMessage('Terjadi kesalahan jaringan', 'error');
                // Fallback ke reload jika error
                setTimeout(() => {
                    window.location.href = `{{ route("guru.wali-kelas.absensi") }}?tanggal=${date}`;
                }, 2000);
            } finally {
                this.isLoading = false;
            }
        },
        
        // Show notification
        showNotificationMessage(message, type = 'success') {
            this.notificationMessage = message;
            this.notificationType = type;
            this.showNotification = true;
            
            setTimeout(() => {
                this.showNotification = false;
            }, 4000);
        },
        
        // Update form data setelah load via AJAX
        updateFormData(siswaList, absensiData) {
            // Clear semua radio button yang sudah dipilih
            document.querySelectorAll('input[type="radio"][name^="absensi"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Clear semua keterangan
            document.querySelectorAll('input[name^="keterangan"]').forEach(input => {
                input.value = '';
            });
            
            // Set radio button dan keterangan berdasarkan data absensi
            Object.entries(absensiData).forEach(([siswaId, absensiInfo]) => {
                const radioButton = document.querySelector(`input[name="absensi[${siswaId}]"][value="${absensiInfo.status}"]`);
                if (radioButton) {
                    radioButton.checked = true;
                    this.absensiData[siswaId] = absensiInfo.status;
                }
                
                const keteranganInput = document.querySelector(`input[name="keterangan[${siswaId}]"]`);
                if (keteranganInput && absensiInfo.keterangan) {
                    keteranganInput.value = absensiInfo.keterangan;
                }
            });
        },
        
        // Format tanggal untuk display
        formatDate(dateString) {
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        },
        
        // Set semua siswa dengan status tertentu
        setAllStatus(status) {
            const students = @json($siswaList->pluck('id')->toArray());
            students.forEach(studentId => {
                const radioButton = document.querySelector(`input[name="absensi[${studentId}]"][value="${status}"]`);
                if (radioButton) {
                    radioButton.checked = true;
                    this.absensiData[studentId] = status;
                }
            });
        },
        
        // Set status individual siswa
        setStatus(studentId, status) {
            const radioButton = document.querySelector(`input[name="absensi[${studentId}]"][value="${status}"]`);
            if (radioButton) {
                radioButton.checked = true;
                this.absensiData[studentId] = status;
            }
        },
        
        // Get summary absensi
        getAbsensiSummary() {
            const students = @json($siswaList->pluck('id')->toArray());
            const selected = students.filter(studentId => {
                const radioButtons = document.querySelectorAll(`input[name="absensi[${studentId}]"]`);
                return [...radioButtons].some(radio => radio.checked);
            });
            
            if (selected.length === 0) {
                return 'Belum ada yang dipilih';
            } else if (selected.length === students.length) {
                return 'Semua sudah dipilih';
            } else {
                return `${selected.length}/${students.length} sudah dipilih`;
            }
        },
        
        // Handle submit form
        onSubmit(event) {
            this.isSubmitting = true;
            
            // Validasi: pastikan semua siswa sudah dipilih statusnya
            const students = @json($siswaList->pluck('id')->toArray());
            const unselectedStudents = students.filter(studentId => {
                const radioButtons = document.querySelectorAll(`input[name="absensi[${studentId}]"]`);
                return ![...radioButtons].some(radio => radio.checked);
            });
            
            if (unselectedStudents.length > 0) {
                event.preventDefault();
                this.isSubmitting = false;
                alert(`Harap pilih status absensi untuk semua siswa. ${unselectedStudents.length} siswa belum dipilih statusnya.`);
                return false;
            }
            
            // Konfirmasi sebelum submit
            if (!confirm(`Apakah Anda yakin ingin menyimpan absensi untuk tanggal ${this.formatDate(this.selectedDate)}?`)) {
                event.preventDefault();
                this.isSubmitting = false;
                return false;
            }
            
            console.log('Menyimpan absensi untuk tanggal:', this.selectedDate);
            return true;
        }
    }
}
</script>
@endsection
