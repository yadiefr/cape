@extends('layouts.admin')

@section('title', $jurusan->nama_jurusan . ' - SMK PGRI CIKAMPEK')

@section('main-content')
    <div class="container px-3 py-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Jurusan</h1>
            <div class="text-sm breadcrumbs">
                <ul class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                    <li class="flex items-center space-x-2">
                        <span class="text-gray-400">/</span>
                        <a href="{{ route('admin.jurusan.index') }}" class="hover:text-blue-600">Jurusan</a>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="text-gray-400">/</span>
                        <span>{{ $jurusan->nama_jurusan }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <div class="space-x-2">
                <a href="{{ route('admin.jurusan.edit', $jurusan->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('admin.jurusan.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Informasi Umum Jurusan -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden lg:col-span-2">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-700">Informasi Jurusan</h3>
                </div>
                <div class="p-5">
                    <div class="flex flex-col md:flex-row md:items-center mb-6">
                        @if($jurusan->logo)
                            <div class="mb-4 md:mb-0 md:mr-6">
                                <img src="{{ Storage::url($jurusan->logo) }}" alt="Logo {{ $jurusan->nama_jurusan }}" class="w-24 h-24 object-contain rounded-lg shadow-sm">
                            </div>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ $jurusan->nama_jurusan }}</h1>
                            <div class="flex flex-wrap items-center mt-2">
                                <span class="px-3 py-1 mr-2 mb-2 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    Kode: {{ $jurusan->kode_jurusan }}
                                </span>
                                @if($jurusan->is_active)
                                    <span class="px-3 py-1 mr-2 mb-2 bg-green-100 text-green-800 rounded-full text-sm">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 mr-2 mb-2 bg-red-100 text-red-800 rounded-full text-sm">
                                        Non-Aktif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h4 class="font-medium text-gray-700 mb-2">Deskripsi Jurusan</h4>
                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($jurusan->deskripsi)) !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Visi</h4>
                            <div class="prose max-w-none text-gray-600">
                                {!! nl2br(e($jurusan->visi)) !!}
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-700 mb-2">Misi</h4>
                            <div class="prose max-w-none text-gray-600">
                                {!! nl2br(e($jurusan->misi)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-700 mb-2">Prospek Karir</h4>
                        <div class="prose max-w-none text-gray-600">
                            {!! nl2br(e($jurusan->prospek_karir)) !!}
                        </div>
                    </div>

                    @if($jurusan->gambar_header)
                        <div class="mt-6">
                            <h4 class="font-medium text-gray-700 mb-2">Gambar Header</h4>
                            <div class="mt-2">
                                <img src="{{ Storage::url($jurusan->gambar_header) }}" alt="Header {{ $jurusan->nama_jurusan }}" class="w-full h-auto object-cover rounded-lg shadow-sm">
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-700">Detail Lainnya</h3>
                </div>
                <div class="p-5">                    <div class="mb-6">
                        <h4 class="font-medium text-gray-700 mb-2">Kepala Jurusan</h4>
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-3 mr-3 text-blue-600">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                @if($jurusan->kepala)
                                    <p class="text-gray-800 font-medium">{{ $jurusan->kepala->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $jurusan->kepala->email }}</p>
                                @else
                                    <p class="text-gray-500">Belum ditetapkan</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="font-medium text-gray-700 mb-2">Statistik</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-700 mb-1">Kelas</p>
                                <p class="text-2xl font-bold text-blue-900">{{ $jurusan->kelas->count() }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-700 mb-1">Siswa</p>
                                <p class="text-2xl font-bold text-green-900">{{ $jurusan->siswa->count() }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm text-purple-700 mb-1">Mata Pelajaran</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $jurusan->mata_pelajaran->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="font-medium text-gray-700 mb-2">Metadata</h4>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-sm text-gray-600">Dibuat pada:</span>
                                <span class="text-sm text-gray-900">{{ $jurusan->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600">Diperbarui pada:</span>
                                <span class="text-sm text-gray-900">{{ $jurusan->updated_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Kelas -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden lg:col-span-3">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="font-semibold text-lg text-gray-700">Daftar Kelas</h3>
                </div>
                <div class="p-5">
                    @if($jurusan->kelas->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jurusan->kelas as $kelas)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $kelas->nama_kelas }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $kelas->wali_kelas ? $kelas->wali_kelas->name : 'Belum ditetapkan' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $kelas->tahun_ajaran }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $kelas->siswa ? $kelas->siswa->count() : 0 }} siswa
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-4 text-center text-gray-500">
                            Belum ada kelas untuk jurusan ini
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
