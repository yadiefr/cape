@extends('layouts.admin')

@section('title', 'Manajemen PKL')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-briefcase mr-3 text-blue-600"></i>
                Manajemen Praktek Kerja Lapangan
            </h1>
            <p class="text-gray-600 mt-1">Kelola data PKL siswa dan monitoring kegiatan</p>
        </div>
        <a href="{{ route('admin.pkl.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Data PKL
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total PKL</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $praktikKerjaLapangan->total() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $praktikKerjaLapangan->where('status', 'berlangsung')->count() }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-play-circle text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $praktikKerjaLapangan->where('status', 'selesai')->count() }}</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Pengajuan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $praktikKerjaLapangan->where('status', 'pengajuan')->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
            </h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.pkl.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status PKL</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pengajuan" {{ request('status') == 'pengajuan' ? 'selected' : '' }}>Pengajuan</option>
                        <option value="berlangsung" {{ request('status') == 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusan as $j)
                            <option value="{{ $j->id }}" {{ request('jurusan_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jurusan }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="nama_siswa" class="block text-sm font-medium text-gray-700 mb-2">Nama Siswa</label>
                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ request('nama_siswa') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari berdasarkan nama siswa">
                </div>
                <div class="flex flex-col justify-end">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <a href="{{ route('admin.pkl.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-table mr-2 text-blue-600"></i>Daftar PKL Siswa
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($praktikKerjaLapangan as $key => $pkl)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ($praktikKerjaLapangan->currentPage() - 1) * $praktikKerjaLapangan->perPage() + $key + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ optional($pkl->siswa)->nama_lengkap ?? 'Siswa Tidak Ditemukan' }}</div>
                                    <div class="text-sm text-gray-500">NIS: {{ optional($pkl->siswa)->nis ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ optional(optional($pkl->siswa)->kelas)->nama_kelas ?? 'Kelas Tidak Ditemukan' }}</div>
                                    <div class="text-sm text-gray-500">{{ optional(optional($pkl->siswa)->jurusan)->nama_jurusan ?? 'Jurusan Tidak Ditemukan' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $pkl->nama_perusahaan }}</div>
                                    <div class="text-sm text-gray-500">{{ $pkl->bidang_usaha }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pkl->tanggal_mulai->format('d/m/Y') }} - {{ $pkl->tanggal_selesai->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pkl->status == 'pengajuan')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pengajuan
                                        </span>
                                    @elseif($pkl->status == 'berlangsung')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Berlangsung
                                        </span>
                                    @elseif($pkl->status == 'selesai')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    @elseif($pkl->status == 'gagal')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Gagal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.pkl.show', $pkl->id) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.pkl.edit', $pkl->id) }}" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pkl.destroy', $pkl->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data PKL</p>
                                        <p class="text-gray-400 text-sm mt-1">Belum ada siswa yang terdaftar PKL</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($praktikKerjaLapangan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $praktikKerjaLapangan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
