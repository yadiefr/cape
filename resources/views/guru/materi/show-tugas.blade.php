@extends('layouts.guru')

@section('title', 'Detail Tugas - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    /* Mobile-first responsive design for tugas detail */
    @media (max-width: 767px) {
        /* Header optimizations */
        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header-actions {
            display: flex;
            width: 100%;
            gap: 0.5rem;
        }

        .mobile-header-actions a {
            flex: 1;
            justify-content: center;
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
        }

        /* Content optimizations */
        .mobile-content-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-badge {
            align-self: flex-start;
            margin-top: 0;
        }

        /* Info grid mobile - FULL WIDTH FIX */
        .mobile-info-grid {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
            width: 100% !important;
        }

        .mobile-info-item {
            padding: 0.75rem !important;
            background: #f9fafb !important;
            border-radius: 0.5rem !important;
            border: 1px solid #e5e7eb !important;
            width: 100% !important;
            display: flex !important;
            align-items: center !important;
        }

        .mobile-info-item i {
            margin-right: 0.75rem !important;
            flex-shrink: 0;
        }

        .mobile-info-item > div {
            flex: 1;
            min-width: 0;
        }

        /* Statistics cards mobile */
        .mobile-stats-grid {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }

        .mobile-stat-card {
            padding: 0.75rem !important;
        }

        .mobile-stat-card .stat-icon {
            padding: 0.5rem !important;
            margin-right: 0.75rem !important;
        }

        .mobile-stat-card .stat-icon i {
            font-size: 1rem !important;
        }

        .mobile-stat-card h3 {
            font-size: 1.5rem !important;
        }

        .mobile-stat-card p {
            font-size: 0.75rem !important;
        }

        /* File section mobile */
        .mobile-file-section {
            margin-bottom: 1.5rem;
        }

        .mobile-file-card {
            padding: 0.75rem;
        }

        .mobile-file-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-file-info {
            flex: 1;
            width: 100%;
        }

        .mobile-file-action {
            width: 100%;
            justify-content: center;
        }

        /* Deadline alert mobile */
        .mobile-deadline-alert {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        /* Action buttons mobile - EQUAL SIZE FIX */
        .mobile-actions {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 1rem !important;
        }

        .mobile-actions-group {
            display: grid !important;
            grid-template-columns: 1fr 1fr 1fr !important;
            gap: 0.5rem !important;
            width: 100% !important;
        }

        .mobile-actions-group a,
        .mobile-actions-group button,
        .mobile-actions-group form {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            font-size: 0.75rem !important;
            padding: 0.75rem 0.5rem !important;
            min-height: 2.5rem !important;
            visibility: visible !important;
            opacity: 1 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .mobile-actions-group form {
            width: 100% !important;
        }

        .mobile-actions-group form button {
            width: 100% !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            font-size: 0.75rem !important;
            padding: 0.75rem 0.5rem !important;
        }

        /* Ensure action buttons are always visible and equal */
        .action-btn {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            text-align: center !important;
            white-space: nowrap !important;
        }

        /* Force equal text sizing */
        .mobile-actions-group .action-btn span {
            font-size: 0.75rem !important;
        }

        .mobile-actions-group .action-btn i {
            font-size: 0.875rem !important;
            margin-right: 0.25rem !important;
        }

        /* Typography mobile */
        .mobile-title {
            font-size: 1.5rem;
            line-height: 1.3;
        }

        .mobile-section-title {
            font-size: 1.125rem;
        }

        /* Spacing adjustments */
        .mobile-spacing {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-section-spacing {
            margin-bottom: 1.5rem;
        }
    }

    /* Enhanced visual effects */
    .deadline-alert {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }

    .action-btn:hover {
        transform: scale(1.02);
    }

    .info-item:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
    }
</style>
@endpush

@section('main-content')
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm mobile-spacing md:p-6 border border-gray-100 mobile-section-spacing md:mb-6">
        <div class="flex mobile-header md:items-center md:justify-between">
            <div class="flex-1">
                <h1 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-tasks text-blue-600 mr-2 md:mr-3"></i>
                    Detail Tugas Siswa
                </h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Informasi lengkap tugas untuk siswa</p>
            </div>
            <div class="mobile-header-actions md:flex md:space-x-3">
                <a href="{{ route('guru.materi.edit-tugas', $tugas->id) }}"
                   class="action-btn px-3 md:px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors flex items-center">
                    <i class="fas fa-edit mr-1 md:mr-2"></i>
                    Edit
                </a>
                <a href="{{ route('guru.materi.index') }}"
                   class="action-btn px-3 md:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-1 md:mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="mobile-spacing md:p-6">
            <!-- Header Info -->
            <div class="border-b border-gray-200 pb-4 md:pb-6 mobile-section-spacing md:mb-6">
                <div class="flex mobile-content-header md:items-start md:justify-between">
                    <div class="flex-1 w-full">
                        <h2 class="mobile-title md:text-3xl font-bold text-gray-800 mb-3 md:mb-4">{{ $tugas->judul }}</h2>

                        <!-- Deadline Alert for Mobile -->
                        @php
                            $deadline = $tugas->tanggal_deadline ? \Carbon\Carbon::parse($tugas->tanggal_deadline) : \Carbon\Carbon::parse($tugas->deadline);
                            $isOverdue = $deadline->isPast();
                        @endphp
                        @if($isOverdue)
                            <div class="mobile-deadline-alert md:hidden bg-red-100 border border-red-200 rounded-lg p-3 mb-3 deadline-alert">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                                    <span class="text-sm font-medium text-red-800">Deadline Terlewat: {{ $deadline->format('d M Y') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="mobile-info-grid md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 w-full">
                            <div class="mobile-info-item info-item">
                                <i class="fas fa-users text-blue-500"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Kelas</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $tugas->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mobile-info-item info-item">
                                <i class="fas fa-book text-green-500"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Mata Pelajaran</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $tugas->mapel->nama ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mobile-info-item info-item">
                                <i class="fas fa-calendar-alt text-red-500"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Deadline</p>
                                    <p class="text-sm md:text-base font-semibold {{ $isOverdue ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ $deadline->format('d M Y') }}
                                        @if($isOverdue)
                                            <span class="hidden md:inline text-xs bg-red-100 text-red-600 px-2 py-1 rounded ml-1">Terlambat</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="mobile-info-item info-item">
                                <i class="fas fa-calendar text-purple-500"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Dibuat</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $tugas->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-badge md:ml-6">
                        <div class="bg-orange-100 text-orange-800 px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium">
                            <i class="fas fa-tasks mr-2"></i>
                            Tugas Siswa
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid mobile-stats-grid md:grid-cols-3 gap-3 md:gap-4 mobile-section-spacing md:mb-8">
                <div class="mobile-stat-card bg-blue-50 rounded-xl p-3 md:p-6 border border-blue-200">
                    <div class="flex items-center">
                        <div class="stat-icon bg-blue-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-users text-blue-600 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-blue-600">Total Siswa</p>
                            <h3 class="text-lg md:text-2xl font-bold text-blue-800">{{ $totalStudents }}</h3>
                        </div>
                    </div>
                </div>

                <div class="mobile-stat-card bg-green-50 rounded-xl p-3 md:p-6 border border-green-200">
                    <div class="flex items-center">
                        <div class="stat-icon bg-green-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-check-circle text-green-600 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-green-600">Sudah Mengumpulkan</p>
                            <h3 class="text-lg md:text-2xl font-bold text-green-800">{{ $submissionCount }}</h3>
                        </div>
                    </div>
                </div>

                <div class="mobile-stat-card bg-red-50 rounded-xl p-3 md:p-6 border border-red-200">
                    <div class="flex items-center">
                        <div class="stat-icon bg-red-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                            <i class="fas fa-clock text-red-600 text-lg md:text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs md:text-sm text-red-600">Belum Mengumpulkan</p>
                            <h3 class="text-lg md:text-2xl font-bold text-red-800">{{ $totalStudents - $submissionCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mobile-section-spacing md:mb-8">
                <h3 class="mobile-section-title md:text-xl font-semibold text-gray-800 mb-3 md:mb-4 flex items-center">
                    <i class="fas fa-align-left text-blue-500 mr-2 md:mr-3"></i>
                    Deskripsi Tugas
                </h3>
                <div class="bg-gray-50 rounded-lg p-3 md:p-6 border border-gray-200">
                    <div class="prose max-w-none text-sm md:text-base text-gray-700 leading-relaxed">
                        {!! nl2br(e($tugas->deskripsi)) !!}
                    </div>
                </div>
            </div>

            <!-- File Section -->
            @if($tugas->file_path)
            <div class="mobile-file-section md:mb-8">
                <h3 class="mobile-section-title md:text-xl font-semibold text-gray-800 mb-3 md:mb-4 flex items-center">
                    <i class="fas fa-file-download text-blue-500 mr-2 md:mr-3"></i>
                    File Soal
                </h3>
                <div class="bg-blue-50 rounded-xl mobile-file-card md:p-6 border border-blue-200">
                    <div class="flex mobile-file-content md:items-center md:justify-between bg-white rounded-lg p-3 md:p-4 border border-blue-200">
                        <div class="mobile-file-info flex items-center">
                            <div class="bg-blue-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                                @php
                                    $extension = pathinfo($tugas->file_path, PATHINFO_EXTENSION);
                                    $iconClass = match(strtolower($extension)) {
                                        'pdf' => 'fas fa-file-pdf text-red-500',
                                        'doc', 'docx' => 'fas fa-file-word text-blue-500',
                                        'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                        'jpg', 'jpeg', 'png' => 'fas fa-file-image text-green-500',
                                        default => 'fas fa-file text-gray-500'
                                    };
                                @endphp
                                <i class="{{ $iconClass }} text-xl md:text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm md:text-base font-semibold text-gray-800">{{ $tugas->file_name ?? basename($tugas->file_path) }}</p>
                                <p class="text-xs md:text-sm text-gray-500">{{ strtoupper($extension) }} File</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank"
                           class="mobile-file-action action-btn px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-download mr-1 md:mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="border-t border-gray-200 pt-4 md:pt-6">
                <div class="flex mobile-actions md:justify-between md:items-center">
                    <div class="text-xs md:text-sm text-gray-500 mb-3 md:mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Terakhir diupdate: {{ $tugas->updated_at->format('d M Y, H:i') }}
                    </div>
                    <div class="mobile-actions-group md:flex md:space-x-3">
                        <a href="{{ route('guru.materi.tugas-submissions', $tugas->id) }}"
                           class="action-btn px-3 md:px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <i class="fas fa-eye"></i>
                            <span class="hidden sm:inline ml-1">Lihat </span><span class="sm:hidden ml-1">Jawaban</span><span class="hidden sm:inline">Jawaban</span>
                        </a>
                        <a href="{{ route('guru.materi.edit-tugas', $tugas->id) }}"
                           class="action-btn px-3 md:px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                            <i class="fas fa-edit"></i>
                            <span class="hidden sm:inline ml-1">Edit </span><span class="sm:hidden ml-1">Tugas</span><span class="hidden sm:inline">Tugas</span>
                        </a>
                        <form action="{{ route('guru.materi.delete-tugas', $tugas->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus tugas ini?')"
                                    class="action-btn w-full px-3 md:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center">
                                <i class="fas fa-trash"></i>
                                <span class="hidden sm:inline ml-1">Hapus </span><span class="sm:hidden ml-1">Tugas</span><span class="hidden sm:inline">Tugas</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
