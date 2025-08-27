@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Berita</h1>
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Berita</label>
            <input type="text" name="judul" value="{{ old('judul') }}" class="form-input w-full" required>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Isi Berita</label>
            <textarea name="isi" rows="6" class="form-input w-full" required>{{ old('isi') }}</textarea>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="form-input w-full">
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Simpan</button>
        </div>
    </form>
</div>
@endsection
