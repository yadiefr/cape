@extends('layouts.admin')

@section('title', 'Manajemen Bel Sekolah')

@push('styles')
<link href="{{ asset('css/bel-management.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/direct-clock.js') }}?v={{ time() }}"></script>
@endpush

@section('main-content')
<div class="container-fluid px-4">

    <!-- Daily Bells (Every day) -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 relative overflow-hidden">
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 absolute inset-0 opacity-50"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-2">
                        <i class="fas fa-calendar-alt"></i>
                    </span>
                    Jadwal Bel Setiap Hari
                </h3>
                <div class="flex items-center space-x-3">
                    <div id="live-clock" class="bg-gradient-to-r from-gray-800 to-gray-700 text-white px-4 py-2 rounded-lg font-mono flex flex-col items-center shadow-md border border-gray-700">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-blue-400 animate-pulse"></i>
                            <span id="clock-time" class="tracking-wider text-xl">{{ date('H:i:s') }}</span>
                        </div>
                        <div class="text-xs text-gray-300 mt-1 text-center" id="current-day">{{ date('l') === 'Monday' ? 'Senin' : (date('l') === 'Tuesday' ? 'Selasa' : (date('l') === 'Wednesday' ? 'Rabu' : (date('l') === 'Thursday' ? 'Kamis' : (date('l') === 'Friday' ? 'Jumat' : (date('l') === 'Saturday' ? 'Sabtu' : 'Minggu'))))) }}</div>
                    </div>
                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">
                        {{ $dailyBels->count() }} jadwal
                    </span>
                </div>
            </div>
            
            @if ($dailyBels->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Nama Bel</th>
                            <th class="py-3 px-4 text-left">Waktu</th>
                            <th class="py-3 px-4 text-left">Tipe</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dailyBels as $bel)
                        <tr class="border-b hover:bg-gray-50" id="row-{{ $bel->id }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-{{ str_replace('#', '', $bel->kode_warna) }}-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-{{ $bel->ikon }} text-{{ str_replace('#', '', $bel->kode_warna) }}-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $bel->nama }}</p>
                                        @if ($bel->deskripsi)
                                        <p class="text-xs text-gray-500">{{ $bel->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ date('H:i', strtotime($bel->waktu)) }}</td>
                            <td class="py-3 px-4">
                                @if ($bel->tipe === 'reguler')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Reguler</span>
                                @elseif ($bel->tipe === 'istirahat')
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs">Istirahat</span>
                                @elseif ($bel->tipe === 'ujian')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">Ujian</span>
                                @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Khusus</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($bel->aktif)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aktif</span>
                                @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.bel.edit', $bel->id) }}" class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="toggleAktif({{ $bel->id }})" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200 transition-colors" id="toggle-btn-{{ $bel->id }}">
                                        @if ($bel->aktif)
                                        <i class="fas fa-toggle-on"></i>
                                        @else
                                        <i class="fas fa-toggle-off"></i>
                                        @endif
                                    </button>
                                    <button type="button" onclick="bunyikanBel({{ $bel->id }}, '{{ addslashes($bel->nama) }}')" class="bg-green-100 text-green-600 p-2 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    <button type="button" onclick="hapusBel({{ $bel->id }}, '{{ addslashes($bel->nama) }}')" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-6 bg-gray-50 rounded-lg">
                <i class="fas fa-bell-slash text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">Tidak ada jadwal bel untuk setiap hari.</p>
                <a href="{{ route('admin.bel.create') }}" class="mt-3 inline-block text-blue-600 hover:underline">Tambah jadwal bel</a>
            </div>
            @endif
        </div>
    </div>

    <!-- Bells By Day of Week -->
    @foreach ($daftarHari as $day => $dayName)
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 relative overflow-hidden">
        <div class="bg-gradient-to-br from-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-50 to-{{ $loop->index % 2 == 0 ? 'indigo' : 'blue' }}-50 absolute inset-0 opacity-50"></div>
        <div class="relative z-10">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <span class="bg-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-100 text-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-600 p-2 rounded-lg mr-2">
                        <i class="fas fa-calendar-day"></i>
                    </span>
                    Jadwal Bel Hari {{ $dayName }}
                </h3>
                <span class="bg-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-100 text-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-600 px-3 py-1 rounded-full text-sm">
                    {{ $groupedBels->get($day, collect())->count() }} jadwal
                </span>
            </div>
            
            @if ($groupedBels->get($day, collect())->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full rounded-lg overflow-hidden">
                    <thead class="bg-gradient-to-r from-{{ $loop->index % 2 == 0 ? 'purple' : 'cyan' }}-500 to-{{ $loop->index % 2 == 0 ? 'indigo' : 'blue' }}-600 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Nama Bel</th>
                            <th class="py-3 px-4 text-left">Waktu</th>
                            <th class="py-3 px-4 text-left">Tipe</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedBels->get($day, collect()) as $bel)
                        <tr class="border-b hover:bg-gray-50" id="row-{{ $bel->id }}">
                            <td class="py-3 px-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-lg bg-{{ str_replace('#', '', $bel->kode_warna) }}-100 flex items-center justify-center mr-3">
                                        <i class="fas fa-{{ $bel->ikon }} text-{{ str_replace('#', '', $bel->kode_warna) }}-500"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $bel->nama }}</p>
                                        @if ($bel->deskripsi)
                                        <p class="text-xs text-gray-500">{{ $bel->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-medium">{{ date('H:i', strtotime($bel->waktu)) }}</td>
                            <td class="py-3 px-4">
                                @if ($bel->tipe === 'reguler')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">Reguler</span>
                                @elseif ($bel->tipe === 'istirahat')
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs">Istirahat</span>
                                @elseif ($bel->tipe === 'ujian')
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs">Ujian</span>
                                @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Khusus</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                @if ($bel->aktif)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Aktif</span>
                                @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.bel.edit', $bel->id) }}" class="bg-amber-100 text-amber-600 p-2 rounded-lg hover:bg-amber-200 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" onclick="toggleAktif({{ $bel->id }})" class="bg-blue-100 text-blue-600 p-2 rounded-lg hover:bg-blue-200 transition-colors" id="toggle-btn-{{ $bel->id }}">
                                        @if ($bel->aktif)
                                        <i class="fas fa-toggle-on"></i>
                                        @else
                                        <i class="fas fa-toggle-off"></i>
                                        @endif
                                    </button>
                                    <button type="button" onclick="bunyikanBel({{ $bel->id }}, '{{ addslashes($bel->nama) }}')" class="bg-green-100 text-green-600 p-2 rounded-lg hover:bg-green-200 transition-colors">
                                        <i class="fas fa-bell"></i>
                                    </button>
                                    <button type="button" onclick="hapusBel({{ $bel->id }}, '{{ addslashes($bel->nama) }}')" class="bg-red-100 text-red-600 p-2 rounded-lg hover:bg-red-200 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-6 bg-gray-50 rounded-lg">
                <i class="fas fa-bell-slash text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-500">Tidak ada jadwal bel untuk hari {{ $dayName }}.</p>
                <a href="{{ route('admin.bel.create') }}" class="mt-3 inline-block text-blue-600 hover:underline">Tambah jadwal bel</a>
            </div>
            @endif
        </div>
    </div>
    @endforeach

</div>

<!-- Include JavaScript untuk AJAX functionality -->
<script src="{{ asset('js/bel-management.js') }}?v={{ time() }}"></script>

<script>
// Script untuk halaman admin bel dan otomatisasi bel
let belOtomatisAktif = true; // Default: aktif

// Fungsi untuk menampilkan jam waktu nyata (real-time)
function updateClock() {
    // console.log('Updating clock display'); // Debug removed
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    
    // Update waktu - pastikan elemen ada terlebih dahulu
    const clockElement = document.getElementById('clock-time');
    if (clockElement) {
        clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        // console.log('Clock updated to:', `${hours}:${minutes}:${seconds}`); // Debug removed
    } else {
        console.error('Clock element not found!');
    }
    
    // Update hari dalam bahasa Indonesia
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const dayName = dayNames[now.getDay()];
    const dayElement = document.getElementById('current-day');
    if (dayElement) {
        dayElement.textContent = dayName;
    }
    
    // Highlight baris bel yang akan berbunyi dalam waktu dekat (dalam 2 menit)
    const nextBellTime = `${hours}:${minutes}`;
    document.querySelectorAll('tr[id^="row-"]').forEach(row => {
        try {
            const belTimeCell = row.querySelector('td:nth-child(2)');
            const statusCell = row.querySelector('td:nth-child(4) span');
            
            if (belTimeCell && statusCell) {
                const belTime = belTimeCell.textContent.trim();
                const isActive = statusCell.textContent.trim() === 'Aktif';
                
                // Reset highlight dulu
                row.classList.remove('bg-yellow-50');
                
                // Highlight jika bel aktif dan waktunya sama dengan waktu sekarang (menit dan jam)
                if (isActive && belTime === nextBellTime) {
                    row.classList.add('bg-yellow-50');
                }
            }
        } catch (err) {
            console.error('Error highlighting bell row:', err);
        }
    });
}

// Menampilkan debug message untuk memudahkan troubleshooting
function showDebugMessage(message) {
    const debugElement = document.getElementById('debug-info');
    const debugMessageElement = document.getElementById('debug-message');
    
    if (debugElement && debugMessageElement) {
        debugMessageElement.textContent = message;
        debugElement.classList.remove('hidden');
        
        // Hide after 10 seconds
        setTimeout(() => {
            debugElement.classList.add('hidden');
        }, 10000);
    }
}

// Update jam setiap detik
const clockInterval = setInterval(updateClock, 1000);

// Jalankan sekali saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // console.log('DOM fully loaded - initializing clock'); // Debug removed
    showDebugMessage('Initializing clock and bell system...');
    updateClock();
    
    // Check if clock is updating
    setTimeout(() => {
        const clockElement = document.getElementById('clock-time');
        if (clockElement && clockElement.textContent === '00:00:00') {
            console.error('Clock not updating!');
            showDebugMessage('Error: Jam tidak berjalan. Coba refresh halaman.');
            
            // Try to force update
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const seconds = now.getSeconds().toString().padStart(2, '0');
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        } else {
            // console.log('Clock is updating correctly'); // Debug removed
            showDebugMessage('Jam berjalan dengan baik. Sistem bel aktif.');
        }
    }, 2000);
});

// Cek bel setiap menit pada detik 1 (untuk memastikan halaman telah dimuat)
setInterval(() => {
    const seconds = new Date().getSeconds();
    if (seconds === 1) {
        // console.log('Running scheduled bell check'); // Debug removed
        cekDanBunyikanBel();
    }
}, 1000);

// Jalankan cek bel pertama saat halaman dimuat (setelah 3 detik)
setTimeout(() => {
    // console.log('Running initial bell check'); // Debug removed
    cekDanBunyikanBel();
}, 3000);

// Fungsi untuk mengecek dan membunyikan bel otomatis
// FIXED: Menggunakan API yang sama dengan sistem global untuk konsistensi
async function cekDanBunyikanBel() {
    // Cek dulu apakah otomatisasi diaktifkan
    if (!belOtomatisAktif) {
        // console.log('[BEL SYSTEM LOCAL] Automatic bell is disabled'); // Debug removed
        return;
    }
    
    // console.log('[BEL SYSTEM LOCAL] Checking for bells via API...'); // Debug removed
    
    try {
        // Menggunakan API yang sama dengan sistem global
        const response = await fetch('/api/bel/check-current-time', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        // console.log('[BEL SYSTEM LOCAL] API Response:', data); // Debug removed
        
        if (data.shouldRing && data.bell) {
            // console.log('[BEL SYSTEM LOCAL] Bell should ring:', data.bell.nama); // Debug removed
            
            // Cek apakah bel ini sudah dibunyikan dalam menit yang sama (prevent duplicate)
            const now = new Date();
            const currentMinute = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
            const bellKey = `${data.bell.id}-${currentMinute}`;
            
            if (window.lastLocalBellPlayed === bellKey) {
                // console.log('[BEL SYSTEM LOCAL] Bell already played for this minute, skipping'); // Debug removed
                return;
            }
            
            window.lastLocalBellPlayed = bellKey;
            
            // Bunyikan bel menggunakan sistem yang ada
            const belId = data.bell.id;
            const namaBel = data.bell.nama;
            const tipeBel = data.bell.tipe || 'reguler';
            
            // console.log(`[BEL SYSTEM LOCAL] Ringing bell: ID=${belId}, Name="${namaBel}", Type=${tipeBel}`); // Debug removed
            
            // Gunakan function bunyikanBel yang sudah ada
            if (typeof bunyikanBel === 'function') {
                bunyikanBel(belId, namaBel, tipeBel, true); // skipConfirmation=true
            } else {
                console.warn('[BEL SYSTEM LOCAL] bunyikanBel function not found, using fallback');
                // Fallback beep
                playLocalBeep();
            }
        } else {
            // console.log('[BEL SYSTEM LOCAL] No bells to ring at this time'); // Debug removed
        }
        
    } catch (error) {
        console.error('[BEL SYSTEM LOCAL] Error checking bells:', error);
    }
}

// Fallback beep function
function playLocalBeep() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 1.0);
        
        // console.log('[BEL SYSTEM LOCAL] Fallback beep played'); // Debug removed
    } catch (error) {
        console.error('[BEL SYSTEM LOCAL] Fallback beep failed:', error);
    }
    
    // Dapatkan waktu sekarang dalam format jam:menit
    const now = new Date();
    const currentHours = now.getHours().toString().padStart(2, '0');
    const currentMinutes = now.getMinutes().toString().padStart(2, '0');
    const currentSeconds = now.getSeconds();
    const currentTime = `${currentHours}:${currentMinutes}`;
    
    // Hanya jalankan pada detik 0, 15, 30, 45 untuk menghindari spam
    if (![0, 15, 30, 45].includes(currentSeconds)) {
        return;
    }
    
    // Catat waktu pemeriksaan
    const lastBelCheckTime = new Date();
    // console.log(`[BEL CHECK] ${lastBelCheckTime.toLocaleTimeString()} - Checking for bells at ${currentTime}`); // Debug removed
    
    // Dapatkan hari sekarang (dalam bahasa Indonesia dan Inggris)
    const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const currentDay = days[now.getDay()];
    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const currentDayIndo = dayNames[now.getDay()];
    
    // Cek semua bel yang ditampilkan di halaman
    const rows = document.querySelectorAll('tr[id^="row-"]');
    // console.log(`[BEL CHECK] Found ${rows.length} bell rows to check on ${currentDayIndo}`); // Debug removed
    
    // Variabel untuk menandai apakah ada bel yang berbunyi
    let belFound = false;
    
    // Cek masing-masing baris bel
    rows.forEach(row => {
        try {
            // Ambil waktu bel dari cell kedua (indeks 1)
            const belTimeCell = row.querySelector('td:nth-child(2)');
            if (!belTimeCell) {
                console.warn('[BEL ERROR] Bell time cell not found for row:', row.id);
                return;
            }
            
            const belTime = belTimeCell.textContent.trim();
            
            // Ambil status aktif dari cell keempat (indeks 3)
            const statusCell = row.querySelector('td:nth-child(4) span');
            if (!statusCell) {
                console.warn('Status cell not found for row:', row.id);
                return;
            }
            
            const isActive = statusCell.textContent.trim() === 'Aktif';
            
            // Debug untuk setiap bel yang diperiksa
            // if (isActive) {
            //     console.log(`Active bell ${row.id}: Time=${belTime}, Current=${currentTime}, Match=${belTime === currentTime}`);
            // }
            
            // Hanya proses bel yang aktif dan waktunya cocok dengan waktu sekarang
            if (isActive && belTime === currentTime) {
                // console.log(`MATCH! Bell ${row.id} matches current time ${currentTime}`); // Debug removed
                
                // Cek apakah bel ini untuk hari ini atau setiap hari
                const tableContainer = row.closest('table').parentNode.parentNode;
                const headerElement = tableContainer.querySelector('h3');
                const headerText = headerElement ? headerElement.textContent.trim() : '';
                
                const isInDailyTable = headerText.includes('Setiap Hari');
                const isDaySpecific = headerText.includes(currentDayIndo);
                
                // console.log(`Is daily bell: ${isInDailyTable}, Is day-specific: ${isDaySpecific}, Header: "${headerText}"`); // Debug removed
                
                // Jika bel ini untuk hari ini atau setiap hari, bunyikan
                if (isInDailyTable || isDaySpecific) {
                    // Ambil ID bel dari ID baris
                    const belId = row.id.replace('row-', '');
                    
                    // Ambil nama bel dari cell pertama
                    const nameElement = row.querySelector('td:nth-child(1) p.font-medium');
                    if (!nameElement) {
                        console.warn('Name element not found for bell:', row.id);
                        return;
                    }
                    
                    const namaBel = nameElement.textContent.trim();
                    
                    // Ambil tipe bel dari cell ketiga
                    const typeElement = row.querySelector('td:nth-child(3) span');
                    if (!typeElement) {
                        console.warn('Type element not found for bell:', row.id);
                        return;
                    }
                    
                    const tipeBelText = typeElement.textContent.trim();
                    let tipeBel = 'reguler';
                    
                    if (tipeBelText === 'Istirahat') tipeBel = 'istirahat';
                    else if (tipeBelText === 'Ujian') tipeBel = 'ujian';
                    else if (tipeBelText === 'Khusus') tipeBel = 'khusus';
                    
                    // console.log(`RINGING BELL: ID=${belId}, Name="${namaBel}", Type=${tipeBel}`); // Debug removed
                    
                    // Highlight baris bel yang akan berbunyi
                    row.classList.add('bg-green-100');
                    setTimeout(() => {
                        row.classList.remove('bg-green-100');
                    }, 10000); // Hapus highlight setelah 10 detik
                    
                    // Bunyikan bel otomatis dengan panggilan API
                    bunyikanBel(belId, namaBel, tipeBel, true); // skipConfirmation=true
                    belFound = true;
                }
            }
        } catch (error) {
            console.error('Error checking bell row:', error, row);
        }
    });
    
    // Jika tidak ada bel yang ditemukan di halaman ini, coba gunakan iframe sebagai cadangan
    if (!belFound) {
        // console.log('No bells found to ring locally, trying iframe as backup'); // Debug removed
        try {
            const iframeWindow = getIframeWindow();
            if (iframeWindow && typeof iframeWindow.checkCurrentBell === 'function') {
                // console.log('Requesting iframe to check for bells'); // Debug removed
                iframeWindow.checkCurrentBell();
            } else {
                console.warn('Iframe or checkCurrentBell function not available');
            }
        } catch (iframeError) {
            console.error('Error using iframe as backup:', iframeError);
        }
    }
}

// GUNAKAN SISTEM BEL OTOMATIS GLOBAL PLUS PENGECEKAN LOKAL
// Untuk halaman bel, kita menggunakan kombinasi sistem global dengan iframe
// ditambah pengecekan khusus secara lokal untuk memastikan bel tetap berfungsi

// Konfigurasi untuk sistem bel lokal
const BEL_CHECK_INTERVAL = 15000; // 15 detik
let lastBelCheckTime = null;

// Perlu akses ke iframe dari halaman ini untuk menjalankan fungsi-fungsi
function getIframeWindow() {
    try {
        // Coba cari iframe dengan id bel-player-iframe dulu
        let iframe = document.getElementById('bel-player-iframe');
        
        // Jika tidak ditemukan, coba cari dengan id bel-iframe
        if (!iframe) {
            iframe = document.getElementById('bel-iframe');
        }
        
        // Jika masih tidak ditemukan, cari iframe lain yang berhubungan dengan bel
        if (!iframe) {
            const iframes = document.querySelectorAll('iframe');
            for (let i = 0; i < iframes.length; i++) {
                if (iframes[i].src && iframes[i].src.includes('bel')) {
                    iframe = iframes[i];
                    break;
                }
            }
        }
        
        // Jika iframe ditemukan, coba akses contentWindow
        if (iframe && iframe.contentWindow) {
            // console.log('Iframe found:', iframe.id || 'unnamed iframe'); // Debug removed
            return iframe.contentWindow;
        } else {
            console.warn('Bel iframe not found or contentWindow not available');
            return null;
        }
    } catch (error) {
        console.error('Error accessing iframe contentWindow:', error);
        return null;
    }
}

// console.log('Halaman bel menggunakan sistem bel ganda (iframe + lokal)'); // Debug removed

// Variabel global untuk status sistem bel
let belOtomatisAktif = true;
let lastBelCheckTime = null;
let audioContextActivated = false;

// Test sistem audio untuk memastikan bel dapat berbunyi
function testAudioSystem() {
    console.log('[AUDIO TEST] Starting audio system test...');
    
    const testBtn = document.getElementById('test-audio-btn');
    const originalText = testBtn.innerHTML;
    testBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testing...';
    testBtn.disabled = true;
    
    // Aktivasi AudioContext untuk browser policy
    if (!audioContextActivated) {
        try {
            const tempContext = new (window.AudioContext || window.webkitAudioContext)();
            if (tempContext.state === 'suspended') {
                tempContext.resume().then(() => {
                    console.log('[AUDIO TEST] AudioContext resumed');
                    audioContextActivated = true;
                });
            } else {
                audioContextActivated = true;
            }
        } catch (e) {
            console.error('[AUDIO TEST] Failed to create AudioContext:', e);
        }
    }
    
    // Test dengan berbagai metode audio
    Promise.resolve()
        .then(() => {
            console.log('[AUDIO TEST] Testing Web Audio API...');
            return testWebAudioAPI();
        })
        .then(() => {
            console.log('[AUDIO TEST] Testing HTML5 Audio...');
            return testHTML5Audio();
        })
        .then(() => {
            console.log('[AUDIO TEST] Testing default bell script...');
            return testDefaultBellScript();
        })
        .then(() => {
            showNotification('✅ Test audio berhasil! Sistem bel siap digunakan.', 'success');
            updateSystemStatus('🟢 Audio Aktif');
        })
        .catch(error => {
            console.error('[AUDIO TEST] Audio test failed:', error);
            showNotification('⚠️ Test audio gagal. Periksa pengaturan audio browser.', 'error');
            updateSystemStatus('🔴 Audio Bermasalah');
        })
        .finally(() => {
            testBtn.innerHTML = originalText;
            testBtn.disabled = false;
        });
}

// Test Web Audio API
function testWebAudioAPI() {
    return new Promise((resolve, reject) => {
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            const now = audioContext.currentTime;
            oscillator.type = 'sine';
            oscillator.frequency.value = 800;
            
            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.3, now + 0.1);
            gainNode.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
            
            oscillator.start(now);
            oscillator.stop(now + 0.5);
            
            console.log('[AUDIO TEST] Web Audio API test successful');
            setTimeout(resolve, 600);
        } catch (error) {
            console.error('[AUDIO TEST] Web Audio API failed:', error);
            reject(error);
        }
    });
}

// Test HTML5 Audio
function testHTML5Audio() {
    return new Promise((resolve, reject) => {
        try {
            const audio = new Audio("data:audio/wav;base64,//uQRAAAAWMSLwUIYAAsYkXgoQwAEaYLWfkWgAI0wWs/ItAAAGDgYtAgAyN+QWaAAihwMWm4G8QQRDiMcCBcH3Cc+CDv/7xA4Tvh9Rz/y8QADBwMWgQAZG/ILNAARQ4GLTcDeIIIhxGOBAuD7hOfBB3/94gcJ3w+o5/5eIAIAAAVwWgQAVQ2ORaIQwEMAJiDg95G4nQL7mQVWI6GwRcfsZAcsKkJvxgxEjzFUgfHoSQ9Qa7KNwqHwuB13MA4a1q/DmBrHgPcmjiGoh//EwC5nGPEmS4RcfkVKOhJf+WOgoxJclFz3kgn//dBA+ya1GhurNn8zb//9NNutNuhz31f////9vt///z+IdAEAAAK4LQIAKobHItEIYCGAExBwe8jcToF9zIKrEdDYIuP2MgOWFSE34wYiR5iqQPj0JIeoVdlG4VD4XA67mAcNa1fhzA1jwHuTRxDUQ//iYBczjHiTJcIuPyKlHQkv/LHQUYkuSi57yQT//uggfZNajQ3Vmz+Zt//+mm3Wm3Q576v////+32///5/EOgAAADVghQAAAAA==");
            audio.volume = 0.5;
            
            audio.addEventListener('canplaythrough', () => {
                console.log('[AUDIO TEST] HTML5 Audio can play through');
            });
            
            audio.addEventListener('ended', () => {
                console.log('[AUDIO TEST] HTML5 Audio test successful');
                resolve();
            });
            
            audio.addEventListener('error', (e) => {
                console.error('[AUDIO TEST] HTML5 Audio error:', e);
                reject(e);
            });
            
            audio.play().catch(reject);
        } catch (error) {
            console.error('[AUDIO TEST] HTML5 Audio failed:', error);
            reject(error);
        }
    });
}

// Test default bell script
function testDefaultBellScript() {
    return new Promise((resolve, reject) => {
        try {
            if (typeof playSimpleBeep === 'function') {
                playSimpleBeep();
                console.log('[AUDIO TEST] Simple beep test successful');
                setTimeout(resolve, 1000);
            } else {
                console.log('[AUDIO TEST] playSimpleBeep not available, loading default bell script...');
                const script = document.createElement('script');
                script.src = '/sounds/default-bell.js';
                
                script.onload = function() {
                    if (typeof playDefaultBell === 'function') {
                        playDefaultBell().then(() => {
                            console.log('[AUDIO TEST] Default bell script test successful');
                            resolve();
                        }).catch(reject);
                    } else {
                        reject(new Error('playDefaultBell function not found'));
                    }
                };
                
                script.onerror = () => reject(new Error('Failed to load default bell script'));
                document.head.appendChild(script);
            }
        } catch (error) {
            console.error('[AUDIO TEST] Default bell script failed:', error);
            reject(error);
        }
    });
}

// Update status sistem
function updateSystemStatus(status) {
    const statusElement = document.getElementById('system-status');
    if (statusElement) {
        statusElement.textContent = status;
    }
}

// Update waktu terakhir dicek
function updateLastCheckTime() {
    const timeElement = document.getElementById('last-check-time');
    if (timeElement) {
        const now = new Date();
        timeElement.textContent = now.toLocaleTimeString();
    }
}

// Force check bells - untuk debugging dan test manual
function forceCheckBells() {
    // console.log('[FORCE CHECK] Manual bell check triggered'); // Debug removed
    updateLastCheckTime();
    showNotification('🔍 Memeriksa jadwal bel...', 'info');
    
    // Jalankan pengecekan lokal
    cekDanBunyikanBel();
    
    // Juga coba iframe jika tersedia
    try {
        const iframeWindow = getIframeWindow();
        if (iframeWindow && typeof iframeWindow.checkCurrentBell === 'function') {
            // console.log('[FORCE CHECK] Also checking via iframe'); // Debug removed
            iframeWindow.checkCurrentBell();
        }
    } catch (error) {
        console.error('[FORCE CHECK] Iframe check failed:', error);
    }
}

// Jalankan sekali saat halaman dimuat untuk menangani bel yang mungkin harus dibunyikan saat halaman terbuka
setTimeout(() => {
    // console.log('[BEL INIT] Initializing bell system...'); // Debug removed
    updateSystemStatus('🟡 Menginisialisasi...');
    
    // Test audio context activation
    document.addEventListener('click', function audioActivation() {
        if (!audioContextActivated) {
            try {
                const tempContext = new (window.AudioContext || window.webkitAudioContext)();
                if (tempContext.state === 'suspended') {
                    tempContext.resume().then(() => {
                        // console.log('[BEL INIT] AudioContext activated by user interaction'); // Debug removed
                        audioContextActivated = true;
                        updateSystemStatus('🟢 Audio Aktif');
                        showNotification('🔊 Sistem audio telah diaktifkan', 'success');
                    });
                } else {
                    audioContextActivated = true;
                    updateSystemStatus('🟢 Audio Aktif');
                }
                // Remove the event listener after first use
                document.removeEventListener('click', audioActivation);
            } catch (e) {
                console.error('[BEL INIT] Failed to activate audio context:', e);
                updateSystemStatus('🔴 Audio Bermasalah');
            }
        }
    });
    
    // Cek iframe system
    const iframeWindow = getIframeWindow();
    if (iframeWindow && typeof iframeWindow.checkCurrentBell === 'function') {
        // console.log('[BEL INIT] Iframe system available, running initial check'); // Debug removed
        iframeWindow.checkCurrentBell();
    } else {
        // console.log('[BEL INIT] Iframe system not available, using local system only'); // Debug removed
    }
    
    // Selalu jalankan pengecekan lokal juga untuk duplikasi keamanan
    // console.log('[BEL INIT] Running initial local check'); // Debug removed
    cekDanBunyikanBel();
    
    // Set interval untuk pengecekan lokal reguler
    const BEL_CHECK_INTERVAL = 10000; // 10 detik
    setInterval(() => {
        cekDanBunyikanBel();
        updateLastCheckTime();
    }, BEL_CHECK_INTERVAL);
    
    // console.log('[BEL INIT] Bell system initialized with 10-second interval'); // Debug removed
    updateSystemStatus('🟢 Sistem Aktif');
    
    // Show initial instruction
    if (!audioContextActivated) {
        showNotification('👆 Klik di mana saja untuk mengaktifkan sistem audio', 'info');
    }
}, 3000);

// Toggle untuk mengaktifkan/menonaktifkan otomatisasi bel
function toggleBelOtomatis() {
    belOtomatisAktif = !belOtomatisAktif;
    
    // console.log(`[BEL TOGGLE] Bell automation ${belOtomatisAktif ? 'ENABLED' : 'DISABLED'}`); // Debug removed
    
    // Update tampilan tombol
    const btn = document.getElementById('toggle-auto-bell');
    if (btn) {
        if (belOtomatisAktif) {
            btn.innerHTML = '<i class="fas fa-bell mr-2"></i><span>Otomatis: Aktif</span>';
            btn.className = btn.className.replace('bg-red-500', 'bg-green-500').replace('hover:bg-red-600', 'hover:bg-green-600');
            showNotification('🔔 Bel otomatis telah diaktifkan', 'success');
            updateSystemStatus('🟢 Otomatis Aktif');
        } else {
            btn.innerHTML = '<i class="fas fa-bell-slash mr-2"></i><span>Otomatis: Tidak Aktif</span>';
            btn.className = btn.className.replace('bg-green-500', 'bg-red-500').replace('hover:bg-green-600', 'hover:bg-red-600');
            showNotification('🔕 Bel otomatis telah dinonaktifkan', 'info');
            updateSystemStatus('🟡 Manual Mode');
        }
    }
    
    // Komunikasi dengan iframe jika tersedia
    try {
        const iframeWindow = getIframeWindow();
        if (iframeWindow) {
            iframeWindow.postMessage({
                type: 'bel-config',
                config: { active: belOtomatisAktif }
            }, window.location.origin);
            console.log('[BEL TOGGLE] Configuration sent to iframe:', belOtomatisAktif);
        }
    } catch (error) {
        console.error('[BEL TOGGLE] Failed to communicate with iframe:', error);
    }
}

// Tambahkan event listener untuk DOM ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('[BEL SYSTEM] DOM ready, initializing...');
    
    // Initialize CSRF token
    const token = document.querySelector('meta[name="csrf-token"]');
    if (token) {
        window.csrfToken = token.getAttribute('content');
        console.log('[BEL SYSTEM] CSRF token initialized');
    }
    
    // Update initial bell count
    const activeBellsElement = document.getElementById('active-bells-count');
    if (activeBellsElement) {
        const activeRows = document.querySelectorAll('tr[id^="row-"] td:nth-child(4) span:contains("Aktif")');
        activeBellsElement.textContent = activeRows.length;
    }
    
    console.log('[BEL SYSTEM] System ready');
});
</script>
@endsection
