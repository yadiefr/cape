@extends('layouts.admin')

@section('title', 'Tambah Foto ke Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Foto ke: {{ $galeri->judul }}</h1>
    <form action="{{ route('admin.galeri.foto.store', $galeri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Pilih Foto</label>
            <input type="file" name="foto[]" class="form-input w-full" multiple required>
            <small class="text-gray-500">Bisa pilih lebih dari satu foto.</small>
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_thumbnail" value="1" class="form-checkbox">
                <span class="ml-2 text-gray-700">Jadikan salah satu foto sebagai thumbnail utama</span>
            </label>
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">Simpan</button>
        </div>
    </form>
</div>
@endsection
