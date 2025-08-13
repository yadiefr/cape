@extends('layouts.guru')

@section('title', 'Detail Siswa: ' . $siswa->nama_lengkap)

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div x-data="siswaDetailManager()" class="min-h-screen bg-gray-50">

    <!-- Student Profile Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <div class="mx-auto h-32 w-32 rounded-full overflow-hidden bg-gray-100 border-4 border-gray-200">
                        @if($siswa->foto && Storage::disk('public')->exists($siswa->foto))
                            <img src="{{ asset('storage/' . $siswa->foto) }}" 
                                 alt="Foto {{ $siswa->nama_lengkap }}" 
                                 class="h-full w-full object-cover">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($siswa->nama_lengkap) }}&background=3b82f6&color=ffffff&size=128" 
                                 alt="Foto {{ $siswa->nama_lengkap }}" 
                                 class="h-full w-full object-cover">
                        @endif
                    </div>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $siswa->nama_lengkap }}</h3>
                    <p class="text-gray-500">{{ $siswa->kelas->nama_kelas }}</p>
                </div>
                
                <div class="mt-6 border-t border-gray-200 pt-6">
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NISN</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->nisn ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">NIS</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $siswa->nis ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Jenis Kelamin</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $siswa->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                    {{ $siswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $siswa->tempat_lahir ?? '-' }}{{ $siswa->tanggal_lahir ? ', ' . \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') : '' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                @if($siswa->status == 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($siswa->status) }}
                                    </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-8">
            <!-- Subject Cards -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Mata Pelajaran yang Anda Ampu
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($jadwalMapel as $jadwal)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-medium text-gray-900">{{ $jadwal->mapel->nama }}</h4>
                                    <div class="text-right">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded block">
                                            {{ $jadwal->hari }} {{ $jadwal->jam_gabungan ?? $jadwal->jam_mulai . '-' . $jadwal->jam_selesai }}
                                        </span>
                                        @if(isset($jadwal->total_jam) && $jadwal->total_jam > 1)
                                            <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded mt-1 block">
                                                {{ $jadwal->total_jam }} jam pelajaran
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Attendance Summary -->
                                @if(isset($rekapAbsensi[$jadwal->mapel->nama]))
                                    <div class="flex space-x-2 mb-3">
                                        @php $absensi = $rekapAbsensi[$jadwal->mapel->nama]; @endphp
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">
                                            H: {{ $absensi['hadir'] }}
                                        </span>
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs">
                                            I: {{ $absensi['izin'] }}
                                        </span>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                            S: {{ $absensi['sakit'] }}
                                        </span>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">
                                            A: {{ $absensi['alpha'] }}
                                        </span>
                                    </div>
                                @endif

                                <!-- Latest Grades -->
                                @if(isset($nilaiMapel[$jadwal->mapel->nama]))
                                    <div class="text-sm">
                                        <p class="text-gray-600 mb-2">Nilai Terbaru:</p>
                                        @foreach($nilaiMapel[$jadwal->mapel->nama]->take(3) as $nilai)
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="text-gray-700">{{ $nilai->jenis_nilai ?? $nilai->keterangan ?? 'Nilai' }}</span>
                                                <span class="font-medium {{ $nilai->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $nilai->nilai }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 text-sm">Belum ada nilai</p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-gray-500">Tidak ada mata pelajaran yang Anda ampu untuk siswa ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Attendance -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Riwayat Absensi Terbaru
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentAbsensi as $absensi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $absensi->mapel->nama }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($absensi->status == 'hadir')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Hadir
                                            </span>
                                        @elseif($absensi->status == 'izin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Izin
                                            </span>
                                        @elseif($absensi->status == 'sakit')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Sakit
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Alpha
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $absensi->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                            </svg>
                                            <p class="text-gray-500">Belum ada riwayat absensi</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function siswaDetailManager() {
    return {
        // Add any JavaScript functionality for the detail page here
        init() {
            console.log('Student detail page initialized');
        }
    }
}
</script>
@endpush
