@extends('layouts.admin')

@section('title', 'Kelola Foto Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Foto untuk: {{ $galeri->judul }}</h1>
    <a href="{{ route('admin.galeri.foto.create', $galeri->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm mb-4 inline-block"><i class="fas fa-plus mr-2"></i>Tambah Foto</a>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @forelse($galeri->foto as $foto)
        <div class="relative group border rounded-lg overflow-hidden bg-gray-50">
            <img src="{{ asset('uploads/galeri/' . $foto->foto) }}" alt="Foto Galeri" class="w-full h-32 object-cover">
            <div class="absolute top-2 left-2 bg-white text-xs px-2 py-1 rounded shadow {{ $foto->is_thumbnail ? 'text-blue-600 font-bold' : 'text-gray-500' }}">
                {{ $foto->is_thumbnail ? 'Thumbnail' : 'Foto' }}
            </div>
            <div class="absolute inset-0 bg-black bg-opacity-30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                <a href="{{ route('admin.galeri.foto.edit', [$galeri->id, $foto->id]) }}" class="text-white bg-yellow-600 px-3 py-1 rounded shadow hover:bg-yellow-700 text-xs font-medium mr-2"><i class="fas fa-edit"></i> Edit</a>
            </div>
        </div>
        @empty
        <div class="col-span-4 text-center text-gray-500 py-12">
            <i class="fas fa-image text-4xl mb-4"></i>
            <p>Belum ada foto untuk galeri ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
