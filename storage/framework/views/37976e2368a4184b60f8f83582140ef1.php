

<?php $__env->startSection('title', 'Jawaban Siswa - ' . $tugas->judul); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Mobile-first responsive design for submissions */
    @media (max-width: 767px) {
        /* Header optimizations */
        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header-info {
            width: 100%;
        }

        .mobile-deadline {
            align-self: flex-start;
            text-align: left;
            margin-top: 0.5rem;
        }

        /* Statistics cards mobile */
        .mobile-stats-grid {
            grid-template-columns: 1fr;
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
            font-size: 1.5rem;
        }

        .mobile-stat-card p {
            font-size: 0.75rem;
        }

        /* Tab navigation mobile */
        .mobile-tabs {
            flex-direction: column;
            padding: 0;
        }

        .mobile-tabs button {
            width: 100%;
            justify-content: flex-start;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            border-right: none;
            border-left: 4px solid transparent;
        }

        .mobile-tabs button.active {
            border-left-color: #10b981;
            border-bottom-color: transparent;
            background-color: #f0fdf4;
        }

        /* Submission cards mobile */
        .mobile-submission-card {
            padding: 0.75rem;
        }

        .mobile-submission-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-student-info {
            flex-direction: row;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
        }

        .mobile-student-avatar {
            width: 3rem;
            height: 3rem;
        }

        .mobile-student-details {
            flex: 1;
            min-width: 0;
        }

        .mobile-student-name {
            font-size: 1rem;
            line-height: 1.3;
        }

        .mobile-submission-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .mobile-submission-meta span {
            font-size: 0.75rem;
        }

        .mobile-submission-actions {
            display: flex;
            gap: 0.5rem;
            width: 100%;
            margin-top: 0.75rem;
        }

        .mobile-submission-actions a,
        .mobile-submission-actions span {
            flex: 1;
            justify-content: center;
            font-size: 0.75rem;
            padding: 0.5rem;
            text-align: center;
        }

        /* Notes section mobile */
        .mobile-notes {
            margin-top: 0.75rem;
            padding: 0.5rem;
        }

        /* Typography mobile */
        .mobile-title {
            font-size: 1.25rem;
            line-height: 1.3;
        }

        .mobile-subtitle {
            font-size: 0.875rem;
        }

        /* Spacing adjustments */
        .mobile-spacing {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-section-spacing {
            margin-bottom: 1.5rem;
        }

        /* Empty state mobile */
        .mobile-empty-state {
            padding: 2rem 1rem;
        }

        .mobile-empty-icon {
            width: 2.5rem;
            height: 2.5rem;
        }
    }

    /* Enhanced visual effects */
    .submission-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Tab button animations */
    .tab-button {
        transition: all 0.2s ease;
    }

    .tab-button:hover {
        background-color: #f9fafb;
    }

    /* Better visual hierarchy */
    .student-avatar {
        transition: all 0.2s ease;
    }

    .student-avatar:hover {
        transform: scale(1.05);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm mobile-spacing md:p-6 border border-gray-100 mobile-section-spacing md:mb-6">
        <div class="flex mobile-header md:items-center md:justify-between">
            <div class="mobile-header-info">
                <h1 class="mobile-title md:text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-file-alt text-green-600 mr-2 md:mr-3"></i>
                    Jawaban Siswa: <?php echo e($tugas->judul); ?>

                </h1>
                <p class="mobile-subtitle md:text-base text-gray-600 mt-1">
                    <?php echo e($tugas->kelas->nama_kelas ?? '-'); ?> • <?php echo e($tugas->mapel->nama ?? '-'); ?>

                </p>
            </div>
            <div class="mobile-deadline md:text-right">
                <div class="text-xs md:text-sm text-gray-500">Deadline</div>
                <div class="text-sm md:text-lg font-semibold text-gray-800">
                    <?php echo e($tugas->tanggal_deadline ? \Carbon\Carbon::parse($tugas->tanggal_deadline)->format('d M Y') : '-'); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid mobile-stats-grid md:grid-cols-3 gap-3 md:gap-4 mobile-section-spacing md:mb-6">
        <div class="mobile-stat-card stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-green-400 to-green-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-check text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Sudah Mengumpulkan</p>
                    <h3 class="text-lg md:text-2xl font-bold text-gray-800"><?php echo e($submissions->total()); ?></h3>
                </div>
            </div>
        </div>

        <div class="mobile-stat-card stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-red-400 to-red-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-times text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Belum Mengumpulkan</p>
                    <h3 class="text-lg md:text-2xl font-bold text-gray-800"><?php echo e($siswaKelas->count()); ?></h3>
                </div>
            </div>
        </div>

        <div class="mobile-stat-card stat-card bg-white rounded-xl p-3 md:p-6 border border-gray-100 shadow-sm">
            <div class="flex items-center">
                <div class="stat-icon flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg p-2 md:p-3 shadow-sm">
                    <i class="fas fa-users text-white text-lg md:text-xl"></i>
                </div>
                <div class="ml-3 md:ml-4">
                    <p class="text-xs md:text-sm text-gray-600">Total Siswa</p>
                    <h3 class="text-lg md:text-2xl font-bold text-gray-800"><?php echo e($submissions->total() + $siswaKelas->count()); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mobile-section-spacing md:mb-6">
        <div class="md:border-b md:border-gray-200">
            <nav class="mobile-tabs md:-mb-px md:flex md:space-x-8 md:px-6" aria-label="Tabs">
                <button onclick="showTab('submitted')" id="tab-submitted"
                        class="tab-button active py-3 md:py-4 px-3 md:px-1 border-b-2 md:border-b-2 border-green-500 font-medium text-sm text-green-600">
                    <i class="fas fa-check mr-2"></i>
                    <span class="hidden sm:inline">Sudah </span>Mengumpulkan (<?php echo e($submissions->total()); ?>)
                </button>
                <button onclick="showTab('not-submitted')" id="tab-not-submitted"
                        class="tab-button py-3 md:py-4 px-3 md:px-1 border-b-2 md:border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                    <i class="fas fa-times mr-2"></i>
                    <span class="hidden sm:inline">Belum </span>Mengumpulkan (<?php echo e($siswaKelas->count()); ?>)
                </button>
            </nav>
        </div>

        <!-- Submitted Tab Content -->
        <div id="content-submitted" class="tab-content mobile-spacing md:p-6">
            <?php if($submissions->count() > 0): ?>
                <div class="space-y-3 md:space-y-4">
                    <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mobile-submission-card submission-card bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
                            <div class="flex mobile-submission-header md:items-start md:justify-between">
                                <div class="mobile-student-info">
                                    <div class="mobile-student-avatar w-10 h-10 md:w-12 md:h-12 rounded-full overflow-hidden bg-gray-200 student-avatar mr-3 md:mr-4">
                                        <?php if($submission->siswa->foto && Storage::disk('public')->exists($submission->siswa->foto)): ?>
                                            <img src="<?php echo e(asset('storage/' . $submission->siswa->foto)); ?>"
                                                 alt="Foto <?php echo e($submission->siswa->nama_lengkap); ?>"
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($submission->siswa->nama_lengkap)); ?>&background=3b82f6&color=ffffff&size=48"
                                                 alt="Foto <?php echo e($submission->siswa->nama_lengkap); ?>"
                                                 class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mobile-student-details flex-1">
                                        <h4 class="mobile-student-name md:text-lg font-semibold text-gray-900"><?php echo e($submission->siswa->nama_lengkap); ?></h4>
                                        <p class="text-xs md:text-sm text-gray-600">
                                            <?php echo e($submission->siswa->nis ?? $submission->siswa->nisn); ?>

                                        </p>
                                        <div class="mt-2 flex mobile-submission-meta md:items-center text-sm text-gray-500 md:space-x-4">
                                            <span>
                                                <i class="fas fa-clock mr-1"></i>
                                                <span class="hidden sm:inline">Dikumpulkan: </span><?php echo e($submission->created_at->format('d M Y H:i')); ?>

                                            </span>
                                            <?php if($submission->catatan): ?>
                                                <span>
                                                    <i class="fas fa-comment mr-1"></i>
                                                    <span class="hidden sm:inline">Ada </span>catatan
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($submission->catatan): ?>
                                            <div class="mobile-notes mt-3 p-2 md:p-3 bg-white rounded border-l-4 border-blue-500">
                                                <p class="text-xs md:text-sm text-gray-700 font-medium">Catatan Siswa:</p>
                                                <p class="text-xs md:text-sm text-gray-600 mt-1"><?php echo e($submission->catatan); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="mobile-submission-actions">
                                    <?php if($submission->file_path): ?>
                                        <a href="<?php echo e(Storage::url($submission->file_path)); ?>" target="_blank"
                                           class="action-btn px-2 md:px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-xs md:text-sm font-medium">
                                            <i class="fas fa-download mr-1"></i> <span class="hidden sm:inline">Download</span>
                                        </a>
                                    <?php endif; ?>
                                    <span class="px-2 md:px-3 py-2 bg-green-100 text-green-700 rounded-lg text-xs md:text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i> <span class="hidden sm:inline">Di</span>kumpulkan
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <?php if($submissions->hasPages()): ?>
                    <div class="mt-6">
                        <?php echo e($submissions->links()); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mobile-empty-state text-center py-8 md:py-12">
                    <div class="mobile-empty-icon mx-auto flex items-center justify-center h-10 w-10 md:h-12 md:w-12 rounded-full bg-green-100">
                        <i class="fas fa-check text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="mt-3 md:mt-4 text-sm md:text-base font-medium text-gray-900">Belum ada yang mengumpulkan</h3>
                    <p class="mt-2 text-xs md:text-sm text-gray-500">Siswa belum mengumpulkan tugas ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Not Submitted Tab Content -->
        <div id="content-not-submitted" class="tab-content mobile-spacing md:p-6 hidden">
            <?php if($siswaKelas->count() > 0): ?>
                <div class="space-y-3 md:space-y-4">
                    <?php $__currentLoopData = $siswaKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mobile-submission-card submission-card bg-gray-50 rounded-lg p-3 md:p-4 border border-gray-200">
                            <div class="flex mobile-submission-header md:items-center md:justify-between">
                                <div class="mobile-student-info">
                                    <div class="mobile-student-avatar w-10 h-10 md:w-12 md:h-12 rounded-full overflow-hidden bg-gray-200 student-avatar mr-3 md:mr-4">
                                        <?php if($siswa->foto && Storage::disk('public')->exists($siswa->foto)): ?>
                                            <img src="<?php echo e(asset('storage/' . $siswa->foto)); ?>"
                                                 alt="Foto <?php echo e($siswa->nama_lengkap); ?>"
                                                 class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($siswa->nama_lengkap)); ?>&background=ef4444&color=ffffff&size=48"
                                                 alt="Foto <?php echo e($siswa->nama_lengkap); ?>"
                                                 class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div class="mobile-student-details flex-1">
                                        <h4 class="mobile-student-name md:text-lg font-semibold text-gray-900"><?php echo e($siswa->nama_lengkap); ?></h4>
                                        <p class="text-xs md:text-sm text-gray-600">
                                            <?php echo e($siswa->nis ?? $siswa->nisn); ?>

                                        </p>
                                    </div>
                                </div>
                                <div class="mobile-submission-actions">
                                    <span class="px-2 md:px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs md:text-sm font-medium">
                                        <i class="fas fa-times mr-1"></i> <span class="hidden sm:inline">Belum </span>Mengumpulkan
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="mobile-empty-state text-center py-8 md:py-12">
                    <div class="mobile-empty-icon mx-auto flex items-center justify-center h-10 w-10 md:h-12 md:w-12 rounded-full bg-green-100">
                        <i class="fas fa-check text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <h3 class="mt-3 md:mt-4 text-sm md:text-base font-medium text-gray-900">Semua siswa sudah mengumpulkan!</h3>
                    <p class="mt-2 text-xs md:text-sm text-gray-500">Tidak ada siswa yang belum mengumpulkan tugas ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.tab-button.active {
    border-color: #10b981;
    color: #10b981;
}

.tab-content.hidden {
    display: none;
}
</style>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
        button.classList.add('border-transparent', 'text-gray-500');
        button.classList.remove('border-green-500', 'text-green-600');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active');
    activeButton.classList.remove('border-transparent', 'text-gray-500');
    activeButton.classList.add('border-green-500', 'text-green-600');
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\materi\submissions.blade.php ENDPATH**/ ?>