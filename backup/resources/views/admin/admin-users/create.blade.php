@extends('layouts.admin')

@section('title', 'Tambah Admin User')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-plus mr-3 text-blue-600"></i>
                Tambah Admin User
            </h1>
            <p class="text-gray-600 mt-1">Buat akun baru untuk admin, guru, atau staff</p>
        </div>
        <a href="{{ route('admin.admin-users.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-edit mr-2 text-blue-600"></i>Informasi Admin User
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.admin-users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                           value="{{ old('name') }}" required>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                           value="{{ old('email') }}" required>
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror" required>
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Role <span class="text-red-500">*</span></label>
                                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('role') border-red-500 @enderror" required>
                                        <option value="">Pilih Role</option>
                                        @foreach($roles as $key => $value)
                                            <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                                    <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror" required>
                                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">NIP</label>
                                    <input type="text" name="nip" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nip') border-red-500 @enderror"
                                           value="{{ old('nip') }}">
                                    @error('nip')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                                <input type="file" name="photo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photo') border-red-500 @enderror"
                                       accept="image/*">
                                <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                                @error('photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-4 pt-6">
                                <a href="{{ route('admin.admin-users.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Role Information -->
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>Informasi Role
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="font-semibold text-blue-900">Admin</h4>
                            <p class="text-sm text-blue-700">Akses penuh ke semua fitur sistem</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="font-semibold text-green-900">Guru</h4>
                            <p class="text-sm text-green-700">Manajemen pembelajaran, nilai, dan materi</p>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <h4 class="font-semibold text-purple-900">Kurikulum</h4>
                            <p class="text-sm text-purple-700">Manajemen kurikulum, jadwal, dan mata pelajaran</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <h4 class="font-semibold text-yellow-900">Tata Usaha</h4>
                            <p class="text-sm text-yellow-700">Administrasi siswa dan data sekolah</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-lg">
                            <h4 class="font-semibold text-emerald-900">Bendahara</h4>
                            <p class="text-sm text-emerald-700">Manajemen keuangan dan pembayaran</p>
                        </div>
                        <div class="p-3 bg-indigo-50 rounded-lg">
                            <h4 class="font-semibold text-indigo-900">Hubin</h4>
                            <p class="text-sm text-indigo-700">Hubungan industri dan PKL</p>
                        </div>
                        <div class="p-3 bg-cyan-50 rounded-lg">
                            <h4 class="font-semibold text-cyan-900">Perpustakaan</h4>
                            <p class="text-sm text-cyan-700">Manajemen buku dan peminjaman</p>
                        </div>
                        <div class="p-3 bg-orange-50 rounded-lg">
                            <h4 class="font-semibold text-orange-900">Kesiswaan</h4>
                            <p class="text-sm text-orange-700">Manajemen siswa dan kegiatan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
