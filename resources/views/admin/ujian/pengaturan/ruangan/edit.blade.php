@extends('layouts.ujian')

@section('title', 'Edit Ruangan Ujian')

@section('content')
<div class="min-h-screen bg-gray-50 px-2 py-3">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg px-4 py-3 mb-3 shadow-sm border border-gray-200" aria-label="Breadcrumb">
            <a href="{{ route('admin.ujian.pengaturan.ruangan.index') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-door-open mr-2"></i>
                <span>Ruangan</span>
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <a href="{{ route('admin.ujian.pengaturan.ruangan.show', $ruangan) }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                <span>{{ $ruangan->nama_ruangan }}</span>
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900 font-medium">Edit</span>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg mr-3">
                    <i class="fas fa-edit text-lg text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Edit Ruangan: {{ $ruangan->nama_ruangan }}</h1>
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Perbarui informasi ruangan ujian
                    </p>
                </div>
            </div>
        </div>

        <!-- Full Width Form Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-door-open mr-3 p-2 bg-white rounded-lg text-blue-600 shadow-sm"></i>
                    Informasi Ruangan
                </h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6">
                <!-- Main Form - Takes 4 columns -->
                <div class="lg:col-span-4">
                    <form action="{{ route('admin.ujian.pengaturan.ruangan.update', $ruangan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Ruangan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kode_ruangan') border-red-500 @enderror" 
                                       id="kode_ruangan" 
                                       name="kode_ruangan" 
                                       value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}"
                                       placeholder="R001"
                                       required>
                                @error('kode_ruangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Ruangan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nama_ruangan') border-red-500 @enderror" 
                                       id="nama_ruangan" 
                                       name="nama_ruangan" 
                                       value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}"
                                       placeholder="Ruang Kelas 1A"
                                       required>
                                @error('nama_ruangan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kapasitas <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kapasitas') border-red-500 @enderror" 
                                       id="kapasitas" 
                                       name="kapasitas" 
                                       value="{{ old('kapasitas', $ruangan->kapasitas) }}"
                                       min="1" 
                                       max="1000"
                                       placeholder="30"
                                       required>
                                @error('kapasitas')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('status') border-red-500 @enderror" 
                                        id="status" 
                                        name="status"
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia" {{ old('status', $ruangan->status) == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="terpakai" {{ old('status', $ruangan->status) == 'terpakai' ? 'selected' : '' }}>
                                        Terpakai
                                    </option>
                                    <option value="maintenance" {{ old('status', $ruangan->status) == 'maintenance' ? 'selected' : '' }}>
                                        Maintenance
                                    </option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i>Lokasi
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('lokasi') border-red-500 @enderror" 
                                       id="lokasi" 
                                       name="lokasi" 
                                       value="{{ old('lokasi', $ruangan->lokasi) }}"
                                       placeholder="Lantai 2, Gedung A">
                                @error('lokasi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-gray-500 mr-1"></i>Deskripsi
                            </label>
                            <input type="text" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('deskripsi') border-red-500 @enderror" 
                                   id="deskripsi" 
                                   name="deskripsi" 
                                   value="{{ old('deskripsi', $ruangan->deskripsi) }}"
                                   placeholder="Deskripsi ruangan...">
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-6">
                            <label for="fasilitas" class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-cogs text-gray-500 mr-1"></i>Fasilitas Ruangan
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="fasilitasContainer">
                                @php
                                    $fasilitas = old('fasilitas', $ruangan->fasilitas ?? []);
                                    if (empty($fasilitas)) $fasilitas = [''];
                                @endphp
                                @foreach($fasilitas as $index => $fas)
                                    <div class="fasilitas-item">
                                        <div class="flex">
                                            <input type="text" 
                                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   name="fasilitas[]" 
                                                   value="{{ $fas }}"
                                                   placeholder="Proyektor, AC, Wifi, Papan Tulis">
                                            <button type="button" class="px-4 py-3 bg-red-500 text-white rounded-r-lg hover:bg-red-600 transition-colors remove-fasilitas">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors" id="addFasilitas">
                                <i class="fas fa-plus mr-2"></i>Tambah Fasilitas
                            </button>
                        </div>

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-8">
                            <a href="{{ route('admin.ujian.pengaturan.ruangan.show', $ruangan) }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Update Ruangan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Sidebar - Takes 1 column -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 space-y-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <h3 class="text-sm font-semibold text-blue-900 mb-3">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Status Saat Ini
                            </h3>
                            <div class="grid grid-cols-2 gap-3 text-center mb-4">
                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                    <i class="fas fa-users text-lg text-blue-600 mb-1"></i>
                                    <div class="text-lg font-bold text-gray-900">{{ $ruangan->kapasitas }}</div>
                                    <div class="text-xs text-gray-600">Kapasitas</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-green-100">
                                    <i class="fas fa-graduation-cap text-lg text-green-600 mb-1"></i>
                                    <div class="text-lg font-bold text-gray-900">{{ $ruangan->kelas()->count() }}</div>
                                    <div class="text-xs text-gray-600">Kelas</div>
                                </div>
                            </div>
                            <div class="text-center">
                                {!! $ruangan->status_badge !!}
                            </div>
                        </div>
                        
                        <!-- Quick Info -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                            <h3 class="text-sm font-semibold text-green-900 mb-3">
                                <i class="fas fa-chart-line mr-2 text-green-500"></i>
                                Statistik
                            </h3>
                            <div class="bg-white rounded-lg p-3 border border-green-100">
                                <div class="space-y-2 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Dibuat:</span>
                                        <span class="font-medium">{{ $ruangan->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Diupdate:</span>
                                        <span class="font-medium">{{ $ruangan->updated_at->format('d/m/Y') }}</span>
                                    </div>
                                    @if($ruangan->fasilitas)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Fasilitas:</span>
                                            <span class="font-medium">{{ count($ruangan->fasilitas) }} item</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Status Saat Ini
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-3 text-center mb-4">
                            <div class="bg-blue-50 rounded-lg p-3">
                                <i class="fas fa-users text-lg text-blue-600 mb-1"></i>
                                <div class="text-lg font-bold text-gray-900">{{ $ruangan->kapasitas }}</div>
                                <div class="text-xs text-gray-600">Kapasitas</div>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3">
                                <i class="fas fa-graduation-cap text-lg text-green-600 mb-1"></i>
                                <div class="text-lg font-bold text-gray-900">{{ $ruangan->kelas()->count() }}</div>
                                <div class="text-xs text-gray-600">Kelas</div>
                            </div>
                        </div>
                        <div class="text-center">
                            {!! $ruangan->status_badge !!}
                        </div>
                    </div>
                </div>
            </div>

                <!-- Information Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-gray-500"></i>
                        Informasi Ruangan
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Dibuat:</span>
                            <span class="text-gray-600">{{ $ruangan->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Diupdate:</span>
                            <span class="text-gray-600">{{ $ruangan->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-700">Status Aktif:</span>
                            <span>
                                @if($ruangan->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="text-sm font-medium text-yellow-800 mb-2">
                            <i class="fas fa-lightbulb mr-1"></i>Tips Edit:
                        </h4>
                        <ul class="text-xs text-yellow-700 space-y-1">
                            <li>• Perhatikan kelas yang sudah terdaftar</li>
                            <li>• Pastikan kapasitas tidak lebih kecil dari jumlah siswa</li>
                            <li>• Update fasilitas sesuai kondisi terkini</li>
                            <li>• Status berpengaruh pada ketersediaan ruangan</li>
                        </ul>
                    </div>

                    @if($ruangan->kelas->count() > 0)
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">
                                <i class="fas fa-users mr-1"></i>Kelas Terdaftar:
                            </h4>
                            <ul class="text-xs text-blue-700 space-y-1">
                                @foreach($ruangan->kelas->take(3) as $kelas)
                                    <li>• {{ $kelas->nama_kelas }}</li>
                                @endforeach
                                @if($ruangan->kelas->count() > 3)
                                    <li class="italic">dan {{ $ruangan->kelas->count() - 3 }} kelas lainnya</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add fasilitas functionality
    document.getElementById('addFasilitas').addEventListener('click', function() {
        const container = document.getElementById('fasilitasContainer');
        const newFasilitas = document.createElement('div');
        newFasilitas.className = 'fasilitas-item';
        newFasilitas.innerHTML = `
            <div class="flex">
                <input type="text" 
                       class="flex-1 px-4 py-3 rounded-l-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors" 
                       name="fasilitas[]" 
                       placeholder="Contoh: Proyektor, AC, Wifi">
                <button type="button" class="px-4 py-3 bg-red-500 text-white rounded-r-lg hover:bg-red-600 transition-colors remove-fasilitas">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newFasilitas);
    });

    // Remove fasilitas functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-fasilitas')) {
            const fasilitasItems = document.querySelectorAll('.fasilitas-item');
            if (fasilitasItems.length > 1) {
                e.target.closest('.fasilitas-item').remove();
            } else {
                alert('Minimal harus ada satu fasilitas');
            }
        }
    });

    // Form validation preview
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('border-red-500');
                this.classList.add('border-green-500');
            } else {
                this.classList.remove('border-green-500');
            }
        });
    });
});
</script>
@endpush
