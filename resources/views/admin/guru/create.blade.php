@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Guru</h1>
        <a href="{{ route('admin.guru.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <!-- Box Form Tambah Guru -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Form Tambah Guru</h2>
            <p class="text-sm text-gray-600">Isi detail guru baru di bawah ini.</p>
        </div>

        <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIP -->
                <div>
                    <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-gray-400">(Opsional)</span></label>
                    <input type="text" name="nip" id="nip" value="{{ old('nip') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nip') border-red-500 @enderror"
                        placeholder="Masukkan NIP guru">
                    @error('nip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('nama') border-red-500 @enderror"
                        placeholder="Masukkan nama lengkap guru" required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('email') border-red-500 @enderror"
                        placeholder="Masukkan email guru" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('password') border-red-500 @enderror"
                        placeholder="Masukkan password" required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" 
                        class="w-full h-11 px-3 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('jenis_kelamin') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No. HP -->
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('no_hp') border-red-500 @enderror"
                        placeholder="Masukkan nomor HP guru" required>
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Foto -->
                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                    <input type="file" name="foto" id="foto" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('foto') border-red-500 @enderror"
                        accept="image/*">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="md:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('alamat') border-red-500 @enderror"
                        placeholder="Masukkan alamat lengkap guru">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Wali Kelas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status Wali Kelas</label>
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-blue-50">
                        <input type="checkbox" name="is_wali_kelas" id="is_wali_kelas" value="1" 
                            class="rounded border-gray-300 text-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                            {{ old('is_wali_kelas') ? 'checked' : '' }}
                            onchange="toggleKelasSelectionCreate()">
                        <label for="is_wali_kelas" class="ml-3 flex items-center">
                            <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                            <span class="text-gray-700">Jadikan sebagai Wali Kelas</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Wali kelas memiliki tanggung jawab khusus untuk membimbing satu kelas</p>
                    
                    <!-- Pilihan Kelas (muncul jika wali kelas dicentang) -->
                    <div id="kelas-selection-create" class="mt-4" style="display: {{ old('is_wali_kelas') ? 'block' : 'none' }}">
                        <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas yang Dipimpin</label>
                        <select name="wali_kelas_id" id="wali_kelas_id" 
                            class="w-full h-11 px-3 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 @error('wali_kelas_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @if(isset($kelas) && $kelas->count() > 0)
                                @foreach($kelas as $kelasItem)
                                    <option value="{{ $kelasItem->id }}" {{ old('wali_kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                        {{ $kelasItem->nama_kelas }}
                                    </option>
                                @endforeach
                            @else
                                <option value="" disabled>Tidak ada kelas yang tersedia</option>
                            @endif
                        </select>
                        @error('wali_kelas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Hanya kelas yang belum memiliki wali kelas yang dapat dipilih</p>
                    </div>
                </div>

                <!-- Status Aktif -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Status Guru</label>
                    <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-green-50">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                            class="rounded border-gray-300 text-green-600 focus:ring focus:ring-green-200 focus:ring-opacity-50" 
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label for="is_active" class="ml-3 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="text-gray-700">Status Aktif</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Guru yang tidak aktif tidak dapat mengakses sistem</p>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('admin.guru.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleKelasSelectionCreate() {
    const checkbox = document.getElementById('is_wali_kelas');
    const kelasSelection = document.getElementById('kelas-selection-create');
    const kelasSelect = document.getElementById('wali_kelas_id');
    
    if (checkbox.checked) {
        kelasSelection.style.display = 'block';
        kelasSelect.required = true;
    } else {
        kelasSelection.style.display = 'none';
        kelasSelect.required = false;
        kelasSelect.value = ''; // Reset selection
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleKelasSelectionCreate();
});
</script>