@extends('layouts.admin')

@section('title', 'Manajemen Jurusan - SMK PGRI CIKAMPEK')

@section('main-content')

    <!-- Header -->
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Manajemen Jurusan</h1>
    <p class="text-gray-600 mb-4">Kelola data jurusan yang ada di sekolah Anda. Tambah, edit, dan hapus jurusan sesuai kebutuhan.</p>

    <!-- Tombol Tambah Jurusan -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('admin.jurusan.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Jurusan
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Jurusan -->
        <div class="bg-white/80 backdrop-blur-lg border border-blue-200 rounded-xl p-4 text-blue-600 shadow-sm hover:shadow-lg transition-all relative group overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-blue-600/10 rounded-xl transition-transform duration-500 group-hover:scale-95"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Total Jurusan</p>
                    <p class="text-2xl font-bold">{{ $jurusan->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-building text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Siswa -->
        <div class="bg-white/80 backdrop-blur-lg border border-red-200 rounded-xl p-4 text-red-600 shadow-sm hover:shadow-lg transition-all relative group overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-red-600/10 rounded-xl transition-transform duration-500 group-hover:scale-95"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Total Siswa</p>
                    <p class="text-2xl font-bold">{{ $jurusan->sum(function($j) { return $j->siswa ? $j->siswa->count() : 0; }) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="bg-white/80 backdrop-blur-lg border border-green-200 rounded-xl p-4 text-green-600 shadow-sm hover:shadow-lg transition-all relative group overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-green-500/10 to-green-600/10 rounded-xl transition-transform duration-500 group-hover:scale-95"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Total Kelas</p>
                    <p class="text-2xl font-bold">{{ $jurusan->sum(function($j) { return $j->kelas ? $j->kelas->count() : 0; }) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-door-open text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Jurusan Aktif -->
        <div class="bg-white/80 backdrop-blur-lg border border-amber-200 rounded-xl p-4 text-amber-600 shadow-sm hover:shadow-lg transition-all relative group overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-amber-600/10 rounded-xl transition-transform duration-500 group-hover:scale-95"></div>
            <div class="relative flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-75">Jurusan Aktif</p>
                    <p class="text-2xl font-bold">{{ $jurusan->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabel Jurusan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kepala Jurusan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kelas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($jurusan as $i => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ $jurusan->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->nama_jurusan }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $item->kode_jurusan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->kepala)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($item->kepala->foto)
                                            <img class="h-8 w-8 rounded-full" src="{{ asset('storage/'.$item->kepala->foto) }}" alt="">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->kepala->nama }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $item->kelas ? $item->kelas->count() : 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $item->siswa ? $item->siswa->count() : 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($item->is_active)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    <i class="fas fa-times-circle mr-1"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.jurusan.edit', $item->id) }}" 
                                   class="text-amber-600 hover:text-amber-900">
                                    <i class="fas fa-edit"></i>
                                    <span class="ml-1">Edit</span>
                                </a>
                                <form action="{{ route('admin.jurusan.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Yakin ingin menghapus jurusan ini?')">
                                        <i class="fas fa-trash"></i>
                                        <span class="ml-1">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <img src="{{ asset('images/empty.svg') }}" alt="Data Kosong" class="w-32 h-32 mb-4">
                                <h4 class="text-lg font-medium text-gray-500 mb-1">Belum Ada Data Jurusan</h4>
                                <p class="text-sm text-gray-400 mb-4">Silakan tambahkan data jurusan pertama Anda</p>
                                <a href="{{ route('admin.jurusan.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Tambah Jurusan Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $jurusan->links() }}
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush
@endsection
