@extends('layouts.admin')

@section('title', 'Manajemen Kelas - SMK PGRI CIKAMPEK')

@section('main-content')
<div class="w-full px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Manajemen Kelas</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Manajemen Kelas</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Add Kelas Button and Search -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <!-- Search Form -->
        <div class="flex-1 max-w-md">
            <form action="{{ route('admin.kelas.index') }}" method="GET" class="flex">
                <div class="relative flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari nama kelas, jurusan, atau wali kelas..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                </div>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('admin.kelas.index') }}" 
                   class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>
        
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Kelas
        </a>
    </div>
    
    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-r-md" role="alert">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    
    @if(session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-md" role="alert">
        <p>{{ session('error') }}</p>
    </div>
    @endif
    
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.kelas.index', ['sort_by' => 'nama_kelas', 'sort_direction' => request('sort_by') == 'nama_kelas' && request('sort_direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" 
                               class="flex items-center text-gray-500 hover:text-gray-700">
                                Nama Kelas
                                @if(request('sort_by') == 'nama_kelas')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.kelas.index', ['sort_by' => 'jurusan_id', 'sort_direction' => request('sort_by') == 'jurusan_id' && request('sort_direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" 
                               class="flex items-center text-gray-500 hover:text-gray-700">
                                Jurusan
                                @if(request('sort_by') == 'jurusan_id')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total Siswa
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.kelas.index', ['sort_by' => 'tingkat', 'sort_direction' => request('sort_by') == 'tingkat' && request('sort_direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" 
                               class="flex items-center text-gray-500 hover:text-gray-700">
                                Tingkat
                                @if(request('sort_by') == 'tingkat')
                                    <i class="fas fa-sort-{{ request('sort_direction') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.kelas.index', ['sort_by' => 'tahun_ajaran', 'sort_direction' => request('sort_by') == 'tahun_ajaran' && request('sort_direction') == 'asc' ? 'desc' : 'asc', 'search' => request('search')]) }}" 
                               class="flex items-center text-gray-500 hover:text-gray-700">
                                Tahun Ajaran
                                @if(request('sort_by') == 'tahun_ajaran' || !request('sort_by'))
                                    <i class="fas fa-sort-{{ request('sort_direction', 'desc') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @else
                                    <i class="fas fa-sort ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kelas as $index => $k)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            {{ ($kelas->currentPage() - 1) * $kelas->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-door-open text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $k->nama_kelas }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                {{ $k->jurusan->nama_jurusan ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                {{ $k->siswa->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($k->wali)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        @if($k->wali->foto)
                                            <img class="h-8 w-8 rounded-full" src="{{ asset('storage/'.$k->wali->foto) }}" alt="">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $k->wali->nama }}</div>
                                    </div>
                                </div>
                            @else
                                <span class="text-sm text-gray-500">Belum ditentukan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                @if($k->tingkat == 1 || $k->tingkat == 10)
                                    X (Sepuluh)
                                @elseif($k->tingkat == 2 || $k->tingkat == 11)
                                    XI (Sebelas)
                                @elseif($k->tingkat == 3 || $k->tingkat == 12)
                                    XII (Dua Belas)
                                @else
                                    -
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $k->tahun_ajaran ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.kelas.show', $k->id) }}" 
                                   class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-eye"></i>
                                    <span class="ml-1">Detail</span>
                                </a>
                                <a href="{{ route('admin.kelas.edit', $k->id) }}" 
                                   class="text-amber-600 hover:text-amber-900">
                                    <i class="fas fa-edit"></i>
                                    <span class="ml-1">Edit</span>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                        <i class="fas fa-trash"></i>
                                        <span class="ml-1">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-10 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-door-open text-gray-400 text-5xl mb-4"></i>
                                <h4 class="text-lg font-medium text-gray-500 mb-1">Belum Ada Data Kelas</h4>
                                <p class="text-sm text-gray-400 mb-4">Silakan tambahkan data kelas pertama Anda</p>
                                <a href="{{ route('admin.kelas.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-plus-circle mr-2"></i>
                                    Tambah Kelas Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Links -->
        @if($kelas->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $kelas->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
