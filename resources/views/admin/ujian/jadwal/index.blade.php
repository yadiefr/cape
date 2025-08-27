@extends('layouts.ujian')

@section('title', 'Master Jadwal Ujian')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.ujian.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-home w-4 h-4"></i>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <span class="text-gray-500">Jadwal Ujian</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Master Jadwal Ujian</h1>
            <p class="text-gray-600 mt-1">Kelola jadwal pelaksanaan ujian</p>
        </div>
        <div class="flex space-x-3 header-buttons">
            <a href="{{ route('admin.ujian.jadwal.create') }}" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                Tambah Jadwal
            </a>
            <button type="button" id="batchModalBtn" onclick="openBatchModal()" 
                    class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                <i class="fas fa-table w-4 h-4 mr-2"></i>
                Mode Tabel
            </button>
            <a href="{{ route('admin.ujian.jadwal.create-table') }}"
               class="inline-flex items-center bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-calendar-plus w-4 h-4 mr-2"></i>
                Tabel Lengkap
            </a>
            <button type="button" id="quickScheduleBtn" onclick="openQuickScheduleModal()" 
                    class="inline-flex items-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 cursor-pointer">
                <i class="fas fa-magic w-4 h-4 mr-2"></i>
                Jadwal Cepat
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-calendar-alt text-blue-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Jadwal</p>
                    <p class="text-2xl font-bold text-gray-800">24</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-play text-green-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Sedang Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-800">3</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Akan Datang</p>
                    <p class="text-2xl font-bold text-gray-800">12</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100">
                    <i class="fas fa-check text-gray-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="text-2xl font-bold text-gray-800">156</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                <select name="mata_pelajaran" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id }}" {{ request('mata_pelajaran') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $kelasItem)
                        <option value="{{ $kelasItem->id }}" {{ request('kelas') == $kelasItem->id ? 'selected' : '' }}>
                            {{ $kelasItem->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                    <i class="fas fa-search w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.ujian.jadwal.index') }}" 
                   class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 rounded-lg font-medium transition-colors duration-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Daftar Jadwal Ujian</h3>
                <div class="flex space-x-2">
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-download w-5 h-5"></i>
                    </button>
                    <button class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-print w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ujian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($jadwal as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $item->id }}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_ujian }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst($item->jenis_ujian) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->mataPelajaran->nama ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->kelas->nama_kelas ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->tanggal_formatted }}</div>
                            <div class="text-sm text-gray-500">{{ $item->waktu_mulai_formatted }} - {{ $item->waktu_selesai_formatted }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $item->durasi }} menit</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-{{ $item->status_color }}-100 text-{{ $item->status_color }}-800 rounded-full">
                                {{ $item->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                @if($item->canBeEdited())
                                    <a href="{{ route('admin.ujian.jadwal.edit', $item->id) }}" class="text-blue-600 hover:text-blue-700" title="Edit">
                                        <i class="fas fa-edit w-4 h-4"></i>
                                    </a>
                                @endif
                                <a href="#" class="text-green-600 hover:text-green-700" title="Lihat Detail">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </a>
                                @if($item->canBeDeleted())
                                    <form action="{{ route('admin.ujian.jadwal.destroy', $item->id) }}" method="POST" class="inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus jadwal ujian ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700" title="Hapus">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-times w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">Belum ada jadwal ujian</p>
                                <p class="text-sm">Mulai dengan menambahkan jadwal ujian pertama</p>
                                <a href="{{ route('admin.ujian.jadwal.create') }}" 
                                   class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                    <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                    Tambah Jadwal
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($jadwal->hasPages())
        <div class="bg-white px-6 py-3 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-medium">{{ $jadwal->firstItem() }}</span> sampai <span class="font-medium">{{ $jadwal->lastItem() }}</span> dari <span class="font-medium">{{ $jadwal->total() }}</span> hasil
                </div>
                <div>
                    {{ $jadwal->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Batch/Table Mode Modal -->
<div id="batchModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Mode Tabel - Tambah Jadwal Batch</h3>
            <button id="closeBatchBtn" onclick="closeBatchModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times w-6 h-6"></i>
            </button>
        </div>
        
        <form id="batchScheduleForm">
            @csrf
            <!-- Filter Section -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select id="batch_kelas" name="kelas_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Kelas</option>
                        @foreach($kelas as $kelasItem)
                            <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="batch_start_date" name="start_date" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           min="{{ date('Y-m-d') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                    <input type="time" id="batch_start_time" name="start_time" value="08:00"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Durasi Default (menit)</label>
                    <input type="number" id="batch_duration" name="duration" value="90" min="15" max="480"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            
            <button type="button" onclick="generateBatchTable()" 
                    class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                Generate Tabel Jadwal
            </button>
            
            <!-- Batch Table -->
            <div id="batchTableContainer" class="hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">Mata Pelajaran</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Tanggal</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Jam Mulai</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Durasi</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Jenis Ujian</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Aktif</th>
                            </tr>
                        </thead>
                        <tbody id="batchTableBody">
                            <!-- Dynamic content will be inserted here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeBatchModal()" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                        Simpan Semua Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Quick Schedule Modal -->
<div id="quickScheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-900">Jadwal Cepat - Template Ujian</h3>
            <button id="closeQuickBtn" onclick="closeQuickScheduleModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times w-6 h-6"></i>
            </button>
        </div>
        
        <form id="quickScheduleForm">
            @csrf
            <input type="hidden" name="mode" value="quick">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Template Ujian</label>
                    <select id="quick_template" name="template" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Pilih Template</option>
                        <option value="uts">UTS - Semua Mapel (90 menit)</option>
                        <option value="uas">UAS - Semua Mapel (120 menit)</option>
                        <option value="quiz_harian">Quiz Harian (30 menit)</option>
                        <option value="praktek">Ujian Praktek (180 menit)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas Target</label>
                    <select id="quick_kelas" name="kelas_ids[]" multiple class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        @foreach($kelas as $kelasItem)
                            <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Tahan Ctrl untuk pilih multiple</small>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="quick_start_date" name="start_date" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2"
                           min="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interval Antar Ujian (hari)</label>
                    <input type="number" id="quick_interval" name="interval" value="1" min="1" max="7"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai Default</label>
                <div class="grid grid-cols-2 gap-4">
                    <input type="time" id="quick_time_1" name="time_slots[]" value="08:00"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                    <input type="time" id="quick_time_2" name="time_slots[]" value="10:00"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeQuickScheduleModal()" 
                        class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                    Generate Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Global variables for debugging
let batchModalElement = null;
let quickScheduleModalElement = null;

// Initialize modal elements on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    batchModalElement = document.getElementById('batchModal');
    quickScheduleModalElement = document.getElementById('quickScheduleModal');
    
    // Debug: Check if modals exist
    console.log('Batch modal element:', batchModalElement);
    console.log('Quick schedule modal element:', quickScheduleModalElement);
    
    // Add fallback event listeners for buttons
    const batchBtn = document.getElementById('batchModalBtn');
    const quickBtn = document.getElementById('quickScheduleBtn');
    const closeBatchBtn = document.getElementById('closeBatchBtn');
    const closeQuickBtn = document.getElementById('closeQuickBtn');
    
    if (batchBtn) {
        batchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Batch button clicked via event listener');
            openBatchModal();
        });
        console.log('Batch button event listener added');
    }
    
    if (quickBtn) {
        quickBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Quick schedule button clicked via event listener');
            openQuickScheduleModal();
        });
        console.log('Quick schedule button event listener added');
    }
    
    if (closeBatchBtn) {
        closeBatchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close batch button clicked via event listener');
            closeBatchModal();
        });
    }
    
    if (closeQuickBtn) {
        closeQuickBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Close quick button clicked via event listener');
            closeQuickScheduleModal();
        });
    }
    
    // Initialize select all functionality
    initializeSelectAll();
    
    // Initialize form handlers
    initializeBatchForm();
    initializeQuickScheduleForm();
});

// Modal functions with error handling
function openBatchModal() {
    console.log('Opening batch modal...');
    try {
        if (batchModalElement) {
            batchModalElement.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        } else {
            console.error('Batch modal element not found');
            alert('Terjadi kesalahan: Modal tidak ditemukan. Silakan refresh halaman.');
        }
    } catch (error) {
        console.error('Error opening batch modal:', error);
        alert('Terjadi kesalahan saat membuka modal batch.');
    }
}

function closeBatchModal() {
    console.log('Closing batch modal...');
    try {
        if (batchModalElement) {
            batchModalElement.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
            
            // Clear table data
            const batchTableContainer = document.getElementById('batchTableContainer');
            const batchTableBody = document.getElementById('batchTableBody');
            
            if (batchTableContainer) {
                batchTableContainer.classList.add('hidden');
            }
            if (batchTableBody) {
                batchTableBody.innerHTML = '';
            }
        }
    } catch (error) {
        console.error('Error closing batch modal:', error);
    }
}

function openQuickScheduleModal() {
    console.log('Opening quick schedule modal...');
    try {
        if (quickScheduleModalElement) {
            quickScheduleModalElement.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        } else {
            console.error('Quick schedule modal element not found');
            alert('Terjadi kesalahan: Modal tidak ditemukan. Silakan refresh halaman.');
        }
    } catch (error) {
        console.error('Error opening quick schedule modal:', error);
        alert('Terjadi kesalahan saat membuka modal jadwal cepat.');
    }
}

function closeQuickScheduleModal() {
    console.log('Closing quick schedule modal...');
    try {
        if (quickScheduleModalElement) {
            quickScheduleModalElement.classList.add('hidden');
            document.body.style.overflow = ''; // Restore scrolling
        }
    } catch (error) {
        console.error('Error closing quick schedule modal:', error);
    }
}

// Generate batch table
function generateBatchTable() {
    const kelasId = document.getElementById('batch_kelas')?.value;
    const startDate = document.getElementById('batch_start_date')?.value;
    const startTime = document.getElementById('batch_start_time')?.value;
    const duration = document.getElementById('batch_duration')?.value;
    
    if (!kelasId || !startDate) {
        alert('Pilih kelas dan tanggal mulai terlebih dahulu');
        return;
    }
    
    try {
        // Get mata pelajaran data
        const mataPelajaran = @json($mataPelajaran ?? []);
        
        const tableBody = document.getElementById('batchTableBody');
        if (!tableBody) {
            console.error('Batch table body not found');
            return;
        }
        
        tableBody.innerHTML = '';
        
        let currentDate = new Date(startDate);
        let currentTime = startTime || '08:00';
        
        mataPelajaran.forEach((mapel, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="border border-gray-300 px-4 py-2">
                    <input type="hidden" name="schedules[${index}][mata_pelajaran_id]" value="${mapel.id}">
                    <input type="hidden" name="schedules[${index}][kelas_id]" value="${kelasId}">
                    <input type="hidden" name="schedules[${index}][nama_ujian]" value="Ujian ${mapel.nama}">
                    <input type="hidden" name="schedules[${index}][status]" value="scheduled">
                    ${mapel.nama}
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <input type="date" name="schedules[${index}][tanggal]" 
                           value="${formatDate(currentDate)}" 
                           class="w-full border-0 focus:ring-0" required>
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <input type="time" name="schedules[${index}][waktu_mulai]" 
                           value="${currentTime}" 
                           class="w-full border-0 focus:ring-0" required>
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <input type="number" name="schedules[${index}][durasi]" 
                           value="${duration || 90}" min="15" max="480"
                           class="w-full border-0 focus:ring-0" required>
                </td>
                <td class="border border-gray-300 px-4 py-2">
                    <select name="schedules[${index}][jenis_ujian]" class="w-full border-0 focus:ring-0" required>
                        <option value="uts">UTS</option>
                        <option value="uas">UAS</option>
                        <option value="quiz">Quiz</option>
                        <option value="praktek">Praktek</option>
                    </select>
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <input type="checkbox" name="schedules[${index}][selected]" value="1" checked
                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </td>
            `;
            tableBody.appendChild(row);
            
            // Increment date for next subject
            currentDate.setDate(currentDate.getDate() + 1);
            // Skip weekends
            while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
                currentDate.setDate(currentDate.getDate() + 1);
            }
        });
        
        const batchTableContainer = document.getElementById('batchTableContainer');
        if (batchTableContainer) {
            batchTableContainer.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error generating batch table:', error);
        alert('Terjadi kesalahan saat membuat tabel batch.');
    }
}

// Helper function to format date
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Initialize batch form handler
function initializeBatchForm() {
    const form = document.getElementById('batchScheduleForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('mode', 'batch');
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            fetch('{{ route("admin.ujian.jadwal.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Jadwal berhasil disimpan!');
                    closeBatchModal();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                    if (data.errors && data.errors.length > 0) {
                        alert('Errors: ' + data.errors.join('\n'));
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan jadwal');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    });
}

// Initialize quick schedule form handler
function initializeQuickScheduleForm() {
    const form = document.getElementById('quickScheduleForm');
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('mode', 'quick');
        
        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Generating...';
            submitBtn.disabled = true;
            
            fetch('{{ route("admin.ujian.jadwal.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Jadwal template berhasil di-generate!');
                    closeQuickScheduleModal();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat generate jadwal');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        }
    });
}

// Initialize select all functionality
function initializeSelectAll() {
    const selectAll = document.querySelector('thead input[type="checkbox"]');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
}

// Add click event listeners to close modals when clicking outside
document.addEventListener('click', function(event) {
    // Close batch modal if clicking on backdrop
    if (event.target && event.target.id === 'batchModal') {
        closeBatchModal();
    }
    
    // Close quick schedule modal if clicking on backdrop
    if (event.target && event.target.id === 'quickScheduleModal') {
        closeQuickScheduleModal();
    }
});

// Add keyboard event listeners
document.addEventListener('keydown', function(event) {
    // Close modals with Escape key
    if (event.key === 'Escape') {
        if (batchModalElement && !batchModalElement.classList.contains('hidden')) {
            closeBatchModal();
        }
        if (quickScheduleModalElement && !quickScheduleModalElement.classList.contains('hidden')) {
            closeQuickScheduleModal();
        }
    }
});
</script>
@endpush

@push('styles')
<style>
/* Ensure buttons are clickable */
button[onclick], button[type="button"] {
    cursor: pointer !important;
    pointer-events: auto !important;
}

/* Modal styling */
.modal-backdrop {
    backdrop-filter: blur(2px);
}

/* Button hover effects */
button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Fix any potential z-index issues */
.header-buttons {
    position: relative;
    z-index: 10;
}

/* Ensure proper spacing and alignment */
.inline-flex {
    display: inline-flex !important;
    align-items: center !important;
}

/* Debug styles - remove in production */
button[onclick]:not(:hover) {
    outline: 1px solid transparent;
}

button[onclick]:hover {
    outline: 2px solid rgba(59, 130, 246, 0.5);
}

/* Active state for buttons */
button:active {
    transform: translateY(0px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Visual feedback for button clicks */
.button-clicked {
    animation: buttonClick 0.2s ease-in-out;
}

@keyframes buttonClick {
    0% { transform: scale(1); }
    50% { transform: scale(0.95); }
    100% { transform: scale(1); }
}
</style>
@endpush
@endsection
