@extends('layouts.guru')

@section('title', 'Absensi Kelas ' . $kelas->nama_kelas . ' - SMK PGRI CIKAMPEK')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Absensi {{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $kelas->jurusan->nama_jurusan }}</p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="{{ route('guru.absensi.create', ['kelas_id' => $kelas->id]) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Input Absensi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $siswa->count() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $mapelDiajar->count() }}</p>
                </div>
                <div class="p-3 bg-green-50 rounded-full">
                    <i class="fas fa-book text-green-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List with Attendance Records -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Rekap Kehadiran Siswa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa as $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <x-student-avatar :student="$s" size="md" />
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $s->nisn }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                {{ $rekapAbsensi[$s->id]['hadir'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                {{ $rekapAbsensi[$s->id]['izin'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                {{ $rekapAbsensi[$s->id]['sakit'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                {{ $rekapAbsensi[$s->id]['alpha'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('guru.absensi.show', $s->id) }}" 
                               class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
