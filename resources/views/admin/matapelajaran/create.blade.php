@extends('layouts.admin')

@section('title', 'Tambah Mata Pelajaran - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="w-full bg-gray-100 min-h-screen p-4 md:p-6 font-sans">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <a href="{{ route('admin.matapelajaran.index') }}" class="flex items-center justify-center w-10 h-10 rounded-lg bg-white shadow-sm hover:bg-amber-50 mr-3 transition-colors">
                <i class="fas fa-arrow-left text-amber-600"></i>
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Mata Pelajaran</h1>
        </div>
        <p class="text-gray-600 ml-1">Isi formulir di bawah untuk menambahkan mata pelajaran baru ke sistem</p>
    </div>
    
    <!-- Alert Messages -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-red-500"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan:</h3>
                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-amber-100 to-amber-50 py-4 px-6 border-b border-amber-200">
            <h2 class="text-lg font-semibold text-amber-800 flex items-center">
                <i class="fas fa-book-open mr-2"></i>
                Form Tambah Mata Pelajaran
            </h2>
        </div>
          <form action="{{ route('admin.matapelajaran.store') }}" method="POST" id="mapelForm">
            @csrf            <!-- Form Content -->
            <div class="p-6">
                <!-- Section: Informasi Umum -->
                <div class="mb-8">
                    <div class="border-l-4 border-amber-500 pl-3 mb-6">
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Informasi Umum
                        </h3>
                        <p class="text-sm text-gray-600">
                            Masukkan informasi dasar tentang mata pelajaran ini
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kode Mapel -->
                        <div>
                            <label for="kode" class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="kode" id="kode" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Contoh: MP001" value="{{ old('kode') }}" required>
                            <p class="text-xs text-gray-500 mt-1">Kode unik untuk mata pelajaran ini</p>
                            @error('kode')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Mapel -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" id="nama" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Contoh: Matematika" value="{{ old('nama') }}" required>
                            @error('nama')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Hidden field for is_unggulan, always false -->
                        <input type="hidden" name="is_unggulan" value="0">
                    </div>
                </div>
                  <!-- Section: Pengajar & Kurikulum -->
                <div class="mb-8">
                    <div class="border-l-4 border-blue-500 pl-3 mb-6">
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Pengajar & Kurikulum
                        </h3>
                        <p class="text-sm text-gray-600">
                            Tentukan pengajar dan aspek kurikulum mata pelajaran ini
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Guru Pengajar -->
                        <div>
                            <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-1">Guru Pengajar <span class="text-red-500">*</span></label>
                            <select name="guru_id" id="guru_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" required>
                                <option value="">Pilih Guru Pengajar</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}" {{ old('guru_id') == $g->id ? 'selected' : '' }}>{{ $g->nama }} {{ !empty($g->nip) ? '('.$g->nip.')' : '' }}</option>
                                @endforeach
                            </select>
                            @error('guru_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror                        </div>

                        <!-- Jenis Mapel (Hidden) -->
                        <input type="hidden" name="jenis" id="jenis" value="Wajib">
                        
                        <!-- Hidden defaults -->
                        <input type="hidden" name="jam_pelajaran" value="2">
                        <input type="hidden" name="status" value="Aktif">
                        
                        <!-- Mata Pelajaran Jurusan Checkbox -->
                        <div>
                            <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-100">
                                <input type="checkbox" name="is_jurusan" id="is_jurusan" value="1" class="rounded border-blue-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" {{ old('is_jurusan') ? 'checked' : '' }}>
                                <label for="is_jurusan" class="ml-2 text-sm text-blue-800 font-medium">Apakah ini mata pelajaran jurusan?</label>
                            </div>
                            @error('jenis')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Tahun Ajaran -->
                        <div>
                            <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran <span class="text-red-500">*</span></label>
                            <select name="tahun_ajaran" id="tahun_ajaran" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" required>
                                @php
                                    $currentYear = date('Y');
                                @endphp
                                @for($i = $currentYear+1; $i >= $currentYear-2; $i--)
                                    <option value="{{ $i }}/{{ $i+1 }}" {{ old('tahun_ajaran') == "$i/".($i+1) ? 'selected' : ($i == $currentYear ? 'selected' : '') }}>{{ $i }}/{{ $i+1 }}</option>
                                @endfor
                            </select>
                            @error('tahun_ajaran')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KKM -->
                        <div>
                            <label for="kkm" class="block text-sm font-medium text-gray-700 mb-1">KKM <span class="text-red-500">*</span></label>
                            <input type="number" name="kkm" id="kkm" min="1" max="100" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Contoh: 75" value="{{ old('kkm') }}" required>
                            <p class="text-xs text-gray-500 mt-1">Kriteria Ketuntasan Minimal</p>
                            @error('kkm')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                  <!-- Section: Detail Tambahan -->
                <div class="mb-8">
                    <div class="border-l-4 border-green-500 pl-3 mb-6">
                        <h3 class="font-semibold text-gray-800 text-lg">
                            Detail Tambahan
                        </h3>
                        <p class="text-sm text-gray-600">
                            Tambahkan informasi pendukung untuk mata pelajaran ini
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Deskripsi -->
                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Mata Pelajaran</label>
                            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="Deskripsi singkat tentang mata pelajaran ini...">{{ old('deskripsi') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Deskripsi ini akan membantu siswa memahami tujuan mata pelajaran</p>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Materi Pokok -->
                        <div>
                            <label for="materi_pokok" class="block text-sm font-medium text-gray-700 mb-1">Materi Pokok</label>
                            <div class="relative">
                                <textarea name="materi_pokok" id="materi_pokok" rows="6" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50" placeholder="• Pengenalan konsep dasar&#10;• Rumus dan perhitungan&#10;• Aplikasi dalam kehidupan sehari-hari">{{ old('materi_pokok') }}</textarea>
                                <div class="absolute top-3 right-3">
                                    <i class="fas fa-lightbulb text-amber-400 animate-pulse" title="Format sebagai daftar dengan bullet points untuk hasil terbaik"></i>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Gunakan format bullet points (•) dan pisahkan materi dengan baris baru</p>
                            @error('materi_pokok')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="mt-8 border-t border-gray-100 pt-6 flex justify-end">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.matapelajaran.index') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-times mr-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Mata Pelajaran
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission validation
        const mapelForm = document.getElementById('mapelForm');
        
        function validateForm() {
            let isValid = true;
            let firstErrorField = null;
            
            // Validate all required fields
            mapelForm.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
                // Find existing error element or create a new one
                let errorElement;
                const nextSibling = field.nextElementSibling;
                if (nextSibling && nextSibling.classList && nextSibling.classList.contains('text-red-500')) {
                    errorElement = nextSibling;
                } else {
                    errorElement = document.createElement('p');
                }
                
                if (!field.value.trim()) {
                    isValid = false;
                    
                    if (!errorElement.classList.contains('text-red-500')) {
                        errorElement.classList.add('text-red-500', 'text-xs', 'mt-1');
                        const fieldLabel = field.previousElementSibling ? 
                            field.previousElementSibling.textContent.replace(' *', '') : 
                            'Field ini';
                        errorElement.textContent = `${fieldLabel} wajib diisi`;
                        field.parentNode.insertBefore(errorElement, field.nextSibling);
                    }
                    
                    field.classList.add('border-red-500');
                    // Store first error field for scrolling
                    if (firstErrorField === null) {
                        firstErrorField = field;
                    }
                } else {
                    if (errorElement.parentNode) {
                        errorElement.remove();
                    }
                    field.classList.remove('border-red-500');
                }
            });
              // Kelas validation removed - no longer required
              
              // Jurusan validation removed as it's now using a hidden field with default value
            
            // Scroll to first error field if any
            if (firstErrorField) {
                firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            return isValid;
        }
        
        // Form submission validation
        mapelForm.addEventListener('submit', function(e) {
            // Use the validateForm function that handles all validation logic
            if (!validateForm()) {
                e.preventDefault();
                
                // Display a more informative error message
                const errorMessageContainer = document.createElement('div');
                errorMessageContainer.className = 'bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md shadow-sm';
                errorMessageContainer.innerHTML = `
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ada beberapa kesalahan:</h3>
                            <p class="mt-1 text-sm text-red-700">Mohon periksa dan lengkapi semua kolom yang ditandai sebelum menyimpan.</p>
                        </div>
                    </div>
                `;
                
                // Insert at the top of the form if it doesn't exist already
                const existingError = mapelForm.querySelector('.bg-red-50.border-l-4.border-red-500');
                if (!existingError) {
                    mapelForm.insertBefore(errorMessageContainer, mapelForm.firstChild);
                }
            }
        });
        
        // Auto format materi pokok with bullet points
        const materiPokokField = document.getElementById('materi_pokok');
        if (materiPokokField) {
            materiPokokField.addEventListener('blur', function() {
                let content = this.value.trim();
                if (content && !content.startsWith('•')) {
                    const lines = content.split('\n').map(line => {
                        line = line.trim();
                        if (line && !line.startsWith('•')) {
                            return '• ' + line;
                        }
                        return line;
                    });
                    this.value = lines.join('\n');
                }
            });
        }
        
        // Input mask for kode field to enforce pattern
        const kodeField = document.getElementById('kode');
        if (kodeField) {
            kodeField.addEventListener('input', function() {
                // Convert to uppercase
                this.value = this.value.toUpperCase();
                
                // Limit length
                if (this.value.length > 10) {
                    this.value = this.value.substring(0, 10);
                }
            });
        }
    });
</script>
@endsection
