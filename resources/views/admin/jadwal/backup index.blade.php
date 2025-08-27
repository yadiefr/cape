@extends('layouts.admin')
@section('title', 'Manajemen Jadwal Pelajaran')

@section('styles')
<style>
    .schedule-table {
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .schedule-table th,
    .schedule-table td {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .schedule-cell {
        min-height: 60px;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        border-radius: 6px;
        position: relative;
        overflow: hidden;
        border-width: 2px;
        border-style: solid;
    }
    
    .schedule-cell:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-opacity: 75%;
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
        border-radius: 4px;
        padding: 6px 8px;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
    }
    
    .break-time-label {
        color: rgb(180, 83, 9);
        font-weight: 500;
    }
    
    /* Action buttons - improved visibility on colored backgrounds */
    .action-btn {
        padding: 4px 6px;
        border-radius: 4px;
        transition: all 0.2s;
        cursor: pointer;
        opacity: 1;
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(0, 0, 0, 0.15);
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .schedule-cell:hover .action-btn {
        opacity: 1;
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        border-color: rgba(0, 0, 0, 0.2);
    }
    
    .action-btn:hover {
        background: rgba(255, 255, 255, 1);
        transform: scale(1.05);
    }
    
    /* Class badge improvements */
    .class-badge {
        background: rgba(255, 255, 255, 0.9);
        color: inherit;
        border: 1px solid rgba(0, 0, 0, 0.15);
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 4px;
        display: inline-block;
        font-weight: 600;
        backdrop-filter: blur(2px);
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
        
        .schedule-cell {
            font-size: 0.65rem;
            padding: 4px;
        }
        
        .class-badge {
            font-size: 0.6rem;
            padding: 1px 3px;
        }
    }
</style>
@endsection

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Jadwal Pelajaran</h1>
            <p class="text-gray-600 mt-1">Kelola jadwal pelajaran untuk semua kelas</p>
        </div>
        <div class="flex space-x-2">
            <button onclick="openImportModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-file-import mr-2"></i>Import Jadwal
            </button>
            <a href="{{ route('admin.settings.jadwal.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-cog mr-2"></i>Pengaturan Jadwal
            </a>
            <a href="{{ route('admin.jadwal.create-table') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-table mr-2"></i>Mode Tabel
            </a>
            <button id="btnCreateJadwal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-plus mr-2"></i>Tambah Jadwal
            </button>
            <button id="btnBatchDelete" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-trash-alt mr-2"></i>Hapus Berdasarkan Hari
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Jadwal</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_jadwal'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Kelas Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_kelas'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-tie text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Guru Mengajar</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_guru'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-book text-white text-sm"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Mata Pelajaran</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_mapel'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Form -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <form action="{{ route('admin.jadwal.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas_list as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="hari" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Semua Hari</option>
                    @foreach($hari_list as $hari)
                        <option value="{{ $hari }}" {{ $selectedHari == $hari ? 'selected' : '' }}>
                            {{ $hari }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Guru</label>
                <select name="guru_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    <option value="">Semua Guru</option>
                    @foreach($guru_list as $guru)
                        <option value="{{ $guru->id }}" {{ request('guru_id') == $guru->id ? 'selected' : '' }}>
                            {{ $guru->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                <select name="tahun_ajaran" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @foreach($tahun_ajaran_list as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun_ajaran', $filter_tahun_ajaran) == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                    @foreach($semester_list as $semester)
                        <option value="{{ $semester }}" {{ request('semester', $filter_semester) == $semester ? 'selected' : '' }}>
                            {{ $semester }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5 flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.jadwal.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Unified Weekly Schedule View Section -->
    <div id="weeklyScheduleSection" class="space-y-6">
        @if($kelas_list->isNotEmpty())
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold">Jadwal Pelajaran Mingguan</h3>
                            <p class="text-indigo-100 text-sm">
                                @if($settingsJadwal->isNotEmpty())
                                    @php
                                        $activeDaysFromSettings = $settingsJadwal->pluck('hari')->toArray();
                                        $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                        $displayDays = array_intersect($dayOrder, $activeDaysFromSettings);
                                    @endphp
                                    Hari Aktif: {{ implode(', ', $displayDays) }}
                                @else
                                    Semua Kelas - {{ $kelas_list->pluck('nama_kelas')->join(', ') }}
                                @endif
                            </p>
                            @if($settingsJadwal->isNotEmpty())
                            <p class="text-indigo-100 text-xs mt-1">
                                <i class="fas fa-info-circle mr-1"></i> Jadwal menggunakan waktu dari pengaturan di halaman Pengaturan Jadwal
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
                                <span class="px-2 py-1 bg-white bg-opacity-20 rounded">
                                    <i class="fas fa-clock mr-1"></i> Durasi per Jam: {{ $daySettings->durasi_per_jam }} menit
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="printAllSchedule()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="fas fa-print mr-1"></i>Cetak Semua
                            </button>
                            <button onclick="exportScheduleToExcel()" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1 rounded text-sm transition-colors">
                                <i class="fas fa-file-excel mr-1"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full schedule-table">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200 sticky left-0 bg-gray-50">Jam</th>
                                @php
                                    // Get active days from settings_jadwal
                                    $activeDays = [];
                                    if($settingsJadwal->isNotEmpty()) {
                                        $activeDays = $settingsJadwal->pluck('hari')->toArray();
                                    } else {
                                        // Fallback: use all days if no settings
                                        $activeDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    }
                                    
                                    // Ensure proper order of days
                                    $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    $orderedActiveDays = array_intersect($dayOrder, $activeDays);
                                @endphp
                                
                                @foreach($orderedActiveDays as $hari)
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200{{ $loop->last ? '' : '' }}">{{ $hari }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                // Get all schedules and group by time slots (use unfiltered data for weekly view)
                                $jam_slots = [];
                                
                                // Cache untuk warna mapel yang sudah di-generate dan tracking warna yang digunakan
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
                                
                                // Pre-assign colors untuk semua mata pelajaran yang ada di database
                                // untuk memastikan konsistensi warna
                                $allMapel = \App\Models\MataPelajaran::orderBy('nama')->get();
                                $color_index = 0;
                                
                                foreach ($allMapel as $mapel) {
                                    if (!isset($mapel_colors[$mapel->nama])) {
                                        // Assign warna berdasarkan urutan untuk menghindari collision
                                        $mapel_colors[$mapel->nama] = $color_palette[$color_index % count($color_palette)];
                                        $used_colors[] = $color_palette[$color_index % count($color_palette)];
                                        $color_index++;
                                    }
                                }
                                
                                
                                
                                // Initialize all possible time slots for all days
                                $all_time_slots = [];
                                
                                // Get active days from settings_jadwal for table processing
                                $hari_list_table = [];
                                if($settingsJadwal->isNotEmpty()) {
                                    $activeDaysFromSettings = $settingsJadwal->pluck('hari')->toArray();
                                    // Ensure proper order of days
                                    $dayOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                    $hari_list_table = array_intersect($dayOrder, $activeDaysFromSettings);
                                } else {
                                    // Fallback: use all days if no settings
                                    $hari_list_table = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                }
                                
                                // Use settings_jadwal for time slots instead of jadwal_pelajaran
                                // Create time slots based on settingsJadwal data, which comes from SettingsJadwal table
                                if ($settingsJadwal->isNotEmpty()) {
                                    // Get first day settings to calculate time slots (assuming all days have same number of periods)
                                    $daySettings = $settingsJadwal->first();
                                    $numPeriods = $daySettings->jumlah_jam_pelajaran;
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
                                    $weekly_data = isset($jadwal_mingguan) ? $jadwal_mingguan : $jadwal_pelajaran;
                                    $total_jadwal = count($weekly_data);
                                    
                                    // First pass: collect all unique time slots from jadwal
                                    foreach($weekly_data as $jadwal) {
                                        $jam_key = \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i');
                                        $all_time_slots[$jam_key] = [
                                            'jam_ke' => $jadwal->jam_ke,
                                            'jam_mulai' => $jadwal->jam_mulai,
                                            'jam_selesai' => $jadwal->jam_selesai
                                        ];
                                    }
                                }
                                
                                // Sort time slots by time
                                ksort($all_time_slots);
                                
                                // Use unfiltered data for weekly view
                                $weekly_data = isset($jadwal_mingguan) ? $jadwal_mingguan : $jadwal_pelajaran;
                                $total_jadwal = count($weekly_data);
                                
                                // Initialize jam_slots with all time slots and days (ensure every slot has every day)
                                foreach($all_time_slots as $jam_key => $time_info) {
                                    $jam_slots[$jam_key] = [];
                                    foreach($hari_list_table as $hari) {
                                        $jam_slots[$jam_key][$hari] = [];
                                    }
                                }
                                
                                // Second pass: populate with actual schedules
                                // Match jadwal to closest time slot from settings
                                foreach($weekly_data as $jadwal) {
                                    // Find appropriate time slot for this schedule
                                    $jadwalStart = \Carbon\Carbon::parse($jadwal->jam_mulai);
                                    $jadwalEnd = \Carbon\Carbon::parse($jadwal->jam_selesai);
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
                                    $targetTimeSlot = $bestTimeSlot ?? (\Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i'));
                                    
                                    if (isset($jam_slots[$targetTimeSlot][$jadwal->hari])) {
                                        $jam_slots[$targetTimeSlot][$jadwal->hari][] = $jadwal;
                                    }
                                }
                                
                                // Debug output
                                echo "<!-- DEBUG: Total jadwal untuk weekly view: {$total_jadwal}, Time slots: " . count($all_time_slots) . " -->";
                            @endphp
                            
                            @if(count($jam_slots) > 0)
                                @php
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
                                    <td class="px-3 py-3 text-sm font-bold text-gray-900 border-r border-gray-200 bg-gray-50 sticky left-0 {{ $isBreakTime ? 'bg-amber-100' : '' }}">
                                        <div class="flex flex-col" data-time-slot="{{ $jam_key }}" data-jam-ke="{{ $all_time_slots[$jam_key]['jam_ke'] ?? '' }}" data-is-break="{{ isset($all_time_slots[$jam_key]['is_break']) && $all_time_slots[$jam_key]['is_break'] ? 'true' : 'false' }}">
                                            <span class="text-sm font-semibold">{{ $jam_key }}</span>
                                            <span class="text-xs text-gray-500">
                                                @if($isBreakTime)
                                                    <span class="text-amber-600 font-medium"><i class="fas fa-coffee mr-1"></i>{{ $breakLabel }}</span>
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
                                    @foreach($hari_list_table as $hari)
                                    <td class="px-2 py-3 text-sm border-r border-gray-200 text-center min-w-[250px] align-top">
                                        @if(isset($all_time_slots[$jam_key]['is_break']) && $all_time_slots[$jam_key]['is_break'])
                                            <div class="break-time-cell">
                                                <i class="fas {{ $all_time_slots[$jam_key]['break_label'] == 'Istirahat 1' ? 'fa-coffee' : 'fa-utensils' }}"></i>
                                                <span class="break-time-label">{{ $all_time_slots[$jam_key]['break_label'] }}</span>
                                            </div>
                                        @elseif(isset($hari_jadwal[$hari]) && count($hari_jadwal[$hari]) > 0)
                                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                                @foreach($hari_jadwal[$hari] as $jadwal)
                                                    @php
                                                        // Color coding by mata pelajaran with consistent assignment
                                                        $mapelNama = $jadwal->mapel->nama ?? 'Mapel tidak ditemukan';
                                                        
                                                        // Gunakan warna yang sudah di-assign dari pre-loaded colors
                                                        $color_class = $mapel_colors[$mapelNama] ?? 'bg-gray-100 border-gray-300 text-gray-800';
                                                    @endphp
                                                    <div class="schedule-cell {{ $color_class }} border rounded-lg p-2 mb-1 hover:shadow-md transition-all cursor-pointer group relative" onclick="editJadwal({{ $jadwal->id }})" title="Mata Pelajaran: {{ $jadwal->mapel->nama ?? 'Mapel tidak ditemukan' }}">
                                                        <div class="flex justify-between items-start mb-1">
                                                            <span class="class-badge">{{ $jadwal->kelas->nama_kelas ?? 'Kelas tidak ditemukan' }}</span>
                                                            <div class="flex space-x-1 opacity-90 group-hover:opacity-100">
                                                                <button onclick="event.stopPropagation(); editJadwal({{ $jadwal->id }})" class="action-btn text-blue-600 hover:text-blue-800 bg-white hover:bg-blue-50" title="Edit Jadwal">
                                                                    <i class="fas fa-edit text-xs"></i>
                                                                </button>
                                                                <button onclick="event.stopPropagation(); deleteJadwal({{ $jadwal->id }})" class="action-btn text-red-600 hover:text-red-800 bg-white hover:bg-red-50" title="Hapus Jadwal">
                                                                    <i class="fas fa-trash text-xs"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="text-xs font-medium mb-1 truncate" title="{{ $jadwal->mapel->nama ?? 'Mapel tidak ditemukan' }}">
                                                            <i class="fas fa-book text-xs mr-1 opacity-75"></i>{{ $jadwal->mapel->nama ?? 'Mapel tidak ditemukan' }}
                                                        </div>
                                                        <div class="text-xs text-gray-600 truncate" title="{{ $jadwal->guru->nama ?? 'Guru tidak ditemukan' }}">
                                                            <i class="fas fa-user text-xs mr-1 opacity-75"></i>{{ $jadwal->guru->nama ?? 'Guru tidak ditemukan' }}
                                                        </div>
                                                        @if($jadwal->keterangan)
                                                            <div class="text-xs text-gray-500 mt-1 italic truncate" title="{{ $jadwal->keterangan }}">
                                                                <i class="fas fa-info-circle text-xs mr-1 opacity-75"></i>{{ Str::limit($jadwal->keterangan, 30) }}
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Tooltip untuk hover -->
                                                        <div class="absolute invisible group-hover:visible bg-gray-800 text-white text-xs rounded py-1 px-2 -top-8 left-1/2 transform -translate-x-1/2 z-50 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                                            {{ $jadwal->mapel->nama ?? 'Mapel tidak ditemukan' }}
                                                            <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-800"></div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="schedule-cell-empty h-16 bg-gray-50 border-2 border-dashed border-gray-200 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-100 hover:border-gray-300 transition-colors group" onclick="addJadwalForSlot('{{ $hari }}', '{{ $jam_key }}')">
                                                <span class="text-gray-400 text-xs group-hover:text-gray-600">
                                                    <i class="fas fa-plus mr-1"></i>Tambah Jadwal
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ count($hari_list_table) + 1 }}" class="px-4 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center">
                                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Jadwal</h3>
                                            <p class="text-sm text-gray-500 mb-4">Belum ada jadwal pelajaran yang dibuat untuk semester ini.</p>
                                            <button onclick="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm transition-colors">
                                                <i class="fas fa-plus mr-2"></i>Tambah Jadwal Pertama
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Summary Information -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-blue-600">{{ $jadwal_pelajaran->count() }}</div>
                            <div class="text-xs text-gray-500">Total Jadwal</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-green-600">{{ $jadwal_pelajaran->where('is_active', true)->count() }}</div>
                            <div class="text-xs text-gray-500">Jadwal Aktif</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-purple-600">{{ $kelas_list->count() }}</div>
                            <div class="text-xs text-gray-500">Total Kelas</div>
                        </div>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <div class="text-2xl font-bold text-orange-600">{{ $jadwal_pelajaran->pluck('mapel_id')->unique()->count() }}</div>
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
                            // Sort mapel alphabetically untuk konsistensi tampilan
                            $sortedMapelColors = $mapel_colors;
                            ksort($sortedMapelColors);
                        @endphp
                        @foreach($sortedMapelColors as $mapelNama => $colorClass)
                            <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                                <div class="w-6 h-6 rounded border-2 {{ $colorClass }} flex-shrink-0"></div>
                                <span class="text-xs text-gray-700 truncate flex-1" title="{{ $mapelNama }}">
                                    {{ Str::limit($mapelNama, 18) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Debug info for development -->
                    @if(config('app.debug'))
                        <details class="mt-4">
                            <summary class="text-xs text-gray-400 cursor-pointer hover:text-gray-600">
                                <i class="fas fa-bug mr-1"></i>Debug: Color Distribution
                            </summary>
                            <div class="mt-2 text-xs text-gray-500 bg-gray-100 p-2 rounded">
                                <div>Total Mata Pelajaran: {{ count($mapel_colors) }}</div>
                                <div>Total Warna Berbeda: {{ count(array_unique(array_values($mapel_colors))) }}</div>
                                <div>Warna Digunakan: {{ count($used_colors) }}</div>
                                @if(count($mapel_colors) > count(array_unique(array_values($mapel_colors))))
                                    <div class="text-red-600">⚠️ Ada {{ count($mapel_colors) - count(array_unique(array_values($mapel_colors))) }} collision terdeteksi</div>
                                @else
                                    <div class="text-green-600">✅ Tidak ada collision warna</div>
                                @endif
                            </div>
                        </details>
                    @endif
                </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <i class="fas fa-chalkboard text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Kelas</h3>
                <p class="text-gray-500 mb-4">Silakan tambahkan kelas terlebih dahulu untuk mengelola jadwal pelajaran.</p>
                <a href="{{ route('admin.kelas.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Kelola Kelas
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="jadwalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold" id="modalTitle">Tambah Jadwal Pelajaran</h3>
                <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Error Alert -->
            <div id="errorDiv" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline" id="errorMessage"></span>
            </div>
            
            <form id="jadwalForm" onsubmit="submitJadwal(event)">
                @csrf
                <input type="hidden" id="jadwalId" name="jadwal_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelas *</label>
                        <select id="kelas_id" name="kelas_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas_list as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran *</label>
                        <select id="mapel_id" name="mapel_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Mata Pelajaran</option>
                            @foreach($mapel_list as $mapel)
                                <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guru *</label>
                        <select id="guru_id" name="guru_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Guru</option>
                            @foreach($guru_list as $guru)
                                <option value="{{ $guru->id }}">{{ $guru->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hari *</label>
                        <select id="hari" name="hari" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Hari</option>
                            @foreach($hari_list as $hari)
                                <option value="{{ $hari }}">{{ $hari }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jam Ke *</label>
                        <select id="jam_ke" name="jam_ke" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Pilih Jam Ke</option>
                            @php
                                // Generate jam_ke options based on ACTUAL settings jadwal
                                $jamKeOptions = [];
                                if($settingsJadwal->isNotEmpty()) {
                                    $daySettings = $settingsJadwal->first();
                                    $numPeriods = $daySettings->jumlah_jam_pelajaran ?? 10;
                                    $periodDuration = (int)($daySettings->durasi_per_jam ?? 35);
                                    
                                    // Start time from settings
                                    $startTime = \Carbon\Carbon::parse($daySettings->jam_mulai ?? '07:55');
                                    
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
                                    
                                    // Generate time slots exactly like the main schedule
                                    for ($i = 1; $i <= $numPeriods; $i++) {
                                        $periodStart = clone $startTime;
                                        $periodEnd = (clone $startTime)->addMinutes($periodDuration);
                                        
                                        // Check for breaks and adjust timing
                                        $breakAdjusted = false;
                                        foreach ($breaks as $break) {
                                            // If this period starts exactly when a break should start
                                            if ($periodStart->format('H:i') === $break['start']->format('H:i')) {
                                                // Move period start to after the break
                                                $periodStart = clone $break['end'];
                                                $periodEnd = (clone $periodStart)->addMinutes($periodDuration);
                                                $breakAdjusted = true;
                                                break;
                                            }
                                            // If break starts during this period
                                            else if ($periodStart->format('H:i') < $break['start']->format('H:i') && 
                                                    $periodEnd->format('H:i') > $break['start']->format('H:i')) {
                                                // End this period at break start
                                                $periodEnd = clone $break['start'];
                                                $breakAdjusted = true;
                                                break;
                                            }
                                        }
                                        
                                        // Store the time slot
                                        $timeRange = $periodStart->format('H:i') . ' - ' . $periodEnd->format('H:i');
                                        $jamKeOptions[$i] = $timeRange;
                                        
                                        // Move to next period
                                        $startTime = clone $periodEnd;
                                        
                                        // If we hit a break, skip over it
                                        if ($breakAdjusted) {
                                            foreach ($breaks as $break) {
                                                if ($startTime->format('H:i') === $break['start']->format('H:i')) {
                                                    $startTime = clone $break['end'];
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    // Fallback to hardcoded if no settings
                                    $jamKeOptions = [
                                        1 => '07:55 - 08:30',
                                        2 => '08:30 - 09:05', 
                                        3 => '09:20 - 09:55',
                                        4 => '09:55 - 10:30',
                                        5 => '10:45 - 11:20',
                                        6 => '11:20 - 11:55',
                                        7 => '12:10 - 12:45',
                                        8 => '12:45 - 13:20',
                                        9 => '13:35 - 14:10',
                                        10 => '14:10 - 14:45'
                                    ];
                                }
                            @endphp
                            
                            @foreach($jamKeOptions as $jamKe => $timeRange)
                                <option value="{{ $jamKe }}">Jam ke-{{ $jamKe }} ({{ $timeRange }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jam</label>
                        <select id="jumlah_jam" name="jumlah_jam" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @php
                                // Get max periods from actual settings
                                $maxPeriods = 5; // Default max
                                if($settingsJadwal->isNotEmpty()) {
                                    $daySettings = $settingsJadwal->first();
                                    $numPeriods = $daySettings->jumlah_jam_pelajaran ?? 10;
                                    $maxPeriods = min(5, $numPeriods); // Limit to reasonable maximum
                                }
                            @endphp
                            
                            @for($i = 1; $i <= $maxPeriods; $i++)
                                <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'Jam Pelajaran' : 'Jam Pelajaran' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Semester *</label>
                        <select id="semester" name="semester" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @foreach($semester_list as $semester)
                                <option value="{{ $semester }}" {{ $semester == $filter_semester ? 'selected' : '' }}>{{ $semester }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran *</label>
                        <select id="tahun_ajaran" name="tahun_ajaran" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            @foreach($tahun_ajaran_list as $tahun)
                                <option value="{{ $tahun }}" {{ $tahun == $filter_tahun_ajaran ? 'selected' : '' }}>{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"></textarea>
                </div>
                
                <div class="col-span-1 md:col-span-2 mb-4">
                    <div class="flex items-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" checked>
                        <label for="is_active" class="ml-2 block text-sm font-medium text-gray-700">
                            Jadwal Aktif
                        </label>
                    </div>
                </div>

                <div class="col-span-1 md:col-span-2 flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <span id="submitText">Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Jadwal Modal -->
<div id="importJadwalModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-import text-green-600 mr-2"></i>
                Import Jadwal Pelajaran
            </h3>
            <button type="button" 
                    onclick="closeImportModal()" 
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <!-- Import Form -->
            <form id="importJadwalForm" action="{{ route('admin.jadwal.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- File Upload Section -->
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-lg font-medium text-gray-700">Upload File Jadwal</p>
                            <p class="text-sm text-gray-500">Pilih file Excel (.xlsx) atau CSV (.csv)</p>
                        </div>
                        
                        <input type="file" 
                               id="jadwalFile" 
                               name="file" 
                               accept=".xlsx,.csv" 
                               class="hidden" 
                               onchange="handleJadwalFileSelect(this)"
                               required>
                        
                        <button type="button" 
                                onclick="document.getElementById('jadwalFile').click()" 
                                class="bg-green-100 hover:bg-green-200 text-green-700 px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-folder-open mr-2"></i>Pilih File
                        </button>
                        
                        <!-- File Info -->
                        <div id="jadwalFileInfo" class="hidden mt-4 p-3 bg-gray-50 rounded-lg text-left">
                            <div id="jadwalFileDetails" class="text-sm"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Options -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-800 mb-3">Pengaturan Import</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="replaceExisting" 
                                   name="replace_existing" 
                                   value="1" 
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                            <label for="replaceExisting" class="ml-2 text-sm text-gray-700">
                                Ganti jadwal yang sudah ada
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="validateOnly" 
                                   name="validate_only" 
                                   value="1" 
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="validateOnly" class="ml-2 text-sm text-gray-700">
                                Validasi saja (tidak menyimpan data)
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Format Info -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                    <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Format File Import
                    </h5>
                    <div class="text-sm text-blue-700 space-y-1">
                        <p><strong>Kolom yang diperlukan:</strong></p>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li>hari (Senin, Selasa, Rabu, Kamis, Jumat, Sabtu)</li>
                            <li>jam_mulai (format: HH:mm, contoh: 07:30)</li>
                            <li>jam_selesai (format: HH:mm, contoh: 09:00)</li>
                            <li>kelas (contoh: X RPL 1, XI TKJ 2)</li>
                            <li>mata_pelajaran (nama mata pelajaran)</li>
                            <li>guru (nama guru pengajar)</li>
                            <li>ruangan (nama ruangan, opsional)</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Template Download -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-6">
                    <h5 class="font-medium text-green-800 mb-2 flex items-center">
                        <i class="fas fa-download mr-2"></i>Download Template
                    </h5>
                    <p class="text-sm text-green-700 mb-3">
                        Gunakan template ini untuk memastikan format data yang benar
                    </p>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.jadwal.template', ['format' => 'excel']) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm transition">
                            <i class="fas fa-file-excel mr-1"></i>Template Excel
                        </a>
                        <a href="{{ route('admin.jadwal.template', ['format' => 'csv']) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm transition">
                            <i class="fas fa-file-csv mr-1"></i>Template CSV
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
            <div class="text-sm text-gray-600">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                <strong>Peringatan:</strong> Pastikan data sudah benar sebelum import
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="closeImportModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="button" 
                        onclick="submitImportForm()" 
                        id="importSubmitBtn"
                        disabled
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Batch Delete Modal -->
<div id="batchDeleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-trash-alt text-red-600 mr-2"></i>Hapus Jadwal Berdasarkan Hari
            </h3>
            <button type="button" 
                    onclick="closeBatchDeleteModal()" 
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-4">
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            <strong>Peringatan:</strong> Fitur ini akan menghapus SEMUA jadwal pada hari yang dipilih. 
                            Aksi ini tidak dapat dibatalkan!
                        </p>
                    </div>
                </div>
            </div>

            <form id="batchDeleteForm">
                <div class="space-y-4">
                    <!-- Day Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-day mr-1"></i>Pilih Hari
                        </label>
                        <select id="batch_hari" name="hari" required 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                    </div>
                    
                    <!-- Class Filter (Optional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-school mr-1"></i>Kelas (Opsional)
                        </label>
                        <select id="batch_kelas_id" name="kelas_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelas_list as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menghapus jadwal semua kelas</p>
                    </div>
                    
                    <!-- Semester Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>Semester
                        </label>
                        <select id="batch_semester" name="semester" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">-- Semua Semester --</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                    
                    <!-- Academic Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-graduation-cap mr-1"></i>Tahun Ajaran
                        </label>
                        <input type="text" id="batch_tahun_ajaran" name="tahun_ajaran" 
                               placeholder="Contoh: 2024/2025"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan untuk menghapus semua tahun ajaran</p>
                    </div>
                    
                    <!-- Preview Section -->
                    <div id="deletePreview" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-medium text-gray-900 mb-2">
                            <i class="fas fa-eye mr-1"></i>Preview Jadwal yang akan dihapus:
                        </h4>
                        <div id="previewContent" class="text-sm text-gray-600">
                            <!-- Preview content will be loaded here -->
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
            <button type="button" onclick="loadDeletePreview()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-search mr-1"></i>Preview
            </button>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="closeBatchDeleteModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="button" 
                        onclick="confirmBatchDelete()" 
                        id="batchDeleteBtn"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-trash-alt mr-1"></i>
                    <span id="batchDeleteText">Hapus Jadwal</span>
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Global variables
let isEditMode = false;
let isUpdatingSelections = false; // Flag to prevent infinite loop

// Initialize event listeners when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Create Jadwal button
    const btnCreateJadwal = document.getElementById('btnCreateJadwal');
    if (btnCreateJadwal) {
        btnCreateJadwal.addEventListener('click', function() {
            // Get current filter values
            const currentKelasId = '{{ request("kelas_id") ?? "" }}';
            const currentHari = '{{ $selectedHari ?? "" }}';
            
            // Call openCreateModal with preselected values
            openCreateModal(currentKelasId || null, currentHari || null);
        });
    }
    
    // Add event listeners for jam_ke and jumlah_jam validation
    const jamKeSelect = document.getElementById('jam_ke');
    const jumlahJamSelect = document.getElementById('jumlah_jam');
    
    if (jamKeSelect && jumlahJamSelect) {
        jamKeSelect.addEventListener('change', updateJumlahJamOptions);
        jumlahJamSelect.addEventListener('change', validateJamRange);
    }

    // Add event listeners for dynamic filtering
    const mapelSelect = document.getElementById('mapel_id');
    const guruSelect = document.getElementById('guru_id');
    const kelasSelect = document.getElementById('kelas_id');
    
    if (mapelSelect) {
        mapelSelect.addEventListener('change', onMapelChange);
    }
    
    if (guruSelect) {
        guruSelect.addEventListener('change', onGuruChange);
    }

    if (kelasSelect) {
        kelasSelect.addEventListener('change', onKelasChange);
    }
});

// Function to open create modal
// Function to open create modal, with optional preselected kelas and hari
function openCreateModal(preselectedKelas = null, preselectedHari = null) {
    console.log('openCreateModal called with:', {preselectedKelas, preselectedHari});
    
    // Test endpoint first
    testEndpoint();

    isEditMode = false;

    // Reset form
    const form = document.getElementById('jadwalForm');
    if (form) form.reset();

    // Clear jadwal ID to indicate this is a new entry
    const jadwalIdInput = document.getElementById('jadwalId');
    if (jadwalIdInput) jadwalIdInput.value = '';

    // Hide error message if any
    const errorDiv = document.getElementById('errorDiv');
    if (errorDiv) errorDiv.classList.add('hidden');

    // Set default values
    const semesterSelect = document.getElementById('semester');
    if (semesterSelect) semesterSelect.value = '{{ $filter_semester ?? 1 }}';

    const tahunInput = document.getElementById('tahun_ajaran');
    if (tahunInput) tahunInput.value = '{{ $filter_tahun_ajaran ?? date("Y")."/".(date("Y")+1) }}';

    // Set active checkbox
    const isActiveCheckbox = document.getElementById('is_active');
    if (isActiveCheckbox) isActiveCheckbox.checked = true;

    // Update modal title
    const modalTitle = document.getElementById('modalTitle');
    if (modalTitle) modalTitle.textContent = 'Tambah Jadwal Pelajaran';

    // Reset select boxes to show all options
    loadAllMapel();
    loadAllGurus();

    // Show modal first
    const modal = document.getElementById('jadwalModal');
    if (modal) modal.classList.remove('hidden');

    // Set kelas dan hari jika ada preselected
    setTimeout(() => {
        console.log('Setting preselected values after timeout');
        if (preselectedKelas) {
            const kelasSelect = document.getElementById('kelas_id');
            console.log('Found kelasSelect:', kelasSelect);
            if (kelasSelect) {
                console.log('Setting kelas value to:', preselectedKelas);
                kelasSelect.value = preselectedKelas;
                // Trigger event agar filter guru/mapel ikut berubah
                kelasSelect.dispatchEvent(new Event('change'));
            }
        }
        if (preselectedHari) {
            const hariSelect = document.getElementById('hari');
            console.log('Found hariSelect:', hariSelect);
            if (hariSelect) {
                console.log('Setting hari value to:', preselectedHari);
                hariSelect.value = preselectedHari;
            }
        }
    }, 200);
}

// Test function for debugging
function testEndpoint() {
    console.log('Testing endpoint...');
    
    fetch('/admin/jadwal/test', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({test: 'data'})
    })
    .then(response => {
        console.log('Test response status:', response.status);
        console.log('Test response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Test response data:', data);
    })
    .catch(error => {
        console.error('Test error:', error);
    });
}

// Function to update jumlah jam options based on selected jam_ke
function updateJumlahJamOptions() {
    const jamKeSelect = document.getElementById('jam_ke');
    const jumlahJamSelect = document.getElementById('jumlah_jam');
    
    if (!jamKeSelect || !jumlahJamSelect) return;
    
    const selectedJamKe = parseInt(jamKeSelect.value);
    if (!selectedJamKe) return;
    
    // Get max periods from actual settings
    const maxPeriods = {{ $settingsJadwal->isNotEmpty() ? ($settingsJadwal->first()->jumlah_jam_pelajaran ?? 10) : 10 }};
    
    // Calculate maximum jam berturut-turut available
    const maxJumlahJam = Math.min(5, maxPeriods - selectedJamKe + 1);
    
    // Update options
    jumlahJamSelect.innerHTML = '';
    for (let i = 1; i <= maxJumlahJam; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i + (i === 1 ? ' Jam' : ' Jam Berturut-turut');
        if (i === 1) option.selected = true;
        jumlahJamSelect.appendChild(option);
    }
}

// Function to validate jam range
function validateJamRange() {
    const jamKeSelect = document.getElementById('jam_ke');
    const jumlahJamSelect = document.getElementById('jumlah_jam');
    const errorDiv = document.getElementById('errorDiv');
    const errorMessage = document.getElementById('errorMessage');
    
    if (!jamKeSelect || !jumlahJamSelect) return;
    
    const selectedJamKe = parseInt(jamKeSelect.value);
    const selectedJumlahJam = parseInt(jumlahJamSelect.value);
    
    if (!selectedJamKe || !selectedJumlahJam) return;
    
    // Get max periods from actual settings
    const maxPeriods = {{ $settingsJadwal->isNotEmpty() ? ($settingsJadwal->first()->jumlah_jam_pelajaran ?? 10) : 10 }};
    
    // Check if selection exceeds limit
    if (selectedJamKe + selectedJumlahJam - 1 > maxPeriods) {
        if (errorDiv && errorMessage) {
            errorMessage.textContent = `Tidak dapat membuat ${selectedJumlahJam} jam berturut-turut mulai dari jam ke-${selectedJamKe}. Jam maksimal adalah jam ke-${maxPeriods}.`;
            errorDiv.classList.remove('hidden');
        }
        
        // Reset jumlah jam to valid value
        const maxValidJumlahJam = maxPeriods - selectedJamKe + 1;
        jumlahJamSelect.value = Math.max(1, maxValidJumlahJam);
    } else {
        // Hide error if valid
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }
    }
}

// Function for handling mata pelajaran selection change
async function onMapelChange() {
    if (isUpdatingSelections) return; // Prevent infinite loop
    
    const mapelSelect = document.getElementById('mapel_id');
    const guruSelect = document.getElementById('guru_id');
    const kelasSelect = document.getElementById('kelas_id');
    
    if (!mapelSelect || !guruSelect) return;
    
    const mapelId = mapelSelect.value;
    const kelasId = kelasSelect ? kelasSelect.value : null;
    const currentGuruId = guruSelect.value; // Save current selection
    
    if (!mapelId) {
        // If no mapel selected, restore all gurus
        isUpdatingSelections = true;
        await loadAllGurus();
        // Restore guru selection if it was previously selected
        if (currentGuruId) {
            guruSelect.value = currentGuruId;
        }
        isUpdatingSelections = false;
        return;
    }
    
    try {
        isUpdatingSelections = true;
        
        // Show loading state
        const originalOptions = guruSelect.innerHTML;
        guruSelect.innerHTML = '<option value="">Loading...</option>';
        guruSelect.disabled = true;
        
        // Build query parameters
        const params = new URLSearchParams({
            mapel_id: mapelId
        });
        
        if (kelasId) {
            params.append('kelas_id', kelasId);
        }
        
        const response = await fetch(`/admin/jadwal/guru-by-mapel?${params}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Populate guru options
            guruSelect.innerHTML = '<option value="">Pilih Guru</option>';
            
            let foundCurrentGuru = false;
            data.data.forEach(guru => {
                const option = document.createElement('option');
                option.value = guru.id;
                option.textContent = `${guru.nama} ${guru.nip ? '(' + guru.nip + ')' : ''}`;
                guruSelect.appendChild(option);
                
                // Check if current selected guru is still available
                if (guru.id == currentGuruId) {
                    foundCurrentGuru = true;
                }
            });
            
            // Restore guru selection if it's still available
            if (foundCurrentGuru && currentGuruId) {
                guruSelect.value = currentGuruId;
            }
            
            if (data.data.length === 0) {
                guruSelect.innerHTML = '<option value="">Tidak ada guru tersedia untuk mata pelajaran ini</option>';
            }
        } else {
            console.error('Error loading guru:', data.message);
            guruSelect.innerHTML = originalOptions; // Restore original options on error
        }
    } catch (error) {
        console.error('Error fetching guru by mapel:', error);
        await loadAllGurus(); // Restore all gurus on error
        if (currentGuruId) {
            guruSelect.value = currentGuruId;
        }
    } finally {
        guruSelect.disabled = false;
        isUpdatingSelections = false;
    }
}

// Function for handling guru selection change
async function onGuruChange() {
    if (isUpdatingSelections) return; // Prevent infinite loop
    
    const guruSelect = document.getElementById('guru_id');
    const mapelSelect = document.getElementById('mapel_id');
    const kelasSelect = document.getElementById('kelas_id');
    
    if (!guruSelect || !mapelSelect) return;
    
    const guruId = guruSelect.value;
    const kelasId = kelasSelect ? kelasSelect.value : null;
    const currentMapelId = mapelSelect.value; // Save current selection
    
    if (!guruId) {
        // If no guru selected, restore all mapel
        isUpdatingSelections = true;
        await loadAllMapel();
        // Restore mapel selection if it was previously selected
        if (currentMapelId) {
            mapelSelect.value = currentMapelId;
        }
        isUpdatingSelections = false;
        return;
    }
    
    try {
        isUpdatingSelections = true;
        
        // Show loading state
        const originalOptions = mapelSelect.innerHTML;
        mapelSelect.innerHTML = '<option value="">Loading...</option>';
        mapelSelect.disabled = true;
        
        // Build query parameters
        const params = new URLSearchParams({
            guru_id: guruId
        });
        
        if (kelasId) {
            params.append('kelas_id', kelasId);
        }
        
        const response = await fetch(`/admin/jadwal/mapel-by-guru?${params}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Populate mapel options
            mapelSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
            
            let foundCurrentMapel = false;
            data.data.forEach(mapel => {
                const option = document.createElement('option');
                option.value = mapel.id;
                option.textContent = `${mapel.nama} ${mapel.kode ? '(' + mapel.kode + ')' : ''}`;
                mapelSelect.appendChild(option);
                
                // Check if current selected mapel is still available
                if (mapel.id == currentMapelId) {
                    foundCurrentMapel = true;
                }
            });
            
            // Restore mapel selection if it's still available
            if (foundCurrentMapel && currentMapelId) {
                mapelSelect.value = currentMapelId;
            }
            
            if (data.data.length === 0) {
                mapelSelect.innerHTML = '<option value="">Tidak ada mata pelajaran tersedia untuk guru ini</option>';
            }
        } else {
            console.error('Error loading mapel:', data.message);
            mapelSelect.innerHTML = originalOptions; // Restore original options on error
        }
    } catch (error) {
        console.error('Error fetching mapel by guru:', error);
        await loadAllMapel(); // Restore all mapel on error
        if (currentMapelId) {
            mapelSelect.value = currentMapelId;
        }
    } finally {
        mapelSelect.disabled = false;
        isUpdatingSelections = false;
    }
}

// Helper function to load all guru options
async function loadAllGurus() {
    const guruSelect = document.getElementById('guru_id');
    if (!guruSelect) return;
    
    // Reset to original options from server-side data
    guruSelect.innerHTML = '<option value="">Pilih Guru</option>';
    
    @foreach($guru_list as $guru)
        const option{{ $guru->id }} = document.createElement('option');
        option{{ $guru->id }}.value = '{{ $guru->id }}';
        option{{ $guru->id }}.textContent = '{{ $guru->nama }}';
        guruSelect.appendChild(option{{ $guru->id }});
    @endforeach
}

// Helper function to load all mapel options
async function loadAllMapel() {
    const mapelSelect = document.getElementById('mapel_id');
    if (!mapelSelect) return;
    
    // Reset to original options from server-side data
    mapelSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
    
    @foreach($mapel_list as $mapel)
        const option{{ $mapel->id }} = document.createElement('option');
        option{{ $mapel->id }}.value = '{{ $mapel->id }}';
        option{{ $mapel->id }}.textContent = '{{ $mapel->nama }}';
        mapelSelect.appendChild(option{{ $mapel->id }});
    @endforeach
}

// Function for handling kelas selection change
async function onKelasChange() {
    if (isUpdatingSelections) return;
    
    const kelasSelect = document.getElementById('kelas_id');
    const mapelSelect = document.getElementById('mapel_id');
    const guruSelect = document.getElementById('guru_id');
    
    if (!kelasSelect || !mapelSelect || !guruSelect) return;
    
    const kelasId = kelasSelect.value;
    
    if (!kelasId) {
        // If no kelas selected, restore all options
        await loadAllMapel();
        await loadAllGurus();
        return;
    }
    
    try {
        isUpdatingSelections = true;
        
        // Show loading state for both selects
        mapelSelect.innerHTML = '<option value="">Loading...</option>';
        guruSelect.innerHTML = '<option value="">Loading...</option>';
        mapelSelect.disabled = true;
        guruSelect.disabled = true;
        
        const response = await fetch(`/admin/jadwal/data-by-kelas?kelas_id=${kelasId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Store current selections
            const currentMapelId = mapelSelect.value;
            const currentGuruId = guruSelect.value;
            
            // Populate mapel options
            mapelSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
            data.data.mapel.forEach(mapel => {
                const option = document.createElement('option');
                option.value = mapel.id;
                option.textContent = `${mapel.nama} ${mapel.kode ? '(' + mapel.kode + ')' : ''}`;
                option.selected = mapel.id == currentMapelId;
                mapelSelect.appendChild(option);
            });
            
            // Populate guru options
            guruSelect.innerHTML = '<option value="">Pilih Guru</option>';
            data.data.guru.forEach(guru => {
                const option = document.createElement('option');
                option.value = guru.id;
                option.textContent = `${guru.nama} ${guru.nip ? '(' + guru.nip + ')' : ''}`;
                option.selected = guru.id == currentGuruId;
                guruSelect.appendChild(option);
            });
            
            // Show message if no data available
            if (data.data.mapel.length === 0) {
                mapelSelect.innerHTML = '<option value="">Tidak ada mata pelajaran tersedia untuk kelas ini</option>';
            }
            
            if (data.data.guru.length === 0) {
                guruSelect.innerHTML = '<option value="">Tidak ada guru tersedia untuk kelas ini</option>';
            }
        } else {
            console.error('Error loading data by kelas:', data.message);
            mapelSelect.innerHTML = '<option value="">Error loading data</option>';
            guruSelect.innerHTML = '<option value="">Error loading data</option>';
        }
    } catch (error) {
        console.error('Error fetching data by kelas:', error);
        mapelSelect.innerHTML = '<option value="">Error loading data</option>';
        guruSelect.innerHTML = '<option value="">Error loading data</option>';
    } finally {
        mapelSelect.disabled = false;
        guruSelect.disabled = false;
        isUpdatingSelections = false;
    }
}

// Function to close modal
function closeModal() {
    const modal = document.getElementById('jadwalModal');
    if (modal) modal.classList.add('hidden');
    
    // Reset form
    const form = document.getElementById('jadwalForm');
    if (form) form.reset();
    
    // Reset select boxes to show all options
    loadAllMapel();
    loadAllGurus();
    
    isEditMode = false;
}

// Function to submit jadwal form
function submitJadwal(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const jadwalId = document.getElementById('jadwalId').value;
    const jumlahJam = parseInt(document.getElementById('jumlah_jam').value) || 1;
    const jamKe = parseInt(document.getElementById('jam_ke').value);
    
    // Validasi jika jam_ke + jumlah_jam melebihi batas maksimal dari settings
    const maxPeriods = {{ $settingsJadwal->isNotEmpty() ? ($settingsJadwal->first()->jumlah_jam_pelajaran ?? 10) : 10 }};
    
    if (jamKe + jumlahJam - 1 > maxPeriods) {
        const errorDiv = document.getElementById('errorDiv');
        if (errorDiv) {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.textContent = `Tidak dapat membuat ${jumlahJam} jam berturut-turut mulai dari jam ke-${jamKe}. Jam maksimal adalah jam ke-${maxPeriods}.`;
            }
            errorDiv.classList.remove('hidden');
        }
        return;
    }
    
    let url = '{{ route("admin.jadwal.store") }}';
    let method = 'POST';
    
    if (isEditMode && jadwalId) {
        url = `/admin/jadwal/${jadwalId}`;
        method = 'PUT';
        formData.append('_method', 'PUT');
    }
    
    // Tambahkan jumlah_jam ke formData
    formData.append('jumlah_jam', jumlahJam);
    
    // Debug: Log form data
    console.log('Submitting jadwal form:');
    console.log('URL:', url);
    console.log('Method:', method);
    console.log('Jumlah jam:', jumlahJam);
    console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Hide previous errors
    const errorDiv = document.getElementById('errorDiv');
    if (errorDiv) errorDiv.classList.add('hidden');
    
    // Show loading state
    const submitBtn = document.querySelector('#jadwalForm button[type="submit"]');
    const submitText = document.getElementById('submitText');
    if (submitBtn && submitText) {
        submitBtn.disabled = true;
        submitText.textContent = 'Menyimpan...';
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            console.error('Response is not JSON:', contentType);
            return response.text().then(text => {
                console.error('Response text:', text);
                throw new Error('Server returned non-JSON response: ' + text.substring(0, 100));
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            closeModal();
            location.reload(); // Reload to show updated schedule
        } else {
            // Log validation errors for debugging
            if (data.errors) {
                console.log('Validation errors:', data.errors);
                Object.keys(data.errors).forEach(field => {
                    console.log(`Field ${field}:`, data.errors[field]);
                });
            }
            
            // Show error message
            if (errorDiv) {
                const errorMessage = document.getElementById('errorMessage');
                if (errorMessage) {
                    let errorText = data.message || 'Terjadi kesalahan';
                    if (data.errors) {
                        // Add first validation error to message
                        const firstError = Object.values(data.errors)[0];
                        if (firstError && firstError[0]) {
                            errorText += ': ' + firstError[0];
                        }
                    }
                    errorMessage.textContent = errorText;
                }
                errorDiv.classList.remove('hidden');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (errorDiv) {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.textContent = 'Terjadi kesalahan jaringan';
            }
            errorDiv.classList.remove('hidden');
        }
    })
    .finally(() => {
        // Reset loading state
        if (submitBtn && submitText) {
            submitBtn.disabled = false;
            submitText.textContent = isEditMode ? 'Update' : 'Simpan';
        }
    });
}

// Function to edit jadwal
function editJadwal(jadwalId) {
    isEditMode = true;
    
    // Fetch jadwal data
    fetch(`/admin/jadwal/${jadwalId}/edit`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const jadwal = data.jadwal;
                
                // Populate form
                document.getElementById('jadwalId').value = jadwal.id;
                document.getElementById('kelas_id').value = jadwal.kelas_id;
                document.getElementById('mapel_id').value = jadwal.mapel_id;
                document.getElementById('guru_id').value = jadwal.guru_id;
                document.getElementById('hari').value = jadwal.hari;
                document.getElementById('jam_ke').value = jadwal.jam_ke || '';
                document.getElementById('semester').value = jadwal.semester;
                document.getElementById('tahun_ajaran').value = jadwal.tahun_ajaran;
                document.getElementById('keterangan').value = jadwal.keterangan || '';
                document.getElementById('is_active').checked = jadwal.is_active == 1 || jadwal.is_active === true;
                
                // Update modal title
                document.getElementById('modalTitle').textContent = 'Edit Jadwal Pelajaran';
                
                // Show modal
                document.getElementById('jadwalModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data jadwal');
        });
}

// Function to delete jadwal
function deleteJadwal(jadwalId) {
    if (confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) {
        fetch(`/admin/jadwal/${jadwalId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Reload to show updated schedule
            } else {
                alert('Gagal menghapus jadwal: ' + (data.message || 'Terjadi kesalahan'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan jaringan');
        });
    }
}

// Function to print all schedules
function printAllSchedule() {
    window.print();
}

// Function to export schedule to Excel (placeholder)
function exportScheduleToExcel() {
    alert('Fitur export Excel akan segera tersedia!');
}

// Function to add jadwal for specific slot
function addJadwalForSlot(hari, jamKey) {
    // Check if this is a break time slot
    const timeSlotData = document.querySelector(`[data-time-slot="${jamKey}"]`);
    if (timeSlotData && timeSlotData.dataset.isBreak === 'true') {
        alert('Tidak dapat menambahkan jadwal pada jam istirahat.');
        return;
    }
    
    openCreateModal();
    
    setTimeout(() => {
        const hariSelect = document.getElementById('hari');
        if (hariSelect) hariSelect.value = hari;
        
        const timeParts = jamKey.split(' - ');
        if (timeParts.length === 2) {
            const jamMulaiInput = document.getElementById('jam_mulai');
            const jamSelesaiInput = document.getElementById('jam_selesai');
            const jamKeInput = document.getElementById('jam_ke');
            
            if (jamMulaiInput) jamMulaiInput.value = timeParts[0];
            if (jamSelesaiInput) jamSelesaiInput.value = timeParts[1];
            
            // Try to find the jam_ke from time slot data
            if (timeSlotData && jamKeInput) {
                const jamKe = timeSlotData.dataset.jamKe;
                if (jamKe) jamKeInput.value = jamKe;
            }
        }
    }, 100);
}

// Import Modal Functions
function openImportModal() {
    const modal = document.getElementById('importJadwalModal');
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeImportModal() {
    const modal = document.getElementById('importJadwalModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    
    // Reset form
    const form = document.getElementById('importJadwalForm');
    if (form) form.reset();
    
    // Hide file info
    const fileInfo = document.getElementById('jadwalFileInfo');
    if (fileInfo) fileInfo.classList.add('hidden');
    
    // Reset button
    const submitBtn = document.getElementById('importSubmitBtn');
    const submitText = document.getElementById('importSubmitText');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
    }
    if (submitText) submitText.textContent = 'Import Jadwal';
}

function handleJadwalFileSelect(input) {
    const fileInfo = document.getElementById('jadwalFileInfo');
    const fileDetails = document.getElementById('jadwalFileDetails');
    const submitBtn = document.getElementById('importSubmitBtn');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / (1024 * 1024)).toFixed(2);
        const fileExtension = fileName.split('.').pop().toLowerCase();
        
        // Validate file type
        const allowedTypes = ['xlsx', 'csv'];
        if (!allowedTypes.includes(fileExtension)) {
            alert('Format file tidak didukung. Gunakan file .xlsx atau .csv');
            input.value = '';
            return;
        }
        
        // Validate file size (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB');
            input.value = '';
            return;
        }
        
        // Show file info
        fileDetails.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-file-${fileExtension === 'xlsx' ? 'excel text-green-600' : 'csv text-blue-600'} mr-2"></i>
                    <div>
                        <p class="font-medium text-gray-800">${fileName}</p>
                        <p class="text-xs text-gray-500">${fileSize} MB • ${fileExtension.toUpperCase()}</p>
                    </div>
                </div>
                <div class="text-green-600">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        `;
        fileInfo.classList.remove('hidden');
        
        // Enable submit button
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
        }
    } else {
        fileInfo.classList.add('hidden');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
        }
    }
}

function submitImportForm() {
    const form = document.getElementById('importJadwalForm');
    const submitBtn = document.getElementById('importSubmitBtn');
    const submitText = document.getElementById('importSubmitText');
    
    if (!form) return;
    
    // Validate file is selected
    const fileInput = document.getElementById('jadwalFile');
    if (!fileInput.files || !fileInput.files[0]) {
        alert('Silakan pilih file terlebih dahulu');
        return;
    }
    
    // Show loading state
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.classList.add('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
    }
    if (submitText) {
        submitText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
    }
    
    // Submit form
    form.submit();
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('importJadwalModal');
    if (event.target === modal) {
        closeImportModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('importJadwalModal');
        if (!modal.classList.contains('hidden')) {
            closeImportModal();
        }
    }
});

// Make functions globally available
window.openCreateModal = openCreateModal;
window.closeModal = closeModal;
window.submitJadwal = submitJadwal;
window.editJadwal = editJadwal;
window.deleteJadwal = deleteJadwal;
window.printAllSchedule = printAllSchedule;
window.exportScheduleToExcel = exportScheduleToExcel;
window.addJadwalForSlot = addJadwalForSlot;
window.openImportModal = openImportModal;
window.closeImportModal = closeImportModal;
window.handleJadwalFileSelect = handleJadwalFileSelect;
window.submitImportForm = submitImportForm;

// === BATCH DELETE FUNCTIONS ===

// Open batch delete modal
function openBatchDeleteModal() {
    document.getElementById('batchDeleteModal').classList.remove('hidden');
    // Reset form
    document.getElementById('batchDeleteForm').reset();
    document.getElementById('deletePreview').classList.add('hidden');
}

// Close batch delete modal
function closeBatchDeleteModal() {
    document.getElementById('batchDeleteModal').classList.add('hidden');
}

// Load preview of schedules to be deleted
async function loadDeletePreview() {
    const hari = document.getElementById('batch_hari').value;
    const kelasId = document.getElementById('batch_kelas_id').value;
    const semester = document.getElementById('batch_semester').value;
    const tahunAjaran = document.getElementById('batch_tahun_ajaran').value;
    
    if (!hari) {
        alert('Pilih hari terlebih dahulu');
        return;
    }
    
    try {
        // Build query parameters
        const params = new URLSearchParams();
        params.append('hari', hari);
        if (kelasId) params.append('kelas_id', kelasId);
        if (semester) params.append('semester', semester);
        if (tahunAjaran) params.append('tahun_ajaran', tahunAjaran);
        params.append('preview', 'true');
        
        const response = await fetch(`{{ route('admin.jadwal.get-schedule') }}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success && data.jadwal) {
            const previewDiv = document.getElementById('deletePreview');
            const contentDiv = document.getElementById('previewContent');
            
            if (data.jadwal.length === 0) {
                contentDiv.innerHTML = '<p class="text-gray-500 italic">Tidak ada jadwal yang ditemukan dengan kriteria tersebut.</p>';
            } else {
                let html = `<p class="font-medium mb-2">${data.jadwal.length} jadwal akan dihapus:</p><ul class="space-y-1">`;
                data.jadwal.forEach(jadwal => {
                    html += `
                        <li class="flex justify-between items-center p-2 bg-white rounded border">
                            <div>
                                <span class="font-medium">${jadwal.mapel ? jadwal.mapel.nama : 'N/A'}</span>
                                <span class="text-gray-600">- ${jadwal.kelas ? jadwal.kelas.nama_kelas : 'N/A'}</span>
                                <span class="text-sm text-gray-500">(${jadwal.jam_mulai} - ${jadwal.jam_selesai})</span>
                            </div>
                            <span class="text-sm text-gray-500">${jadwal.guru ? jadwal.guru.nama : 'N/A'}</span>
                        </li>
                    `;
                });
                html += '</ul>';
                contentDiv.innerHTML = html;
            }
            
            previewDiv.classList.remove('hidden');
        } else {
            alert('Gagal memuat preview: ' + (data.message || 'Terjadi kesalahan'));
        }
    } catch (error) {
        console.error('Error loading preview:', error);
        alert('Terjadi kesalahan saat memuat preview');
    }
}

// Confirm and execute batch delete
async function confirmBatchDelete() {
    const hari = document.getElementById('batch_hari').value;
    const kelasId = document.getElementById('batch_kelas_id').value;
    const semester = document.getElementById('batch_semester').value;
    const tahunAjaran = document.getElementById('batch_tahun_ajaran').value;
    
    if (!hari) {
        alert('Pilih hari terlebih dahulu');
        return;
    }
    
    // Double confirmation
    const confirmMessage = `Apakah Anda yakin ingin menghapus SEMUA jadwal pada hari ${hari}?` + 
        (kelasId ? ` untuk kelas yang dipilih` : '') + `\n\nAksi ini tidak dapat dibatalkan!`;
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    // Show loading state
    const deleteBtn = document.getElementById('batchDeleteBtn');
    const deleteText = document.getElementById('batchDeleteText');
    const originalText = deleteText.textContent;
    
    deleteBtn.disabled = true;
    deleteText.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Menghapus...';
    
    try {
        const formData = new FormData();
        formData.append('hari', hari);
        if (kelasId) formData.append('kelas_id', kelasId);
        if (semester) formData.append('semester', semester);
        if (tahunAjaran) formData.append('tahun_ajaran', tahunAjaran);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        const response = await fetch('{{ route("admin.jadwal.batch-delete-by-day") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Berhasil: ' + data.message);
            closeBatchDeleteModal();
            // Reload page to refresh the schedule table
            window.location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        console.error('Error in batch delete:', error);
        alert('Terjadi kesalahan saat menghapus jadwal');
    } finally {
        // Reset button state
        deleteBtn.disabled = false;
        deleteText.textContent = originalText;
    }
}

// Event listeners
document.getElementById('btnBatchDelete').addEventListener('click', openBatchDeleteModal);

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('batchDeleteModal');
    if (event.target === modal) {
        closeBatchDeleteModal();
    }
});

// Close modal with Escape key for batch delete modal
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('batchDeleteModal');
        if (!modal.classList.contains('hidden')) {
            closeBatchDeleteModal();
        }
    }
});

// Expose functions to global scope
window.openBatchDeleteModal = openBatchDeleteModal;
window.closeBatchDeleteModal = closeBatchDeleteModal;
window.loadDeletePreview = loadDeletePreview;
window.confirmBatchDelete = confirmBatchDelete;
</script>
@endpush
