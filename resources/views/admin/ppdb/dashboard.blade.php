@extends('layouts.admin')

@section('title', 'Dashboard PPDB - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Dashboard PPDB</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>PPDB</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pendaftar -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500">Total Pendaftar</h2>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPendaftar }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">Tahun Ajaran {{ $tahun }}</p>
            </div>

            <!-- Menunggu Verifikasi -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500">Menunggu Verifikasi</h2>
                        <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $statusPendaftar['menunggu'] }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="{{ route('admin.ppdb.index', ['status' => 'menunggu']) }}" class="text-blue-600 hover:underline">Lihat detail</a>
                </p>
            </div>

            <!-- Diterima -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500">Diterima</h2>
                        <p class="text-3xl font-bold text-green-500 mt-1">{{ $statusPendaftar['diterima'] }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="{{ route('admin.ppdb.index', ['status' => 'diterima']) }}" class="text-blue-600 hover:underline">Lihat detail</a>
                </p>
            </div>

            <!-- Ditolak -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-sm font-medium text-gray-500">Ditolak</h2>
                        <p class="text-3xl font-bold text-red-500 mt-1">{{ $statusPendaftar['ditolak'] }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    <a href="{{ route('admin.ppdb.index', ['status' => 'ditolak']) }}" class="text-blue-600 hover:underline">Lihat detail</a>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pendaftar per Jurusan -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-lg text-gray-700">Pendaftar per Jurusan</h2>
            </div>
            <div class="p-5">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jurusan
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Jumlah Pendaftar
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pendaftarJurusan as $jurusan)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $jurusan['nama'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $jurusan['total'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <a href="{{ route('admin.ppdb.index', ['jurusan' => array_search($jurusan, $pendaftarJurusan)]) }}" class="text-blue-600 hover:underline">Lihat Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pendaftar Terbaru -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-lg text-gray-700">Pendaftar Terbaru</h2>
            </div>
            <div class="p-5">
                <div class="space-y-4">
                    @foreach($pendaftarTerbaru as $pendaftar)
                    <div class="border-b border-gray-100 pb-4 last:border-b-0 last:pb-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $pendaftar->nama_lengkap }}</h3>
                                <p class="text-xs text-gray-500">{{ $pendaftar->nomor_pendaftaran }}</p>
                                @if($pendaftar->created_at)
                                    <p class="text-xs text-gray-500 mt-1">{{ $pendaftar->created_at->diffForHumans() }}</p>
                                @endif
                            </div>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $pendaftar->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                                ($pendaftar->status == 'diterima' ? 'bg-green-100 text-green-800' : 
                                ($pendaftar->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($pendaftar->status) }}
                            </span>
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('admin.ppdb.show', $pendaftar->id) }}" class="text-sm text-blue-600 hover:underline">Lihat Detail</a>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-4 text-center">
                    <a href="{{ route('admin.ppdb.index') }}" class="text-sm text-blue-600 hover:underline">Lihat Semua Pendaftar</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
