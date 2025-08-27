

<?php $__env->startSection('title', 'Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengumuman</h1>

    <?php if($pengumuman->isEmpty()): ?>
        <div class="bg-gray-50 rounded-lg p-6 text-center">
            <p class="text-gray-500">Tidak ada pengumuman saat ini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-lg font-semibold text-gray-800">
                                        <a href="<?php echo e(route('siswa.pengumuman.show', $p->id)); ?>" class="hover:text-blue-600">
                                            <?php echo e($p->judul); ?>

                                        </a>
                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        <?php echo e(\Carbon\Carbon::parse($p->tanggal_mulai)->format('d F Y')); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="text-gray-600 text-sm mb-4">
                            <?php echo e(Str::limit(strip_tags($p->isi), 100)); ?>

                        </div>
                        <div class="text-right">
                            <a href="<?php echo e(route('siswa.pengumuman.show', $p->id)); ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                Baca selengkapnya
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\pengumuman\index.blade.php ENDPATH**/ ?>