

<?php $__env->startSection('title', 'Tambah Kelas Baru - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container mx-auto px-4 py-6 max-w-md">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Kelas Baru</h1>
        <div class="text-sm breadcrumbs mb-4">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.kelas.index')); ?>" class="hover:text-blue-600">Manajemen Kelas</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Tambah Kelas</span>
                </li>
            </ul>
        </div>

        <?php if($errors->any()): ?>
        <div class="bg-red-50 text-red-700 p-4 mb-4 rounded-md">
            <ul class="list-disc pl-5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?php echo e(route('admin.kelas.store')); ?>" method="POST" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-gray-700">Nama Kelas</label>
                <input type="text" name="nama_kelas" id="nama_kelas" value="<?php echo e(old('nama_kelas')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
            </div>

            <div>
                <label for="jurusan_id" class="block text-sm font-medium text-gray-700">Jurusan</label>
                <select name="jurusan_id" id="jurusan_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
                    <option value="">Pilih Jurusan</option>
                    <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($j->id); ?>" <?php echo e(old('jurusan_id') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jurusan); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label for="wali_kelas" class="block text-sm font-medium text-gray-700">Wali Kelas</label>
                <select name="wali_kelas" id="wali_kelas" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3">
                    <option value="">-- Pilih Wali Kelas --</option>
                    <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $hasClass = $g->kelas()->exists();
                        ?>
                        <option value="<?php echo e($g->id); ?>" 
                            <?php echo e(old('wali_kelas') == $g->id ? 'selected' : ''); ?>

                            <?php echo e($hasClass ? 'disabled' : ''); ?>>
                            <?php echo e($g->nama); ?> (<?php echo e($g->nip); ?>)
                            <?php if($hasClass): ?>
                                - Sudah Menjadi Wali Kelas Lain
                            <?php elseif($g->is_wali_kelas): ?>
                                - Dapat Menjadi Wali Kelas
                            <?php endif; ?>
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <p class="text-xs text-gray-500 mt-1">Hanya guru yang belum menjadi wali kelas yang dapat dipilih</p>
            </div>

            <div>
                <label for="tingkat" class="block text-sm font-medium text-gray-700">Tingkat</label>
                <input type="number" name="tingkat" id="tingkat" value="<?php echo e(old('tingkat')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required min="10" max="12">
            </div>

            <div>
                <label for="tahun_ajaran" class="block text-sm font-medium text-gray-700">Tahun Ajaran</label>
                <input type="text" name="tahun_ajaran" id="tahun_ajaran" value="<?php echo e(old('tahun_ajaran', date('Y').'/'.((int)date('Y')+1))); ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-2 px-3" required>
                <p class="text-xs text-gray-500 mt-1">Format: 2023/2024</p>
            </div>

            <div class="flex justify-between pt-4">
                <a href="<?php echo e(route('admin.kelas.index')); ?>" class="bg-gray-300 text-gray-700 px-5 py-2 rounded-md shadow hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="bg-blue-500 text-white px-5 py-2 rounded-md shadow hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-save mr-1"></i> Simpan Kelas
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\kelas\create.blade.php ENDPATH**/ ?>