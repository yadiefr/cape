@extends('layouts.admin')

@section('title', 'Data Pendaftaran PPDB - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Data Pendaftaran PPDB</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('admin.ppdb.dashboard') }}" class="hover:text-blue-600">PPDB</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Data Pendaftaran</span>
                </li>
            </ul>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
            <p>{{ session('error') }}</p>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <div class="flex-1">
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div class="p-5">
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <div class="mb-4 lg:mb-0">
                            <h2 class="text-lg font-semibold text-gray-800">Statistik Pendaftaran</h2>
                            <p class="text-sm text-gray-500">Tahun Ajaran {{ $tahun }}</p>
                        </div>
                        
                        <div class="flex flex-wrap gap-3 items-center">
                            <button type="button" onclick="document.getElementById('modal-ppdb-setting').classList.remove('hidden')" class="flex items-center px-3 py-2 bg-gray-100 hover:bg-blue-100 text-blue-700 rounded-md shadow-sm border border-blue-200 font-medium transition-all">
                                <i class="fas fa-cog mr-2"></i> Pengaturan
                            </button>
                            <a href="{{ route('admin.ppdb.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                <i class="fas fa-plus-circle mr-2"></i> Tambah Pendaftar
                            </a>
                            <a href="{{ route('admin.ppdb.export', ['status' => $status, 'jurusan' => $jurusan]) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                                <i class="fas fa-file-export mr-2"></i> Export Data
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                        <!-- Card Statistik -->
                        <a href="{{ route('admin.ppdb.index', ['status' => 'all']) }}" class="block border border-gray-300 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-colors
                            {{ $status == 'all' ? 'border-blue-500 bg-blue-50' : '' }}">
                            <p class="text-sm font-medium text-gray-500">Semua</p>
                            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $total['semua'] }}</p>
                        </a>
                        
                        <a href="{{ route('admin.ppdb.index', ['status' => 'menunggu']) }}" class="block border border-gray-300 rounded-lg p-4 hover:border-yellow-300 hover:bg-yellow-50 transition-colors
                            {{ $status == 'menunggu' ? 'border-yellow-500 bg-yellow-50' : '' }}">
                            <p class="text-sm font-medium text-gray-500">Menunggu</p>
                            <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $total['menunggu'] }}</p>
                        </a>
                        
                        <a href="{{ route('admin.ppdb.index', ['status' => 'diterima']) }}" class="block border border-gray-300 rounded-lg p-4 hover:border-green-300 hover:bg-green-50 transition-colors
                            {{ $status == 'diterima' ? 'border-green-500 bg-green-50' : '' }}">
                            <p class="text-sm font-medium text-gray-500">Diterima</p>
                            <p class="text-2xl font-bold text-green-500 mt-1">{{ $total['diterima'] }}</p>
                        </a>
                        
                        <a href="{{ route('admin.ppdb.index', ['status' => 'ditolak']) }}" class="block border border-gray-300 rounded-lg p-4 hover:border-red-300 hover:bg-red-50 transition-colors
                            {{ $status == 'ditolak' ? 'border-red-500 bg-red-50' : '' }}">
                            <p class="text-sm font-medium text-gray-500">Ditolak</p>
                            <p class="text-2xl font-bold text-red-500 mt-1">{{ $total['ditolak'] }}</p>
                        </a>
                        
                        <a href="{{ route('admin.ppdb.index', ['status' => 'cadangan']) }}" class="block border border-gray-300 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-colors
                            {{ $status == 'cadangan' ? 'border-blue-500 bg-blue-50' : '' }}">
                            <p class="text-sm font-medium text-gray-500">Cadangan</p>
                            <p class="text-2xl font-bold text-blue-500 mt-1">{{ $total['cadangan'] }}</p>
                        </a>
                    </div>
                    
                    <!-- Filter dan Pencarian -->
                    <div class="mb-6">
                        <form action="{{ route('admin.ppdb.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="menunggu" {{ $status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                    <option value="cadangan" {{ $status == 'cadangan' ? 'selected' : '' }}>Cadangan</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="jurusan" class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
                                <select name="jurusan" id="jurusan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="all" {{ $jurusan == 'all' ? 'selected' : '' }}>Semua Jurusan</option>                                    @foreach($daftarJurusan as $j)
                                        <option value="{{ $j->id }}" {{ $jurusan == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
                                <input type="text" name="tahun" id="tahun" value="{{ $tahun }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                            
                            <div>
                                <label for="cari" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                                <div class="flex">
                                    <input type="text" name="cari" id="cari" value="{{ $cari }}" placeholder="Nomor pendaftaran / Nama / NISN" class="w-full border-gray-300 rounded-l-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <button type="submit" class="bg-blue-600 text-white px-4 rounded-r-md hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Tabel Data Pendaftaran -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nomor Pendaftaran
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama Lengkap
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        NISN
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Asal Sekolah
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jurusan
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($pendaftaran as $p)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $p->nomor_pendaftaran }}</div>
                                        @if($p->tanggal_pendaftaran)
                                            <div class="text-xs text-gray-500">{{ $p->tanggal_pendaftaran->format('d/m/Y') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $p->nama_lengkap }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $p->jenis_kelamin }} | {{ $p->tempat_lahir }}{{ $p->tanggal_lahir ? ', '.$p->tanggal_lahir->format('d/m/Y') : '' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $p->nisn }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">{{ $p->asal_sekolah }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-gray-900">{{ $p->jurusanPertama?->nama_jurusan ?? 'Tidak ada jurusan' }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $p->status == 'menunggu' ? 'bg-yellow-100 text-yellow-800' : 
                                            ($p->status == 'diterima' ? 'bg-green-100 text-green-800' : 
                                            ($p->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800')) }}">
                                            {{ ucfirst($p->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.ppdb.show', $p->id) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.ppdb.edit', $p->id) }}" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center">
                                        <div class="flex flex-col items-center">
                                            <img src="{{ asset('images/empty.svg') }}" alt="Tidak ada data" class="w-32 h-32 mb-4">
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada data pendaftaran</h3>
                                            <p class="text-gray-500">Belum ada pendaftar untuk saat ini</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $pendaftaran->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pengaturan PPDB -->
<div id="modal-ppdb-setting" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button type="button" onclick="document.getElementById('modal-ppdb-setting').classList.add('hidden')" class="absolute top-3 right-3 text-gray-400 hover:text-red-500 text-xl">
            <i class="fas fa-times"></i>
        </button>
        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <i class="fas fa-cog text-blue-500"></i> Pengaturan PPDB
        </h3>
        <form action="{{ route('admin.ppdb.updateStatusSetting') }}" method="POST" class="space-y-4">
            @csrf
            @method('POST')
            <div>
                <label for="is_ppdb_open" class="block text-sm font-medium text-gray-700 mb-1">Status PPDB</label>
                <select name="is_ppdb_open" id="is_ppdb_open" class="appearance-none border-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 rounded-md px-3 py-2 text-sm font-semibold transition-all duration-150 w-full {{ \App\Models\Settings::getValue('is_ppdb_open') == 'true' ? 'bg-green-50 border-green-400 text-green-700' : 'bg-red-50 border-red-400 text-red-700' }}">
                    <option value="true" class="text-green-700 bg-green-50" {{ \App\Models\Settings::getValue('is_ppdb_open') == 'true' ? 'selected' : '' }}>🟢 PPDB Dibuka</option>
                    <option value="false" class="text-red-700 bg-red-50" {{ \App\Models\Settings::getValue('is_ppdb_open') == 'false' ? 'selected' : '' }}>🔴 PPDB Ditutup</option>
                </select>
            </div>
            <div>
                <label for="ppdb_year" class="block text-sm font-medium text-gray-700 mb-1">Tahun PPDB</label>
                <input type="text" name="ppdb_year" id="ppdb_year" value="{{ \App\Models\Settings::getValue('ppdb_year', '2025/2026') }}" class="border-2 border-blue-200 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full" placeholder="Tahun">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="ppdb_start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="ppdb_start_date" id="ppdb_start_date" value="{{ \App\Models\Settings::getValue('ppdb_start_date', date('Y-m-d')) }}" class="border-2 border-blue-200 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                </div>
                <div>
                    <label for="ppdb_end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="ppdb_end_date" id="ppdb_end_date" value="{{ \App\Models\Settings::getValue('ppdb_end_date', date('Y-m-d', strtotime('+30 days'))) }}" class="border-2 border-blue-200 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 w-full">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('modal-ppdb-setting').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">Batal</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold shadow-sm transition-all">
                    <i class="fas fa-save mr-2"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
