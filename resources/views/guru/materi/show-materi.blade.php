@extends('layouts.guru')

@section('title', 'Detail Materi - SMK PGRI CIKAMPEK')

@push('styles')
<style>
    /* Mobile-first responsive design */
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

        /* Info grid mobile */
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

        /* Files section mobile */
        .mobile-files-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .mobile-file-card {
            padding: 1rem;
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

        /* Action buttons mobile */
        .mobile-actions {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .mobile-actions-group {
            display: flex;
            gap: 0.5rem;
        }

        .mobile-actions-group a,
        .mobile-actions-group button {
            flex: 1;
            justify-content: center;
            font-size: 0.875rem;
            padding: 0.75rem;
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

    /* Enhanced hover effects */
    .file-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
        transform: scale(1.02);
    }

    /* Better visual hierarchy */
    .info-item {
        transition: all 0.2s ease;
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
                    <i class="fas fa-book-open text-blue-600 mr-2 md:mr-3"></i>
                    Detail Materi Pembelajaran
                </h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Informasi lengkap materi pembelajaran</p>
            </div>
            <div class="mobile-header-actions md:flex md:space-x-3">
                <a href="{{ route('guru.materi.edit-materi', $materi->id) }}"
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
                    <div class="flex-1">
                        <h2 class="mobile-title md:text-3xl font-bold text-gray-800 mb-3 md:mb-4">{{ $materi->judul }}</h2>
                        <div class="grid mobile-info-grid md:grid-cols-3 gap-3 md:gap-4">
                            <div class="mobile-info-item info-item flex items-center text-gray-600">
                                <i class="fas fa-users text-blue-500 mr-2 md:mr-3"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Kelas</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $materi->kelas->nama_kelas ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mobile-info-item info-item flex items-center text-gray-600">
                                <i class="fas fa-book text-green-500 mr-2 md:mr-3"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Mata Pelajaran</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $materi->mapel->nama ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="mobile-info-item info-item flex items-center text-gray-600">
                                <i class="fas fa-calendar text-purple-500 mr-2 md:mr-3"></i>
                                <div>
                                    <p class="text-xs md:text-sm text-gray-500">Dibuat</p>
                                    <p class="text-sm md:text-base font-semibold">{{ $materi->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mobile-badge md:ml-6">
                        <div class="bg-blue-100 text-blue-800 px-3 md:px-4 py-2 rounded-lg text-xs md:text-sm font-medium">
                            <i class="fas fa-book-open mr-2"></i>
                            Materi Pembelajaran
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mobile-section-spacing md:mb-8">
                <h3 class="mobile-section-title md:text-xl font-semibold text-gray-800 mb-3 md:mb-4 flex items-center">
                    <i class="fas fa-align-left text-blue-500 mr-2 md:mr-3"></i>
                    Deskripsi Materi
                </h3>
                <div class="bg-gray-50 rounded-lg p-3 md:p-6 border border-gray-200">
                    <div class="prose max-w-none text-sm md:text-base text-gray-700 leading-relaxed">
                        {!! nl2br(e($materi->deskripsi)) !!}
                    </div>
                </div>
            </div>

            <!-- Files and Links Section -->
            <div class="grid mobile-files-grid lg:grid-cols-2 gap-4 md:gap-6 mobile-section-spacing md:mb-8">
                <!-- File Download -->
                @if($materi->file_path)
                <div class="bg-blue-50 rounded-xl mobile-file-card md:p-6 border border-blue-200 file-card">
                    <h4 class="mobile-section-title md:text-lg font-semibold text-blue-800 mb-3 md:mb-4 flex items-center">
                        <i class="fas fa-file-download text-blue-600 mr-2 md:mr-3"></i>
                        File Materi
                    </h4>
                    <div class="flex mobile-file-content md:items-center md:justify-between bg-white rounded-lg p-3 md:p-4 border border-blue-200">
                        <div class="mobile-file-info flex items-center">
                            <div class="bg-blue-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                                @php
                                    $extension = pathinfo($materi->file_path, PATHINFO_EXTENSION);
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
                                <p class="text-sm md:text-base font-semibold text-gray-800">{{ $materi->file_name ?? basename($materi->file_path) }}</p>
                                <p class="text-xs md:text-sm text-gray-500">{{ strtoupper($extension) }} File</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank"
                           class="mobile-file-action action-btn px-3 md:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-download mr-1 md:mr-2"></i>
                            Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Video Link -->
                @if($materi->link_video)
                <div class="bg-red-50 rounded-xl mobile-file-card md:p-6 border border-red-200 file-card">
                    <h4 class="mobile-section-title md:text-lg font-semibold text-red-800 mb-3 md:mb-4 flex items-center">
                        <i class="fas fa-play-circle text-red-600 mr-2 md:mr-3"></i>
                        Video Pembelajaran
                    </h4>
                    <div class="bg-white rounded-lg p-3 md:p-4 border border-red-200">
                        <div class="flex mobile-file-content md:items-center md:justify-between">
                            <div class="mobile-file-info flex items-center">
                                <div class="bg-red-100 p-2 md:p-3 rounded-lg mr-3 md:mr-4">
                                    <i class="fas fa-video text-red-500 text-xl md:text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm md:text-base font-semibold text-gray-800">Video Materi</p>
                                    <p class="text-xs md:text-sm text-gray-500">Link eksternal</p>
                                </div>
                            </div>
                            <a href="{{ $materi->link_video }}" target="_blank"
                               class="mobile-file-action action-btn px-3 md:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-external-link-alt mr-1 md:mr-2"></i>
                                <span class="hidden sm:inline">Buka </span>Video
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- No Files or Videos -->
                @if(!$materi->file_path && !$materi->link_video)
                <div class="col-span-full">
                    <div class="bg-gray-50 rounded-xl p-4 md:p-8 border border-gray-200 text-center">
                        <i class="fas fa-file-alt text-3xl md:text-4xl text-gray-400 mb-3 md:mb-4"></i>
                        <h4 class="text-base md:text-lg font-medium text-gray-600 mb-2">Tidak Ada File atau Video</h4>
                        <p class="text-sm md:text-base text-gray-500">Materi ini hanya berisi deskripsi teks</p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="border-t border-gray-200 pt-4 md:pt-6">
                <div class="flex mobile-actions md:justify-between md:items-center">
                    <div class="text-xs md:text-sm text-gray-500 mb-3 md:mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Terakhir diupdate: {{ $materi->updated_at->format('d M Y, H:i') }}
                    </div>
                    <div class="mobile-actions-group md:flex md:space-x-3">
                        <a href="{{ route('guru.materi.edit-materi', $materi->id) }}"
                           class="action-btn px-3 md:px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors flex items-center">
                            <i class="fas fa-edit mr-1 md:mr-2"></i>
                            <span class="hidden sm:inline">Edit </span>Materi
                        </a>
                        <form action="{{ route('guru.materi.delete-materi', $materi->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus materi ini?')"
                                    class="action-btn px-3 md:px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-trash mr-1 md:mr-2"></i>
                                <span class="hidden sm:inline">Hapus </span>Materi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
