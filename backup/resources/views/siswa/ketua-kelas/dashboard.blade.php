@extends('layouts.siswa')

@section('title', 'Dashboard Ketua Kelas')

@section('content')
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Ketua Kelas</h1>
                <p class="text-gray-600">{{ $kelas->nama_kelas }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $siswa->nama_lengkap }}
                </span>
            </div>
        </div>
    </div>

    <!-- Statistik Cards - Mobile First 2x2 Layout -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Siswa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Total Siswa</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $totalSiswa }}</p>
                </div>
            </div>
        </div>

        <!-- Hadir Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Hadir Hari Ini</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $attendanceToday['hadir'] }}</p>
                    <p class="text-xs text-gray-400">{{ number_format($attendanceToday['persentase'], 1) }}%</p>
                </div>
            </div>
        </div>

        <!-- Tidak Hadir -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-100 text-red-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Tidak Hadir</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $attendanceToday['alpha'] + $attendanceToday['sakit'] + $attendanceToday['izin'] }}</p>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Kehadiran</p>
                    <p class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-800">{{ $attendanceToday['persentase'] ? number_format($attendanceToday['persentase'], 1) : '0' }}%</p>
                    <p class="text-xs text-gray-400">Bulan ini</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <!-- Grafik Kehadiran Mingguan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Kehadiran Mingguan</h3>
            <div class="h-64 flex items-end justify-between space-x-2">
                @foreach($weeklyAttendance as $day)
                <div class="flex flex-col items-center flex-1">
                    <div class="bg-blue-500 rounded-t w-full flex justify-center items-end text-white text-xs font-medium min-h-[20px]" 
                         style="height: {{ max($day['percentage'], 5) }}%;">
                        @if($day['hadir'] > 0)
                            {{ $day['hadir'] }}
                        @endif
                    </div>
                    <div class="mt-2 text-xs text-gray-600 text-center">
                        {{ $day['tanggal'] }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 text-xs text-gray-500 text-center">
                Data kehadiran 7 hari terakhir
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Kehadiran Hari Ini</h3>
            <a href="{{ route('siswa.ketua-kelas.rekap-absensi') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todayAttendanceDetail as $attendance)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $attendance->siswa->nama_lengkap }}</div>
                            <div class="text-sm text-gray-500">{{ $attendance->siswa->nis }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($attendance->status == 'hadir') bg-green-100 text-green-800
                                @elseif($attendance->status == 'izin') bg-blue-100 text-blue-800
                                @elseif($attendance->status == 'sakit') bg-yellow-100 text-yellow-800
                                @elseif($attendance->status == 'alpha') bg-red-100 text-red-800
                                @elseif($attendance->status == 'terlambat') bg-orange-100 text-orange-800
                                @endif">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $attendance->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            Belum ada data absensi hari ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Actions - Mobile First 2x2 Layout -->
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <a href="{{ route('siswa.ketua-kelas.absensi') }}" 
           class="bg-orange-600 hover:bg-orange-700 text-white p-3 sm:p-4 rounded-lg text-center transition-colors duration-200 group">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="font-medium text-sm sm:text-base">Input Absensi</span>
            <p class="text-xs opacity-75 mt-1 hidden sm:block">Input absensi harian</p>
        </a>
        
        <a href="{{ route('siswa.ketua-kelas.rekap-absensi') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white p-3 sm:p-4 rounded-lg text-center transition-colors duration-200 group">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="font-medium text-sm sm:text-base">Rekap Absensi</span>
            <p class="text-xs opacity-75 mt-1 hidden sm:block">Lihat rekap bulanan</p>
        </a>
        
        <a href="{{ route('siswa.ketua-kelas.daftar-siswa') }}" 
           class="bg-green-600 hover:bg-green-700 text-white p-3 sm:p-4 rounded-lg text-center transition-colors duration-200 group">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="font-medium text-sm sm:text-base">Daftar Siswa</span>
            <p class="text-xs opacity-75 mt-1 hidden sm:block">Kelola data siswa</p>
        </a>
        
        <button @click="showNotificationModal = true" 
                class="bg-purple-600 hover:bg-purple-700 text-white p-3 sm:p-4 rounded-lg text-center transition-colors duration-200 group">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM5 19h6a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span class="font-medium text-sm sm:text-base">Kirim Pengumuman</span>
            <p class="text-xs opacity-75 mt-1 hidden sm:block">Buat pengumuman kelas</p>
        </button>
    </div>

    <!-- Modal Notification (Hidden by default) -->
    <div x-show="showNotificationModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75" @click="showNotificationModal = false"></div>
            </div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Kirim Pengumuman Kelas</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Pengumuman</label>
                        <input x-model="notificationTitle" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea x-model="notificationMessage" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button @click="sendNotification" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Kirim
                    </button>
                    <button @click="showNotificationModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardKM', () => ({
        showNotificationModal: false,
        notificationTitle: '',
        notificationMessage: '',
        
        init() {
            console.log('Dashboard KM loaded');
        },
        
        sendNotification() {
            if (!this.notificationTitle || !this.notificationMessage) {
                alert('Mohon isi judul dan pesan pengumuman');
                return;
            }
            
            // Simulasi kirim pengumuman (nanti bisa diintegrasikan dengan backend)
            alert(`Pengumuman "${this.notificationTitle}" berhasil dikirim ke seluruh siswa kelas!`);
            
            // Reset form
            this.notificationTitle = '';
            this.notificationMessage = '';
            this.showNotificationModal = false;
        }
    }));
});
</script>

<style>
@media (max-width: 768px) {
    .grid-cols-3 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 480px) {
    .grid-cols-3 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}

.chart-bar {
    transition: all 0.3s ease;
}

.chart-bar:hover {
    transform: scaleY(1.05);
    filter: brightness(1.1);
}

.quick-action-card {
    transition: all 0.2s ease;
}

.quick-action-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
</style>
@endsection
