@extends('layouts.guru')

@section('title', 'Data Siswa - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    /* Mobile-first responsive design */
    .container-responsive {
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .container-responsive {
            padding: 1.5rem;
        }
    }

    /* Desktop table styling */
    .siswa-row {
        transition: all 0.3s ease;
    }

    .siswa-row:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Mobile responsive table */
    @media (max-width: 767px) {
        .desktop-table {
            display: none;
        }

        .mobile-cards {
            display: block;
        }

        /* Mobile filter adjustments */
        .mobile-filter-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .mobile-filter-actions {
            flex-direction: row;
            gap: 0.5rem;
        }

        .mobile-filter-actions button,
        .mobile-filter-actions a {
            flex: 1;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }

        .filter-section {
            padding: 0.5rem;
        }

        .filter-title {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .filter-form {
            margin-bottom: 0;
        }

        .filter-form .space-y-3 {
            gap: 0.5rem;
        }

        .filter-input {
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .filter-label {
            font-size: 0.75rem;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .filter-grid-mobile {
            gap: 0.5rem;
        }

        /* Mobile statistics cards */
        .mobile-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .mobile-stats .header-card {
            padding: 0.75rem;
            height: auto;
            min-height: 4rem;
        }

        .mobile-stats .header-card .text-lg {
            font-size: 1rem;
        }

        .mobile-stats .header-card .text-2xl {
            font-size: 1.25rem;
        }
    }

    @media (min-width: 768px) {
        .desktop-table {
            display: block;
        }

        .mobile-cards {
            display: none;
        }
    }

    /* Compact mobile student card styling */
    .mobile-student-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .mobile-student-card:hover {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-color: #d1d5db;
    }

    .student-header {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .student-avatar {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        margin-right: 0.75rem;
        flex-shrink: 0;
    }

    .student-info h3 {
        font-size: 0.875rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.125rem;
        line-height: 1.2;
    }

    .student-info p {
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.2;
    }

    .student-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-left: auto;
        flex-shrink: 0;
    }

    .student-number {
        font-size: 0.75rem;
        font-weight: 500;
        color: #6b7280;
        background-color: #f3f4f6;
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
    }

    .student-details {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .detail-chip {
        background-color: #f3f4f6;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .detail-chip .label {
        font-weight: 500;
        color: #6b7280;
    }

    .detail-chip .value {
        font-weight: 600;
        color: #111827;
        margin-left: 0.25rem;
    }

    .student-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .student-badges {
        display: flex;
        gap: 0.25rem;
    }

    .student-actions {
        display: flex;
        gap: 0.25rem;
    }

    .action-btn {
        padding: 0.375rem;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        min-height: 2rem;
    }

    .action-btn:hover {
        transform: scale(1.05);
    }

    /* Status badges mobile optimization */
    .status-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .status-badge svg {
        width: 0.75rem;
        height: 0.75rem;
        margin-right: 0.25rem;
    }
</style>
@endpush

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="container-responsive">
    <div class="header-section mb-6">
    <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2 lg:mb-4">
                    <div class="p-1.5 lg:p-3 bg-blue-100 rounded-lg mr-2 lg:mr-4">
                        <svg class="w-4 h-4 lg:w-8 lg:h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg lg:text-4xl font-bold text-gray-900 mb-1 lg:mb-2">
                            Data Siswa
                        </h1>
                        <p class="text-gray-600 text-sm lg:text-lg">
                            Kelola data siswa dari mata pelajaran yang Anda ampu
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid mobile-stats lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="header-card bg-white rounded-lg shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow h-20 lg:h-28 flex items-center">
            <div class="flex items-center w-full">
                <div class="p-2 lg:p-3 rounded-lg bg-blue-100 flex-shrink-0">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 lg:ml-4 flex-grow">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="text-lg lg:text-2xl font-bold text-gray-900">{{ number_format($totalSiswa) }}</p>
                </div>
            </div>
        </div>

        <div class="header-card bg-white rounded-lg shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow h-20 lg:h-28 flex items-center">
            <div class="flex items-center w-full">
                <div class="p-2 lg:p-3 rounded-lg bg-green-100 flex-shrink-0">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 lg:ml-4 flex-grow">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Siswa Aktif</p>
                    <p class="text-lg lg:text-2xl font-bold text-gray-900">{{ number_format($siswaAktif) }}</p>
                </div>
            </div>
        </div>

        <div class="header-card bg-white rounded-lg shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow h-20 lg:h-28 flex items-center">
            <div class="flex items-center w-full">
                <div class="p-2 lg:p-3 rounded-lg bg-blue-100 flex-shrink-0">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3 lg:ml-4 flex-grow">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Laki-Laki</p>
                    <p class="text-lg lg:text-2xl font-bold text-gray-900">{{ number_format($siswaLakiLaki) }}</p>
                </div>
            </div>
        </div>

        <div class="header-card bg-white rounded-lg shadow-sm border border-gray-200 p-3 lg:p-6 hover:shadow-md transition-shadow h-20 lg:h-28 flex items-center">
            <div class="flex items-center w-full">
                <div class="p-2 lg:p-3 rounded-lg bg-pink-100 flex-shrink-0">
                    <svg class="w-4 h-4 lg:w-6 lg:h-6 text-pink-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div class="ml-3 lg:ml-4 flex-grow">
                    <p class="text-xs lg:text-sm font-medium text-gray-600">Perempuan</p>
                    <p class="text-lg lg:text-2xl font-bold text-gray-900">{{ number_format($siswaPerempuan) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
                <button @click="show = false" class="text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Filters and Search -->
    <div class="filter-card bg-white rounded-lg shadow-sm border border-gray-200 mb-3 md:mb-6">
        <div class="filter-section md:p-6">
            <h2 class="filter-title text-gray-900 flex items-center">
                <svg class="w-4 h-4 text-gray-400 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter & Pencarian
            </h2>
            <form method="GET" action="{{ route('guru.siswa.index') }}" class="filter-form space-y-2 md:space-y-4">
                <div class="grid filter-grid-mobile lg:grid-cols-4 gap-3 md:gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="filter-label block text-gray-700">Cari Siswa</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Nama atau NIS..."
                               class="filter-input w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Class Filter -->
                    <div>
                        <label for="kelas_id" class="filter-label block text-gray-700">Kelas</label>
                        <select name="kelas_id" id="kelas_id" class="filter-input w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kelas</option>
                            @foreach($kelasOptions as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }} - {{ $kelas->jurusan->nama_jurusan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label for="jenis_kelamin" class="filter-label block text-gray-700">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="filter-input w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="filter-label block text-gray-700">Status</label>
                        <select name="status" id="status" class="filter-input w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                            <option value="pindah" {{ request('status') == 'pindah' ? 'selected' : '' }}>Pindah</option>
                            <option value="lulus" {{ request('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-2 border-t border-gray-200">
                    <div class="flex items-center space-x-2 mb-2 sm:mb-0 mobile-filter-actions">
                        <button type="submit"
                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>
                        <a href="{{ route('guru.siswa.index') }}"
                           class="inline-flex items-center px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200 text-sm">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Reset
                        </a>
                    </div>
                    
                    <!-- Current Sort Info -->
                    <div class="flex items-center text-sm text-gray-600 mb-4 sm:mb-0">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4"></path>
                        </svg>
                        <span>Diurutkan berdasarkan: 
                            <strong class="text-blue-600">
                                @if(request('sort_by') == 'nama')
                                    Nama
                                @elseif(request('sort_by') == 'kelas')
                                    Kelas
                                @elseif(request('sort_by') == 'nisn')
                                    NISN
                                @elseif(request('sort_by') == 'jenis_kelamin')
                                    Jenis Kelamin
                                @elseif(request('sort_by') == 'status')
                                    Status
                                @else
                                    Kelas
                                @endif
                            </strong>
                            <span class="ml-1 text-gray-500">({{ request('sort_order', 'asc') == 'asc' ? 'A-Z' : 'Z-A' }})</span>
                        </span>
                        @if(request('sort_by') || request('sort_order'))
                            <a href="{{ route('guru.siswa.index', request()->except(['sort_by', 'sort_order'])) }}" 
                               class="ml-2 text-blue-600 hover:text-blue-700 p-1 rounded-full hover:bg-blue-50"
                               title="Reset Sorting">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="filter-card bg-white rounded-lg shadow-sm border border-gray-200 mb-5">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Daftar Siswa 
                        <span class="ml-2 text-sm text-gray-500 font-normal">({{ count($siswa) }} siswa)</span>
                    </h2>
                </div>
            </div>
        </div>

        <!-- Desktop Table -->
        <div class="desktop-table overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Nama Siswa</span>
                                <div class="flex flex-col">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'nama', 'sort_order' => 'asc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'nama' && request('sort_order') == 'asc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'nama', 'sort_order' => 'desc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'nama' && request('sort_order') == 'desc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>NIS</span>
                                <div class="flex flex-col">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'nis', 'sort_order' => 'asc'])) }}"
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'nis' && request('sort_order') == 'asc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'nis', 'sort_order' => 'desc'])) }}"
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'nis' && request('sort_order') == 'desc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span class="{{ (request('sort_by') == 'kelas' || (!request('sort_by') && !request('sort_order'))) ? 'text-blue-600 font-semibold' : '' }}">Kelas</span>
                                <div class="flex flex-col">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'kelas', 'sort_order' => 'asc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ (request('sort_by') == 'kelas' && request('sort_order') == 'asc') || (!request('sort_by') && !request('sort_order')) ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'kelas', 'sort_order' => 'desc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'kelas' && request('sort_order') == 'desc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <span>Status</span>
                                <div class="flex flex-col">
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_order' => 'asc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'status' && request('sort_order') == 'asc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('guru.siswa.index', array_merge(request()->all(), ['sort_by' => 'status', 'sort_order' => 'desc'])) }}" 
                                       class="text-gray-400 hover:text-gray-600 {{ request('sort_by') == 'status' && request('sort_order') == 'desc' ? 'text-blue-600' : '' }}">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $index => $student)
                        <tr class="siswa-row hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100">
                                        @if($student->foto && Storage::disk('public')->exists($student->foto))
                                            <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto {{ $student->nama_lengkap }}" class="h-full w-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama_lengkap) }}&background=3b82f6&color=ffffff" alt="Foto {{ $student->nama_lengkap }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student->nama_lengkap }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $student->nis ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $student->kelas->nama_kelas }}</div>
                                <div class="text-sm text-gray-500">{{ $student->kelas->jurusan->nama_jurusan }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($student->status == 'aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif($student->status == 'non-aktif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Non-Aktif
                                    </span>
                                @elseif($student->status == 'pindah')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pindah
                                    </span>
                                @elseif($student->status == 'lulus')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Lulus
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($student->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('guru.siswa.show', $student) }}"
                                   class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data siswa</h3>
                                    <p class="text-gray-500 mb-4">Tidak ditemukan siswa yang sesuai dengan kriteria pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards">
            @forelse($siswa as $index => $student)
                <div class="mobile-student-card">
                    <div class="student-header">
                        <div class="student-avatar">
                            @if($student->foto && Storage::disk('public')->exists($student->foto))
                                <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto {{ $student->nama_lengkap }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($student->nama_lengkap) }}&background=3b82f6&color=ffffff" alt="Foto {{ $student->nama_lengkap }}" class="w-full h-full object-cover rounded-full">
                            @endif
                        </div>
                        <div class="student-info flex-1">
                            <h3>{{ $student->nama_lengkap }}</h3>
                            <p>{{ $student->kelas->nama_kelas }} - {{ $student->kelas->jurusan->nama_jurusan }}</p>
                        </div>
                        <div class="student-meta">
                            <div class="student-number">#{{ $index + 1 }}</div>
                        </div>
                    </div>

                    <div class="student-details">
                        <div class="detail-chip">
                            <span class="label">NIS:</span>
                            <span class="value">{{ $student->nis ?? '-' }}</span>
                        </div>
                        <div class="detail-chip">
                            <span class="label">TTL:</span>
                            <span class="value">{{ $student->tempat_lahir }}, {{ $student->tanggal_lahir ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>

                    <div class="student-footer">
                        <div class="student-badges">
                            <span class="status-badge {{ $student->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $student->jenis_kelamin == 'L' ? 'L' : 'P' }}
                            </span>

                            @if($student->status == 'aktif')
                                <span class="status-badge bg-green-100 text-green-800">Aktif</span>
                            @elseif($student->status == 'non-aktif')
                                <span class="status-badge bg-red-100 text-red-800">Non-Aktif</span>
                            @elseif($student->status == 'pindah')
                                <span class="status-badge bg-yellow-100 text-yellow-800">Pindah</span>
                            @elseif($student->status == 'lulus')
                                <span class="status-badge bg-purple-100 text-purple-800">Lulus</span>
                            @else
                                <span class="status-badge bg-gray-100 text-gray-800">{{ ucfirst($student->status) }}</span>
                            @endif
                        </div>

                        <div class="student-actions">
                            <a href="{{ route('guru.siswa.show', $student) }}"
                               class="action-btn text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50"
                               title="Lihat Detail">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data siswa</h3>
                        <p class="text-gray-500 mb-4">Tidak ditemukan siswa yang sesuai dengan kriteria pencarian.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Statistics Info -->
        @if($siswa->count() > 0)
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-600">
                    Menampilkan {{ $siswa->count() }} dari {{ $totalSiswa }} siswa dalam kelas yang Anda ajar
                </div>
            </div>
        @endif
    </div>
</div>
</div>
@endsection

@push('scripts')
<script>
function siswaManager() {
    return {
        async exportData() {
            try {
                const params = new URLSearchParams(window.location.search);
                const response = await fetch(`{{ route('guru.siswa.export') }}?${params.toString()}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('success', data.message + ` (${data.count} siswa)`);
                } else {
                    this.showNotification('error', data.message || 'Gagal export data');
                }
            } catch (error) {
                console.error('Export error:', error);
                this.showNotification('error', 'Terjadi kesalahan sistem');
            }
        },

        showNotification(type, message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full ${type === 'success' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'} border rounded-lg p-4 shadow-lg transition-all duration-300 transform translate-x-full`;
            
            notification.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 ${type === 'success' ? 'text-green-400' : 'text-red-400'} mr-2" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'success' 
                                ? '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>'
                                : '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>'
                            }
                        </svg>
                        <p class="text-sm font-medium ${type === 'success' ? 'text-green-800' : 'text-red-800'}">${message}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()" class="${type === 'success' ? 'text-green-400 hover:text-green-600' : 'text-red-400 hover:text-red-600'}">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }
    }
}
</script>
@endpush
