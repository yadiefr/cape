

<?php $__env->startSection('title', 'Edit Pengaturan Jadwal'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="w-full px-3 py-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Pengaturan Jadwal</h1>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Oops!</strong>
            <span class="block sm:inline">Ada beberapa masalah dengan input Anda:</span>
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h2 class="text-xl font-semibold mb-4">Edit Pengaturan Jadwal</h2>        <form action="<?php echo e(route('admin.settings.jadwal.update', $setting->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" id="hari" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="">Pilih Hari</option>
                        <option value="Senin" <?php echo e($setting->hari == 'Senin' ? 'selected' : ''); ?>>Senin</option>
                        <option value="Selasa" <?php echo e($setting->hari == 'Selasa' ? 'selected' : ''); ?>>Selasa</option>
                        <option value="Rabu" <?php echo e($setting->hari == 'Rabu' ? 'selected' : ''); ?>>Rabu</option>
                        <option value="Kamis" <?php echo e($setting->hari == 'Kamis' ? 'selected' : ''); ?>>Kamis</option>
                        <option value="Jumat" <?php echo e($setting->hari == 'Jumat' ? 'selected' : ''); ?>>Jumat</option>
                        <option value="Sabtu" <?php echo e($setting->hari == 'Sabtu' ? 'selected' : ''); ?>>Sabtu</option>
                        <option value="Minggu" <?php echo e($setting->hari == 'Minggu' ? 'selected' : ''); ?>>Minggu</option>
                    </select>
                    <?php $__errorArgs = ['hari'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div>
                    <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="is_active" id="is_active" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                        <option value="1" <?php echo e(old('is_active', $setting->is_active) == 1 ? 'selected' : ''); ?>>Aktif</option>
                        <option value="0" <?php echo e(old('is_active', $setting->is_active) == 0 ? 'selected' : ''); ?>>Nonaktif</option>
                    </select>
                    <?php $__errorArgs = ['is_active'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label for="jam_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" id="jam_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_mulai', \Carbon\Carbon::parse($setting->jam_mulai)->format('H:i'))); ?>">
                    <?php $__errorArgs = ['jam_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="jam_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" id="jam_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_selesai', \Carbon\Carbon::parse($setting->jam_selesai)->format('H:i'))); ?>">
                    <?php $__errorArgs = ['jam_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="jam_istirahat_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 1 Mulai</label>
                    <input type="time" name="jam_istirahat_mulai" id="jam_istirahat_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_istirahat_mulai', $setting->jam_istirahat_mulai ? \Carbon\Carbon::parse($setting->jam_istirahat_mulai)->format('H:i') : '')); ?>">
                    <?php $__errorArgs = ['jam_istirahat_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="jam_istirahat_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 1 Selesai</label>
                    <input type="time" name="jam_istirahat_selesai" id="jam_istirahat_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_istirahat_selesai', $setting->jam_istirahat_selesai ? \Carbon\Carbon::parse($setting->jam_istirahat_selesai)->format('H:i') : '')); ?>">
                    <?php $__errorArgs = ['jam_istirahat_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="jam_istirahat2_mulai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 2 Mulai</label>
                    <input type="time" name="jam_istirahat2_mulai" id="jam_istirahat2_mulai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_istirahat2_mulai', $setting->jam_istirahat2_mulai ? \Carbon\Carbon::parse($setting->jam_istirahat2_mulai)->format('H:i') : '')); ?>">
                    <?php $__errorArgs = ['jam_istirahat2_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>                <div>
                    <label for="jam_istirahat2_selesai" class="block text-sm font-medium text-gray-700 mb-1">Jam Istirahat 2 Selesai</label>
                    <input type="time" name="jam_istirahat2_selesai" id="jam_istirahat2_selesai" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jam_istirahat2_selesai', $setting->jam_istirahat2_selesai ? \Carbon\Carbon::parse($setting->jam_istirahat2_selesai)->format('H:i') : '')); ?>">
                    <?php $__errorArgs = ['jam_istirahat2_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="jumlah_jam_pelajaran" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Jam Pelajaran</label>
                    <input type="number" name="jumlah_jam_pelajaran" id="jumlah_jam_pelajaran" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('jumlah_jam_pelajaran', $setting->jumlah_jam_pelajaran)); ?>" min="1" max="12">
                    <?php $__errorArgs = ['jumlah_jam_pelajaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="durasi_per_jam" class="block text-sm font-medium text-gray-700 mb-1">Durasi per Jam (menit)</label>
                    <input type="number" name="durasi_per_jam" id="durasi_per_jam" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" value="<?php echo e(old('durasi_per_jam', $setting->durasi_per_jam)); ?>" min="30" max="120">
                    <?php $__errorArgs = ['durasi_per_jam'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Update Pengaturan
                </button>                <a href="<?php echo e(route('admin.settings.jadwal.index')); ?>" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\settings\jadwal\edit.blade.php ENDPATH**/ ?>