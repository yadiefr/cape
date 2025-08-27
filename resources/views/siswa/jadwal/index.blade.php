@extends('layouts.siswa')

@section('title', 'Jadwal Pelajaran - SMK PGRI CIKAMPEK')

@section('styles')
<style>
    /* Remove any default margins/padding that might cause spacing issues */
    .main-content-container {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .schedule-table {
        border-collapse: separate;
        border-spacing: 0;
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
        border-left: 4px solid;
        height: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .schedule-cell {
            min-height: auto !important;
            height: auto !important;
            padding: 4px !important;
            margin-bottom: 1px;
            line-height: 1.3;
            font-size: 0.7rem !important;
        }

        .schedule-cell .text-xs {
            font-size: 0.65rem !important;
            line-height: 1.2 !important;
        }

        .schedule-cell .guru-name {
            display: block !important;
            font-size: 0.6rem !important;
            opacity: 0.8;
            margin-top: 2px;
        }
    }
    
    .schedule-cell:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .class-badge {
        background: rgba(255, 255, 255, 0.8);
        color: inherit;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 0.6rem;
        padding: 1px 3px;
        border-radius: 3px;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    @media (max-width: 768px) {
        .class-badge {
            font-size: 0.55rem;
            padding: 1px 2px;
        }
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
            height: 20px !important;
            min-height: 20px !important;
            padding: 1px 2px;
            font-size: 0.55rem;
            gap: 0.5px;
        }
    }
    
    .break-time-label {
        color: rgb(180, 83, 9);
        font-weight: 500;
    }

    /* Day color styling */
    .senin-color { background-color: #3b82f6; }    /* blue-500 */
    .selasa-color { background-color: #10b981; }   /* green-500 */
    .rabu-color { background-color: #f59e0b; }     /* amber-500 */
    .kamis-color { background-color: #8b5cf6; }    /* purple-500 */
    .jumat-color { background-color: #ef4444; }    /* red-500 */    
    /* Today highlight styles */
    .today-column {
        background-color: #f0f9ff !important; /* light blue bg */
        border-left: 2px solid #3b82f6 !important;
        border-right: 2px solid #3b82f6 !important;
    }
    
    .today-header {
        background-color: #dbeafe !important; /* blue-100 */
        position: relative;
    }
    
    .today-indicator {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #3b82f6; /* blue-500 */
    }
    
    .day-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
        vertical-align: middle;
    }
    
    /* Guru avatar styling */
    .guru-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 0.7rem;
        margin-right: 1px;
        text-align: center;
        line-height: 24px;
        overflow: hidden;
        flex-shrink: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .schedule-table th,
        .schedule-table td {
            padding: 6px 4px;
            font-size: 0.8rem;
        }
        
        .schedule-cell {
            font-size: 0.75rem;
        }
    }
    
    @media (max-width: 768px) {
        .schedule-table {
            font-size: 0.7rem;
        }
        
        .schedule-table td {
            padding: 2px 1px !important;
            vertical-align: top !important;
            height: auto !important;
            min-height: auto !important;
        }
        
        .schedule-cell {
            font-size: 0.7rem !important;
            padding: 4px !important;
            min-height: auto !important;
            height: auto !important;
            line-height: 1.3 !important;
        }

        .schedule-cell .mapel-name {
            font-size: 0.65rem !important;
            font-weight: 600 !important;
            margin-bottom: 2px !important;
        }

        .schedule-cell .guru-name {
            font-size: 0.6rem !important;
            opacity: 0.85 !important;
            display: block !important;
        }
        
        .class-badge {
            font-size: 0.6rem;
            padding: 1px 3px;
        }
        
        /* Container untuk semua schedule cell di mobile */
        .space-y-1 {
            min-height: auto !important;
            height: auto !important;
        }
        
        .max-h-48 {
            max-height: none !important;
        }
        
        /* Fix untuk cell kosong */
        .h-auto.py-4 {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }
        
        /* Force auto height for all schedule containers on mobile */
        tbody tr td {
            height: auto !important;
        }
        
        tbody tr td > div {
            height: auto !important;
            min-height: auto !important;
        }
        
        /* Specific mobile fixes */
        .text-center.min-w-\\[140px\\].align-top {
            height: auto !important;
            min-height: auto !important;
        }
        
        /* Force mobile table cells to be compact */
        @media (max-width: 768px) {
            table td {
                padding: 1px !important;
                height: auto !important;
                min-height: auto !important;
            }
            
            .schedule-cell-container {
                height: auto !important;
                min-height: auto !important;
            }
            
            /* Override all Tailwind height classes on mobile */
            .h-12, .h-auto, .min-h-\\[45px\\] {
                height: auto !important;
                min-height: auto !important;
            }

            /* Mobile cell height optimization */
            .schedule-table td {
                height: auto !important;
                vertical-align: top !important;
                padding: 2px !important;
            }

            .schedule-cell {
                padding: 4px !important;
                min-height: fit-content !important;
                height: fit-content !important;
            }

            /* Ensure text is readable on mobile */
            .schedule-cell .fas {
                font-size: 0.6rem !important;
            }

            /* Better spacing for mobile */
            .schedule-cell .mapel-name {
                margin-bottom: 1px !important;
            }

            .schedule-cell .guru-name {
                margin-top: 1px !important;
            }
        }
    }
</style>
@endsection

@section('content')
    @if(isset($error))
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400 text-lg"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Pemberitahuan</h3>
                    <p class="text-sm text-yellow-700 mt-1">{{ $error }}</p>
                </div>
            </div>
        </div>
    @else
        <!-- Unified Weekly Schedule View Section -->
        <div id="weeklyScheduleSection">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold">Jadwal Pelajaran</h3>
                            @if(isset($kelas))
                                <p class="text-indigo-100 text-sm">
                                    Kelas: {{ $kelas->nama_kelas }}
                                </p>
                            @endif
                            @if($settingsJadwal->isNotEmpty())
                            <p class="text-indigo-100 text-xs mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Jadwal menggunakan waktu dari pengaturan jadwal sekolah
                            </p>
                            <div class="text-indigo-100 text-xs mt-2 flex flex-wrap gap-2">
                                @php 
                                    $daySettings = $settingsJadwal->first(); 
                                @endphp
                                @if($daySettings->jam_istirahat_mulai && $daySettings->jam_istirahat_selesai)
                                    <span class="px-2 py-1 bg-white bg-opacity-20 rounded">
                                        <i class="fas fa-coffee mr-1"></i> Istirahat 1: {{ \Carbon\Carbon::parse($daySettings->jam_istirahat_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($daySettings->jam_istirahat_selesai)->format('H:i') }}
                                    </span>
                                @endif
                                @if($daySettings->jam_istirahat2_mulai && $daySettings->jam_istirahat2_selesai)
                                    <span class="px-2 py-1 bg-white bg-opacity-20 rounded">
                                        <i class="fas fa-utensils mr-1"></i> Istirahat 2: {{ \Carbon\Carbon::parse($daySettings->jam_istirahat2_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($daySettings->jam_istirahat2_selesai)->format('H:i') }}
                                    </span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full schedule-table text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                @php 
                                    $today = strtolower(now()->locale('id')->dayName);
                                    $dayMapping = [
                                        'senin' => 'Senin',
                                        'selasa' => 'Selasa',
                                        'rabu' => 'Rabu',
                                        'kamis' => 'Kamis',
                                        'jumat' => 'Jumat',
                                    ];
                                    $todayInIndonesian = $dayMapping[$today] ?? '';
                                @endphp
                                <th class="px-2 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 sticky left-0 bg-gray-50">Jam</th>
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $day)
                                    @php
                                        $isToday = strtolower($day) === strtolower($todayInIndonesian);
                                    @endphp
                                    <th class="px-2 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 {{ $isToday ? 'today-header' : '' }}">
                                        @if($isToday)<div class="today-indicator"></div>@endif
                                        <div class="flex items-center justify-center">
                                            <div class="day-indicator {{ strtolower($day) }}-color"></div>
                                            <span>{{ $day }}</span>
                                            @if($isToday)
                                                <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-calendar-day mr-1"></i>Hari ini
                                                </span>
                                            @endif
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Get all schedules and group by time slots (use unfiltered data for weekly view)
                                $jam_slots = [];
                                
                                // Color palette untuk mata pelajaran
                                $mapel_colors = [];
                                $used_colors = [];
                                
                                // Daftar warna untuk mata pelajaran (30 warna berbeda)
                                $color_palette = [
                                    'bg-blue-100 border-blue-300 text-blue-800',        // Biru
                                    'bg-green-100 border-green-300 text-green-800',     // Hijau
                                    'bg-purple-100 border-purple-300 text-purple-800',  // Ungu
                                    'bg-red-100 border-red-300 text-red-800',           // Merah
                                    'bg-yellow-100 border-yellow-300 text-yellow-800',  // Kuning
                                    'bg-indigo-100 border-indigo-300 text-indigo-800',  // Indigo
                                    'bg-pink-100 border-pink-300 text-pink-800',        // Pink
                                    'bg-teal-100 border-teal-300 text-teal-800',        // Teal
                                    'bg-orange-100 border-orange-300 text-orange-800',  // Orange
                                    'bg-cyan-100 border-cyan-300 text-cyan-800',        // Cyan
                                    'bg-lime-100 border-lime-300 text-lime-800',        // Lime
                                    'bg-emerald-100 border-emerald-300 text-emerald-800', // Emerald
                                    'bg-violet-100 border-violet-300 text-violet-800',   // Violet
                                    'bg-fuchsia-100 border-fuchsia-300 text-fuchsia-800', // Fuchsia
                                    'bg-rose-100 border-rose-300 text-rose-800',         // Rose
                                    'bg-sky-100 border-sky-300 text-sky-800',            // Sky
                                    'bg-amber-100 border-amber-300 text-amber-800',      // Amber
                                    'bg-slate-100 border-slate-300 text-slate-800',      // Slate
                                    'bg-zinc-100 border-zinc-300 text-zinc-800',         // Zinc
                                    'bg-stone-100 border-stone-300 text-stone-800',      // Stone
                                    'bg-red-50 border-red-200 text-red-700',             // Light Red
                                    'bg-blue-50 border-blue-200 text-blue-700',          // Light Blue
                                    'bg-green-50 border-green-200 text-green-700',       // Light Green
                                    'bg-purple-50 border-purple-200 text-purple-700',    // Light Purple
                                    'bg-yellow-50 border-yellow-200 text-yellow-700',    // Light Yellow
                                    'bg-pink-50 border-pink-200 text-pink-700',          // Light Pink
                                    'bg-indigo-50 border-indigo-200 text-indigo-700',    // Light Indigo
                                    'bg-teal-50 border-teal-200 text-teal-700',          // Light Teal
                                    'bg-orange-50 border-orange-200 text-orange-700',    // Light Orange
                                    'bg-cyan-50 border-cyan-200 text-cyan-700',          // Light Cyan
                                ];
                                
                                // Pre-assign colors hanya untuk mata pelajaran yang ada di jadwal kelas ini
                                // untuk memastikan konsistensi warna
                                $classMapel = collect($jadwal)->pluck('mapel.nama')->filter()->unique()->sort();
                                $color_index = 0;
                                
                                foreach ($classMapel as $mapelNama) {
                                    if (!isset($mapel_colors[$mapelNama])) {
                                        // Assign warna berdasarkan urutan untuk menghindari collision
                                        $mapel_colors[$mapelNama] = $color_palette[$color_index % count($color_palette)];
                                        $used_colors[] = $color_palette[$color_index % count($color_palette)];
                                        $color_index++;
                                    }
                                }
                                
                                $grade_colors = [
                                    'X' => 'grade-X',
                                    'XI' => 'grade-XI', 
                                    'XII' => 'grade-XII',
                                    'Lainnya' => 'grade-X'
                                ];
                                
                                // Initialize all possible time slots for all days
                                $all_time_slots = [];
                                $hari_list_table = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
                                
                                // Use settings_jadwal for time slots instead of jadwal_pelajaran
                                // Create time slots based on settingsJadwal data, which comes from SettingsJadwal table
                                if ($settingsJadwal->isNotEmpty()) {
                                    // Get first day settings to calculate time slots (assuming all days have same number of periods)
                                    $daySettings = $settingsJadwal->first();
                                    $numPeriods = (int)$daySettings->jumlah_jam_pelajaran;
                                    $periodDuration = (int)$daySettings->durasi_per_jam;
                                    
                                    // Start time is the first jam_mulai from settings
                                    $startTime = \Carbon\Carbon::parse($daySettings->jam_mulai);
                                    
                                    // Prepare break times
                                    $breaks = [];
                                    if ($daySettings->jam_istirahat_mulai && $daySettings->jam_istirahat_selesai) {
                                        $breaks[] = [
                                            'start' => \Carbon\Carbon::parse($daySettings->jam_istirahat_mulai),
                                            'end' => \Carbon\Carbon::parse($daySettings->jam_istirahat_selesai),
                                            'label' => 'Istirahat 1'
                                        ];
                                    }
                                    if ($daySettings->jam_istirahat2_mulai && $daySettings->jam_istirahat2_selesai) {
                                        $breaks[] = [
                                            'start' => \Carbon\Carbon::parse($daySettings->jam_istirahat2_mulai),
                                            'end' => \Carbon\Carbon::parse($daySettings->jam_istirahat2_selesai),
                                            'label' => 'Istirahat 2'
                                        ];
                                    }
                                    
                                    // Create all time slots based on settings
                                    for ($i = 1; $i <= $numPeriods; $i++) {
                                        // Calculate period start and end times
                                        $periodStart = clone $startTime;
                                        $periodEnd = (clone $startTime)->addMinutes($periodDuration);
                                        
                                        // Check if we need to add a break time slot before this period
                                        foreach ($breaks as $break) {
                                            // If break starts exactly at the end of the previous period
                                            if ($periodStart->format('H:i') === $break['start']->format('H:i')) {
                                                // Add a special break time slot
                                                $breakKey = $break['start']->format('H:i') . ' - ' . $break['end']->format('H:i');
                                                $all_time_slots[$breakKey] = [
                                                    'jam_ke' => null, // No jam_ke for breaks
                                                    'jam_mulai' => $break['start']->format('H:i'),
                                                    'jam_selesai' => $break['end']->format('H:i'),
                                                    'is_break' => true,
                                                    'break_label' => $break['label']
                                                ];
                                                
                                                // Update period start time to after the break
                                                $periodStart = clone $break['end'];
                                                $periodEnd = (clone $periodStart)->addMinutes($periodDuration);
                                            }
                                            // If break starts during this period, split the period
                                            else if ($periodStart->format('H:i') < $break['start']->format('H:i') && 
                                                    $periodEnd->format('H:i') > $break['start']->format('H:i')) {
                                                
                                                // Create first part of the period (before break)
                                                $firstPartKey = $periodStart->format('H:i') . ' - ' . $break['start']->format('H:i');
                                                $all_time_slots[$firstPartKey] = [
                                                    'jam_ke' => $i,
                                                    'jam_mulai' => $periodStart->format('H:i'),
                                                    'jam_selesai' => $break['start']->format('H:i')
                                                ];
                                                
                                                // Add the break slot
                                                $breakKey = $break['start']->format('H:i') . ' - ' . $break['end']->format('H:i');
                                                $all_time_slots[$breakKey] = [
                                                    'jam_ke' => null,
                                                    'jam_mulai' => $break['start']->format('H:i'),
                                                    'jam_selesai' => $break['end']->format('H:i'),
                                                    'is_break' => true,
                                                    'break_label' => $break['label']
                                                ];
                                                
                                                // Update period start time to after the break, and create second part in next iteration
                                                $periodStart = clone $break['end'];
                                                $periodEnd = (clone $periodStart)->addMinutes($periodDuration);
                                                
                                                // Skip creating regular period since we've handled it specially
                                                continue 2;
                                            }
                                        }
                                        
                                        // Format regular time slot key
                                        $jam_key = $periodStart->format('H:i') . ' - ' . $periodEnd->format('H:i');
                                        
                                        // Store time slot info
                                        $all_time_slots[$jam_key] = [
                                            'jam_ke' => $i,
                                            'jam_mulai' => $periodStart->format('H:i'),
                                            'jam_selesai' => $periodEnd->format('H:i'),
                                            'is_break' => false
                                        ];
                                        
                                        // Move start time to next period
                                        $startTime = $periodEnd;
                                    }
                                } else {
                                    // Fallback: use actual jadwal data for time slots if settings are not available
                                    $total_jadwal = count($jadwal);
                                    
                                    // First pass: collect all unique time slots from jadwal
                                    foreach($jadwal as $item) {
                                        $jam_key = \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->jam_selesai)->format('H:i');
                                        $all_time_slots[$jam_key] = [
                                            'jam_ke' => $item->jam_ke,
                                            'jam_mulai' => $item->jam_mulai,
                                            'jam_selesai' => $item->jam_selesai
                                        ];
                                    }
                                }
                                
                                // Sort time slots by time
                                ksort($all_time_slots);
                                
                                // Initialize jam_slots with all time slots and days (ensure every slot has every day)
                                foreach($all_time_slots as $jam_key => $time_info) {
                                    $jam_slots[$jam_key] = [];
                                    foreach($hari_list_table as $hari) {
                                        $jam_slots[$jam_key][$hari] = [];
                                    }
                                }
                                
                                // Populate with actual schedules
                                foreach($jadwal as $item) {
                                    // Find appropriate time slot for this schedule
                                    $jadwalStart = \Carbon\Carbon::parse($item->jam_mulai);
                                    $jadwalEnd = \Carbon\Carbon::parse($item->jam_selesai);
                                    $bestTimeSlot = null;
                                    $bestOverlap = 0;
                                    
                                    foreach($all_time_slots as $jam_key => $time_info) {
                                        $slotStart = \Carbon\Carbon::parse($time_info['jam_mulai']);
                                        $slotEnd = \Carbon\Carbon::parse($time_info['jam_selesai']);
                                        
                                        // Calculate overlap between jadwal time and this slot
                                        $overlapStart = max($jadwalStart->timestamp, $slotStart->timestamp);
                                        $overlapEnd = min($jadwalEnd->timestamp, $slotEnd->timestamp);
                                        $overlap = max(0, $overlapEnd - $overlapStart);
                                        
                                        if ($overlap > $bestOverlap) {
                                            $bestOverlap = $overlap;
                                            $bestTimeSlot = $jam_key;
                                        }
                                    }
                                    
                                    // Use the best matching time slot or fallback to exact jadwal times
                                    $targetTimeSlot = $bestTimeSlot ?? (\Carbon\Carbon::parse($item->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->jam_selesai)->format('H:i'));
                                    
                                    if (isset($jam_slots[$targetTimeSlot][$item->hari])) {
                                        $jam_slots[$targetTimeSlot][$item->hari][] = $item;
                                    }
                                }
                                
                                // Get break times for visual indicators
                                $breakTimes = [];
                                if($settingsJadwal->isNotEmpty()) {
                                    $daySettings = $settingsJadwal->first();
                                    if($daySettings->jam_istirahat_mulai && $daySettings->jam_istirahat_selesai) {
                                        $breakTimes[] = [
                                            'start' => \Carbon\Carbon::parse($daySettings->jam_istirahat_mulai)->format('H:i'),
                                            'end' => \Carbon\Carbon::parse($daySettings->jam_istirahat_selesai)->format('H:i'),
                                            'label' => 'Istirahat 1'
                                        ];
                                    }
                                    if($daySettings->jam_istirahat2_mulai && $daySettings->jam_istirahat2_selesai) {
                                        $breakTimes[] = [
                                            'start' => \Carbon\Carbon::parse($daySettings->jam_istirahat2_mulai)->format('H:i'),
                                            'end' => \Carbon\Carbon::parse($daySettings->jam_istirahat2_selesai)->format('H:i'),
                                            'label' => 'Istirahat 2'
                                        ];
                                    }
                                }
                            @endphp
                            
                            @if(count($jam_slots) > 0)
                                @foreach($jam_slots as $jam_key => $hari_jadwal)
                                @php
                                    // Check if this time slot is a break time
                                    $isBreakTime = false;
                                    $breakLabel = '';
                                    foreach($breakTimes as $break) {
                                        list($slotStart, $slotEnd) = explode(' - ', $jam_key);
                                        if($slotStart == $break['start'] && $slotEnd == $break['end']) {
                                            $isBreakTime = true;
                                            $breakLabel = $break['label'];
                                            break;
                                        }
                                    }
                                @endphp

                                <tr class="hover:bg-gray-50 {{ $isBreakTime ? 'bg-amber-50' : '' }}">
                                    <td class="px-2 py-2 text-xs font-bold text-gray-900 border-r border-gray-200 bg-gray-50 sticky left-0 {{ $isBreakTime ? 'bg-amber-100' : '' }}">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-semibold">{{ $jam_key }}</span>
                                            <span class="text-xs text-gray-500">
                                                @if($isBreakTime)
                                                    <span class="text-amber-600 font-medium text-xs"><i class="fas fa-coffee mr-1"></i>{{ $breakLabel }}</span>
                                                @else
                                                    @php
                                                        // Get jam_ke from time slot info or first available schedule
                                                        $jam_ke_info = '';
                                                        if (isset($all_time_slots[$jam_key]['jam_ke']) && $all_time_slots[$jam_key]['jam_ke']) {
                                                            $jam_ke_info = 'Jam ke-' . $all_time_slots[$jam_key]['jam_ke'];
                                                        } else {
                                                            // Fallback: get from any schedule in this time slot
                                                            $sample_jadwal = collect($hari_jadwal)->flatten()->first();
                                                            $jam_ke_info = $sample_jadwal ? 'Jam ke-' . $sample_jadwal->jam_ke : '';
                                                        }
                                                        echo $jam_ke_info;
                                                    @endphp
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $hari)
                                    @php
                                        $isToday = strtolower($hari) === strtolower($todayInIndonesian);
                                    @endphp
                                    <td class="px-1 py-1 text-xs border-r border-gray-200 text-center min-w-[100px] md:min-w-[140px] align-top {{ $isToday ? 'today-column' : '' }}" style="height: auto !important;">
                                        @if(isset($all_time_slots[$jam_key]['is_break']) && $all_time_slots[$jam_key]['is_break'])
                                            <div class="break-time-cell">
                                                <i class="fas {{ $all_time_slots[$jam_key]['break_label'] == 'Istirahat 1' ? 'fa-coffee' : 'fa-utensils' }} text-xs"></i>
                                                <span class="break-time-label text-xs">{{ $all_time_slots[$jam_key]['break_label'] }}</span>
                                            </div>
                                        @elseif(isset($hari_jadwal[$hari]) && count($hari_jadwal[$hari]) > 0)
                                            <div class="space-y-1 max-h-48 md:max-h-48 overflow-y-auto" style="min-height: auto !important;">
                                                @foreach($hari_jadwal[$hari] as $item)
                                                    @php
                                                        // Color coding by mata pelajaran with consistent assignment
                                                        $mapelNama = $item->mapel->nama ?? 'Mapel tidak ditemukan';
                                                        
                                                        // Gunakan warna yang sudah di-assign dari pre-loaded colors
                                                        $color_class = $mapel_colors[$mapelNama] ?? 'bg-gray-100 border-gray-300 text-gray-800';
                                                    @endphp
                                                    <div class="schedule-cell {{ $color_class }} border rounded-lg p-1 mb-1 hover:shadow-md transition-all cursor-pointer" title="Mata Pelajaran: {{ $mapelNama }}">
                                                        @if(isset($item->is_break) && $item->is_break)
                                                            <div class="flex items-center justify-center text-amber-700">
                                                                <i class="fas fa-mug-hot mr-2"></i>
                                                                <span class="font-medium">ISTIRAHAT</span>
                                                            </div>
                                                        @elseif(isset($item->mapel))

                                                            <div class="text-xs font-medium mb-1 truncate mapel-name" title="{{ $item->mapel->nama }}">
                                                                <i class="fas fa-book text-xs mr-1 opacity-75"></i>{{ $item->mapel->nama }}
                                                            </div>
                                                            @if(isset($item->guru))
                                                                <div class="text-xs text-gray-600 truncate guru-name" title="{{ $item->guru->nama }}">
                                                                    <i class="fas fa-user text-xs mr-1 opacity-75"></i>{{ $item->guru->nama }}
                                                                </div>
                                                            @endif
                                                            @if($item->keterangan)
                                                                <div class="mt-1 text-xs text-gray-500 truncate hidden lg:block" title="{{ $item->keterangan }}">
                                                                    <i class="fas fa-info-circle mr-1"></i>{{ $item->keterangan }}
                                                                </div>
                                                            @endif
                                                            @if(isset($item->ruangan))
                                                                <div class="mt-1 text-xs bg-white bg-opacity-50 text-gray-600 inline-block px-1 py-0.5 rounded hidden sm:block">
                                                                    <i class="fas fa-door-open mr-1"></i>{{ $item->ruangan }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="h-auto flex items-center justify-center py-2 md:py-4">
                                                <span class="text-gray-400 text-xs">-</span>
                                            </div>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jadwal</h3>
                                            <p class="text-sm text-gray-500 mb-4">Belum ada jadwal pelajaran yang dibuat untuk kelas Anda.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Color Legend Section -->
                <div class="mt-6 bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-palette mr-2"></i>Keterangan Warna Mata Pelajaran
                    </h4>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 text-xs">
                        @foreach($classMapel as $mapelNama)
                            @if(isset($mapel_colors[$mapelNama]))
                                <div class="flex items-center space-x-2">
                                    <div class="w-4 h-4 rounded border {{ $mapel_colors[$mapelNama] }}"></div>
                                    <span class="text-gray-700 truncate" title="{{ $mapelNama }}">{{ $mapelNama }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="mt-3 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Setiap mata pelajaran memiliki warna yang unik untuk memudahkan identifikasi dalam jadwal
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
