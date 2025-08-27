@extends('layouts.guru')

@section('title', 'Jadwal Mengajar')

@push('styles')
<style>
    .schedule-table {
        border-collapse: separate;
          <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-white text-xs md:text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500">Mata Pelajaran</p>
                    <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $totalMapel ?? $jadwal->pluck('mapel_id')->unique()->count() }}</p>
                </div>
            </div>
        </div>spacing: 0;
    }
    
    .schedule-table th,
    .schedule-table td {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .schedule-cell {
        min-height: 45px;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        border-radius: 4px;
        position: relative;
        overflow: hidden;
        border-width: 1px;
        border-style: solid;
        z-index: 10;
    }
    
    @media (max-width: 768px) {
        .schedule-cell {
            min-height: 40px;
            padding: 4px !important;
            margin-bottom: 1px;
        }
    }
    
    .schedule-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-opacity: 75%;
        z-index: 15;
    }
    
    .schedule-cell-empty {
        transition: all 0.2s ease;
        border: 2px dashed #d1d5db;
        border-radius: 6px;
    }
    
    .schedule-cell-empty:hover {
        background-color: #f3f4f6 !important;
        border-color: #9ca3af !important;
    }
    
    /* Sticky column jam - highest z-index */
    .sticky-jam-column {
        position: sticky;
        left: 0;
        z-index: 20 !important;
        background: #f9fafb;
    }
    
    /* Break time styles */
    .break-time-row {
        background-color: rgba(251, 191, 36, 0.1);
    }
    
    .break-time-cell {
        background-color: rgba(251, 191, 36, 0.2);
        border-radius: 3px;
        padding: 2px 4px;
        font-size: 0.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1px;
        height: 30px;
    }
    
    @media (max-width: 768px) {
        .break-time-cell {
            height: 25px;
            padding: 1px 2px;
            font-size: 0.55rem;
            gap: 0.5px;
        }
    }
    
    .break-time-label {
        color: rgb(180, 83, 9);
        font-weight: 500;
    }
    
    /* Class badge improvements */
    .class-badge {
        background: rgba(255, 255, 255, 0.9);
        color: inherit;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-size: 0.65rem;
        padding: 1px 4px;
        border-radius: 3px;
        margin-bottom: 2px;
        display: inline-block;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .class-badge {
            font-size: 0.6rem;
            padding: 1px 3px;
            margin-bottom: 1px;
        }
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .schedule-table {
            font-size: 12px;
        }
        
        .schedule-cell {
            min-height: 40px !important;
            padding: 4px !important;
            margin: 1px !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Mengajar</h1>
            <p class="text-gray-600 mt-1">Lihat jadwal mengajar Anda</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-xs md:text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 md:ml-3">
                    <p class="text-xs md:text-sm font-medium text-gray-500">Total Jadwal</p>
                    <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $totalJadwal ?? $jadwal->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard text-white text-xs md:text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500">Kelas Diampu</p>
                    <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $totalKelas ?? $jadwal->pluck('kelas_id')->unique()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-white text-xs md:text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500">Mata Pelajaran</p>
                    <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $totalMapel ?? $jadwal->pluck('mapel_id')->unique()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 md:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-6 h-6 md:w-8 md:h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xs md:text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500">Jam Mengajar</p>
                    <p class="text-lg md:text-2xl font-semibold text-gray-900">{{ $totalJam }} JPL</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    @if($jadwal->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6 text-center">
                <div class="mb-4">
                    <i class="fas fa-calendar-times text-gray-400 text-6xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Jadwal Tidak Tersedia</h3>
                
                @if(isset($debugInfo))
                    @if($debugInfo['assignments'] > 0 && $debugInfo['scheduled'] == 0)
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <div class="text-sm text-blue-800">
                                <strong>Status:</strong> Admin telah mengassign {{ $debugInfo['assignments'] }} mata pelajaran kepada Anda, 
                                namun belum membuat jadwal waktu yang spesifik. Silahkan hubungi admin untuk membuat jadwal waktu mengajar.
                            </div>
                        </div>
                    @elseif($debugInfo['total_entries'] == 0)
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <div class="text-sm text-red-800">
                                <strong>Status:</strong> Admin belum mengassign mata pelajaran kepada Anda. 
                                Silahkan hubungi admin untuk mendapatkan assignment mengajar.
                            </div>
                        </div>
                    @endif
                @endif
                
                <p class="text-gray-600">
                    Jadwal mengajar Anda belum tersedia atau belum diatur oleh admin.
                </p>
                <div class="mt-4">
                    <button onclick="location.reload()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh Halaman
                    </button>
                </div>
            </div>
        </div>
    @else
        <!-- Today's Schedule (if available) -->
        @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
            <div class="p-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-calendar-day text-green-600 mr-2"></i>
                    Jadwal Hari Ini ({{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }})
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($jadwalHariIni as $j)
                    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="font-medium text-blue-800">{{ $j->mapel->nama ?? 'Mata Pelajaran tidak tersedia' }}</h4>
                            @if($j->jam_mulai && $j->jam_selesai)
                            <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">
                                {{ \Carbon\Carbon::parse($j->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($j->jam_selesai)->format('H:i') }}
                            </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mb-1">
                            <i class="fas fa-users mr-1"></i>{{ $j->kelas->nama_kelas ?? 'Kelas tidak tersedia' }}
                        </p>
                        @if($j->ruang)
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $j->ruang }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Weekly Schedule Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 rounded-t-xl">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-calendar-week mr-2"></i>
                            Jadwal Mengajar Mingguan
                        </h3>
                        <p class="text-blue-100 text-sm mt-1">{{ auth()->guard('guru')->user()->nama_lengkap }}</p>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full schedule-table text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 sticky-jam-column">Jam</th>
                            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                <th class="px-1 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">{{ $hari }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            // Get time slots from controller (passed via compact)
                            // Initialize schedule matrix
                            $scheduleMatrix = [];
                            $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                            
                            foreach($days as $day) {
                                $scheduleMatrix[strtolower($day)] = [];
                            }
                            
                            // Convert timeSlots to allSlots format for compatibility
                            $allSlots = [];
                            foreach($timeSlots as $slot) {
                                if(isset($slot['start']) && isset($slot['end'])) {
                                    $key = $slot['start'] . ' - ' . $slot['end'];
                                    $allSlots[$key] = $slot;
                                } else {
                                    // For break slots
                                    $allSlots[$slot['time']] = $slot;
                                }
                            }
                            
                            // Map existing jadwal to time slots
                            foreach($jadwal as $j) {
                                if($j->jam_mulai && $j->jam_selesai && $j->hari) {
                                    $dayKey = strtolower($j->hari);
                                    
                                    // Convert jam_mulai and jam_selesai to time objects for comparison
                                    $jadwalStart = \Carbon\Carbon::parse($j->jam_mulai);
                                    $jadwalEnd = \Carbon\Carbon::parse($j->jam_selesai);
                                    
                                    // Check which time slots fall within this jadwal's time range
                                    foreach($allSlots as $stdKey => $stdSlot) {
                                        if(!($stdSlot['isBreak'] ?? false) && isset($stdSlot['start']) && isset($stdSlot['end'])) {
                                            $slotStart = \Carbon\Carbon::parse($stdSlot['start']);
                                            $slotEnd = \Carbon\Carbon::parse($stdSlot['end']);
                                            
                                            // Check if this time slot overlaps with the jadwal
                                            // A slot is considered part of the jadwal if:
                                            // 1. Slot starts within jadwal time range, OR
                                            // 2. Slot is completely within jadwal time range, OR  
                                            // 3. Jadwal starts within slot time range
                                            if (($slotStart >= $jadwalStart && $slotStart < $jadwalEnd) ||
                                                ($slotStart >= $jadwalStart && $slotEnd <= $jadwalEnd) ||
                                                ($jadwalStart >= $slotStart && $jadwalStart < $slotEnd)) {
                                                
                                                $scheduleMatrix[$dayKey][$stdKey] = $j;
                                            }
                                        }
                                    }
                                }
                            }
                            
                            // Color palette for mata pelajaran
                            $mapel_colors = [];
                            $color_palette = [
                                'bg-blue-100 border-blue-300 text-blue-800',
                                'bg-green-100 border-green-300 text-green-800',
                                'bg-purple-100 border-purple-300 text-purple-800',
                                'bg-red-100 border-red-300 text-red-800',
                                'bg-yellow-100 border-yellow-300 text-yellow-800',
                                'bg-indigo-100 border-indigo-300 text-indigo-800',
                                'bg-pink-100 border-pink-300 text-pink-800',
                                'bg-teal-100 border-teal-300 text-teal-800',
                                'bg-orange-100 border-orange-300 text-orange-800',
                                'bg-cyan-100 border-cyan-300 text-cyan-800',
                            ];
                            
                            // Assign colors to mata pelajaran
                            $allMapel = $jadwal->pluck('mapel.nama')->unique();
                            $color_index = 0;
                            foreach ($allMapel as $mapelNama) {
                                if (!isset($mapel_colors[$mapelNama])) {
                                    $mapel_colors[$mapelNama] = $color_palette[$color_index % count($color_palette)];
                                    $color_index++;
                                }
                            }
                        @endphp
                        
                        @foreach($timeSlots as $index => $slot)
                            <tr class="hover:bg-gray-50 {{ $slot['isBreak'] ?? false ? 'bg-amber-50' : '' }}">
                                <td class="px-2 py-2 text-xs font-bold text-gray-900 border-r border-gray-200 sticky-jam-column {{ $slot['isBreak'] ?? false ? 'bg-amber-100' : '' }}">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-semibold">{{ $slot['time'] }}</span>
                                        <span class="text-xs text-gray-500">
                                            @if($slot['isBreak'] ?? false)
                                                <span class="text-amber-600 font-medium text-xs"><i class="fas fa-coffee mr-1"></i>{{ $slot['label'] ?? 'Istirahat' }}</span>
                                            @else
                                                <span class="text-gray-500">Jam ke-{{ $slot['jam_ke'] ?? ($index + 1) }}</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                
                                @foreach($days as $day)
                                    @php 
                                        $dayKey = strtolower($day); 
                                        $timeKey = isset($slot['start']) && isset($slot['end']) ? 
                                                   $slot['start'] . ' - ' . $slot['end'] : 
                                                   $slot['time'];
                                    @endphp
                                    <td class="px-1 py-2 text-xs border-r border-gray-200 text-center min-w-[140px] align-top">
                                        @if($slot['isBreak'] ?? false)
                                            <div class="break-time-cell">
                                                <i class="fas fa-coffee text-xs"></i>
                                                <span class="break-time-label text-xs">{{ $slot['label'] ?? 'Istirahat' }}</span>
                                            </div>
                                        @elseif(isset($scheduleMatrix[$dayKey][$timeKey]))
                                            @php 
                                                $jadwalItem = $scheduleMatrix[$dayKey][$timeKey];
                                                $mapelNama = $jadwalItem->mapel->nama ?? 'Mata Pelajaran tidak tersedia';
                                                $color_class = $mapel_colors[$mapelNama] ?? 'bg-gray-100 border-gray-300 text-gray-800';
                                                
                                                // Check if this is a multi-slot jadwal
                                                $jadwalStart = \Carbon\Carbon::parse($jadwalItem->jam_mulai);
                                                $jadwalEnd = \Carbon\Carbon::parse($jadwalItem->jam_selesai);
                                                $slotStart = isset($slot['start']) ? \Carbon\Carbon::parse($slot['start']) : $jadwalStart;
                                                $slotEnd = isset($slot['end']) ? \Carbon\Carbon::parse($slot['end']) : $jadwalEnd;
                                                $isMultiSlot = $jadwalEnd->diffInMinutes($jadwalStart) > 35; // More than 1 slot (35 minutes)
                                                $isFirstSlot = $slotStart->equalTo($jadwalStart);
                                                $isLastSlot = $slotEnd->equalTo($jadwalEnd);
                                            @endphp
                                            <div class="schedule-cell {{ $color_class }} border rounded-lg p-1 mb-1 hover:shadow-md transition-all cursor-pointer group relative {{ $isMultiSlot ? 'border-l-4' : '' }}" title="Mata Pelajaran: {{ $mapelNama }}">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="class-badge">{{ $jadwalItem->kelas->nama_kelas ?? 'Kelas tidak ditemukan' }}</span>
                                                    @if($isMultiSlot)
                                                        <span class="text-xs bg-blue-600 text-white px-1 py-0.5 rounded text-[9px]">
                                                            {{ \Carbon\Carbon::parse($jadwalItem->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jadwalItem->jam_selesai)->format('H:i') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="text-xs font-medium mb-1 truncate" title="{{ $mapelNama }}">
                                                    <i class="fas fa-book text-xs mr-1 opacity-75"></i>{{ $mapelNama }}
                                                    @if($isMultiSlot)
                                                        <span class="text-[9px] text-gray-500 ml-1">({{ $jadwalEnd->diffInMinutes($jadwalStart) }} mnt)</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-gray-600 truncate hidden md:block" title="{{ auth()->guard('guru')->user()->nama_lengkap }}">
                                                    <i class="fas fa-user text-xs mr-1 opacity-75"></i>{{ auth()->guard('guru')->user()->nama_lengkap }}
                                                </div>
                                                @if($jadwalItem->ruang)
                                                    <div class="text-xs text-gray-500 mt-1 italic truncate hidden md:block" title="{{ $jadwalItem->ruang }}">
                                                        <i class="fas fa-map-marker-alt text-xs mr-1 opacity-75"></i>{{ $jadwalItem->ruang }}
                                                    </div>
                                                @endif
                                                
                                                <!-- Tooltip untuk hover -->
                                                <div class="absolute invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -top-8 left-1/2 transform -translate-x-1/2 z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                                    {{ $mapelNama }} 
                                                    @if($isMultiSlot)
                                                        ({{ \Carbon\Carbon::parse($jadwalItem->jam_mulai)->format('H:i') }}-{{ \Carbon\Carbon::parse($jadwalItem->jam_selesai)->format('H:i') }})
                                                    @endif
                                                    <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="schedule-cell-empty h-12 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">
                                                    <i class="fas fa-calendar-times mr-1"></i>Kosong
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Summary Information -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-2xl font-bold text-blue-600">{{ $jadwal->count() }}</div>
                        <div class="text-xs text-gray-500">Total Jadwal</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-2xl font-bold text-green-600">{{ $jadwal->count() }}</div>
                        <div class="text-xs text-gray-500">Jadwal Aktif</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-2xl font-bold text-purple-600">{{ $jadwal->pluck('kelas_id')->unique()->count() }}</div>
                        <div class="text-xs text-gray-500">Total Kelas</div>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <div class="text-2xl font-bold text-orange-600">{{ $jadwal->pluck('mapel_id')->unique()->count() }}</div>
                        <div class="text-xs text-gray-500">Mata Pelajaran</div>
                    </div>
                </div>
            </div>
            
            <!-- Color Legend for Mata Pelajaran -->
            @if(count($mapel_colors) > 0)
            <div class="px-6 py-4 bg-white border-t border-gray-200">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">
                        <i class="fas fa-palette mr-2"></i>Legend Warna Mata Pelajaran ({{ count($mapel_colors) }} mapel)
                    </h4>
                    <div class="text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Setiap mata pelajaran memiliki warna unik
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @php
                        $sortedMapelColors = collect($mapel_colors)->sortKeys();
                    @endphp
                    @foreach($sortedMapelColors as $mapelName => $colorClass)
                        <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="w-4 h-4 rounded border-2 {{ $colorClass }} flex-shrink-0"></div>
                            <span class="text-xs text-gray-700 truncate" title="{{ $mapelName }}">{{ $mapelName }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
function printSchedule() {
    window.print();
}
</script>
@endpush
@endsection