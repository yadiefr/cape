@extends('layouts.guru')

@section('title', 'Dashboard Guru - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    .dashboard-card {
        transition: all 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Mobile touch feedback for cards */
    .dashboard-card:active {
        transform: translateY(0px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    /* Touch highlight for mobile */
    @media (max-width: 1023px) {
        .dashboard-card {
            -webkit-tap-highlight-color: rgba(59, 130, 246, 0.1);
        }
        
        .dashboard-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 12px -2px rgba(0, 0, 0, 0.08), 0 3px 6px -1px rgba(0, 0, 0, 0.05);
        }
    }
    
    /* Calendar Styles */
    .calendar-day {
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .calendar-day:hover {
        background-color: #f3f4f6;
    }
    
    .calendar-day.active {
        background-color: #3b82f6;
        color: white;
    }
    
    .calendar-day.has-event {
        background-color: #dbeafe;
        color: #1d4ed8;
        font-weight: 600;
    }
    
    .calendar-day.has-event::after {
        content: '';
        position: absolute;
        top: 4px;
        right: 4px;
        width: 6px;
        height: 6px;
        background-color: #3b82f6;
        border-radius: 50%;
    }
    
    .calendar-day.has-event.active::after {
        background-color: white;
    }
    
    /* Event Detail Modal */
    .event-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }
    
    .event-modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .event-modal-content {
        background: white;
        border-radius: 12px;
        padding: 20px;
        max-width: 500px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        animation: modalSlideIn 0.3s ease;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .event-item {
        padding: 12px;
        border-left: 4px solid transparent;
        border-radius: 6px;
        margin-bottom: 8px;
        background-color: #f9fafb;
    }
    
    .event-item.agenda {
        border-left-color: #10b981;
        background-color: #ecfdf5;
    }
    
    .event-item.jadwal {
        border-left-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    .event-item.pengumuman {
        border-left-color: #f59e0b;
        background-color: #fffbeb;
    }
    
    /* Line clamp for mobile card titles */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="p-3 sm:p-4 lg:p-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden mb-6 sm:mb-8">
        <div class="relative p-4 sm:p-6 lg:p-8">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 h-32 w-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 right-1/4 -mb-12 h-20 w-20 bg-white opacity-10 rounded-full"></div>
            
            <div class="relative z-10">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-2">
                    Selamat {{ now()->format('H') < 10 ? 'Pagi' : (now()->format('H') < 15 ? 'Siang' : (now()->format('H') < 18 ? 'Sore' : 'Malam')) }}, {{ auth()->guard('guru')->user()->nama_lengkap }}!
                </h1>
                <p class="text-blue-100 text-sm sm:text-base">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                
                @if(isset($kelasWali) && $kelasWali->count() > 0)
                <div class="mt-3 sm:mt-4 inline-flex items-center px-3 sm:px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg text-sm">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    <span>Anda adalah wali kelas {{ $kelasWali->pluck('nama_kelas')->join(', ') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats Cards - Mobile 2x2, Desktop 4x1 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-8">
        <!-- Total Jam Mengajar -->
        <div class="bg-white rounded-lg shadow p-2 sm:p-3 lg:p-4 dashboard-card border-l-4 border-blue-500 cursor-pointer" onclick="window.location.href='{{ route('guru.jadwal.index') }}'">
            <div class="flex flex-col lg:flex-row lg:justify-between">
                <div class="mb-1 lg:mb-0">
                    <h3 class="text-xs sm:text-sm lg:text-xs font-medium text-gray-500">Total Jam Mengajar</h3>
                    <p class="text-lg sm:text-xl lg:text-lg font-bold text-gray-800">{{ isset($jadwal) ? $jadwal->count() : 0 }} <span class="hidden sm:inline">Jam</span></p>
                </div>
                <div class="h-8 w-8 sm:h-10 sm:w-10 lg:h-10 lg:w-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-500 flex-shrink-0 self-end lg:self-start">
                    <i class="fas fa-clock text-sm sm:text-lg lg:text-lg"></i>
                </div>
            </div>
            <div class="mt-1 hidden sm:block">
                <a href="{{ route('guru.jadwal.index') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                    Lihat Detail →
                </a>
            </div>
        </div>

        <!-- Kelas Diampu -->
        <div class="bg-white rounded-lg shadow p-2 sm:p-3 lg:p-4 dashboard-card border-l-4 border-green-500 cursor-pointer" onclick="window.location.href='{{ route('guru.kelas.index') }}'">
            <div class="flex flex-col lg:flex-row lg:justify-between">
                <div class="mb-1 lg:mb-0">
                    <h3 class="text-xs sm:text-sm lg:text-xs font-medium text-gray-500">Kelas Diampu</h3>
                    <p class="text-lg sm:text-xl lg:text-lg font-bold text-gray-800">{{ isset($kelasDiajar) ? $kelasDiajar->count() : 0 }} <span class="hidden sm:inline">Kelas</span></p>
                </div>
                <div class="h-8 w-8 sm:h-10 sm:w-10 lg:h-10 lg:w-10 bg-green-50 rounded-full flex items-center justify-center text-green-500 flex-shrink-0 self-end lg:self-start">
                    <i class="fas fa-chalkboard-teacher text-sm sm:text-lg lg:text-lg"></i>
                </div>
            </div>
            <div class="mt-1 hidden sm:block">
                <a href="{{ route('guru.kelas.index') }}" class="text-green-600 hover:text-green-800 text-xs sm:text-sm font-medium">
                    Lihat Detail →
                </a>
            </div>
        </div>

        <!-- Absensi -->
        <div class="bg-white rounded-lg shadow p-2 sm:p-3 lg:p-4 dashboard-card border-l-4 border-purple-500 cursor-pointer" onclick="window.location.href='{{ route('guru.absensi.index') }}'">
            <div class="flex flex-col lg:flex-row lg:justify-between">
                <div class="mb-1 lg:mb-0">
                    <h3 class="text-xs sm:text-sm lg:text-xs font-medium text-gray-500">Absensi</h3>
                    <p class="text-lg sm:text-xl lg:text-lg font-bold text-gray-800">Kelola Absensi</p>
                </div>
                <div class="h-8 w-8 sm:h-10 sm:w-10 lg:h-10 lg:w-10 bg-purple-50 rounded-full flex items-center justify-center text-purple-500 flex-shrink-0 self-end lg:self-start">
                    <i class="fas fa-clipboard-check text-sm sm:text-lg lg:text-lg"></i>
                </div>
            </div>
            <div class="mt-2 hidden sm:block">
                <a href="{{ route('guru.absensi.index') }}" class="text-purple-600 hover:text-purple-800 text-sm sm:text-base font-medium">
                    Kelola Absensi →
                </a>
            </div>
        </div>

        <!-- Wali Kelas atau Daftar Kelas -->
        @if(isset($kelasWali) && $kelasWali->count() > 0)
        <div class="bg-white rounded-lg shadow p-2 sm:p-3 lg:p-4 dashboard-card border-l-4 border-amber-500 cursor-pointer" onclick="window.location.href='{{ route('guru.wali-kelas.dashboard') }}'">
            <div class="flex flex-col lg:flex-row lg:justify-between">
                <div class="mb-1 lg:mb-0">
                    <h3 class="text-xs sm:text-sm lg:text-xs font-medium text-gray-500">Wali Kelas</h3>
                    <p class="text-lg sm:text-xl lg:text-lg font-bold text-gray-800">{{ $kelasWali->first()->nama_kelas }}</p>
                </div>
                <div class="h-8 w-8 sm:h-10 sm:w-10 lg:h-10 lg:w-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 flex-shrink-0 self-end lg:self-start">
                    <i class="fas fa-user-tie text-sm sm:text-lg lg:text-lg"></i>
                </div>
            </div>
            <div class="mt-1 hidden sm:block">
                <a href="{{ route('guru.wali-kelas.dashboard') }}" class="text-amber-600 hover:text-amber-800 text-xs sm:text-sm font-medium">
                    Dashboard Wali Kelas →
                </a>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow p-2 sm:p-3 lg:p-4 dashboard-card border-l-4 border-amber-500 cursor-pointer" onclick="window.location.href='{{ route('guru.kelas.index') }}'">
            <div class="flex flex-col lg:flex-row lg:justify-between">
                <div class="mb-1 lg:mb-0">
                    <h3 class="text-xs sm:text-sm lg:text-xs font-medium text-gray-500">Daftar Kelas</h3>
                    <p class="text-lg sm:text-xl lg:text-lg font-bold text-gray-800">Lihat Semua Kelas</p>
                </div>
                <div class="h-8 w-8 sm:h-10 sm:w-10 lg:h-10 lg:w-10 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 flex-shrink-0 self-end lg:self-start">
                    <i class="fas fa-list text-sm sm:text-lg lg:text-lg"></i>
                </div>
            </div>
            <div class="mt-1 hidden sm:block">
                <a href="{{ route('guru.kelas.index') }}" class="text-amber-600 hover:text-amber-800 text-xs sm:text-sm font-medium">
                    Daftar Kelas →
                </a>
            </div>
        </div>
        @endif
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mt-6 sm:mt-8">
        <!-- Jadwal Hari Ini -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-3 sm:p-5 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                        <h2 class="font-semibold text-gray-800 text-base sm:text-lg mb-2 sm:mb-0">Jadwal Mengajar Hari Ini</h2>
                        <div class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-lg self-start">
                            {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd') }}
                        </div>
                    </div>
                </div>
                
                <div class="p-3 sm:p-5">
                    @php
                        $hariIni = strtolower(\Carbon\Carbon::now()->locale('id')->dayName);
                        $jadwalHariIni = isset($jadwal) ? $jadwal->filter(function($item) use ($hariIni) {
                            return strtolower($item->hari) === $hariIni;
                        })->sortBy('jam_mulai') : collect();
                        
                        // Group consecutive classes for the same subject and class
                        $groupedJadwal = collect();
                        $currentGroup = null;
                        
                        foreach ($jadwalHariIni as $j) {
                            $key = $j->mapel_id . '_' . $j->kelas_id;
                            
                            if ($currentGroup && $currentGroup['key'] === $key) {
                                // Update end time for current group
                                $currentGroup['jam_selesai'] = $j->jam_selesai;
                                $currentGroup['items'][] = $j;
                            } else {
                                // Save previous group if exists
                                if ($currentGroup) {
                                    $groupedJadwal->push($currentGroup);
                                }
                                
                                // Start new group
                                $currentGroup = [
                                    'key' => $key,
                                    'mapel' => $j->mapel,
                                    'kelas' => $j->kelas,
                                    'jam_mulai' => $j->jam_mulai,
                                    'jam_selesai' => $j->jam_selesai,
                                    'items' => [$j]
                                ];
                            }
                        }
                        
                        // Add the last group
                        if ($currentGroup) {
                            $groupedJadwal->push($currentGroup);
                        }
                    @endphp
                    
                    @if($groupedJadwal->count() > 0)
                        <div class="space-y-3">
                            @foreach($groupedJadwal as $group)
                            @php
                                $now = \Carbon\Carbon::now();
                                $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $group['jam_mulai']);
                                $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $group['jam_selesai']);
                                $isCurrentClass = $now->between($startTime, $endTime);
                                $isUpcoming = $now->lt($startTime);
                                $isPast = $now->gt($endTime);

                                $statusClass = '';
                                if ($isCurrentClass) {
                                    $statusClass = 'bg-green-500';
                                } elseif ($isUpcoming) {
                                    $statusClass = 'bg-blue-500';
                                } else {
                                    $statusClass = 'bg-gray-400';
                                }
                            @endphp
                            <div class="bg-blue-50 rounded-lg p-3 hover:bg-blue-100 transition-colors overflow-hidden">
                                <div class="flex items-start space-x-3">
                                    <div class="flex flex-col items-center flex-shrink-0">
                                        <div class="w-3 h-3 {{ $statusClass }} rounded-full"></div>
                                        <div class="w-0.5 h-12 {{ $statusClass }} bg-opacity-30 mt-1"></div>
                                    </div>
                                    <div class="flex-1 min-w-0 overflow-hidden">
                                        <h4 class="text-sm font-medium text-gray-900 line-clamp-2 leading-tight">{{ $group['mapel'] ? $group['mapel']->nama : 'Mata Pelajaran tidak ditemukan' }}</h4>
                                        <p class="text-xs text-gray-600 mt-1">
                                            {{ substr($group['jam_mulai'], 0, 5) }} - {{ substr($group['jam_selesai'], 0, 5) }}
                                            @if(count($group['items']) > 1)
                                            <span class="text-blue-600 font-medium">({{ count($group['items']) }} JP)</span>
                                            @endif
                                        </p>
                                        <div class="flex items-center mt-1 overflow-hidden">
                                            <div class="flex items-center justify-center w-4 h-4 bg-blue-500 text-white rounded-full text-xs mr-1 flex-shrink-0">
                                                <i class="fas fa-users text-xs"></i>
                                            </div>
                                            <p class="text-xs text-gray-700 truncate">{{ $group['kelas'] ? $group['kelas']->nama_kelas : 'Kelas tidak ditemukan' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-2 flex-shrink-0">
                                        @if ($isCurrentClass)
                                        <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded whitespace-nowrap">
                                            Live
                                        </span>
                                        @elseif ($isUpcoming)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded whitespace-nowrap">
                                            {{ $startTime->diffForHumans() }}
                                        </span>
                                        @else
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded whitespace-nowrap">
                                            Selesai
                                        </span>
                                        @endif
                                        <a href="{{ route('guru.absensi.create', ['jadwal_id' => $group['items'][0]->id, 'kelas_id' => $group['kelas']->id, 'mapel_id' => $group['mapel']->id]) }}"
                                           class="text-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg transition-all whitespace-nowrap">
                                            <i class="fas fa-clipboard-check mr-1"></i>
                                            Absen
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-6 sm:py-8 text-center">
                            <div class="h-12 w-12 sm:h-16 sm:w-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3 sm:mb-4">
                                <i class="fas fa-calendar-check text-lg sm:text-xl"></i>
                            </div>
                            <h3 class="text-gray-500 font-medium text-sm sm:text-base">Tidak ada jadwal mengajar hari ini</h3>
                            <p class="text-gray-400 text-xs sm:text-sm mt-1">Anda dapat melihat jadwal lengkap di menu Jadwal Mengajar</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Kelas yang Diajar -->
            <div class="bg-white rounded-lg shadow-md mt-4 sm:mt-6" id="daftar-kelas">
                <div class="p-3 sm:p-5 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800 text-base sm:text-lg">Kelas yang Diajar</h2>
                </div>
                
                <div class="p-3 sm:p-5">
                    @if(isset($kelasDiajar) && $kelasDiajar->count() > 0)
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        @foreach($kelasDiajar as $kelas)
                        <div class="bg-gray-50 rounded-lg p-2 sm:p-3 hover:bg-blue-50 transition-all">
                            <div class="flex items-center mb-2">
                                <div class="h-6 w-6 sm:h-8 sm:w-8 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mr-2 flex-shrink-0">
                                    <i class="fas fa-users text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 text-xs sm:text-sm">{{ $kelas->nama_kelas }}</h4>
                                    <p class="text-xs text-gray-600">{{ $kelas->jurusan->nama_jurusan ?? 'Jurusan tidak tersedia' }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <a href="{{ route('guru.kelas.show', $kelas->id) }}" 
                                   class="flex-1 text-center px-1 py-1 bg-white border border-gray-300 rounded text-xs font-medium hover:bg-gray-50 transition-all">
                                    Detail
                                </a>
                                <a href="{{ route('guru.absensi.index') }}?kelas_id={{ $kelas->id }}" 
                                   class="flex-1 text-center px-1 py-1 bg-blue-600 text-white rounded text-xs font-medium hover:bg-blue-700 transition-all">
                                    Absensi
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="py-6 sm:py-8 text-center">
                        <div class="h-12 w-12 sm:h-16 sm:w-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3 sm:mb-4">
                            <i class="fas fa-chalkboard text-lg sm:text-xl"></i>
                        </div>
                        <h3 class="text-gray-500 font-medium text-sm sm:text-base">Anda belum mengajar kelas manapun</h3>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Hubungi admin untuk informasi lebih lanjut</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar Content - Mobile: Full width, Desktop: Sidebar -->
        <div class="lg:col-span-1">
            <div class="space-y-4 sm:space-y-6">
                <!-- Calendar Widget -->
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-3 sm:p-5 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800 text-base sm:text-lg">Kalender</h2>
                    </div>
                    <div class="p-3 sm:p-5">
                        <div class="flex justify-between items-center mb-3 sm:mb-4">
                            <h3 class="text-gray-700 font-medium text-sm sm:text-base" id="currentMonth">Mei 2025</h3>
                            <div class="flex space-x-1 sm:space-x-2">
                                <button id="prevMonth" class="p-1 rounded hover:bg-gray-100 text-sm">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button id="nextMonth" class="p-1 rounded hover:bg-gray-100 text-sm">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Days of week -->
                        <div class="grid grid-cols-7 gap-1 text-center text-xs sm:text-sm font-medium text-gray-500 mb-2">
                            <div>Min</div>
                            <div>Sen</div>
                            <div>Sel</div>
                            <div>Rab</div>
                            <div>Kam</div>
                            <div>Jum</div>
                            <div>Sab</div>
                        </div>
                        
                        <!-- Calendar days -->
                        <div class="grid grid-cols-7 gap-1" id="calendarDays"></div>
                        
                        <div class="mt-3 sm:mt-4 pt-3 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <p class="text-xs sm:text-sm text-gray-600">
                                    <i class="fas fa-info-circle text-blue-500 mr-1"></i> 
                                    Klik tanggal untuk melihat kegiatan
                                </p>
                                <div class="flex items-center space-x-3 sm:space-x-4 text-xs">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-400 rounded-full mr-1"></div>
                                        <span class="text-gray-600">Agenda</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-400 rounded-full mr-1"></div>
                                        <span class="text-gray-600">Jadwal</span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-amber-400 rounded-full mr-1"></div>
                                        <span class="text-gray-600">Pengumuman</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            <!-- Event Detail Modal -->
            <div id="eventModal" class="event-modal">
                <div class="event-modal-content">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalDate" class="text-lg font-semibold text-gray-800"></h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="eventsList" class="space-y-3"></div>
                </div>
            </div>
            
            <!-- Pengumuman Terbaru -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-3 sm:p-5 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800 text-base sm:text-lg">Pengumuman Terbaru</h2>
                </div>
                <div class="p-3 sm:p-5">
                    @if(isset($pengumuman) && $pengumuman->count() > 0)
                    <div class="space-y-3 sm:space-y-4">
                        @foreach($pengumuman as $p)
                        <div class="border-l-4 border-blue-500 pl-3 py-1">
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">{{ $p->judul }}</h4>
                            <p class="text-xs sm:text-sm text-gray-600">{{ \Illuminate\Support\Str::limit(strip_tags($p->isi), 70) }}</p>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-xs text-gray-500">{{ $p->created_at->diffForHumans() }}</span>
                                <a href="{{ route('guru.pengumuman.show', $p->id) }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm">Baca</a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-3 sm:mt-4 text-center">
                        <a href="{{ route('guru.pengumuman.index') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">
                            Lihat Semua Pengumuman <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    @else
                    <div class="py-6 sm:py-8 text-center">
                        <div class="h-12 w-12 sm:h-16 sm:w-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center text-blue-500 mb-3 sm:mb-4">
                            <i class="fas fa-newspaper text-lg sm:text-xl"></i>
                        </div>
                        <h3 class="text-gray-500 font-medium text-sm sm:text-base">Tidak ada pengumuman baru</h3>
                        <p class="text-gray-400 text-xs sm:text-sm mt-1">Pengumuman akan muncul di sini</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Calendar events data from Laravel
        const calendarEvents = @json($calendarEvents ?? []);
        
        // Calendar functionality
        const calendarDays = document.getElementById('calendarDays');
        const currentMonthElement = document.getElementById('currentMonth');
        const prevMonthButton = document.getElementById('prevMonth');
        const nextMonthButton = document.getElementById('nextMonth');
        const eventModal = document.getElementById('eventModal');
        const modalDate = document.getElementById('modalDate');
        const eventsList = document.getElementById('eventsList');
        const closeModal = document.getElementById('closeModal');
        
        let currentDate = new Date();
        
        // Generate calendar
        function generateCalendar(year, month) {
            calendarDays.innerHTML = '';
            
            // Fetch events for the selected month
            fetchCalendarEvents(year, month + 1).then(events => {
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const daysInMonth = lastDay.getDate();
                
                // Update month heading
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                currentMonthElement.textContent = `${monthNames[month]} ${year}`;
                
                // Add empty cells for days before the first of the month
                let dayOfWeek = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
                for (let i = 0; i < dayOfWeek; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.classList.add('calendar-day', 'text-gray-300');
                    calendarDays.appendChild(emptyCell);
                }
                
                // Generate the days of the month
                const today = new Date();
                for (let i = 1; i <= daysInMonth; i++) {
                    const dayCell = document.createElement('div');
                    dayCell.classList.add('calendar-day', 'text-gray-800', 'cursor-pointer');
                    
                    // Check if this day is today
                    if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
                        dayCell.classList.add('active');
                    }
                    
                    // Check if this day has events
                    const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                    const dayEvents = events[dateString];
                    
                    if (dayEvents && dayEvents.hasEvents) {
                        dayCell.classList.add('has-event');
                    }
                    
                    dayCell.textContent = i;
                    dayCell.addEventListener('click', function() {
                        document.querySelectorAll('.calendar-day.active').forEach(el => el.classList.remove('active'));
                        dayCell.classList.add('active');
                        
                        // Show events for this day
                        showEventModal(i, monthNames[month], year, dayEvents);
                    });
                    
                    calendarDays.appendChild(dayCell);
                }
            });
        }
        
        // Fetch calendar events from API
        async function fetchCalendarEvents(year, month) {
            try {
                const response = await fetch(`{{ route('guru.calendar.events') }}?year=${year}&month=${month}`);
                if (response.ok) {
                    return await response.json();
                }
                return {};
            } catch (error) {
                console.error('Error fetching calendar events:', error);
                return {};
            }
        }
        
        // Show event modal
        function showEventModal(day, monthName, year, dayEvents) {
            modalDate.textContent = `${day} ${monthName} ${year}`;
            eventsList.innerHTML = '';
            
            if (dayEvents && dayEvents.events && dayEvents.events.length > 0) {
                dayEvents.events.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.className = `event-item ${event.type}`;
                    
                    const eventTypeText = {
                        'agenda': 'Agenda',
                        'jadwal': 'Jadwal Mengajar',
                        'pengumuman': 'Pengumuman'
                    };
                    
                    eventElement.innerHTML = `
                        <div class="flex justify-between items-start mb-2">
                            <h4 class="font-semibold text-gray-800">${event.title}</h4>
                            <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded">${eventTypeText[event.type]}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${event.description}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-clock mr-1"></i>
                            ${event.time}
                        </div>
                    `;
                    eventsList.appendChild(eventElement);
                });
            } else {
                eventsList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="h-16 w-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <i class="fas fa-calendar-times text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Tidak ada kegiatan pada tanggal ini</p>
                    </div>
                `;
            }
            
            eventModal.classList.add('show');
        }
        
        // Close modal
        closeModal.addEventListener('click', function() {
            eventModal.classList.remove('show');
        });
        
        // Close modal when clicking outside
        eventModal.addEventListener('click', function(e) {
            if (e.target === eventModal) {
                eventModal.classList.remove('show');
            }
        });
        
        // Initialize the calendar
        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        
        // Event listeners for prev/next buttons
        prevMonthButton.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        });
        
        nextMonthButton.addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        });
    });
</script>
@endpush