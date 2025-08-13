@extends('layouts.siswa')

@section('title', 'Detail Nilai - SMK PGRI CIKAMPEK')

@section('content')
<div class="w-full px-6 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Nilai</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('siswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('siswa.nilai.index') }}" class="hover:text-blue-600">Nilai</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>{{ $nilai->mataPelajaran->nama }}</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Informasi Mata Pelajaran -->
            <div>
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Informasi Mata Pelajaran</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Mata Pelajaran</p>
                        <p class="font-medium text-gray-800">{{ $nilai->mataPelajaran->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Guru Pengajar</p>
                        <p class="font-medium text-gray-800">{{ $nilai->guru->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Semester</p>
                        <p class="font-medium text-gray-800">Semester {{ $nilai->semester }}</p>
                    </div>
                </div>
            </div>

            <!-- Detail Nilai -->
            <div>
                <h3 class="font-semibold text-lg text-gray-800 mb-4">Detail Nilai</h3>
                <div class="space-y-3">
                    @if($nilai->jenis_nilai)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Nilai</p>
                        <p class="font-medium text-gray-800">{{ $nilai->jenis_nilai }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nilai</p>
                        <div class="flex items-center space-x-4">
                            <span class="text-2xl font-bold text-{{ $nilai->nilai_akhir >= $nilai->mataPelajaran->kkm ? 'green' : 'red' }}-500">
                                {{ $nilai->nilai_akhir ?? $nilai->nilai }}
                            </span>
                            @if($nilai->mataPelajaran->kkm)
                            <span class="text-sm text-gray-500">
                                (KKM: {{ $nilai->mataPelajaran->kkm }})
                            </span>
                            @endif
                        </div>
                    </div>
                    @if($nilai->catatan)
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Keterangan</p>
                        <p class="text-gray-800">{{ $nilai->catatan }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-end">
            <a href="{{ route('siswa.nilai.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
