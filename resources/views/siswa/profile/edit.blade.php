@extends('layouts.siswa')

@section('title', 'Edit Profil - SMK PGRI CIKAMPEK')

@section('content')
<div class="w-full px-6 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Edit Profil</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="{{ route('siswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('siswa.profile.index') }}" class="hover:text-blue-600">Profil</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Edit</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="font-medium">Oops! Ada beberapa kesalahan:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(session('success'))
    <div class="mb-6">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6">
            <form action="{{ route('siswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->

                <!-- Foto Profile -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <div class="flex items-center space-x-6">
                        <div class="shrink-0">
                            @if($siswa->foto)
                                <img class="h-24 w-24 object-cover rounded-lg" src="{{ asset('storage/' . $siswa->foto) }}" alt="Foto profil">
                            @else
                                <div class="h-24 w-24 rounded-lg bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-user text-4xl text-gray-400"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="foto" id="foto" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG atau GIF (Maks. 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- NIS (Read Only) -->
                <div>
                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS <span class="text-red-500">*</span></label>
                    <input type="text" name="nis" id="nis" value="{{ $siswa->nis }}" readonly class="mt-1 bg-gray-50 border border-gray-300 text-gray-500 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">NIS tidak dapat diubah</p>
                </div>


                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $siswa->email) }}" required class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- No. Telepon -->
                <div>
                    <label for="telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $siswa->telepon) }}" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                </div>

                <!-- Alamat -->
                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <p class="text-xs text-gray-500 mt-1">Harus sama dengan password baru</p>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('siswa.profile.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
