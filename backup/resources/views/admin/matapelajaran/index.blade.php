@extends('layouts.admin')

@section('title', 'Manajemen Mata Pelajaran - SMK PGRI CIKAMPEK')

@section('styles')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main-content')
<!-- Page Content -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col space-y-6">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
            <div class="absolute right-0 -top-12 h-40 w-40 bg-amber-100 opacity-50 rounded-full"></div>
            <div class="absolute -right-8 top-20 h-20 w-20 bg-amber-200 opacity-30 rounded-full"></div>
            <div class="relative z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-book text-amber-600 mr-3"></i>
                            Manajemen Mata Pelajaran
                        </h1>
                        <p class="text-gray-600 mt-1">Kelola daftar mata pelajaran dan kurikulum sekolah</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <a href="{{ route('admin.matapelajaran.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2.5 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">                            <i class="fas fa-plus mr-2"></i>
                            Tambah Mata Pelajaran
                        </a>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-5">
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-amber-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-amber-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <h3 class="text-amber-800 font-medium text-lg mb-2 flex items-center">
                            <i class="fas fa-file-excel text-amber-600 mr-2"></i>
                            Ekspor Data
                        </h3>
                        <p class="text-amber-700 text-sm mb-4">Ekspor data mata pelajaran ke format CSV, Excel, atau PDF</p>
                        <div class="flex space-x-2">
                            <button class="bg-white text-amber-700 hover:bg-amber-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-excel mr-1"></i>
                                Excel
                            </button>
                            <button class="bg-white text-amber-700 hover:bg-amber-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-csv mr-1"></i>
                                CSV
                            </button>
                            <button class="bg-white text-amber-700 hover:bg-amber-200 px-3 py-2 rounded-lg text-sm flex items-center shadow-sm">
                                <i class="fas fa-file-pdf mr-1"></i>
                                PDF
                            </button>
                        </div>
                    </div>
                      <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-blue-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-blue-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <div class="relative z-10">
                            <h3 class="text-blue-800 font-medium text-lg mb-2 flex items-center">
                                <i class="fas fa-file-import text-blue-600 mr-2"></i>
                                Import Mata Pelajaran
                            </h3>
                            <p class="text-blue-700 text-sm mb-4">Import daftar mata pelajaran dari file Excel, CSV, atau TSV</p>
                            
                            <!-- Import Actions -->
                            <div class="flex flex-col sm:flex-row gap-2 mb-3">
                                <!-- Upload Button - Opens Modal -->
                                <button type="button" 
                                        onclick="openImportModal()" 
                                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-all transform hover:scale-105 shadow-md flex-1 text-sm">
                                    <i class="fas fa-upload text-sm"></i> 
                                    <span>Upload File</span>
                                </button>
                                
                                <!-- Template Download Button -->
                                <a href="{{ route('admin.matapelajaran.template') }}" 
                                   class="w-full sm:w-auto bg-white border border-blue-300 text-blue-700 font-semibold px-4 py-2 rounded-lg flex items-center justify-center gap-2 hover:bg-blue-50 transition-all shadow-md text-sm">
                                    <i class="fas fa-file-excel text-green-600 text-sm"></i> 
                                    <span>Template Excel</span>
                                </a>
                                
                                <!-- Info Button -->
                                <button type="button" 
                                        onclick="showImportInfo()" 
                                        class="w-full sm:w-auto bg-white border border-blue-300 text-blue-700 px-3 py-2 rounded-lg hover:bg-blue-50 transition-all shadow-md text-sm"
                                        title="Info Format Import">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                            
                            <!-- Quick Info -->
                            <div class="text-xs text-blue-600 bg-blue-50 p-2 rounded border border-blue-200">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>Format yang didukung:</strong> Excel (.xlsx), CSV (.csv), TSV (.tsv/.txt)
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-br from-green-50 to-green-100 p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-green-200 relative overflow-hidden group">
                        <div class="absolute -right-6 -top-6 h-24 w-24 bg-green-300 opacity-20 rounded-full group-hover:scale-110 transition-all"></div>
                        <h3 class="text-green-800 font-medium text-lg mb-2 flex items-center">
                            <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                            Pengajar Mata Pelajaran
                        </h3>
                        <p class="text-green-700 text-sm mb-4">Kelola guru pengajar melalui popup modal di tabel mata pelajaran</p>
                        <div class="text-xs text-green-600 bg-green-50 p-2 rounded border border-green-200">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Cara:</strong> Klik tombol pengaturan di kolom aksi pada tabel mata pelajaran
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->                
                 <div class="mt-6 flex flex-wrap gap-3">
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="search" class="block w-full p-3 pl-10 text-sm text-gray-900 border border-gray-200 rounded-lg bg-gray-50 focus:ring-amber-500 focus:border-amber-500" placeholder="Cari mata pelajaran...">
                    </div>
                    <div class="flex gap-2">
                        <select id="filterJenis" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-2.5 min-w-[180px]">
                            <option value="">Semua Jenis</option>
                            <option value="Wajib">Mata Pelajaran Wajib</option>
                            <option value="Kejuruan">Mata Pelajaran Kejuruan</option>
                        </select>
                    </div>
                    <button id="resetFilter" class="bg-blue-100 text-blue-600 hover:bg-blue-200 px-4 py-2.5 rounded-lg flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Reset Filter
                    </button>
                </div>
                
                <!-- Kelas Filter -->                
                 <div class="mt-3 bg-gray-50 border border-gray-200 p-3 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-2">Filter berdasarkan Kelas:</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                        @if(isset($kelas_list) && count($kelas_list) > 0)
                            @php
                                $uniqueClassNames = [];
                                foreach ($kelas_list as $k) {
                                    // Use nama_kelas directly instead of constructing from tingkat + jurusan
                                    if ($k && $k->nama_kelas && !in_array($k->nama_kelas, $uniqueClassNames)) {
                                        $uniqueClassNames[] = $k->nama_kelas;
                                    }
                                }
                                sort($uniqueClassNames);
                            @endphp
                            
                            @foreach($uniqueClassNames as $className)
                            <div class="flex items-center px-3 py-2 bg-white rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                                <input type="checkbox" name="filterKelasList[]" id="filter_kelas_{{ str_replace(' ', '_', $className) }}" value="{{ $className }}" class="filter-kelas-checkbox rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                <label for="filter_kelas_{{ str_replace(' ', '_', $className) }}" class="ml-2 text-sm text-gray-700">{{ $className }}</label>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-center px-3 py-2 bg-white rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                                <input type="checkbox" name="filterKelasList[]" id="filter_kelas_x_rpl_1" value="X RPL 1" class="filter-kelas-checkbox rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                <label for="filter_kelas_x_rpl_1" class="ml-2 text-sm text-gray-700">X RPL 1</label>
                            </div>
                            <div class="flex items-center px-3 py-2 bg-white rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                                <input type="checkbox" name="filterKelasList[]" id="filter_kelas_xi_rpl_1" value="XI RPL 1" class="filter-kelas-checkbox rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                <label for="filter_kelas_xi_rpl_1" class="ml-2 text-sm text-gray-700">XI RPL 1</label>
                            </div>
                            <div class="flex items-center px-3 py-2 bg-white rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                                <input type="checkbox" name="filterKelasList[]" id="filter_kelas_xii_rpl_1" value="XII RPL 1" class="filter-kelas-checkbox rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                <label for="filter_kelas_xii_rpl_1" class="ml-2 text-sm text-gray-700">XII RPL 1</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>       
        
        <!-- Mata Pelajaran Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 mt-2 mr-2">
                    <div class="rounded-full h-12 w-12 bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-layer-group text-blue-600"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Total Mata Pelajaran</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMapel }}</p>
                <div class="flex items-center text-green-600 text-xs mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>Aktif</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 mt-2 mr-2">
                    <div class="rounded-full h-12 w-12 bg-green-100 flex items-center justify-center">
                        <i class="fas fa-users-cog text-green-600"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Guru Pengajar</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $guruPengajar->count() }}</p>
                <div class="flex items-center text-green-600 text-xs mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>
                    <span>Tersedia</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 mt-2 mr-2">
                    <div class="rounded-full h-12 w-12 bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-book text-blue-600"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Mata Pelajaran Wajib</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMapelWajib }}</p>
                <div class="flex items-center text-blue-600 text-xs mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span>Mata Pelajaran Wajib</span>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm hover:shadow-md transition-all border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 mt-2 mr-2">
                    <div class="rounded-full h-12 w-12 bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-book-open text-amber-600"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-medium">Mata Pelajaran Kejuruan</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalMapelKejuruan }}</p>
                <div class="flex items-center text-amber-600 text-xs mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <span>Mata Pelajaran Kejuruan</span>
                </div>
            </div>
        </div>

        <!-- Notifikasi Flash -->
        @if(session('success'))
            <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300 flex items-center gap-2">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 rounded bg-red-100 text-red-800 border border-red-300 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <!-- Mata Pelajaran Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Kode</span>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'kode', 'direction' => 'asc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'kode' && request('direction') == 'asc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-up text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'kode', 'direction' => 'desc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'kode' && request('direction') == 'desc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-down text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Mata Pelajaran</span>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'nama', 'direction' => 'asc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'nama' && request('direction') == 'asc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-up text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'nama', 'direction' => 'desc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'nama' && request('direction') == 'desc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-down text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kelas
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Jenis</span>
                                    <div class="flex flex-col">
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'jenis', 'direction' => 'asc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'jenis' && request('direction') == 'asc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-up text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.matapelajaran.index', ['sort' => 'jenis', 'direction' => 'desc']) }}" 
                                           class="text-gray-400 hover:text-gray-600 {{ request('sort') == 'jenis' && request('direction') == 'desc' ? 'text-blue-600' : '' }}">
                                            <i class="fas fa-caret-down text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Guru Pengajar
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Mata Pelajaran Items -->                        @forelse($mataPelajaran as $mp)                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $mp->kode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $mp->nama }}</div>
                            </td>                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-500">
                                    @if(isset($mp->assigned_kelas) && $mp->assigned_kelas->count() > 0)
                                        @php
                                            $assignedKelasNames = [];
                                            foreach ($mp->assigned_kelas as $kelas) {
                                                // Use nama_kelas directly instead of constructing from tingkat + jurusan
                                                if ($kelas && $kelas->nama_kelas) {
                                                    $assignedKelasNames[] = $kelas->nama_kelas;
                                                }
                                            }
                                            $totalKelas = count($assignedKelasNames);
                                        @endphp
                                        
                                        @if($totalKelas > 4)
                                            @php
                                                $halfPoint = ceil($totalKelas / 2);
                                                $firstRow = array_slice($assignedKelasNames, 0, $halfPoint);
                                                $secondRow = array_slice($assignedKelasNames, $halfPoint);
                                            @endphp
                                            <div class="flex flex-wrap gap-1 mb-1">
                                                @foreach($firstRow as $kelas)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">{{ $kelas }}</span>
                                                @endforeach
                                            </div>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($secondRow as $kelas)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">{{ $kelas }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($assignedKelasNames as $kelas)
                                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">{{ $kelas }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Belum ada kelas yang ditugaskan</span>
                                    @endif
                                </div>
                            </td>
                                </div>
                            </td><td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $jenisColor = $mp->jenis == 'Wajib' ? 'blue' : 'amber';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $jenisColor }}-100 text-{{ $jenisColor }}-800">
                                    {{ $mp->jenis }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500">
                                    @if(isset($mp->assigned_guru) && $mp->assigned_guru->count() > 0)
                                        @if($mp->assigned_guru->count() == 1)
                                            {{ $mp->assigned_guru->first()->nama }}
                                        @else
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($mp->assigned_guru as $guru)
                                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-md text-xs">
                                                        {{ $guru->nama }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Belum ada guru yang ditugaskan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.matapelajaran.edit', $mp->kode) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 p-1.5 rounded hover:bg-indigo-200 transition-colors"
                                       title="Edit Mata Pelajaran">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.matapelajaran.show', $mp->kode) }}" 
                                       class="text-blue-600 hover:text-blue-900 bg-blue-100 p-1.5 rounded hover:bg-blue-200 transition-colors"
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button"
                                            onclick="openAssignTeacherModal({{ $mp->id }}, '{{ $mp->nama }}')"
                                            class="text-green-600 hover:text-green-900 bg-green-100 p-1.5 rounded hover:bg-green-200 transition-colors"
                                            title="Kelola Guru & Kelas">
                                        <i class="fas fa-cogs"></i>
                                    </button>
                                    <button 
                                        class="delete-mapel text-red-600 hover:text-red-900 bg-red-100 p-1.5 rounded hover:bg-red-200 transition-colors" 
                                        data-id="{{ $mp->kode }}" 
                                        data-name="{{ $mp->nama }}"
                                        title="Hapus Mata Pelajaran">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Showing Results Info -->
            <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
                <div class="flex-1 flex justify-center">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $mataPelajaran->count() }}</span>
                            mata pelajaran
                        </p>
                    </div>
                </div>
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
                <i class="fas fa-file-import text-blue-600 mr-2"></i>
                Import Mata Pelajaran
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
            <form id="modalImportForm" action="{{ route('admin.matapelajaran.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- File Upload Section -->
                <div class="mb-6">
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors bg-gray-50">
                        <div class="mb-4">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                            <p class="text-lg font-medium text-gray-700">Upload File Mata Pelajaran</p>
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
                                class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-semibold px-6 py-3 rounded-lg transition-colors">
                            <i class="fas fa-folder-open mr-2"></i>Pilih File
                        </button>
                        
                        <!-- File Info -->
                        <div id="modalFileInfo" class="hidden mt-4 p-4 bg-white rounded-lg border text-left">
                            <div id="modalFileDetails" class="text-sm"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Format Information -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
                    <h5 class="font-medium text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Format File Import
                    </h5>
                    <div class="text-sm text-blue-700 space-y-1">
                        <p><strong>Kolom yang diperlukan:</strong></p>
                        <ul class="list-disc list-inside ml-4 space-y-1">
                            <li><strong>kode</strong> - Kode mata pelajaran (unik, wajib)</li>
                            <li><strong>nama</strong> - Nama mata pelajaran (wajib)</li>
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
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:bg-gray-400 disabled:cursor-not-allowed">
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
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                Detail Format Import Mata Pelajaran
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
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
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
                <a href="{{ route('admin.matapelajaran.template') }}" 
                   class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center">
                    <i class="fas fa-file-excel mr-1 text-white"></i>Template Excel
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

<!-- Assign Teacher Modal -->
<div id="assignTeacherModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-plus text-green-600 mr-2"></i>
                Kelola Pengajar Mata Pelajaran
            </h3>
            <button type="button" 
                    onclick="closeAssignTeacherModal()" 
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div id="assignTeacherContent">
                <!-- Subject info -->
                <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="font-medium text-blue-800 mb-1">Mata Pelajaran:</h4>
                    <p class="text-blue-700" id="selectedSubjectName">-</p>
                </div>
                
                <!-- Current assignments -->
                <div class="mb-6">
                    <h5 class="font-medium text-gray-800 mb-3">Guru yang Sudah Ditugaskan:</h5>
                    <div id="currentAssignments" class="space-y-2">
                        <!-- Will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Add new assignment -->
                <div class="border-t pt-4">
                    <h5 class="font-medium text-gray-800 mb-3">Tambah Pengajar Baru:</h5>
                    <form id="assignTeacherForm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Guru:</label>
                                <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50">
                                    @foreach($guruPengajar as $guru)
                                        <div class="flex items-center mb-2 p-2 hover:bg-blue-50 rounded">
                                            <input type="checkbox" 
                                                   id="assign_guru_{{ $guru->id }}" 
                                                   value="{{ $guru->id }}" 
                                                   class="assign-guru-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="assign_guru_{{ $guru->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer flex-1">
                                                <span class="font-medium">{{ $guru->nama }}</span>
                                                @if($guru->nip)
                                                    <span class="text-gray-500 text-xs block">NIP: {{ $guru->nip }}</span>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <button type="button" 
                                            onclick="toggleAllGuru(true)" 
                                            class="text-xs text-blue-600 hover:text-blue-800 mr-3">
                                        Pilih Semua
                                    </button>
                                    <button type="button" 
                                            onclick="toggleAllGuru(false)" 
                                            class="text-xs text-gray-600 hover:text-gray-800">
                                        Hapus Semua
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas:</label>
                                <div class="max-h-48 overflow-y-auto border border-gray-300 rounded-lg p-3 bg-gray-50">
                                    @foreach($kelas_list as $kelas)
                                        <div class="flex items-center mb-2 p-2 hover:bg-green-50 rounded">
                                            <input type="checkbox" 
                                                   id="assign_kelas_{{ $kelas->id }}" 
                                                   value="{{ $kelas->id }}" 
                                                   class="assign-kelas-checkbox rounded border-gray-300 text-green-600 focus:ring-green-500">
                                            <label for="assign_kelas_{{ $kelas->id }}" class="ml-3 text-sm text-gray-700 cursor-pointer">{{ $kelas->nama_kelas ?? 'Kelas tidak ditemukan' }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-2">
                                    <button type="button" 
                                            onclick="toggleAllKelas(true)" 
                                            class="text-xs text-green-600 hover:text-green-800 mr-3">
                                        Pilih Semua
                                    </button>
                                    <button type="button" 
                                            onclick="toggleAllKelas(false)" 
                                            class="text-xs text-gray-600 hover:text-gray-800">
                                        Hapus Semua
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selection Summary -->
                        <div class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="text-sm text-blue-800">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-user-check text-blue-600 mr-2"></i>
                                    <span>Guru terpilih: <span id="selectedGuruCount">0</span></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-school text-blue-600 mr-2"></i>
                                    <span>Kelas terpilih: <span id="selectedKelasCount">0</span></span>
                                </div>
                                <div class="text-xs text-blue-600 mt-1">
                                    Total assignment yang akan dibuat: <span id="totalAssignments">0</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" 
                                    onclick="submitAssignTeacher()" 
                                    id="submitAssignBtn"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    disabled>
                                <i class="fas fa-plus mr-1"></i>Tugaskan Guru
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex items-center justify-end p-4 border-t border-gray-200 bg-gray-50">
            <button type="button" 
                    onclick="closeAssignTeacherModal()" 
                    class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Tutup
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global functions that are called from onclick events
    
    // Function to show notification - moved to global scope
    function showNotification(message, type = 'success') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 ${type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700'} p-4 shadow-md rounded-r-md`;
        notification.setAttribute('role', 'alert');
        
        // Create notification content
        const content = document.createElement('div');
        content.className = 'flex items-center';
        
        const icon = document.createElement('i');
        icon.className = `fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2`;
        
        const text = document.createElement('p');
        text.textContent = message;
        
        const closeButton = document.createElement('button');
        closeButton.className = `ml-4 ${type === 'success' ? 'text-green-800 hover:text-green-900' : 'text-red-800 hover:text-red-900'}`;
        closeButton.innerHTML = '<i class="fas fa-times"></i>';
        closeButton.onclick = () => notification.remove();
        
        // Assemble notification
        content.appendChild(icon);
        content.appendChild(text);
        content.appendChild(closeButton);
        notification.appendChild(content);
        
        // Add to document
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    // Function to update statistics
    function updateStatistics(change) {
        // Get all statistics elements (first one is total)
        const statElements = document.querySelectorAll('.text-3xl.font-bold.text-gray-800');
        
        // Update total count
        if (statElements.length > 0) {
            let currentTotal = parseInt(statElements[0].textContent);
            if (!isNaN(currentTotal)) {
                statElements[0].textContent = currentTotal + change;
            }
        }
        
        // We would need to know which type of subject was deleted to update the correct counter
        // This simplified version just updates the total count
    }
    
    // Attach to window for global access
    window.showNotification = showNotification;
    window.updateStatistics = updateStatistics;
    
    // Show import info modal
    function showImportInfo() {
        const modal = document.getElementById('importInfoModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Try to load import info when modal is opened
        loadImportInfo();
    }
    
    // Close import info modal
    function closeImportInfo() {
        document.getElementById('importInfoModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
    
    // Attach to window for global access
    window.showImportInfo = showImportInfo;
    window.closeImportInfo = closeImportInfo;
    
    // Import Upload Modal Functions
    function openImportModal() {
        const modal = document.getElementById('importUploadModal');
        if (modal) {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
    }
    
    // Make sure functions are attached to window for global access
    window.openImportModal = openImportModal;

    function closeImportModal() {
        const modal = document.getElementById('importUploadModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reset form
        const form = document.getElementById('modalImportForm');
        if (form) form.reset();
        
        // Hide file info
        const fileInfo = document.getElementById('modalFileInfo');
        if (fileInfo) fileInfo.classList.add('hidden');
        
        // Reset button
        const submitBtn = document.getElementById('modalImportSubmitBtn');
        const submitText = document.getElementById('modalImportSubmitText');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('disabled:bg-gray-400', 'disabled:cursor-not-allowed');
        }
        if (submitText) submitText.textContent = 'Import Data';
    }
    
    // Attach to window for global access
    window.closeImportModal = closeImportModal;

    function handleModalFileSelect(input) {
        const fileInfo = document.getElementById('modalFileInfo');
        const fileDetails = document.getElementById('modalFileDetails');
        const submitBtn = document.getElementById('modalImportSubmitBtn');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileName = file.name;
            const fileSize = (file.size / (1024 * 1024)).toFixed(2);
            const fileExtension = fileName.split('.').pop().toLowerCase();
            
            // Validate file type
            const allowedTypes = ['xlsx', 'csv', 'tsv', 'txt'];
            if (!allowedTypes.includes(fileExtension)) {
                alert('Format file tidak didukung. Gunakan file .xlsx, .csv, .tsv, atau .txt');
                input.value = '';
                return;
            }
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB');
                input.value = '';
                return;
            }
            
            // Show file info
            let icon = 'fas fa-file';
            let iconColor = 'text-gray-600';
            
            switch(fileExtension) {
                case 'xlsx':
                    icon = 'fas fa-file-excel';
                    iconColor = 'text-green-600';
                    break;
                case 'csv':
                    icon = 'fas fa-file-csv';
                    iconColor = 'text-blue-600';
                    break;
                case 'tsv':
                case 'txt':
                    icon = 'fas fa-file-alt';
                    iconColor = 'text-purple-600';
                    break;
            }
            
            fileDetails.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="${icon} ${iconColor} mr-3 text-lg"></i>
                        <div>
                            <p class="font-medium text-gray-800">${fileName}</p>
                            <p class="text-xs text-gray-500">${fileSize} MB • ${fileExtension.toUpperCase()}</p>
                        </div>
                    </div>
                    <div class="text-green-600">
                        <i class="fas fa-check-circle text-lg"></i>
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
    
    // Attach to window for global access
    window.handleModalFileSelect = handleModalFileSelect;

    function submitModalImportForm() {
        const form = document.getElementById('modalImportForm');
        const submitBtn = document.getElementById('modalImportSubmitBtn');
        const submitText = document.getElementById('modalImportSubmitText');
        
        if (!form) return;
        
        // Validate file is selected
        const fileInput = document.getElementById('modalFileInput');
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
    
    // Attach to window for global access  
    window.submitModalImportForm = submitModalImportForm;    
    // DOM Content Loaded Event
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('keyup', function() {
            applyFilters();
        });
        
        // Filter functionality
        const filterJenis = document.getElementById('filterJenis');
        const filterKelasCheckboxes = document.querySelectorAll('.filter-kelas-checkbox');
          function applyFilters() {
            const searchTerm = searchInput.value.toLowerCase();
            const jenisFilter = filterJenis.value.toLowerCase();
            const checkedKelas = Array.from(filterKelasCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value.toLowerCase());
                
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const kelasCell = row.querySelectorAll('td')[2];
                const kelas = kelasCell.textContent.toLowerCase().replace(/\s+/g, ' '); // Normalize spaces
                const jenis = row.querySelectorAll('td')[3].textContent.toLowerCase();
                
                let showRow = true;
                
                // Apply search filter
                if(searchTerm && !text.includes(searchTerm)) {
                    showRow = false;
                }
                
                // Apply jenis filter
                if(jenisFilter && !jenis.includes(jenisFilter)) {
                    showRow = false;
                }
                  // Apply kelas filter - show row if ANY of the checked classes match
                if(checkedKelas.length > 0) {
                    let matchesAnyClass = false;
                    // Get original kelas string from the row (without badges formatting)
                    const kelasBadges = kelasCell.querySelectorAll('span');
                    const kelasList = Array.from(kelasBadges).map(badge => badge.textContent.trim().toLowerCase());
                    
                    for(const kelasValue of checkedKelas) {
                        // Check if any of the class badges match the filter value
                        if(kelasList.some(k => k === kelasValue.toLowerCase())) {
                            matchesAnyClass = true;
                            break;
                        }
                    }
                    
                    if(!matchesAnyClass) {
                        showRow = false;
                    }
                }
                
                row.style.display = showRow ? '' : 'none';
            });
        }
        
        // Add event listeners
        filterJenis.addEventListener('change', applyFilters);
        filterKelasCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });
        
        // Reset filter button
        document.getElementById('resetFilter').addEventListener('click', function() {
            filterJenis.value = '';
            filterKelasCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            searchInput.value = '';
            
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.style.display = '';
            });
        });

        // Handle delete with AJAX
        const deleteButtons = document.querySelectorAll('.delete-mapel');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                
                if (confirm(`Apakah Anda yakin ingin menghapus mata pelajaran "${name}"?`)) {
                    // Create the CSRF token element
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Create FormData object
                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    formData.append('_token', csrfToken);
                    
                    // Create and configure the request
                    fetch(`/admin/matapelajaran/${id}`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the row from the table
                            const row = this.closest('tr');
                            row.remove();
                            
                            // Show success notification
                            showNotification(data.message, 'success');
                            
                            // Update statistics if needed
                            updateStatistics(-1);
                        } else {
                            // Show error notification
                            showNotification(data.message || 'Terjadi kesalahan saat menghapus data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification('Terjadi kesalahan saat menghapus data', 'error');
                    });
                }
            });
        });
          // Function to show notification
        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 ${type === 'success' ? 'bg-green-100 border-l-4 border-green-500 text-green-700' : 'bg-red-100 border-l-4 border-red-500 text-red-700'} p-4 shadow-md rounded-r-md`;
            notification.setAttribute('role', 'alert');
            
            // Create notification content
            const content = document.createElement('div');
            content.className = 'flex items-center';
            
            const icon = document.createElement('i');
            icon.className = `fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2`;
            
            const text = document.createElement('p');
            text.textContent = message;
            
            const closeButton = document.createElement('button');
            closeButton.className = `ml-4 ${type === 'success' ? 'text-green-800 hover:text-green-900' : 'text-red-800 hover:text-red-900'}`;
            closeButton.innerHTML = '<i class="fas fa-times"></i>';
            closeButton.onclick = () => notification.remove();
            
            // Assemble notification
            content.appendChild(icon);
            content.appendChild(text);
            content.appendChild(closeButton);
            notification.appendChild(content);
            
            // Add to document
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }
          // Function to update statistics
        function updateStatistics(change) {
            // Get all statistics elements (first one is total)
            const statElements = document.querySelectorAll('.text-3xl.font-bold.text-gray-800');
            
            // Update total count
            if (statElements.length > 0) {
                let currentTotal = parseInt(statElements[0].textContent);
                if (!isNaN(currentTotal)) {
                    statElements[0].textContent = currentTotal + change;
                }
            }
            
            // We would need to know which type of subject was deleted to update the correct counter
            // This simplified version just updates the total count
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const uploadModal = document.getElementById('importUploadModal');
        const infoModal = document.getElementById('importInfoModal');
        
        if (event.target === uploadModal) {
            closeImportModal();
        }
        if (event.target === infoModal) {
            closeImportInfo();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const uploadModal = document.getElementById('importUploadModal');
            const infoModal = document.getElementById('importInfoModal');
            
            if (!uploadModal.classList.contains('hidden')) {
                closeImportModal();
            } else if (!infoModal.classList.contains('hidden')) {
                closeImportInfo();
            }
        }
    });
    
    // Load detailed import info
    async function loadImportInfo() {
        try {
            const response = await fetch('{{ route("admin.matapelajaran.import-info") }}');
            
            if (!response.ok) {
                // Show fallback content
                showFallbackImportInfo();
                return;
            }
            
            const data = await response.json();
            
            if (data.success) {
                updateImportInfoModal(data.data);
            } else {
                showFallbackImportInfo();
            }
        } catch (error) {
            showFallbackImportInfo();
        }
    }
    
    // Show fallback import info when API is not available
    function showFallbackImportInfo() {
        const modalContent = document.getElementById('importInfoContent');
        if (!modalContent) return;
        
        modalContent.innerHTML = `
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-file-excel text-green-600 mr-2"></i>
                    Format Import Mata Pelajaran
                </h4>
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-sm text-gray-700 mb-3">Format file yang didukung: Excel (.xlsx), CSV (.csv), TSV (.tsv)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium text-gray-800 mb-2">Kolom yang Diperlukan:</h5>
                            <ul class="text-sm text-gray-600 space-y-1">
                                <li><strong>kode:</strong> Kode mata pelajaran (unik, wajib)</li>
                                <li><strong>nama:</strong> Nama mata pelajaran (wajib)</li>
                                <li><strong>guru_id:</strong> ID guru pengajar (opsional)</li>
\                                <li><strong>jenis:</strong> Jenis (Wajib/Kejuruan/Muatan Lokal)</li>
                                <li><strong>tahun_ajaran:</strong> Tahun ajaran (YYYY/YYYY)</li>
                                <li><strong>kkm:</strong> KKM (0-100)</li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-medium text-gray-800 mb-2">Contoh Data:</h5>
                            <div class="bg-white p-3 rounded border text-xs font-mono">
                                kode   | nama | guru_id <br>
                                MP001 | Matematika | 1 <br>
                                MP002 | B. Inggris | 2 <br>
                                MP003 | Pemrograman | 3
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                    Aturan Validasi
                </h4>
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <ul class="text-sm text-gray-700 space-y-2">
                        <li class="flex items-start"><i class="fas fa-check-circle text-red-500 mr-2 mt-0.5"></i>File maksimal 2MB</li>
                        <li class="flex items-start"><i class="fas fa-check-circle text-red-500 mr-2 mt-0.5"></i>Kode mata pelajaran harus unik</li>
                        <li class="flex items-start"><i class="fas fa-check-circle text-red-500 mr-2 mt-0.5"></i>Kolom kode dan nama wajib diisi</li>
                        <li class="flex items-start"><i class="fas fa-check-circle text-red-500 mr-2 mt-0.5"></i>Header (baris pertama) akan diabaikan</li>
                    </ul>
                </div>
            </div>
        `;
    }
    
    // Update modal content with import info  
    function updateImportInfoModal(data) {
        const modalContent = document.getElementById('importInfoContent');
        if (!modalContent || !data) {
            return;
        }
        
        let content = '';
        
        // Excel format
        if (data.excel) {
            content += `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-excel text-green-600 mr-2"></i>
                        Format Excel (.xlsx) - Recommended
                    </h4>
                    <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                        <p class="text-sm text-gray-700 mb-2">${data.excel.description}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Kolom yang Diperlukan:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    ${data.excel.columns.map(col => `<li><strong>${col.name}:</strong> ${col.description}</li>`).join('')}
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Contoh Data:</h5>
                                <div class="bg-white p-3 rounded border text-xs font-mono">
                                    ${data.excel.sample_data.map(row => `${row.join(' | ')}`).join('<br>')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // CSV format
        if (data.csv) {
            content += `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-csv text-blue-600 mr-2"></i>
                        Format CSV (.csv)
                    </h4>
                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                        <p class="text-sm text-gray-700 mb-2">${data.csv.description}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Kolom yang Diperlukan:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    ${data.csv.columns.map(col => `<li><strong>${col.name}:</strong> ${col.description}</li>`).join('')}
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Contoh Data:</h5>
                                <div class="bg-white p-3 rounded border text-xs font-mono">
                                    ${data.csv.sample_data.join('<br>')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // TSV format
        if (data.tsv) {
            content += `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt text-purple-600 mr-2"></i>
                        Format TSV (.tsv/.txt)
                    </h4>
                    <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                        <p class="text-sm text-gray-700 mb-2">${data.tsv.description}</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Kolom yang Diperlukan:</h5>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    ${data.tsv.columns.map(col => `<li><strong>${col.name}:</strong> ${col.description}</li>`).join('')}
                                </ul>
                            </div>
                            <div>
                                <h5 class="font-medium text-gray-800 mb-2">Contoh Data:</h5>
                                <div class="bg-white p-3 rounded border text-xs font-mono">
                                    ${data.tsv.sample_data.join('<br>')}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Validation rules
        if (data.validation_rules) {
            content += `
                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-shield-alt text-red-600 mr-2"></i>
                        Aturan Validasi
                    </h4>
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                        <ul class="text-sm text-gray-700 space-y-2">
                            ${data.validation_rules.map(rule => `<li class="flex items-start"><i class="fas fa-check-circle text-red-500 mr-2 mt-0.5"></i>${rule}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            `;
        }
        
        modalContent.innerHTML = content;
    };
    
    // Assign Teacher Modal Functions
    let currentSubjectId = null;
    
    function openAssignTeacherModal(subjectId, subjectName) {
        currentSubjectId = subjectId;
        document.getElementById('selectedSubjectName').textContent = subjectName;
        
        // Load current assignments
        loadCurrentAssignments(subjectId);
        
        const modal = document.getElementById('assignTeacherModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeAssignTeacherModal() {
        const modal = document.getElementById('assignTeacherModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        
        // Reset form
        document.querySelectorAll('.assign-guru-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.assign-kelas-checkbox').forEach(cb => cb.checked = false);
        updateSelectionCounts();
        currentSubjectId = null;
    }
    
    async function loadCurrentAssignments(subjectId) {
        const container = document.getElementById('currentAssignments');
        container.innerHTML = '<div class="text-center py-2"><i class="fas fa-spinner fa-spin mr-2"></i>Memuat...</div>';
        
        try {
            const response = await fetch(`/admin/matapelajaran/${subjectId}/assignments`);
            const data = await response.json();
            
            if (data.success && data.assignments.length > 0) {
                let html = '';
                data.assignments.forEach(assignment => {
                    html += `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                            <div>
                                <span class="font-medium text-gray-800">${assignment.guru.nama}</span>
                                <span class="text-sm text-gray-600 ml-2">(${assignment.kelas ? assignment.kelas.nama_kelas : 'Kelas tidak ditemukan'})</span>
                            </div>
                            <button onclick="removeAssignment(${assignment.id})" 
                                    class="text-red-600 hover:text-red-800 p-1" 
                                    title="Hapus penugasan">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = '<div class="text-gray-500 text-center py-4 italic">Belum ada guru yang ditugaskan</div>';
            }
        } catch (error) {
            container.innerHTML = '<div class="text-red-500 text-center py-4">Error loading assignments</div>';
        }
    }
    
    async function submitAssignTeacher() {
        const guruIds = Array.from(document.querySelectorAll('.assign-guru-checkbox:checked')).map(cb => cb.value);
        const kelasIds = Array.from(document.querySelectorAll('.assign-kelas-checkbox:checked')).map(cb => cb.value);
        
        if (guruIds.length === 0) {
            alert('Silakan pilih minimal satu guru');
            return;
        }
        
        if (kelasIds.length === 0) {
            alert('Silakan pilih minimal satu kelas');
            return;
        }
        
        // Disable submit button during processing
        const submitBtn = document.getElementById('submitAssignBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...';
        
        try {
            const response = await fetch(`/admin/matapelajaran/${currentSubjectId}/assign-teacher`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    guru_ids: guruIds,
                    kelas_ids: kelasIds
                })
            });
            
            // Debug: log response status
            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);
            
            let data;
            try {
                const responseText = await response.text();
                console.log('Raw response text:', responseText);
                data = JSON.parse(responseText);
                console.log('Parsed response data:', data);
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                console.log('Failed to parse response');
                throw new Error('Invalid JSON response from server: ' + parseError.message);
            }
            
            if (response.status >= 200 && response.status < 300 && data.success) {
                // Show success notification
                showNotification(`Berhasil menugaskan ${data.created_count || guruIds.length * kelasIds.length} assignment guru!`, 'success');
                
                // Reload current assignments in modal
                loadCurrentAssignments(currentSubjectId);
                
                // Reset form
                document.querySelectorAll('.assign-guru-checkbox').forEach(cb => cb.checked = false);
                document.querySelectorAll('.assign-kelas-checkbox').forEach(cb => cb.checked = false);
                updateSelectionCounts();
                
                // Update the table row in background without refreshing page
                updateTableRowData(currentSubjectId);
                
            } else {
                console.error('Request failed or unsuccessful response:', {
                    status: response.status,
                    statusText: response.statusText,
                    success: data.success,
                    message: data.message
                });
                showNotification(data.message || `Terjadi kesalahan (Status: ${response.status})`, 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menugaskan guru: ' + error.message, 'error');
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            updateSelectionCounts(); // This will disable again if needed
        }
    }
    
    async function removeAssignment(assignmentId) {
        if (!confirm('Apakah Anda yakin ingin menghapus penugasan ini?')) {
            return;
        }
        
        try {
            const response = await fetch(`/admin/jadwal-pelajaran/${assignmentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Show success notification
                showNotification('Penugasan berhasil dihapus!', 'success');
                
                // Reload current assignments in modal
                loadCurrentAssignments(currentSubjectId);
                
                // Update the table row in background without refreshing page
                updateTableRowData(currentSubjectId);
                
            } else {
                showNotification(data.message || 'Terjadi kesalahan', 'error');
            }
        } catch (error) {
            showNotification('Terjadi kesalahan saat menghapus penugasan', 'error');
        }
    }
    
    // Function to update table row data without page refresh
    async function updateTableRowData(subjectId) {
        try {
            const response = await fetch(`/admin/matapelajaran/${subjectId}/assignments`);
            const data = await response.json();
            
            if (data.success) {
                // Find the table row for this subject
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const editButton = row.querySelector('[onclick*="openAssignTeacherModal"]');
                    if (editButton && editButton.getAttribute('onclick').includes(subjectId)) {
                        // Update guru pengajar column (column index 4)
                        const guruCell = row.cells[4];
                        if (data.assigned_guru && data.assigned_guru.length > 0) {
                            if (data.assigned_guru.length === 1) {
                                guruCell.innerHTML = `<div class="text-sm text-gray-500">${data.assigned_guru[0].nama}</div>`;
                            } else {
                                let guruHtml = '<div class="text-sm text-gray-500"><div class="flex flex-wrap gap-1">';
                                data.assigned_guru.forEach(guru => {
                                    guruHtml += `<span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-md text-xs">${guru.nama}</span>`;
                                });
                                guruHtml += '</div></div>';
                                guruCell.innerHTML = guruHtml;
                            }
                        } else {
                            guruCell.innerHTML = '<div class="text-sm text-gray-500"><span class="text-gray-400 italic">Belum ada guru yang ditugaskan</span></div>';
                        }
                        
                        // Update kelas column (column index 2)
                        const kelasCell = row.cells[2];
                        if (data.assigned_kelas && data.assigned_kelas.length > 0) {
                            const totalKelas = data.assigned_kelas.length;
                            if (totalKelas > 4) {
                                const halfPoint = Math.ceil(totalKelas / 2);
                                const firstRow = data.assigned_kelas.slice(0, halfPoint);
                                const secondRow = data.assigned_kelas.slice(halfPoint);
                                
                                let kelasHtml = '<div class="text-sm text-gray-500">';
                                kelasHtml += '<div class="flex flex-wrap gap-1 mb-1">';
                                firstRow.forEach(kelas => {
                                    kelasHtml += `<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">${kelas.nama_kelas}</span>`;
                                });
                                kelasHtml += '</div><div class="flex flex-wrap gap-1">';
                                secondRow.forEach(kelas => {
                                    kelasHtml += `<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">${kelas.nama_kelas}</span>`;
                                });
                                kelasHtml += '</div></div>';
                                kelasCell.innerHTML = kelasHtml;
                            } else {
                                let kelasHtml = '<div class="text-sm text-gray-500"><div class="flex flex-wrap gap-1">';
                                data.assigned_kelas.forEach(kelas => {
                                    kelasHtml += `<span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs">${kelas.nama_kelas}</span>`;
                                });
                                kelasHtml += '</div></div>';
                                kelasCell.innerHTML = kelasHtml;
                            }
                        } else {
                            kelasCell.innerHTML = '<div class="text-sm text-gray-500"><span class="text-gray-400 italic">Belum ada kelas yang ditugaskan</span></div>';
                        }
                    }
                });
            }
        } catch (error) {
            console.log('Error updating table row:', error);
            // Silently fail, modal data is already updated
        }
    }

    // Attach functions to window for global access
    window.openAssignTeacherModal = openAssignTeacherModal;
    window.closeAssignTeacherModal = closeAssignTeacherModal;
    window.submitAssignTeacher = submitAssignTeacher;
    window.removeAssignment = removeAssignment;
    
    // Toggle functions for assign teacher modal
    function toggleAllGuru(selectAll) {
        document.querySelectorAll('.assign-guru-checkbox').forEach(checkbox => {
            checkbox.checked = selectAll;
        });
        updateSelectionCounts();
    }
    
    function toggleAllKelas(selectAll) {
        document.querySelectorAll('.assign-kelas-checkbox').forEach(checkbox => {
            checkbox.checked = selectAll;
        });
        updateSelectionCounts();
    }
    
    function updateSelectionCounts() {
        const selectedGuru = document.querySelectorAll('.assign-guru-checkbox:checked').length;
        const selectedKelas = document.querySelectorAll('.assign-kelas-checkbox:checked').length;
        const totalAssignments = selectedGuru * selectedKelas;
        
        document.getElementById('selectedGuruCount').textContent = selectedGuru;
        document.getElementById('selectedKelasCount').textContent = selectedKelas;
        document.getElementById('totalAssignments').textContent = totalAssignments;
        
        // Enable/disable submit button based on selection
        const submitBtn = document.getElementById('submitAssignBtn');
        if (submitBtn) {
            submitBtn.disabled = selectedGuru === 0 || selectedKelas === 0;
        }
    }
    
    // Attach to window for global access
    window.toggleAllGuru = toggleAllGuru;
    window.toggleAllKelas = toggleAllKelas;
    window.updateSelectionCounts = updateSelectionCounts;
    
    // Add event listeners when document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners for checkbox changes
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('assign-guru-checkbox') || 
                e.target.classList.contains('assign-kelas-checkbox')) {
                updateSelectionCounts();
            }
        });
    });
</script>
@endpush
