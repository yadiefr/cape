@extends('layouts.siswa')

@section('title', 'Edit Kas Masuk - Bendahara')

@section('content')
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Edit Kas Masuk</h1>
                <p class="text-sm text-gray-500">Untuk Tanggal</p>
                <p class="text-lg sm:text-xl font-bold text-blue-600">{{ $tanggalObj->format('d/m/Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Info Kelas -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
    @endif

    @if(session('info'))
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
    </div>
    @endif

    <!-- Form Edit Kas Masuk -->
    <form action="{{ route('siswa.bendahara.update-kas-masuk') }}" method="POST" id="editKasMasukForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="tanggal" value="{{ $tanggal }}">
        
        <!-- Iuran Bulanan Siswa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <div class="mb-6">
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-users mr-2 text-blue-600"></i>Edit Iuran Bulanan Siswa
                </h3>

                <!-- Responsive Bulk Input Section -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                    <label class="text-sm font-medium text-gray-700 flex-shrink-0">Nominal per siswa:</label>

                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-40">
                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                            <input type="number"
                                   id="nominalBulk"
                                   min="1000"
                                   step="1000"
                                   placeholder="0"
                                   class="pl-8 pr-3 py-2 w-full border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                        </div>

                        <button type="button"
                                onclick="applyBulkNominal()"
                                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition duration-200 flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>
                            <span class="hidden sm:inline">Terapkan Semua</span>
                            <span class="sm:hidden">Terapkan ke Semua Siswa</span>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6">
                @foreach($siswaKelas as $siswaItem)
                <div class="border border-gray-200 rounded-lg p-3 sm:p-4 {{ in_array($siswaItem->id, $siswaSudahBayar) ? 'bg-blue-50 border-blue-300' : 'bg-white' }} hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            @if(in_array($siswaItem->id, $siswaSudahBayar))
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                            @else
                                <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 text-sm"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 text-sm sm:text-base truncate">{{ $siswaItem->nama_lengkap }}</p>
                            <p class="text-xs sm:text-sm text-gray-500">NIS: {{ $siswaItem->nis }}</p>
                            @if(in_array($siswaItem->id, $siswaSudahBayar))
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Sudah Bayar
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-medium text-gray-700">Nominal Kas:</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                            <input type="number"
                                   name="nominal[{{ $siswaItem->id }}]"
                                   id="nominal_{{ $siswaItem->id }}"
                                   min="0"
                                   step="1000"
                                   value="{{ $nominalSiswa[$siswaItem->id] ?? 0 }}"
                                   placeholder="0"
                                   class="nominal-input w-full pl-8 pr-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500">Kosongkan atau isi 0 untuk menghapus</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Keterangan Umum -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 mb-6">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-comment-alt mr-2 text-green-600"></i>Keterangan Umum
            </h3>
            <div class="space-y-3">
                <textarea name="keterangan_umum"
                          rows="3"
                          placeholder="Contoh: Iuran kas bulan Januari 2025"
                          class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                <p class="text-xs sm:text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Keterangan ini akan diterapkan untuk semua siswa yang diinput
                </p>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4">
            <a href="{{ route('siswa.bendahara.kas-masuk') }}"
               class="w-full sm:w-auto px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200 text-center font-medium">
                <i class="fas fa-times mr-2"></i>
                Batal
            </a>
            <button type="submit"
                    class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center font-medium">
                <i class="fas fa-save mr-2"></i>
                <span class="hidden sm:inline">Update Kas Masuk</span>
                <span class="sm:hidden">Simpan Perubahan</span>
            </button>
        </div>
    </form>
</div>
</div>

@push('scripts')
<script>
let kasLainnyaCounter = {{ $kasLainnya->count() }};

function applyBulkNominal() {
    const bulkNominal = document.getElementById('nominalBulk').value;
    if (!bulkNominal || parseInt(bulkNominal) < 1000) {
        alert('Masukkan nominal minimal Rp 1.000');
        return;
    }
    
    // Apply ke semua input nominal siswa
    const nominalInputs = document.querySelectorAll('input[name*="nominal["]');
    
    nominalInputs.forEach(input => {
        input.value = bulkNominal;
    });
}

function addKasLainnya() {
    kasLainnyaCounter++;
    const container = document.getElementById('kasLainnyaContainer');
    
    const template = `
        <div class="border border-gray-200 rounded-lg p-4 mb-3" id="kasLainnya_${kasLainnyaCounter}">
            <div class="flex justify-between items-start mb-3">
                <h4 class="font-medium text-gray-800">Kas Lainnya #${kasLainnyaCounter}</h4>
                <button type="button" onclick="removeKasLainnya(${kasLainnyaCounter})" 
                        class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500 text-sm">Rp</span>
                        <input type="number" 
                               name="kas_lainnya[${kasLainnyaCounter}][nominal]" 
                               min="1000" 
                               step="1000"
                               placeholder="0"
                               class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', template);
}

function removeKasLainnya(id) {
    document.getElementById(`kasLainnya_${id}`).remove();
}

// Format number inputs
document.addEventListener('input', function(e) {
    if (e.target.type === 'number' && e.target.name && (e.target.name.includes('nominal') || e.target.id === 'nominalBulk')) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
    }
});

// Form validation
document.getElementById('editKasMasukForm').addEventListener('submit', function(e) {
    // Cek apakah ada siswa yang input nominal > 0
    const nominalInputs = document.querySelectorAll('input[name*="nominal["]');
    let hasSiswaInput = false;
    
    nominalInputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        if (value > 0) {
            hasSiswaInput = true;
        }
    });
    
    // Cek apakah ada kas lainnya
    const kasLainnya = document.querySelectorAll('input[name*="kas_lainnya"][name*="[nominal]"]').length;
    
    if (!hasSiswaInput && kasLainnya === 0) {
        e.preventDefault();
        alert('Input minimal satu nominal siswa atau kas lainnya!');
        return false;
    }
    
    // Validate minimal nominal jika ada input
    let isValid = true;
    nominalInputs.forEach(input => {
        const value = parseInt(input.value) || 0;
        if (value > 0 && value < 1000) {
            isValid = false;
            input.focus();
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Nominal minimal Rp 1.000 jika diisi!');
        return false;
    }
});
</script>

<style>
/* Mobile Responsive Styles for Edit Kas Masuk */
@media (max-width: 640px) {
    /* Ensure proper spacing for mobile */
    .space-y-3 > * + * {
        margin-top: 0.75rem;
    }

    .space-y-2 > * + * {
        margin-top: 0.5rem;
    }

    /* Mobile-specific input styling */
    .nominal-input {
        font-size: 16px; /* Prevent zoom on iOS */
    }

    /* Ensure buttons are touch-friendly */
    button, .btn {
        min-height: 44px;
        touch-action: manipulation;
    }

    /* Improve text readability on mobile */
    .text-xs {
        font-size: 0.75rem;
        line-height: 1.2;
    }

    /* Better spacing for mobile cards */
    .grid > div {
        margin-bottom: 1rem;
    }
}

/* Tablet optimizations */
@media (min-width: 641px) and (max-width: 1023px) {
    /* Ensure proper grid spacing on tablets */
    .grid {
        gap: 1rem;
    }

    /* Better button sizing for tablets */
    button {
        padding: 0.75rem 1.5rem;
    }
}

/* Desktop optimizations */
@media (min-width: 1024px) {
    /* Ensure optimal spacing on desktop */
    .grid {
        gap: 1.5rem;
    }

    /* Better hover effects on desktop */
    .hover\\:shadow-md:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
}
</style>
@endpush
@endsection
