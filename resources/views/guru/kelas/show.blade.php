@extends('layouts.guru')

@section('title', 'Detail Kelas ' . $kelas->nama_kelas . ' - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    /* Mobile-first responsive design */
    .container-responsive {
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .container-responsive {
            padding: 1.5rem 2rem;
        }
    }

    /* Mobile responsive table */
    @media (max-width: 767px) {
        .desktop-table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        /* Mobile header adjustments */
        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header h2 {
            font-size: 1.5rem;
            line-height: 1.3;
        }

        .mobile-action-btn {
            width: 100%;
            justify-content: center;
        }

        /* Mobile stats grid */
        .mobile-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .mobile-stats .stat-card {
            padding: 0.75rem;
        }

        .mobile-stats .stat-card .text-2xl {
            font-size: 1.25rem;
        }
    }

    @media (min-width: 768px) {
        .desktop-table {
            display: block;
        }

        .mobile-cards {
            display: none;
        }
    }

    /* Mobile student card styling */
    .mobile-student-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .mobile-student-card:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-color: #d1d5db;
    }

    .student-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .student-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .student-info h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.125rem;
        line-height: 1.2;
    }

    .student-info p {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    .student-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
        flex-shrink: 0;
    }

    .student-number {
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
        background-color: #f3f4f6;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
    }

    .attendance-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .attendance-badge {
        font-size: 0.75rem;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-weight: 500;
    }

    /* Mobile schedule styling */
    .mobile-schedule-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .schedule-day-header {
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f3f4f6;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .schedule-item {
        display: flex;
        align-items: center;
        padding: 0.5rem;
        background-color: #f9fafb;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .schedule-time {
        width: 3rem;
        height: 3rem;
        background-color: #dbeafe;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .schedule-time span {
        font-size: 0.75rem;
        font-weight: 600;
        color: #1e40af;
    }

    .schedule-info h4 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.125rem;
    }

    .schedule-info p {
        font-size: 0.75rem;
        color: #6b7280;
    }
</style>
@endpush

@section('content')
<div class="container-responsive">
    <div class="mb-4 md:mb-6">
        <div class="flex mobile-header sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-gray-900">{{ $kelas->nama_kelas }}</h2>
                <p class="mt-1 text-sm text-gray-500">{{ $kelas->jurusan->nama_jurusan }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid mobile-stats sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
        <div class="stat-card bg-white rounded-lg shadow p-3 md:p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="mt-1 text-xl md:text-2xl font-semibold text-gray-900">{{ $siswa->count() }}</p>
                </div>
                <div class="p-2 md:p-3 bg-blue-50 rounded-full">
                    <i class="fas fa-users text-blue-500 text-sm md:text-base"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-3 md:p-4 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-600">Laki-laki</p>
                    <p class="mt-1 text-xl md:text-2xl font-semibold text-gray-900">{{ $siswa->where('jenis_kelamin', 'L')->count() }}</p>
                </div>
                <div class="p-2 md:p-3 bg-green-50 rounded-full">
                    <i class="fas fa-male text-green-500 text-sm md:text-base"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-3 md:p-4 border-l-4 border-pink-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-600">Perempuan</p>
                    <p class="mt-1 text-xl md:text-2xl font-semibold text-gray-900">{{ $siswa->where('jenis_kelamin', 'P')->count() }}</p>
                </div>
                <div class="p-2 md:p-3 bg-pink-50 rounded-full">
                    <i class="fas fa-female text-pink-500 text-sm md:text-base"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-lg shadow p-3 md:p-4 border-l-4 border-purple-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs md:text-sm font-medium text-gray-600">Aktif</p>
                    <p class="mt-1 text-xl md:text-2xl font-semibold text-gray-900">{{ $siswa->where('status', 'aktif')->count() }}</p>
                </div>
                <div class="p-2 md:p-3 bg-purple-50 rounded-full">
                    <i class="fas fa-check-circle text-purple-500 text-sm md:text-base"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-3 md:p-6 border-b border-gray-200">
            <h3 class="text-base md:text-lg leading-6 font-medium text-gray-900">Daftar Siswa</h3>
        </div>

        <!-- Desktop Table -->
        <div class="desktop-table overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($siswa as $index => $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama_lengkap) }}&background=3b82f6&color=ffffff"
                                         alt="Foto {{ $s->nama_lengkap }}"
                                         class="h-full w-full object-cover">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $s->nis ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2 text-sm">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded">
                                    H: {{ $rekapAbsensi[$s->id]['hadir'] ?? 0 }}
                                </span>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded">
                                    I: {{ $rekapAbsensi[$s->id]['izin'] ?? 0 }}
                                </span>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                    S: {{ $rekapAbsensi[$s->id]['sakit'] ?? 0 }}
                                </span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 rounded">
                                    A: {{ $rekapAbsensi[$s->id]['alpha'] ?? 0 }}
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards p-3">
            @foreach($siswa as $index => $s)
                <div class="mobile-student-card">
                    <div class="student-header">
                        <div class="student-avatar">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama_lengkap) }}&background=3b82f6&color=ffffff"
                                 alt="Foto {{ $s->nama_lengkap }}"
                                 class="w-full h-full object-cover rounded-full">
                        </div>
                        <div class="student-info flex-1">
                            <h3>{{ $s->nama_lengkap }}</h3>
                            <p>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }} • NIS: {{ $s->nis ?? '-' }}</p>
                        </div>
                        <div class="student-meta">
                            <div class="student-number">#{{ $index + 1 }}</div>
                        </div>
                    </div>

                    <div class="attendance-badges">
                        <span class="attendance-badge bg-green-100 text-green-800">
                            H: {{ $rekapAbsensi[$s->id]['hadir'] ?? 0 }}
                        </span>
                        <span class="attendance-badge bg-yellow-100 text-yellow-800">
                            I: {{ $rekapAbsensi[$s->id]['izin'] ?? 0 }}
                        </span>
                        <span class="attendance-badge bg-blue-100 text-blue-800">
                            S: {{ $rekapAbsensi[$s->id]['sakit'] ?? 0 }}
                        </span>
                        <span class="attendance-badge bg-red-100 text-red-800">
                            A: {{ $rekapAbsensi[$s->id]['alpha'] ?? 0 }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Class Schedule -->
    <div class="mt-6 md:mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="p-3 md:p-6 border-b border-gray-200">
            <h3 class="text-base md:text-lg leading-6 font-medium text-gray-900">Jadwal Mengajar</h3>
        </div>

        <!-- Desktop Schedule -->
        <div class="desktop-table p-4 sm:p-6">
            @forelse($jadwalKelas as $hari => $jadwalHari)
            <div class="mb-6 last:mb-0">
                <h4 class="text-sm font-semibold text-gray-900 mb-3 uppercase">{{ $hari }}</h4>
                <div class="space-y-3">
                    @foreach($jadwalHari as $jadwal)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 mr-4">
                            <span class="text-sm font-medium">{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $jadwal->mapel ? $jadwal->mapel->nama : '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $jadwal->jam_gabungan ?? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) }}</p>
                        </div>
                        @if(isset($jadwal->total_jam) && $jadwal->total_jam > 1)
                            <div class="text-right">
                                <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                    {{ $jadwal->total_jam }} jam
                                </span>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-gray-500">Tidak ada jadwal mengajar untuk kelas ini</p>
            </div>
            @endforelse
        </div>

        <!-- Mobile Schedule -->
        <div class="mobile-cards p-3">
            @forelse($jadwalKelas as $hari => $jadwalHari)
                <div class="mobile-schedule-card">
                    <div class="schedule-day-header">{{ $hari }}</div>
                    @foreach($jadwalHari as $jadwal)
                        <div class="schedule-item">
                            <div class="schedule-time">
                                <span>{{ substr($jadwal->jam_mulai, 0, 5) }}</span>
                            </div>
                            <div class="schedule-info flex-1">
                                <h4>{{ $jadwal->mapel ? $jadwal->mapel->nama : '-' }}</h4>
                                <p>{{ $jadwal->jam_gabungan ?? substr($jadwal->jam_mulai, 0, 5) . ' - ' . substr($jadwal->jam_selesai, 0, 5) }}</p>
                            </div>
                            @if(isset($jadwal->total_jam) && $jadwal->total_jam > 1)
                                <div class="text-right">
                                    <span class="text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                        {{ $jadwal->total_jam }} jam
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-gray-500">Tidak ada jadwal mengajar untuk kelas ini</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
