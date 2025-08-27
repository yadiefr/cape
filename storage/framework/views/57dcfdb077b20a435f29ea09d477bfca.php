

<?php $__env->startSection('title', 'Detail Ujian'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50/30">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="text-gray-500 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="<?php echo e(route('siswa.ujian.index')); ?>" class="text-gray-500 hover:text-blue-600">Ujian</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900 font-medium"><?php echo e($ujian->nama_ujian); ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Ujian Detail Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
                <h1 class="text-2xl font-bold text-gray-900"><?php echo e($ujian->nama_ujian); ?></h1>
                <p class="text-blue-700 mt-1"><?php echo e($ujian->mataPelajaran->nama_mata_pelajaran); ?></p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Informasi Ujian -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Ujian</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <i class="fas fa-user text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Pengajar</p>
                                    <p class="font-medium text-gray-900"><?php echo e($ujian->guru->nama); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-calendar text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-medium text-gray-900"><?php echo e($ujian->tanggal->format('d F Y')); ?></p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-clock text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Waktu</p>
                                    <p class="font-medium text-gray-900">
                                        <?php echo e($ujian->waktu_mulai->format('H:i')); ?> - <?php echo e($ujian->waktu_selesai->format('H:i')); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-hourglass-half text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Durasi</p>
                                    <p class="font-medium text-gray-900"><?php echo e($ujian->durasi); ?> menit</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-users text-gray-400 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Kelas</p>
                                    <p class="font-medium text-gray-900"><?php echo e($ujian->kelas->nama_kelas); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status dan Aksi -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Aksi</h3>
                        
                        <?php if($hasilUjian): ?>
                            <!-- Sudah Dikerjakan -->
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                    <h4 class="font-semibold text-green-800">Ujian Sudah Dikerjakan</h4>
                                </div>
                                <p class="text-sm text-green-700 mb-3"><?php echo e($hasilUjian->catatan); ?></p>
                                <div class="text-center">
                                    <div class="text-3xl font-bold <?php echo e($hasilUjian->nilai >= 70 ? 'text-green-600' : 'text-red-600'); ?> mb-1">
                                        <?php echo e(number_format($hasilUjian->nilai, 1)); ?>

                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <?php echo e($hasilUjian->nilai >= 70 ? 'Lulus' : 'Tidak Lulus'); ?>

                                    </div>
                                </div>
                            </div>
                        <?php elseif($canTakeExam): ?>
                            <!-- Dapat Dikerjakan -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-clock text-blue-600 mr-2"></i>
                                    <h4 class="font-semibold text-blue-800">Ujian Sedang Berlangsung</h4>
                                </div>
                                <p class="text-sm text-blue-700 mb-4">Ujian dapat dikerjakan sekarang.</p>
                                <a href="<?php echo e(route('siswa.ujian.start', $ujian->id)); ?>" 
                                   class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <i class="fas fa-play mr-2"></i>
                                    Mulai Ujian
                                </a>
                            </div>
                        <?php else: ?>
                            <!-- Belum Dapat Dikerjakan -->
                            <?php
                                $now = now();
                                $isBeforeStart = $ujian->waktu_mulai > $now;
                                $isAfterEnd = $ujian->waktu_selesai < $now;
                            ?>
                            
                            <?php if($isBeforeStart): ?>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-calendar-alt text-yellow-600 mr-2"></i>
                                        <h4 class="font-semibold text-yellow-800">Ujian Belum Dimulai</h4>
                                    </div>
                                    <p class="text-sm text-yellow-700">
                                        Ujian akan dimulai pada <?php echo e($ujian->waktu_mulai->format('d F Y, H:i')); ?>

                                    </p>
                                </div>
                            <?php elseif($isAfterEnd): ?>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-times-circle text-gray-600 mr-2"></i>
                                        <h4 class="font-semibold text-gray-800">Ujian Sudah Berakhir</h4>
                                    </div>
                                    <p class="text-sm text-gray-700">
                                        Ujian berakhir pada <?php echo e($ujian->waktu_selesai->format('d F Y, H:i')); ?>

                                    </p>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Back Button -->
                        <a href="<?php echo e(route('siswa.ujian.index')); ?>" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Ujian
                        </a>
                    </div>
                </div>

                <!-- Deskripsi -->
                <?php if($ujian->deskripsi): ?>
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        <?php echo nl2br(e($ujian->deskripsi)); ?>

                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\ujian\show.blade.php ENDPATH**/ ?>