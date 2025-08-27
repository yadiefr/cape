

<?php $__env->startSection('title', 'Pengumuman'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="<?php echo e(route('guru.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li><i class="fas fa-chevron-right text-xs text-gray-400"></i></li>
                <li class="text-gray-900 font-medium">Pengumuman</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 mb-2">Pengumuman</h1>
            <p class="text-gray-600">Informasi dan pengumuman resmi dari sekolah</p>
        </div>

        <!-- Pengumuman List -->
        <?php if($pengumuman->count() > 0): ?>
            <div class="space-y-6">
                <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-500 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-bullhorn"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h2 class="text-lg font-semibold text-gray-800">
                                        <a href="<?php echo e(route('guru.pengumuman.show', $p->id)); ?>" class="hover:text-blue-600">
                                            <?php echo e($p->judul); ?>

                                        </a>
                                    </h2>
                                    <p class="text-sm text-gray-500">
                                        <?php echo e(\Carbon\Carbon::parse($p->tanggal_mulai)->format('d F Y')); ?>

                                    </p>
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                <?php echo e($p->target_role == 'all' ? 'Semua' : ucfirst($p->target_role)); ?>

                            </span>
                        </div>
                        <div class="text-gray-600 text-sm mb-4">
                            <?php echo e(Str::limit(strip_tags($p->isi), 150)); ?>

                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span><?php echo e($p->created_at->diffForHumans()); ?></span>
                                <?php if($p->tanggal_selesai): ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar-times mr-1"></i>
                                <span>Berlaku sampai <?php echo e(\Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y')); ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo e(route('guru.pengumuman.show', $p->id)); ?>" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-700">
                                Baca selengkapnya
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <?php if($pengumuman->hasPages()): ?>
            <div class="mt-8">
                <?php echo e($pengumuman->links()); ?>

            </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-bullhorn text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Pengumuman</h3>
                <p class="text-gray-600">Saat ini belum ada pengumuman yang tersedia untuk ditampilkan.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\pengumuman\index.blade.php ENDPATH**/ ?>