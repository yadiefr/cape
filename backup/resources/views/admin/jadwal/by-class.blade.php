@extends('layouts.admin')
@section('title', 'Jadwal Per Kelas')

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Per Kelas</h1>
            <p class="text-gray-600 mt-1">Lihat jadwal pelajaran berdasarkan kelas</p>
        </div>
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Class Selection -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('admin.jadwal.by-class') }}" method="GET" class="max-w-xl">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Kelas
                    </label>
                    <select name="kelas" id="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $selectedKelas && $selectedKelas->id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                    <i class="fas fa-search mr-2"></i>
                    Tampilkan Jadwal
                </button>
            </div>
        </form>
    </div>

    @if($selectedKelas)
        <!-- Schedule Display -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-bold text-gray-800">
                    Jadwal Kelas {{ $selectedKelas->nama_kelas }}
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Hari
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jam
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Pelajaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Guru
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jadwal as $j)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $j->hari }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500">Jam ke-{{ $j->jam_ke }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $j->mapel->nama }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $j->guru->nama }}</div>
                                    @if($j->guru->nip)
                                        <div class="text-xs text-gray-500">NIP: {{ $j->guru->nip }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.jadwal.show', $j->id) }}" class="text-blue-600 hover:text-blue-900" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-calendar-times text-gray-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada jadwal</h3>
                                        <p class="text-gray-500 mb-4">Belum ada jadwal pelajaran untuk kelas ini</p>
                                        <a href="{{ route('admin.jadwal.create', ['kelas_id' => $selectedKelas->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus mr-2"></i>
                                            Tambah Jadwal
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- No Class Selected State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-calendar-alt text-blue-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Kelas</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    Silakan pilih kelas untuk melihat jadwal pelajaran
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
