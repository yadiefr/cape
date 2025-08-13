@extends('layouts.admin')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Manajemen Siswa - SMK PGRI CIKAMPEK')

@section('main-content')
<!-- Page Content -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-blue-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-blue-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-graduate text-blue-600 mr-3"></i>
                            Manajemen Siswa
                        </h1>
                        <p class="text-gray-600 mt-1">Kelola data siswa di SMK PGRI CIKAMPEK</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('admin.siswa.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus"></i> Tambah Siswa
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-blue-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-blue-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <h3 class="text-blue-800 font-medium text-lg mb-2 flex items-center">
                            <i class="fas fa-file-excel text-blue-600 mr-2"></i>
                            Ekspor Data
                        </h3>
                        <p class="text-blue-700 text-sm mb-4">Ekspor data siswa ke format CSV, Excel, atau PDF</p>
                        <div class="flex space-x-2">
                            <button class="bg-white text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i>
                                Excel
                            </button>
                            <button class="bg-white text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-csv mr-1"></i>
                                CSV
                            </button>
                            <button class="bg-white text-blue-700 hover:bg-blue-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-pdf mr-1"></i>
                                PDF
                            </button>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-green-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-green-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <div class="relative z-10">
                            <h3 class="text-green-800 font-medium text-lg mb-2 flex items-center">
                                <i class="fas fa-file-import text-green-600 mr-2"></i>
                                Import Data Siswa
                            </h3>
                            <p class="text-green-700 text-sm mb-4">Import daftar siswa dari file Excel, CSV, atau TSV</p>
                            
                            <!-- Import Actions -->
                            <div class="flex flex-col sm:flex-row gap-2 mb-3">
                                <!-- Upload Button - Opens Modal -->
                                <button type="button" 
                                        onclick="openImportModal()" 
                                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all transform hover:scale-105 shadow-md flex-1 text-sm">
                                    <i class="fas fa-upload text-sm"></i> 
                                    <span>Upload File</span>
                                </button>
                                
                                <!-- Template Download Button -->
                                <a href="{{ route('admin.siswa.template') }}" 
                                   class="w-full sm:w-auto bg-white border border-green-300 text-green-700 font-semibold px-4 py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-green-50 transition-all shadow-md text-sm">
                                    <i class="fas fa-file-excel text-green-600 text-sm"></i> 
                                    <span>Template Excel</span>
                                </a>
                                
                                <!-- Info Button -->
                                <button type="button" 
                                        onclick="showImportInfo()" 
                                        class="w-full sm:w-auto bg-white border border-green-300 text-green-700 px-3 py-2 rounded-lg hover:bg-green-50 transition-all shadow-md text-sm"
                                        title="Info Format Import">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                            
                            <!-- Quick Info -->
                            <div class="text-xs text-green-600 bg-green-50 p-2 rounded border border-green-200">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Format yang didukung:</strong> Excel (.xlsx), CSV (.csv), TSV (.tsv/.txt)
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-purple-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-purple-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <h3 class="text-purple-800 font-medium text-lg mb-2 flex items-center">
                            <i class="fas fa-filter text-purple-600 mr-2"></i>
                            Filter & Pencarian
                        </h3>
                        <p class="text-purple-700 text-sm mb-4">Filter data siswa berdasarkan kelas atau jurusan</p>
                        <div class="flex space-x-2">
                            <button class="bg-white text-purple-700 hover:bg-purple-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm"
                                   onclick="document.getElementById('filterPanel').classList.toggle('hidden')">
                                <i class="fas fa-sliders-h mr-1"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Panel -->
        <div id="filterPanel" class="bg-gray-50 rounded-xl border border-gray-200 p-5 hidden">
            <form action="{{ route('admin.siswa.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Hidden fields to preserve sorting -->
                <input type="hidden" name="sort_by" value="{{ request('sort_by', 'siswa.nama_lengkap') }}">
                <input type="hidden" name="sort_order" value="{{ request('sort_order', 'asc') }}">
                <div class="space-y-2">                    <label for="jurusan_id" class="text-gray-700 font-medium text-sm">Jurusan</label>
                    <select id="jurusan_id" name="jurusan_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusan as $j)
                        <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">                    <label for="kelas_id" class="text-gray-700 font-medium text-sm">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="status" class="text-gray-700 font-medium text-sm">Status</label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Semua Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label for="search" class="text-gray-700 font-medium text-sm">Cari</label>
                    <div class="relative">
                        <input type="text" id="search" name="search" value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan nama atau NIS..." 
                               class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-10">
                        <span class="absolute left-3 top-3 text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                
                <div class="md:col-span-4 flex justify-end space-x-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all flex items-center">
                        <i class="fas fa-search mr-2"></i>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all flex items-center">
                        <i class="fas fa-redo mr-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Students Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">            <div class="overflow-x-auto">                <form id="bulkDeleteForm" action="{{ route('admin.siswa.bulkDelete') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="_method" value="DELETE">
                    <table class="min-w-full divide-y divide-gray-200">                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-center">
                                    <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-blue-600">
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Foto
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nama & Email
                                    <div class="inline-flex ml-2">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'siswa.nama_lengkap', 'sort_order' => ($currentSort === 'siswa.nama_lengkap' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 transition-colors">
                                            @if($currentSort === 'siswa.nama_lengkap')
                                                @if($currentOrder === 'asc')
                                                    <i class="fas fa-sort-up text-blue-500"></i>
                                                @else
                                                    <i class="fas fa-sort-down text-blue-500"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kelas
                                    <div class="inline-flex ml-2">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'kelas.nama_kelas', 'sort_order' => ($currentSort === 'kelas.nama_kelas' && $currentOrder === 'asc') ? 'desc' : 'asc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 transition-colors">
                                            @if($currentSort === 'kelas.nama_kelas')
                                                @if($currentOrder === 'asc')
                                                    <i class="fas fa-sort-up text-blue-500"></i>
                                                @else
                                                    <i class="fas fa-sort-down text-blue-500"></i>
                                                @endif
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No Telepon
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Alamat
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead><tbody class="bg-white divide-y divide-gray-200">                            @forelse($siswa as $index => $s)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $s->id }}" class="row-checkbox form-checkbox h-4 w-4 text-blue-600">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $loop->iteration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center">
                                        <div class="h-12 w-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0">
                                            @if($s->foto && Storage::disk('public')->exists($s->foto))
                                                <img src="{{ asset('storage/' . $s->foto) }}" alt="Foto {{ $s->nama_lengkap }}" class="h-full w-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($s->nama_lengkap) }}&background=3b82f6&color=ffffff" alt="Foto {{ $s->nama_lengkap }}" class="h-full w-full object-cover">
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                    <div class="text-sm text-gray-500">{{ $s->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ $s->kelas->nama_kelas ?? 'Belum ada kelas' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ $s->telepon ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm text-gray-900">
                                        @if($s->alamat)
                                            @if(strlen($s->alamat) > 50)
                                                <div>{{ substr($s->alamat, 0, 50) }}</div>
                                                <div class="text-gray-500">{{ substr($s->alamat, 50) }}</div>
                                            @else
                                                {{ $s->alamat }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $s->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route('admin.siswa.show', $s->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.siswa.edit', $s->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" class="inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="text-red-600 hover:text-red-900 delete-btn" data-id="{{ $s->id }}" data-name="{{ $s->nama_lengkap }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            @empty
                            <tr>
                                <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    <div class="flex flex-col items-center py-5">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-500 text-lg font-medium">Tidak ada data siswa ditemukan</span>
                                        <p class="text-gray-500 text-sm mt-1">Tambahkan siswa baru atau ubah filter pencarian</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Siswa Baru
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all flex items-center">
                                <i class="fas fa-trash mr-2"></i> Hapus Terpilih
                            </button>
                            
                            <!-- Show entries info -->
                            <div class="text-sm text-gray-700">
                                Menampilkan semua data siswa ({{ $siswa->count() }} data)
                            </div>
                        </div>
                        
                        <!-- Pagination removed - showing all data -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Import Upload Modal -->
<div id="importUploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-file-import text-green-600 mr-2"></i>
                Import Data Siswa
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
            <!-- Upload Form -->
            <form id="modalImportForm" action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- File Upload Section -->
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-green-400 transition-colors bg-gray-50">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-lg font-medium text-gray-700">Upload File Data Siswa</p>
                            <p class="text-sm text-gray-500">Pilih file Excel (.xlsx), CSV (.csv), atau TSV (.tsv/.txt)</p>
                        </div>
                        
                        <input type="file" 
                               id="modalFileInput" 
                               name="file" 
                               accept=".xlsx,.csv,.tsv,.txt" 
                               class="hidden" 
                               onchange="handleModalFileSelect(this)"
                               required>
                        
                        <button type="button" 
                                onclick="document.getElementById('modalFileInput').click()" 
                                class="bg-green-100 hover:bg-green-200 text-green-700 font-semibold px-6 py-3 rounded-lg transition-colors">
                            <i class="fas fa-folder-open mr-2"></i>Pilih File
                        </button>
                        
                        <!-- File Info -->
                        <div id="modalFileInfo" class="hidden mt-4 p-4 bg-white rounded-lg border text-left">
                            <div id="modalFileDetails" class="text-sm"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Format Information -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-200 mb-6">
                    <h5 class="font-medium text-green-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Format File Import
                    </h5>
                    <div class="text-sm text-green-700 space-y-1">
                        <p><strong>Kolom yang diperlukan:</strong></p>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li><strong>nis</strong> - Nomor Induk Siswa (unik, wajib)</li>
                            <li><strong>nama</strong> - Nama lengkap siswa (wajib)</li>
                            <li><strong>email</strong> - Alamat email siswa (wajib, unik)</li>
                            <li><strong>password</strong> - Password default siswa (format: DDMMYYYY dari tanggal lahir)</li>
                            <li><strong>kelas_id</strong> - ID kelas siswa (wajib)</li>
                            <li><strong>jurusan_id</strong> - ID jurusan siswa (wajib)</li>
                            <li><strong>jenis_kelamin</strong> - L atau P (opsional)</li>
                            <li><strong>tanggal_lahir</strong> - Format: YYYY-MM-DD (opsional)</li>
                            <li><strong>tempat_lahir</strong> - Tempat lahir siswa (opsional)</li>
                            <li><strong>alamat</strong> - Alamat lengkap siswa (opsional)</li>
                            <li><strong>nomor_hp</strong> - Nomor HP siswa (opsional)</li>
                        </ul>
                    </div>
                </div>

            </form>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
            <div class="text-sm text-gray-600">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i>
                <strong>Peringatan:</strong> Pastikan format file sesuai template
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="closeImportModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Batal
                </button>
                <button type="button" 
                        onclick="submitModalImportForm()" 
                        id="modalImportSubmitBtn"
                        disabled
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                    <span id="modalImportSubmitText">Import Data</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Info Modal -->
<div id="importInfoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                Detail Format Import Data Siswa
            </h3>
            <button type="button" 
                    onclick="closeImportInfo()" 
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <div id="importInfoContent">
                <!-- Loading indicator -->
                <div class="flex items-center justify-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600"></div>
                    <span class="ml-2 text-gray-600">Memuat informasi format...</span>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
            <div class="text-sm text-gray-600">
                <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                <strong>Tips:</strong> Gunakan template Excel untuk hasil terbaik
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.siswa.template', ['format' => 'excel']) }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-download mr-1"></i>Template Excel
                </a>
                <button type="button" 
                        onclick="closeImportInfo()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filter panel
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search') || urlParams.has('jurusan_id') || urlParams.has('kelas_id') || urlParams.has('status')) {
        document.getElementById('filterPanel').classList.remove('hidden');
    }    // Get form and checkbox elements
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('input[name="ids[]"]');
    const deleteButton = bulkDeleteForm ? bulkDeleteForm.querySelector('button[type="submit"]') : null;

    console.log('Elements found:', {
        bulkDeleteForm: !!bulkDeleteForm,
        selectAll: !!selectAll,
        checkboxes: checkboxes.length,
        deleteButton: !!deleteButton
    });// Function to update delete button state
    function updateDeleteButton() {
        if (!deleteButton) return;
        const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        
        if (checkedCount === 0) {
            deleteButton.disabled = true;
            deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
            deleteButton.classList.remove('hover:bg-red-700');
        } else {
            deleteButton.disabled = false;
            deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
            deleteButton.classList.add('hover:bg-red-700');
        }
    }

    // Select All checkbox functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateDeleteButton();
        });
    }    // Individual checkboxes functionality
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            const someChecked = Array.from(checkboxes).some(cb => cb.checked);
            
            if (selectAll) {
                selectAll.checked = allChecked;
                selectAll.indeterminate = someChecked && !allChecked;
            }
            
            updateDeleteButton();
        });
    });

    // Handle bulk delete form submission
    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Always prevent default first
            
            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
            if (checkedCount === 0) {
                alert('Pilih setidaknya satu siswa untuk dihapus');
                return;
            }

            if (confirm('Anda yakin ingin menghapus ' + checkedCount + ' siswa yang dipilih?')) {
                // If confirmed, submit the form
                this.submit();
            }
        });
    }

    // Initialize button state
    updateDeleteButton();

    // AJAX Delete Functionality
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            const studentName = this.getAttribute('data-name');
            
            // Show confirmation modal using SweetAlert2 or browser confirm
            if (confirm(`Apakah Anda yakin ingin menghapus siswa "${studentName}"?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
                deleteStudent(studentId, this.closest('tr'));
            }
        });
    });

    function deleteStudent(studentId, tableRow) {
        // Show loading state
        const deleteBtn = tableRow.querySelector('.delete-btn');
        const originalContent = deleteBtn.innerHTML;
        deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;

        // Perform AJAX request
        fetch(`{{ url('/admin/siswa') }}/${studentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
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
                // Remove the row from table with animation
                tableRow.style.transition = 'all 0.3s ease';
                tableRow.style.opacity = '0';
                tableRow.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    tableRow.remove();
                    
                    // Check if table is empty and show empty state
                    const tbody = document.querySelector('tbody');
                    if (tbody.children.length === 0) {
                        const emptyRow = `
                            <tr>
                                <td colspan="9" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    <div class="flex flex-col items-center py-5">
                                        <svg class="h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-500 text-lg font-medium">Tidak ada data siswa ditemukan</span>
                                        <p class="text-gray-500 text-sm mt-1">Tambahkan siswa baru atau ubah filter pencarian</p>
                                        <div class="mt-4">
                                            <a href="{{ route('admin.siswa.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Siswa Baru
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                        tbody.innerHTML = emptyRow;
                    }
                    
                    // Show success message
                    showNotification('Siswa berhasil dihapus!', 'success');
                }, 300);
            } else {
                throw new Error(data.message || 'Gagal menghapus siswa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restore button state
            deleteBtn.innerHTML = originalContent;
            deleteBtn.disabled = false;
            
            // Show error message
            showNotification('Gagal menghapus siswa: ' + error.message, 'error');
        });
    }

    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                <span>${message}</span>
                <button class="ml-3 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        // Slide in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
});

// Import Modal Functions
function openImportModal() {
    document.getElementById('importUploadModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importUploadModal').classList.add('hidden');
    
    // Reset form
    document.getElementById('modalImportForm').reset();
    document.getElementById('modalFileInfo').classList.add('hidden');
    document.getElementById('modalImportSubmitBtn').disabled = true;
}

function handleModalFileSelect(input) {
    const fileInfo = document.getElementById('modalFileInfo');
    const fileDetails = document.getElementById('modalFileDetails');
    const submitBtn = document.getElementById('modalImportSubmitBtn');
    
    if (input.files.length > 0) {
        const file = input.files[0];
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Size in MB
        const fileType = file.type || 'Unknown';
        const fileName = file.name;
        
        fileDetails.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="flex-shrink-0">
                    <i class="fas fa-file-excel text-green-600 text-2xl"></i>
                </div>
                <div class="flex-grow">
                    <p class="font-medium text-gray-900">${fileName}</p>
                    <p class="text-sm text-gray-500">Size: ${fileSize} MB • Type: ${fileType}</p>
                </div>
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
            </div>
        `;
        
        fileInfo.classList.remove('hidden');
        submitBtn.disabled = false;
    } else {
        fileInfo.classList.add('hidden');
        submitBtn.disabled = true;
    }
}

function submitModalImportForm() {
    const form = document.getElementById('modalImportForm');
    const submitBtn = document.getElementById('modalImportSubmitBtn');
    const submitText = document.getElementById('modalImportSubmitText');
    const fileInput = document.getElementById('modalFileInput');
    
    if (!fileInput.files.length) {
        alert('Please select a file first');
        return;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitText.textContent = 'Importing...';
    submitBtn.insertAdjacentHTML('afterbegin', '<i class="fas fa-spinner fa-spin mr-2"></i>');
    
    // Submit the form
    form.submit();
}

// Import Info Functions
function showImportInfo() {
    const modal = document.getElementById('importInfoModal');
    const content = document.getElementById('importInfoContent');
    
    modal.classList.remove('hidden');
    
    // Load import info content (you can customize this)
    content.innerHTML = `
        <div class="space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-file-excel mr-2 text-green-600"></i>Format Excel (.xlsx)
                </h5>
                <p class="text-sm text-blue-700">
                    Format yang direkomendasikan untuk import data siswa. Mendukung validasi data dan multiple sheets.
                </p>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-file-csv mr-2 text-blue-600"></i>Format CSV (.csv)
                </h5>
                <p class="text-sm text-blue-700">
                    Format comma-separated values. Pastikan encoding UTF-8 untuk karakter khusus.
                </p>
            </div>
            
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-file-alt mr-2 text-purple-600"></i>Format TSV (.tsv)
                </h5>
                <p class="text-sm text-blue-700">
                    Format tab-separated values. Cocok untuk data dengan koma dalam isinya.
                </p>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <h5 class="font-medium text-yellow-800 mb-2 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Perhatian Khusus
                </h5>
                <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                    <li>NIS harus unik (tidak boleh sama)</li>
                    <li>Email harus unik dan valid</li>
                    <li>ID Kelas dan Jurusan harus sesuai dengan data yang ada</li>
                    <li>Format tanggal lahir: YYYY-MM-DD (contoh: 2005-06-15)</li>
                    <li>Jenis kelamin hanya boleh 'L' atau 'P'</li>
                    <li>Password akan dibuat otomatis dari tanggal lahir (DDMMYYYY)</li>
                    <li>Contoh: tanggal lahir 2005-01-01 → password: 01012005</li>
                    <li>Contoh: tanggal lahir 2001-12-31 → password: 31122001</li>
                </ul>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h5 class="font-medium text-green-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i>Tips Import yang Berhasil
                </h5>
                <ul class="text-sm text-green-700 space-y-1 list-disc list-inside">
                    <li>Gunakan template Excel untuk hasil terbaik</li>
                    <li>Pastikan tidak ada baris kosong di tengah data</li>
                    <li>Periksa kembali format data sebelum import</li>
                    <li>Import dilakukan secara batch, mohon tunggu hingga selesai</li>
                </ul>
            </div>
        </div>
    `;
}

function closeImportInfo() {
    document.getElementById('importInfoModal').classList.add('hidden');
}
</script>
@endsection
