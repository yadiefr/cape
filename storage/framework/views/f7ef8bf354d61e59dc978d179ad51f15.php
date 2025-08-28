

<?php $__env->startSection('title', 'Nilai - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Nilai</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Nilai</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-6">
        <form action="<?php echo e(route('siswa.nilai.index')); ?>" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <select name="semester" class="w-full pl-3 pr-10 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                        <option value="">Semua Semester</option>
                        <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $semester): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($semester); ?>" <?php echo e(request('semester') == $semester ? 'selected' : ''); ?>>
                                Semester <?php echo e($semester); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="<?php echo e(route('siswa.nilai.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Grades List -->
    <?php if($nilai->isEmpty()): ?>
        <div class="bg-white rounded-xl p-8 text-center border border-gray-100">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-lg mb-4">
                <i class="fas fa-star text-2xl text-blue-500"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Nilai</h3>
            <p class="text-gray-600">Tidak ada data nilai yang tersedia saat ini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $nilai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mataPelajaran => $nilaiList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800"><?php echo e($mataPelajaran); ?></h3>
                </div>
                <div class="p-4">
                    <div class="space-y-4">
                        <?php $__currentLoopData = $nilaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-800">
                                    <?php echo e($n->jenis_nilai); ?>

                                </p>
                                <p class="text-xs text-gray-500">Semester <?php echo e($n->semester); ?></p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-lg font-semibold text-<?php echo e($n->nilai_akhir >= ($n->mataPelajaran->kkm ?? 75) ? 'green' : 'red'); ?>-500">
                                    <?php echo e($n->nilai_akhir ?? $n->nilai); ?>

                                </span>
                                <a href="<?php echo e(route('siswa.nilai.show', $n->id)); ?>" 
                                   class="text-blue-500 hover:text-blue-600 transition-colors">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\nilai\index.blade.php ENDPATH**/ ?>