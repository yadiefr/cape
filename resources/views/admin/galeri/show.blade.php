@extends('layouts.admin')

@section('title', 'Detail Foto Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-2xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-image text-purple-600 mr-3"></i>
                Detail Foto Galeri
            </h1>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <a href="{{ route('admin.galeri.edit', $galeri->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('admin.galeri.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>
    <div class="flex flex-col items-center">
        <img src="{{ asset('uploads/galeri/' . $galeri->gambar) }}" alt="{{ $galeri->judul }}" class="w-full max-w-md h-72 object-cover rounded-lg shadow mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $galeri->judul }}</h2>
        <p class="text-gray-600 mb-4">{{ $galeri->deskripsi }}</p>
        <div class="text-sm text-gray-400">Diunggah pada: {{ $galeri->created_at->format('d M Y H:i') }}</div>
    </div>
</div>
@endsection
