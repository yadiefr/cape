@extends('layouts.guru')

@section('title', 'Materi & Tugas - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    /* Mobile-first responsive optimizations */
    @media (max-width: 767px) {
        /* Header optimizations */
        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header-actions {
            display: flex !important;
            width: 100%;
            gap: 0.5rem;
        }

        .mobile-header-actions a {
            flex: 1;
            justify-content: center;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            text-align: center;
        }

        /* Ensure buttons are visible */
        .mobile-action-btn {
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        /* Stats cards mobile */
        .mobile-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }

        .mobile-stat-card {
            padding: 0.75rem;
        }

        .mobile-stat-card .stat-icon {
            padding: 0.5rem;
        }

        .mobile-stat-card .stat-icon i {
            font-size: 1rem;
        }

        .mobile-stat-card h3 {
            font-size: 1.25rem;
        }

        .mobile-stat-card p {
            font-size: 0.75rem;
        }

        .mobile-stat-card .stat-badge {
            font-size: 0.625rem;
            padding: 0.125rem 0.375rem;
        }

        /* Tab navigation mobile */
        .mobile-tabs {
            flex-direction: column;
        }

        .mobile-tabs button {
            width: 100%;
            justify-content: center;
            padding: 0.75rem 1rem;
            border-bottom: none;
            border-right: 2px solid transparent;
        }

        /* Content cards mobile */
        .mobile-content-card {
            padding: 0.75rem;
        }

        .mobile-content-header {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.75rem;
        }

        .mobile-content-title {
            font-size: 1rem;
            line-height: 1.3;
        }

        .mobile-content-badges {
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .mobile-content-meta {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem;
        }

        .mobile-content-meta span {
            font-size: 0.75rem;
        }

        /* Fix action buttons visibility */
        .mobile-content-actions {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 0.5rem !important;
            width: 100% !important;
            margin-top: 0.75rem !important;
        }

        .mobile-content-actions > * {
            flex: 1 !important;
            min-width: 0 !important;
        }

        .mobile-content-actions a,
        .mobile-content-actions form,
        .mobile-content-actions button {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            font-size: 0.75rem !important;
            padding: 0.5rem !important;
            min-height: 2rem !important;
            height: 2rem !important;
            box-sizing: border-box !important;
        }

        .mobile-content-actions form {
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
        }

        .mobile-content-actions form button {
            width: 100% !important;
            height: 2rem !important;
            margin: 0 !important;
            border: none !important;
        }

        /* Show essential actions on mobile */
        .mobile-essential {
            display: flex !important;
        }

        /* Hide less important actions on mobile to save space */
        .mobile-hide {
            display: none !important;
        }
    }

    @media (min-width: 768px) {
        .mobile-only {
            display: none;
        }
        
        /* Desktop action buttons styling */
        .mobile-content-actions {
            display: flex !important;
            gap: 0.5rem !important;
            flex-wrap: wrap !important;
        }
        
        .mobile-content-actions > * {
            flex: 1 !important;
            min-width: 0 !important;
        }
        
        .mobile-content-actions a,
        .mobile-content-actions form,
        .mobile-content-actions button {
            height: 2.25rem !important;
            box-sizing: border-box !important;
        }
        
        .mobile-content-actions form {
            margin: 0 !important;
            padding: 0 !important;
        }
        
        .mobile-content-actions form button {
            width: 100% !important;
            height: 100% !important;
            margin: 0 !important;
            border: none !important;
        }
    }

    /* Enhanced hover effects */
    .content-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Better action button grouping */
    .action-group {
        display: flex;
        gap: 0.25rem;
        flex-wrap: wrap;
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-3 md:p-6 border border-gray-100 mb-4 md:mb-6">
        <div class="flex mobile-header md:items-center md:justify-between">
            <div class="flex-1">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-book text-blue-600 mr-2 md:mr-3"></i>
                    Materi & Tugas
                </h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Kelola materi pembelajaran dan tugas untuk siswa</p>
            </div>
            <div class="mobile-header-actions md:flex md:space-x-3">
                <a href="{{ route('guru.materi.create-tugas') }}"
                   class="mobile-action-btn action-btn px-3 md:px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Tambah </span>Tugas
                </a>
                <a href="{{ route('guru.materi.create-materi') }}"
                   class="mobile-action-btn action-btn px-3 md:px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Tambah </span>Materi
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid mobile-stats-grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4 md:mb-6">
        <div class="mobile-stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow content-card">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-book-open text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Total Materi</p>
                    <div class="flex items-center">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800">{{ $materi->total() ?? 0 }}</h3>
                        <span class="stat-badge ml-2 text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded-full">Materi</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow content-card">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-tasks text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Total Tugas</p>
                    <div class="flex items-center">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800">{{ $tugas->total() ?? 0 }}</h3>
                        <span class="stat-badge ml-2 text-xs px-2 py-1 bg-orange-100 text-orange-700 rounded-full">Tugas</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow content-card">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-green-400 to-green-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-users text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Kelas Diampu</p>
                    <div class="flex items-center">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800">{{ $kelasList->count() ?? 0 }}</h3>
                        <span class="stat-badge ml-2 text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">Kelas</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mobile-stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow content-card">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-purple-400 to-purple-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-graduation-cap text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Mata Pelajaran</p>
                    <div class="flex items-center">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-800">{{ $mapelList->count() ?? 0 }}</h3>
                        <span class="stat-badge ml-2 text-xs px-2 py-1 bg-purple-100 text-purple-700 rounded-full">Mapel</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div x-data="{ activeTab: 'materi' }" class="mb-4 md:mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex mobile-tabs md:border-b md:border-gray-200">
                <button @click="activeTab = 'materi'"
                        :class="{'bg-blue-50 text-blue-600 border-blue-500': activeTab === 'materi', 'text-gray-500 hover:text-gray-700': activeTab !== 'materi'}"
                        class="flex items-center px-4 md:px-6 py-3 md:py-4 text-sm font-medium border-b-2 md:border-b-2 transition-colors">
                    <i class="fas fa-book-open mr-2"></i>
                    <span class="hidden sm:inline">Materi </span>Materi Pembelajaran
                </button>
                <button @click="activeTab = 'tugas'"
                        :class="{'bg-orange-50 text-orange-600 border-orange-500': activeTab === 'tugas', 'text-gray-500 hover:text-gray-700': activeTab !== 'tugas'}"
                        class="flex items-center px-4 md:px-6 py-3 md:py-4 text-sm font-medium border-b-2 md:border-b-2 transition-colors">
                    <i class="fas fa-tasks mr-2"></i>
                    Tugas
                </button>
            </div>

            <!-- Materi Tab Content -->
            <div x-show="activeTab === 'materi'" class="p-3 md:p-6">
                @if($materi->count() > 0)
                    <div class="grid gap-3 md:gap-4">
                        @foreach($materi as $item)
                            <div class="mobile-content-card bg-gray-50 rounded-lg p-3 md:p-4 hover:bg-gray-100 transition-colors content-card">
                                <div class="flex mobile-content-header md:items-start md:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start md:items-center mb-2 mobile-content-badges">
                                            <h3 class="mobile-content-title text-base md:text-lg font-semibold text-gray-900 mr-2">{{ $item->judul }}</h3>
                                            @if($item->file_path)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-file mr-1"></i> File
                                                </span>
                                            @endif
                                            @if($item->link_video)
                                                <span class="ml-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-video mr-1"></i> Video
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm md:text-base text-gray-600 mb-3">{{ Str::limit($item->deskripsi, 150) }}</p>
                                        <div class="flex mobile-content-meta md:items-center text-sm text-gray-500 md:space-x-4">
                                            <span><i class="fas fa-users mr-1"></i> {{ $item->kelas->nama_kelas ?? '-' }}</span>
                                            <span><i class="fas fa-book mr-1"></i> {{ $item->mapel->nama ?? '-' }}</span>
                                            <span><i class="fas fa-calendar mr-1"></i> {{ $item->created_at->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="mobile-content-actions">
                                        <!-- Essential actions always visible -->
                                        <a href="{{ route('guru.materi.show-materi', $item->id) }}"
                                           class="mobile-essential action-btn px-2 md:px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs md:text-sm">
                                            <i class="fas fa-eye"></i><span class="hidden md:inline ml-1">Lihat</span>
                                        </a>
                                        <a href="{{ route('guru.materi.edit-materi', $item->id) }}"
                                           class="mobile-essential action-btn px-2 md:px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs md:text-sm">
                                            <i class="fas fa-edit"></i><span class="hidden md:inline ml-1">Edit</span>
                                        </a>

                                        <!-- File download button if available -->
                                        @if($item->file_path)
                                            <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                               class="mobile-essential action-btn px-2 md:px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs md:text-sm">
                                                <i class="fas fa-download"></i><span class="hidden md:inline ml-1">File</span>
                                            </a>
                                        @endif
                                        
                                        @if($item->link_video)
                                            <a href="{{ $item->link_video }}" target="_blank"
                                               class="mobile-essential action-btn px-2 md:px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs md:text-sm">
                                                <i class="fas fa-play"></i><span class="hidden md:inline ml-1">Video</span>
                                            </a>
                                        @endif

                                        <!-- Delete button - always visible -->
                                        <form action="{{ route('guru.materi.delete-materi', $item->id) }}" method="POST" class="mobile-essential">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus materi ini?')"
                                                    class="action-btn w-full px-2 md:px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs md:text-sm">
                                                <i class="fas fa-trash"></i><span class="hidden md:inline ml-1">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $materi->links() }}
                    </div>
                @else
                    <div class="text-center py-6 md:py-8">
                        <i class="fas fa-book-open text-3xl md:text-4xl text-gray-400 mb-3 md:mb-4"></i>
                        <h3 class="text-base md:text-lg font-medium text-gray-900 mb-2">Belum Ada Materi</h3>
                        <p class="text-sm md:text-base text-gray-500 mb-4">Mulai tambahkan materi pembelajaran untuk siswa</p>
                        <a href="{{ route('guru.materi.create-materi') }}"
                           class="inline-flex items-center px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm md:text-base">
                            <i class="fas fa-plus mr-2"></i> Tambah Materi Pertama
                        </a>
                    </div>
                @endif
            </div>

            <!-- Tugas Tab Content -->
            <div x-show="activeTab === 'tugas'" class="p-3 md:p-6">
                @if($tugas->count() > 0)
                    <div class="grid gap-3 md:gap-4">
                        @foreach($tugas as $item)
                            <div class="mobile-content-card bg-gray-50 rounded-lg p-3 md:p-4 hover:bg-gray-100 transition-colors content-card">
                                <div class="flex mobile-content-header md:items-start md:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-start md:items-center mb-2 mobile-content-badges">
                                            <h3 class="mobile-content-title text-base md:text-lg font-semibold text-gray-900 mr-2">{{ $item->judul }}</h3>
                                            @if($item->file_path)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    <i class="fas fa-file mr-1"></i> File
                                                </span>
                                            @endif
                                            @php
                                                $deadline = \Carbon\Carbon::parse($item->tanggal_deadline);
                                                $isOverdue = $deadline->isPast();
                                                $isNearDeadline = $deadline->diffInDays(now()) <= 3 && !$isOverdue;
                                            @endphp
                                            @if($isOverdue)
                                                <span class="ml-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> <span class="hidden sm:inline">Terlambat</span>
                                                </span>
                                            @elseif($isNearDeadline)
                                                <span class="ml-1 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-clock mr-1"></i> <span class="hidden sm:inline">Mendekati Deadline</span>
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm md:text-base text-gray-600 mb-3">{{ Str::limit($item->deskripsi, 150) }}</p>
                                        <div class="flex mobile-content-meta md:items-center text-sm text-gray-500 md:space-x-4">
                                            <span><i class="fas fa-users mr-1"></i> {{ $item->kelas->nama_kelas ?? '-' }}</span>
                                            <span><i class="fas fa-book mr-1"></i> {{ $item->mapel->nama ?? '-' }}</span>
                                            <span><i class="fas fa-calendar-alt mr-1"></i> <span class="hidden sm:inline">Deadline: </span>{{ $deadline->format('d M Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="mobile-content-actions">
                                        <!-- Essential actions for mobile -->
                                        <a href="{{ route('guru.materi.show-tugas', $item->id) }}"
                                           class="mobile-essential action-btn px-2 md:px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs md:text-sm">
                                            <i class="fas fa-info-circle"></i><span class="hidden md:inline ml-1">Detail</span>
                                        </a>
                                        <a href="{{ route('guru.materi.tugas-submissions', $item->id) }}"
                                           class="mobile-essential action-btn px-2 md:px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-xs md:text-sm">
                                            <i class="fas fa-eye"></i><span class="hidden md:inline ml-1">Jawaban</span>
                                        </a>
                                        <a href="{{ route('guru.materi.edit-tugas', $item->id) }}"
                                           class="mobile-essential action-btn px-2 md:px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors text-xs md:text-sm">
                                            <i class="fas fa-edit"></i><span class="hidden md:inline ml-1">Edit</span>
                                        </a>

                                        <!-- File download button if available -->
                                        @if($item->file_path)
                                            <a href="{{ Storage::url($item->file_path) }}" target="_blank"
                                               class="mobile-essential action-btn px-2 md:px-3 py-1 bg-orange-100 text-orange-700 rounded-lg hover:bg-orange-200 transition-colors text-xs md:text-sm">
                                                <i class="fas fa-download"></i><span class="hidden md:inline ml-1">File</span>
                                            </a>
                                        @endif

                                        <!-- Delete button - always visible -->
                                        <form action="{{ route('guru.materi.delete-tugas', $item->id) }}" method="POST" class="mobile-essential">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                                    class="action-btn w-full px-2 md:px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-xs md:text-sm">
                                                <i class="fas fa-trash"></i><span class="hidden md:inline ml-1">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $tugas->links() }}
                    </div>
                @else
                    <div class="text-center py-6 md:py-8">
                        <i class="fas fa-tasks text-3xl md:text-4xl text-gray-400 mb-3 md:mb-4"></i>
                        <h3 class="text-base md:text-lg font-medium text-gray-900 mb-2">Belum Ada Tugas</h3>
                        <p class="text-sm md:text-base text-gray-500 mb-4">Mulai tambahkan tugas untuk siswa</p>
                        <a href="{{ route('guru.materi.create-tugas') }}"
                           class="inline-flex items-center px-3 md:px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm md:text-base">
                            <i class="fas fa-plus mr-2"></i> Tambah Tugas Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
