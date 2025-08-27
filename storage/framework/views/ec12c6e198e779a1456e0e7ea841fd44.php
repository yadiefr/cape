

<?php $__env->startSection('title', 'Data Praktik Kerja Lapangan'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="p-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">Data Praktik Kerja Lapangan (PKL)</h2>

            <?php if(session('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if(session('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php $__empty_1 = true; $__currentLoopData = $pkl; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-6 bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2"><?php echo e($p->nama_perusahaan); ?></h3>
                            <p class="text-gray-600 mb-1">
                                <i class="fas fa-map-marker-alt mr-2"></i><?php echo e($p->alamat_perusahaan); ?>

                            </p>
                            <p class="text-gray-600 mb-1">
                                <i class="fas fa-industry mr-2"></i><?php echo e($p->bidang_usaha); ?>

                            </p>
                            <p class="text-gray-600 mb-1">
                                <i class="fas fa-calendar mr-2"></i><?php echo e($p->tanggal_mulai->format('d M Y')); ?> - <?php echo e($p->tanggal_selesai->format('d M Y')); ?>

                            </p>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                <?php if($p->status == 'pengajuan'): ?> bg-yellow-100 text-yellow-800
                                <?php elseif($p->status == 'berlangsung'): ?> bg-blue-100 text-blue-800
                                <?php elseif($p->status == 'selesai'): ?> bg-green-100 text-green-800
                                <?php else: ?> bg-red-100 text-red-800
                                <?php endif; ?>">
                                <?php echo e(ucfirst($p->status)); ?>

                            </span>
                        </div>
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Pembimbing:</p>
                                <p class="text-gray-600"><?php echo e($p->nama_pembimbing); ?></p>
                                <?php if($p->telepon_pembimbing): ?>
                                    <p class="text-gray-600"><i class="fas fa-phone mr-2"></i><?php echo e($p->telepon_pembimbing); ?></p>
                                <?php endif; ?>
                                <?php if($p->email_pembimbing): ?>
                                    <p class="text-gray-600"><i class="fas fa-envelope mr-2"></i><?php echo e($p->email_pembimbing); ?></p>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($p->status === 'selesai'): ?>
                            <div>
                                <p class="font-medium text-gray-700 mb-1">Nilai:</p>
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <p class="text-sm text-gray-600">Teknis</p>
                                        <p class="text-lg font-semibold"><?php echo e($p->nilai_teknis ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Sikap</p>
                                        <p class="text-lg font-semibold"><?php echo e($p->nilai_sikap ?? '-'); ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Laporan</p>
                                        <p class="text-lg font-semibold"><?php echo e($p->nilai_laporan ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="md:text-right">
                                <a href="<?php echo e(route('siswa.pkl.show', $p->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-eye mr-2"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8">
                    <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-500">Belum ada data PKL</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\pkl\index.blade.php ENDPATH**/ ?>