@extends('layouts.admin')

@section('title', 'Edit Profil')

@section('main-content')
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Edit Profil</h1>
        <p class="text-gray-600">Ubah informasi personal dan keamanan akun Anda</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if($errors->any())
        <div class="mb-6">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-blue-100">
                        <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : asset('images/default-avatar.png') }}" 
                             alt="Profile Photo"
                             class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1">{{ Auth::user()->name }}</h3>
                    <p class="text-sm text-gray-500">{{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Photo Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Profil
                            </label>
                            <input type="file" name="photo" 
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0
                                          file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', Auth::user()->name) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', Auth::user()->email) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="text" name="phone" id="phone" value="{{ old('phone', Auth::user()->phone) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat
                            </label>
                            <input type="text" name="address" id="address" value="{{ old('address', Auth::user()->address) }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="md:col-span-2 border-t pt-6 mt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ubah Password</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Current Password -->
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Saat Ini
                                </label>
                                <input type="password" name="current_password" id="current_password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- New Password -->
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <input type="password" name="new_password" id="new_password"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <!-- Confirm New Password -->
                            <div>
                                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah password.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
