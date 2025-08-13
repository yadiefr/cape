@extends('layouts.admin')

@section('title', 'Edit Foto Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Foto Galeri</h1>
    <img src="{{ asset('uploads/galeri/' . $foto->foto) }}" alt="Foto" class="w-40 h-40 object-cover rounded mb-4">
    <form action="{{ route('admin.galeri.foto.update', [$galeri->id, $foto->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Ganti Foto (opsional)</label>
            <input type="file" name="foto" class="form-input w-full">
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_thumbnail" value="1" class="form-checkbox" {{ $foto->is_thumbnail ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700">Jadikan sebagai thumbnail utama</span>
            </label>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
