

<?php $__env->startSection('title', 'Detail Nilai - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="w-full px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Nilai</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.nilai.index')); ?>" class="hover:text-blue-600">Manajemen Nilai</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Detail</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-semibold text-gray-800">Detail Nilai</h3>
            <div class="space-x-2">
                <a href="<?php echo e(route('admin.nilai.edit', $nilai->id)); ?>" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 inline-flex items-center">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="<?php echo e(route('admin.nilai.destroy', $nilai->id)); ?>" method="POST" class="inline-block">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 inline-flex items-center" onclick="return confirm('Apakah Anda yakin ingin menghapus nilai ini?')">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Informasi Siswa</h3>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">NIS</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->siswa->nis ?? '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">NISN</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->siswa->nisn ?? '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Nama Lengkap</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->siswa->nama_lengkap ?? '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Kelas</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->siswa->kelas->nama_kelas ?? '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Jurusan</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->siswa->jurusan->nama_jurusan ?? '-'); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Informasi Nilai</h3>
                        <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Mata Pelajaran</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->mapel ? $nilai->mapel->nama : '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Kategori</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->mapel->kategori ?? '-'); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Nilai</dt>
                                    <dd class="text-gray-800 font-bold text-xl"><?php echo e($nilai->nilai); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Waktu Input</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->created_at->format('d M Y, H:i')); ?></dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500 font-medium">Terakhir Diperbarui</dt>
                                    <dd class="text-gray-800"><?php echo e($nilai->updated_at->format('d M Y, H:i')); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if($nilai->catatan): ?>
            <div class="mt-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan</h3>
                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                    <p class="text-gray-800"><?php echo e($nilai->catatan); ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="p-4 border-t border-gray-100 bg-gray-50">
            <div class="flex justify-between">
                <a href="<?php echo e(route('admin.nilai.index')); ?>" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded flex items-center">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\nilai\show.blade.php ENDPATH**/ ?>