@extends('layouts.admin')
@section('title', 'Jadwal Per Guru')

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Per Guru</h1>
            <p class="text-gray-600 mt-1">Lihat jadwal mengajar berdasarkan guru</p>
        </div>
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Teacher Selection -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('admin.jadwal.by-teacher') }}" method="GET" class="max-w-xl">
            <div class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="guru_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Guru
                    </label>
                    <select name="guru" id="guru_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->id }}" {{ $selectedGuru && $selectedGuru->id == $g->id ? 'selected' : '' }}>
                                {{ $g->nama }} {{ $g->nip ? '(' . $g->nip . ')' : '' }}
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

    @if($selectedGuru)
        <!-- Schedule Display -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Jadwal Mengajar {{ $selectedGuru->nama }}
                        </h2>
                        @if($selectedGuru->nip)
                            <p class="text-sm text-gray-600 mt-1">NIP: {{ $selectedGuru->nip }}</p>
                        @endif
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            Total: {{ $jadwal->count() }} Jadwal
                        </span>
                    </div>
                </div>
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
                                Kelas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mata Pelajaran
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
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
                                        {{ $j->kelas->nama_kelas }}
                                    </div>
                                    @if($j->kelas->jurusan)
                                        <div class="text-xs text-gray-500">{{ $j->kelas->jurusan->nama_jurusan }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $j->mapel->nama }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($j->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Nonaktif
                                        </span>
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
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <div class="h-12 w-12 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-calendar-times text-gray-400 text-xl"></i>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Belum ada jadwal</h3>
                                        <p class="text-gray-500 mb-4">Belum ada jadwal mengajar untuk guru ini</p>
                                        <a href="{{ route('admin.jadwal.create', ['guru_id' => $selectedGuru->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
        <!-- No Teacher Selected State -->
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="h-16 w-16 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-chalkboard-teacher text-purple-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pilih Guru</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    Silakan pilih guru untuk melihat jadwal mengajar
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
