

<?php $__env->startSection('title', 'Detail Nilai - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    @media print {
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        body {
            font-size: 12px;
        }

        .bg-gradient-to-r {
            background: #f3f4f6 !important;
        }

        .text-green-500, .text-red-500 {
            color: #000 !important;
        }
    }

    .print-only {
        display: none;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full px-6 py-6">
    <div class="mb-6 no-print">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Nilai</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <!-- Header Info -->
        <div class="mb-6 pb-4 border-b border-gray-200">
            <div class="flex flex-col gap-2">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Informasi Penilaian -->
            <div>
                <h3 class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Informasi Penilaian
                </h3>
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Mata Pelajaran</p>
                                <p class="font-medium text-gray-800"><?php echo e($nilai->mataPelajaran->nama ?? 'Mata Pelajaran'); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Jenis Penilaian</p>
                                <p class="font-medium text-gray-800">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e(ucfirst($nilai->jenis_nilai ?? 'Penilaian')); ?>

                                    </span>
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Semester</p>
                                <p class="font-medium text-gray-800">Semester <?php echo e($nilai->semester); ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Tahun Ajaran</p>
                                <p class="font-medium text-gray-800"><?php echo e($nilai->tahun_ajaran ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>

                    <?php if($nilai->deskripsi): ?>
                    <div>
                        <p class="text-sm text-gray-600 mb-2 font-medium">Deskripsi Penilaian</p>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <p class="text-gray-800"><?php echo e($nilai->deskripsi); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detail Nilai -->
            <div class="space-y-4">
                <!-- Breakdown Nilai -->
                <?php if($nilai->nilai_tugas || $nilai->nilai_uts || $nilai->nilai_uas || $nilai->nilai_praktik): ?>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-700 mb-3">Rincian Nilai</p>
                    <div class="space-y-2">
                        <?php if($nilai->nilai_tugas): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Tugas</span>
                            <span class="font-medium text-gray-800"><?php echo e($nilai->nilai_tugas); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($nilai->nilai_uts): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">UTS</span>
                            <span class="font-medium text-gray-800"><?php echo e($nilai->nilai_uts); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($nilai->nilai_uas): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">UAS</span>
                            <span class="font-medium text-gray-800"><?php echo e($nilai->nilai_uas); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($nilai->nilai_praktik): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Praktik</span>
                            <span class="font-medium text-gray-800"><?php echo e($nilai->nilai_praktik); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($nilai->catatan): ?>
                <div>
                    <p class="text-sm text-gray-600 mb-2 font-medium">Keterangan Guru</p>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg">
                        <p class="text-gray-800"><?php echo e($nilai->catatan); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($nilai->grade): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Grade</p>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-lg font-bold bg-purple-100 text-purple-800">
                        <?php echo e($nilai->grade); ?>

                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 pt-6 border-t border-gray-200 no-print">
            <div class="flex flex-col sm:flex-row gap-3 sm:justify-between">
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="<?php echo e(route('siswa.nilai.index')); ?>"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar Nilai
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\nilai\show.blade.php ENDPATH**/ ?>