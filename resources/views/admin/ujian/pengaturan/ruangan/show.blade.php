@extends('layouts.ujian')

@section('title', 'Detail Ruangan Ujian')

@section('content')
<div x-data="ruanganDetailManager()" class="min-h-screen bg-gray-50 px-2 py-3">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg px-4 py-3 mb-3 shadow-sm border border-gray-200" aria-label="Breadcrumb">
            <a href="{{ route('admin.ujian.pengaturan.ruangan.index') }}" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-door-open mr-2"></i>
                <span>Ruangan</span>
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900 font-medium">{{ $ruangan->nama_ruangan }}</span>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center mb-4 lg:mb-0">
                    <div class="p-2 bg-blue-50 rounded-lg mr-3">
                        <i class="fas fa-door-open text-lg text-blue-600"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900 mb-1">{{ $ruangan->nama_ruangan }}</h1>
                        <div class="flex flex-wrap items-center space-x-3">
                            <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm font-medium border border-gray-300">
                                {{ $ruangan->kode_ruangan }}
                            </span>
                            <span class="px-3 py-1 rounded-lg text-sm font-medium {{ $ruangan->status === 'tersedia' ? 'bg-green-100 text-green-800 border border-green-300' : ($ruangan->status === 'terpakai' ? 'bg-yellow-100 text-yellow-800 border border-yellow-300' : 'bg-red-100 text-red-800 border border-red-300') }}">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ ucfirst($ruangan->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('admin.ujian.pengaturan.ruangan.edit', $ruangan) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Ruangan
                    </a>
                    <a href="{{ route('admin.ujian.pengaturan.ruangan.manage-kelas', $ruangan) }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors text-sm">
                        <i class="fas fa-users mr-2"></i>
                        Kelola Kelas
                    </a>
                </div>
            </div>
        </div>
        <!-- Full Width Content Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-info-circle mr-3 p-2 bg-white rounded-lg text-blue-600 shadow-sm"></i>
                    Detail Ruangan
                </h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6">
                <!-- Main Content - Takes 4 columns -->
                <div class="lg:col-span-4">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Kode Ruangan:</span>
                                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-lg text-sm font-medium">
                                    {{ $ruangan->kode_ruangan }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Nama Ruangan:</span>
                                <span class="text-gray-900 font-semibold">{{ $ruangan->nama_ruangan }}</span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Kapasitas:</span>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-lg text-sm font-medium flex items-center">
                                    <i class="fas fa-users mr-2"></i>
                                    {{ $ruangan->formatted_kapasitas ?? $ruangan->kapasitas . ' orang' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Lokasi:</span>
                                <span class="text-gray-900 flex items-center font-medium">
                                    <i class="fas fa-map-marker-alt text-gray-500 mr-2"></i>
                                    {{ $ruangan->lokasi ?? 'Belum diset' }}
                                </span>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Status Ruangan:</span>
                                <span class="px-3 py-1 rounded-lg text-sm font-medium {{ $ruangan->status === 'tersedia' ? 'bg-green-100 text-green-800' : ($ruangan->status === 'terpakai' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    <i class="fas fa-circle text-xs mr-2"></i>
                                    {{ ucfirst($ruangan->status) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Status Aktif:</span>
                                <span class="{{ $ruangan->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-3 py-1 rounded-lg text-sm font-medium flex items-center">
                                    <i class="fas {{ $ruangan->is_active ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                                    {{ $ruangan->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Dibuat:</span>
                                <span class="text-gray-900 flex items-center font-medium">
                                    <i class="fas fa-calendar text-gray-500 mr-2"></i>
                                    {{ $ruangan->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200">
                                <span class="text-gray-700 font-medium">Terakhir Update:</span>
                                <span class="text-gray-900 flex items-center font-medium">
                                    <i class="fas fa-clock text-gray-500 mr-2"></i>
                                    {{ $ruangan->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($ruangan->fasilitas && count($ruangan->fasilitas) > 0)
                        <div class="pt-6 border-t border-gray-200 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-cogs text-gray-600 mr-3 p-2 bg-gray-100 rounded-lg"></i>
                                Fasilitas Tersedia
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3">
                                @foreach($ruangan->fasilitas as $fasilitas)
                                    <div class="group">
                                        <span class="inline-flex items-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-medium transition-colors w-full justify-center">
                                            <i class="fas fa-check-circle mr-2 text-green-600"></i>
                                            {{ $fasilitas }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($ruangan->deskripsi)
                        <div class="pt-6 border-t border-gray-200 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-file-alt text-gray-600 mr-3 p-2 bg-gray-100 rounded-lg"></i>
                                Deskripsi
                            </h3>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-gray-700 leading-relaxed">{{ $ruangan->deskripsi }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Button -->
                    <div class="pt-6 border-t border-gray-200">
                        <a href="{{ route('admin.ujian.pengaturan.ruangan.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
                
                <!-- Sidebar - Takes 1 column -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-4">
                        <!-- Kelas yang Menggunakan Ruangan -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                Kelas yang Menggunakan
                            </h3>
                            @if($ruangan->kelas->count() > 0)
                                <div class="space-y-2 mb-3">
                                    @foreach($ruangan->kelas as $kelas)
                                        <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-gray-200">
                                            <div>
                                                <h4 class="font-medium text-gray-900 text-sm">{{ $kelas->nama_kelas }}</h4>
                                                <p class="text-xs text-gray-600">
                                                    Max: {{ $kelas->pivot->kapasitas_maksimal ?? $ruangan->kapasitas }} siswa
                                                </p>
                                            </div>
                                            <span class="{{ $kelas->pivot->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} px-2 py-1 rounded text-xs font-medium">
                                                {{ $kelas->pivot->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <a href="{{ route('admin.ujian.pengaturan.ruangan.manage-kelas', $ruangan) }}" 
                                   class="w-full inline-flex items-center justify-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                                    <i class="fas fa-cog mr-2"></i>
                                    Kelola Kelas
                                </a>
                            @else
                                <div class="text-center py-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-users text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-600 text-sm mb-3">Belum ada kelas</p>
                                    <a href="{{ route('admin.ujian.pengaturan.ruangan.manage-kelas', $ruangan) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors text-sm">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tugaskan Kelas
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-bolt mr-2 text-green-600"></i>
                                Aksi Cepat
                            </h3>
                            <div class="space-y-2">
                                <button type="button" 
                                        @click="toggleActiveStatus({{ $ruangan->id }})"
                                        class="w-full flex items-center justify-center px-3 py-2 border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg transition-colors text-sm">
                                    <i class="fas fa-power-off mr-2"></i>
                                    {{ $ruangan->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                                
                                <div class="grid grid-cols-1 gap-2">
                                    <button type="button" 
                                            @click="changeStatus('tersedia')"
                                            class="flex items-center justify-center px-3 py-2 border border-green-300 text-green-700 hover:bg-green-50 font-medium rounded-lg transition-colors text-sm">
                                        <i class="fas fa-check mr-2"></i>
                                        Tersedia
                                    </button>
                                    <button type="button" 
                                            @click="changeStatus('terpakai')"
                                            class="flex items-center justify-center px-3 py-2 border border-yellow-300 text-yellow-700 hover:bg-yellow-50 font-medium rounded-lg transition-colors text-sm">
                                        <i class="fas fa-clock mr-2"></i>
                                        Terpakai
                                    </button>
                                    <button type="button" 
                                            @click="changeStatus('maintenance')"
                                            class="flex items-center justify-center px-3 py-2 border border-red-300 text-red-700 hover:bg-red-50 font-medium rounded-lg transition-colors text-sm">
                                        <i class="fas fa-tools mr-2"></i>
                                        Maintenance
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Success/Error Notifications -->
        <div x-show="showNotification" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-2"
             class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <div :class="notificationType === 'success' ? 'bg-green-500' : 'bg-red-500'" 
                 class="rounded-lg px-4 py-3 text-white shadow-lg">
                <div class="flex items-center">
                    <i :class="notificationType === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'" 
                       class="mr-2"></i>
                    <span x-text="notificationMessage" class="font-medium text-sm"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('ruanganDetailManager', () => ({
        showNotification: false,
        notificationMessage: '',
        notificationType: 'success',

        showMessage(message, type = 'success') {
            this.notificationMessage = message;
            this.notificationType = type;
            this.showNotification = true;
            
            setTimeout(() => {
                this.showNotification = false;
            }, 4000);
        },

        async toggleActiveStatus(roomId) {
            try {
                const response = await fetch(`/admin/ujian/pengaturan/ruangan/${roomId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showMessage(data.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showMessage('Gagal mengubah status ruangan', 'error');
                }
            } catch (error) {
                this.showMessage('Terjadi kesalahan sistem', 'error');
                console.error('Error:', error);
            }
        },

        changeStatus(status) {
            const statusLabels = {
                'tersedia': 'Tersedia',
                'terpakai': 'Terpakai', 
                'maintenance': 'Maintenance'
            };
            
            if (confirm(`Apakah Anda yakin ingin mengubah status ruangan menjadi "${statusLabels[status]}"?`)) {
                // Implement this functionality if needed in the future
                this.showMessage('Fitur ini akan segera tersedia', 'success');
            }
        }
    }));
});
</script>
@endpush
