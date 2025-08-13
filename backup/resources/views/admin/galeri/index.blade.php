@extends('layouts.admin')

@section('title', 'Galeri Website - Admin')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-images text-purple-600 mr-3"></i>
                Galeri Website
            </h1>
            <p class="text-gray-600 mt-1">Kelola koleksi foto kegiatan, fasilitas, dan momen penting sekolah.</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.galeri.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                <i class="fas fa-plus mr-2"></i> Tambah Foto
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="?kategori=all" class="gallery-filter-btn {{ request('kategori', 'all') == 'all' ? 'active' : '' }}" style="padding: 0.5rem 1.25rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-weight: 500;">Semua</a>
            <a href="?kategori=facilities" class="gallery-filter-btn {{ request('kategori') == 'facilities' ? 'active' : '' }}" style="padding: 0.5rem 1.25rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-weight: 500;">Fasilitas</a>
            <a href="?kategori=activities" class="gallery-filter-btn {{ request('kategori') == 'activities' ? 'active' : '' }}" style="padding: 0.5rem 1.25rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-weight: 500;">Kegiatan</a>
            <a href="?kategori=competitions" class="gallery-filter-btn {{ request('kategori') == 'competitions' ? 'active' : '' }}" style="padding: 0.5rem 1.25rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-weight: 500;">Kompetisi</a>
            <a href="?kategori=campus" class="gallery-filter-btn {{ request('kategori') == 'campus' ? 'active' : '' }}" style="padding: 0.5rem 1.25rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-weight: 500;">Sekolah</a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($galeri as $item)
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden group relative">
            <img src="{{ $item->gambar ? asset('uploads/galeri/' . $item->gambar) : 'https://via.placeholder.com/400x300?text=No+Image' }}" alt="{{ $item->judul }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-200">
            <div class="p-4">
                <h3 class="font-semibold text-gray-800 text-base mb-1">{{ $item->judul }}</h3>
                <p class="text-gray-500 text-sm mb-2">{{ $item->deskripsi }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="inline-flex items-center text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full"><i class="fas fa-images mr-1"></i>{{ $item->foto->count() }} Foto</span>
                    <a href="{{ route('admin.galeri.show', $item->id) }}" class="text-blue-600 hover:text-blue-800" title="Lihat"><i class="fas fa-search"></i></a>
                    <a href="{{ route('admin.galeri.edit', $item->id) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin hapus galeri ini?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 ml-2" title="Hapus"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>
            <div class="absolute top-2 left-2 bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded-full">{{ ucfirst($item->kategori ?? '-') }}</div>
        </div>
        @empty
        <div class="col-span-4 text-center py-16 text-gray-400">
            <i class="fas fa-images text-6xl mb-4"></i>
            <div class="text-lg">Belum ada galeri</div>
        </div>
        @endforelse
    </div>
</div>
@endsection
