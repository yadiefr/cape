@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Tambah Agenda</h1>
        <a href="{{ route('admin.agenda.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.agenda.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        <div class="mb-4">
            <label for="judul" class="block text-gray-700 text-sm font-bold mb-2">Judul</label>
            <input type="text" name="judul" id="judul" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('judul') }}" required>
        </div>
        <div class="mb-4">
            <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="4" required>{{ old('deskripsi') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="tanggal_mulai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
            <input type="datetime-local" name="tanggal_mulai" id="tanggal_mulai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('tanggal_mulai') }}" required>
        </div>
        <div class="mb-4">
            <label for="tanggal_selesai" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
            <input type="datetime-local" name="tanggal_selesai" id="tanggal_selesai" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('tanggal_selesai') }}">
        </div>
        <div class="mb-4">
            <label for="lokasi" class="block text-gray-700 text-sm font-bold mb-2">Lokasi</label>
            <input type="text" name="lokasi" id="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('lokasi') }}">
        </div>
        <div class="mb-4">
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" class="form-checkbox" {{ old('is_active') ? 'checked' : '' }}>
                <span class="ml-2 text-gray-700">Aktif</span>
            </label>
        </div>
        <div class="flex items-center justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection 