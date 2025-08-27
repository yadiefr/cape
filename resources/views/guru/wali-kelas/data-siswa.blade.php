@extends('layouts.guru')

@section('title', 'Data Siswa Kelas - ' . $kelas->nama_kelas)

@section('content')
<style>
@media print {
    .no-print { display: none !important; }
    .print-break { page-break-after: always; }
    body { font-size: 12px; }
    .bg-gray-100 { background: white !important; }
    .shadow-sm { box-shadow: none !important; }
    .border { border: 1px solid #000 !important; }
}

/* Mobile table responsiveness */
@media (max-width: 768px) {
    .mobile-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .mobile-table {
        min-width: 640px;
    }
    
    .mobile-compact td {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
    }
}

/* Smooth transitions */
.transition-colors {
    transition: background-color 0.15s ease-in-out, color 0.15s ease-in-out;
}
</style>

<div class="min-h-screen bg-white-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Data Siswa Kelas</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">
                        {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan ?? 'Jurusan tidak diketahui' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="w-full px-4 sm:px-6 lg:px-8 py-6">
        <!-- Statistik Kelas -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl lg:text-2xl font-bold text-gray-900">{{ $totalSiswa }}</div>
                        <div class="text-sm text-gray-500">Total Siswa</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-check text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl lg:text-2xl font-bold text-gray-900">{{ $siswaAktif }}</div>
                        <div class="text-sm text-gray-500">Siswa Aktif</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-mars text-2xl text-cyan-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl lg:text-2xl font-bold text-gray-900">{{ $siswaLaki }}</div>
                        <div class="text-sm text-gray-500">Laki-laki</div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-venus text-2xl text-pink-600"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl lg:text-2xl font-bold text-gray-900">{{ $siswaPerempuan }}</div>
                        <div class="text-sm text-gray-500">Perempuan</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="bg-white rounded-lg shadow-sm mb-6 no-print">
            <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                <h6 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h6>
            </div>
            <div class="p-4 sm:p-6">
                <form method="GET" action="{{ route('guru.wali-kelas.siswa.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $search }}" 
                                   placeholder="Nama, NISN, atau NIS"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="tidak_aktif" {{ $status == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="pindah" {{ $status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                <option value="lulus" {{ $status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            </select>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua</option>
                                <option value="L" {{ $jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Tombol -->
                        <div class="flex items-end space-x-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-search mr-2"></i> Cari
                            </button>
                            <a href="{{ route('guru.wali-kelas.siswa.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Siswa -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <h6 class="text-lg font-semibold text-gray-900">Daftar Siswa</h6>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="text-sm text-gray-500">
                            Total: {{ $siswaList->total() }} siswa
                        </div>
                        <button onclick="window.print()" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors no-print">
                            <i class="fas fa-print mr-2"></i>
                            <span class="hidden sm:inline">Print</span>
                        </button>
                        <!-- Form Pilih KM & Bendahara -->
                        <form method="POST" action="{{ route('guru.wali-kelas.siswa.set-km-bendahara') }}" class="flex flex-wrap items-center gap-2 no-print">
                            @csrf
                            <div class="flex items-center">
                                <label for="km_id" class="text-xs sm:text-sm mr-1">KM:</label>
                                <select name="km_id" id="km_id" class="text-xs sm:text-sm border rounded px-2 py-1">
                                    <option value="">-Pilih KM-</option>
                                    @foreach($siswaList as $s)
                                        <option value="{{ $s->id }}" {{ $s->is_ketua_kelas ? 'selected' : '' }}>{{ $s->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center">
                                <label for="bendahara_id" class="text-xs sm:text-sm mr-1">Bendahara:</label>
                                <select name="bendahara_id" id="bendahara_id" class="text-xs sm:text-sm border rounded px-2 py-1">
                                    <option value="">-Pilih Bendahara-</option>
                                    @foreach($siswaList as $s)
                                        <option value="{{ $s->id }}" {{ $s->is_bendahara ? 'selected' : '' }}>{{ $s->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-600 text-white text-xs sm:text-sm px-2 py-1 rounded hover:bg-blue-700 transition-colors">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto mobile-scroll">
                <table class="min-w-full divide-y divide-gray-200 mobile-table">
                    <thead class="bg-gray-50">
                        <tr class="mobile-compact">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">No</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Foto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama & Info</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">NISN/NIS</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">Tanggal Lahir</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">No. Telepon</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-36">Absensi Hari Ini</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 mobile-compact">
                        @forelse($siswaList as $index => $siswa)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                {{ $siswaList->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <x-student-avatar :student="$siswa" size="md" />
                            </td>
                            <td class="px-4 py-3">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $siswa->nama_lengkap }}
                                        @if($siswa->is_ketua_kelas)
                                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-user-check mr-1"></i>KM
                                            </span>
                                        @endif
                                        @if($siswa->is_bendahara)
                                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-money-bill-wave mr-1"></i>Bendahara
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        @if($siswa->tempat_lahir && $siswa->tanggal_lahir)
                                            • {{ $siswa->tempat_lahir }}, {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($siswa->nisn || $siswa->nis)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $siswa->nisn ?? $siswa->nis }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($siswa->tanggal_lahir)
                                    <span class="text-xs text-gray-600">
                                        <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($siswa->no_telepon)
                                    <span class="text-xs text-gray-600">
                                        <i class="fas fa-phone-alt mr-1"></i>{{ $siswa->no_telepon }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $siswa->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($siswa->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                @if(isset($absensiToday[$siswa->id]))
                                    @php
                                        $absensi = $absensiToday[$siswa->id];
                                        $statusColors = [
                                            'hadir' => 'bg-green-100 text-green-800',
                                            'sakit' => 'bg-yellow-100 text-yellow-800',
                                            'izin' => 'bg-blue-100 text-blue-800',
                                            'alpha' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusIcons = [
                                            'hadir' => 'fas fa-check',
                                            'sakit' => 'fas fa-thermometer-half',
                                            'izin' => 'fas fa-file-alt',
                                            'alpha' => 'fas fa-times'
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$absensi->status] }}">
                                        <i class="{{ $statusIcons[$absensi->status] }} mr-1"></i>
                                        {{ ucfirst($absensi->status) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-500">
                                        <i class="fas fa-question mr-1"></i>
                                        Belum
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('guru.wali-kelas.siswa.detail', $siswa->id) }}" 
                                   class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-full hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium mb-1">Tidak ada data siswa</p>
                                    <p class="text-gray-400 text-sm">Coba ubah filter atau kata kunci pencarian</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($siswaList->hasPages())
            <div class="px-4 py-4 border-t border-gray-200 no-print">
                {{ $siswaList->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
