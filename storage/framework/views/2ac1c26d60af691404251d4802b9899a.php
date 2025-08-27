

<?php $__env->startSection('title', 'Detail Pengumuman - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Detail Pengumuman</h2>
        <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- Judul -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800"><?php echo e($pengumuman->judul); ?></h1>
                <div class="flex flex-wrap gap-4 mt-3">
                    <div class="inline-flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>
                        <span>Mulai: <?php echo e($pengumuman->tanggal_mulai->format('d M Y')); ?></span>
                    </div>
                    <?php if($pengumuman->tanggal_selesai): ?>
                    <div class="inline-flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-check text-blue-500 mr-2"></i>
                        <span>Selesai: <?php echo e($pengumuman->tanggal_selesai->format('d M Y')); ?></span>
                    </div>
                    <?php endif; ?>                    <div class="inline-flex items-center text-sm text-gray-600">
                        <i class="fas fa-users text-blue-500 mr-2"></i>
                        <span>Target: <?php echo e(ucfirst($pengumuman->target_role)); ?></span>
                    </div>
                    <div class="inline-flex items-center text-sm text-gray-600">
                        <i class="fas fa-globe text-blue-500 mr-2"></i>
                        <span>Tampil di Beranda: <?php echo e($pengumuman->show_on_homepage ? 'Ya' : 'Tidak'); ?></span>
                    </div>
                    <div class="inline-flex items-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($pengumuman->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                            <?php echo e($pengumuman->is_active ? 'Aktif' : 'Nonaktif'); ?>

                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Divider -->
            <hr class="my-6 border-gray-200">
            
            <!-- Isi Pengumuman -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Isi Pengumuman</h3>
                <div class="prose max-w-none text-gray-600">
                    <?php echo nl2br(e($pengumuman->isi)); ?>

                </div>
            </div>
            
            <!-- Aksi -->
            <div class="flex space-x-3 mt-8">
                <a href="<?php echo e(route('admin.pengumuman.edit', $pengumuman->id)); ?>" class="inline-flex items-center px-4 py-2 bg-yellow-100 border border-yellow-200 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-all">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Pengumuman
                </a>
                <form action="<?php echo e(route('admin.pengumuman.destroy', $pengumuman->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 border border-red-200 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                        <i class="fas fa-trash mr-2"></i>
                        Hapus Pengumuman
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\pengumuman\show.blade.php ENDPATH**/ ?>