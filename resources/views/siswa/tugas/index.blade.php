@extends('layouts.siswa')

@section('title', 'Daftar Tugas - SMK PGRI CIKAMPEK')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Daftar Tugas</h1>
        <nav class="text-sm" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center text-gray-500">
                    <a href="{{ route('siswa.dashboard') }}" class="text-gray-500 hover:text-blue-600">
                        Dashboard
                    </a>
                    <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center text-gray-500">
                    <span class="text-gray-500">Tugas</span>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Task List -->
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header with statistics -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Tugas Belum Dikerjakan</div>
                    <div class="text-2xl font-semibold text-blue-600">
                        {{ $tugas->where('pengumpulanTugas', '[]')->count() }}
                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Tugas Selesai</div>
                    <div class="text-2xl font-semibold text-green-600">
                        {{ $tugas->where('pengumpulanTugas', '!=', '[]')->count() }}
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Total Tugas</div>
                    <div class="text-2xl font-semibold text-yellow-600">
                        {{ $tugas->count() }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tugas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Guru
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deadline
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
                    @forelse($tugas as $t)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $t->mapel ? $t->mapel->nama : '-' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $t->judul }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ Str::limit($t->deskripsi, 50) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $t->guru->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $t->deadline->format('d M Y H:i') }}
                                </div>
                                @if($t->deadline < now())
                                    <span class="text-xs text-red-600">Telah berakhir</span>
                                @else
                                    <span class="text-xs text-gray-500">
                                        {{ $t->deadline->diffForHumans() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $submission = $t->pengumpulanTugas->first();
                                @endphp
                                @if(!$submission)
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                        Belum Dikerjakan
                                    </span>
                                @elseif($submission->status == 'submitted')
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        Sudah Dikumpulkan
                                    </span>
                                @elseif($submission->status == 'graded')
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        Sudah Dinilai: {{ $submission->nilai }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('siswa.tugas.show', $t->id) }}" 
                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <img src="{{ asset('images/no-tasks.svg') }}" alt="Tidak ada tugas" class="w-32 h-32 mx-auto mb-4">
                                <p>Belum ada tugas yang perlu dikerjakan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $tugas->links() }}
        </div>
    </div>
</div>
@endsection
