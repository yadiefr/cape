@extends('layouts.ujian')

@section('title', 'Pengaturan Ujian')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.ujian.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="text-sm font-medium text-gray-500">Pengaturan</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Ujian</h1>
        <p class="text-gray-600 mt-1">Kelola pengaturan dan konfigurasi sistem ujian online</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Jenis Ujian -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 p-3 rounded-lg">
                        <i class="fas fa-list-alt text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Jenis Ujian</h3>
                        <p class="text-sm text-gray-500">Kelola jenis ujian (UTS, UAS, dll)</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.index') }}" 
                       class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-cog mr-2"></i>
                        Kelola Jenis Ujian
                    </a>
                </div>
            </div>
        </div>

        <!-- Pengaturan Umum -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <i class="fas fa-cogs text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pengaturan Umum</h3>
                        <p class="text-sm text-gray-500">Konfigurasi dasar sistem ujian</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition-colors" 
                            onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-sliders-h mr-2"></i>
                        Pengaturan Umum
                    </button>
                </div>
            </div>
        </div>

        <!-- Keamanan -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Keamanan</h3>
                        <p class="text-sm text-gray-500">Pengaturan keamanan ujian</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg transition-colors"
                            onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-lock mr-2"></i>
                        Pengaturan Keamanan
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifikasi -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-bell text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                        <p class="text-sm text-gray-500">Pengaturan notifikasi sistem</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded-lg transition-colors"
                            onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-envelope mr-2"></i>
                        Pengaturan Notifikasi
                    </button>
                </div>
            </div>
        </div>

        <!-- Backup & Restore -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 p-3 rounded-lg">
                        <i class="fas fa-database text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Backup & Restore</h3>
                        <p class="text-sm text-gray-500">Backup dan restore data ujian</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors"
                            onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-download mr-2"></i>
                        Backup & Restore
                    </button>
                </div>
            </div>
        </div>

        <!-- Laporan -->
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-indigo-100 p-3 rounded-lg">
                        <i class="fas fa-chart-bar text-indigo-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Laporan</h3>
                        <p class="text-sm text-gray-500">Konfigurasi laporan ujian</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <button class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-4 rounded-lg transition-colors"
                            onclick="alert('Fitur ini akan segera tersedia')">
                        <i class="fas fa-file-alt mr-2"></i>
                        Pengaturan Laporan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-white rounded-lg shadow-md">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('admin.ujian.pengaturan.jenis-ujian.create') }}" 
                   class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i class="fas fa-plus-circle text-purple-600 text-2xl mr-3"></i>
                    <div>
                        <div class="font-medium text-purple-900">Tambah Jenis Ujian</div>
                        <div class="text-sm text-purple-600">Buat jenis ujian baru</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.ujian.bank-soal.index') }}" 
                   class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-book text-blue-600 text-2xl mr-3"></i>
                    <div>
                        <div class="font-medium text-blue-900">Bank Soal</div>
                        <div class="text-sm text-blue-600">Kelola bank soal</div>
                    </div>
                </a>
                
                <a href="{{ route('admin.ujian.jadwal.index') }}" 
                   class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                    <i class="fas fa-calendar-alt text-green-600 text-2xl mr-3"></i>
                    <div>
                        <div class="font-medium text-green-900">Jadwal Ujian</div>
                        <div class="text-sm text-green-600">Atur jadwal ujian</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
