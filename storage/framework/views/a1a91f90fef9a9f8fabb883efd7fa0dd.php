

<?php $__env->startSection('title', 'Pendaftaran PPDB - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="max-w-4xl mx-auto text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl mb-4">
                Pendaftaran Peserta Didik Baru
            </h1>
            <p class="text-lg text-gray-600">
                Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y') + 1); ?>

            </p>
        </div>

        <?php if($errors->any()): ?>
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">
                            Terdapat beberapa kesalahan:
                        </h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="px-6 py-8">
                <form action="<?php echo e(route('ppdb.register.submit')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>

                    <!-- Data Pribadi -->
                    <div class="space-y-6">
                        <h2 class="text-xl font-semibold text-gray-900 border-b pb-2">Data Pribadi</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" 
                                       name="nama_lengkap" 
                                       id="nama_lengkap" 
                                       value="<?php echo e(old('nama_lengkap')); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="no_telp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                                <input type="text" 
                                       name="no_telp" 
                                       id="no_telp" 
                                       value="<?php echo e(old('no_telp')); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" 
                                       name="password" 
                                       id="password" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" 
                                       name="password_confirmation" 
                                       id="password_confirmation" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-6">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="max-w-4xl mx-auto mt-8">
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-500"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Informasi Penting</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Setelah mendaftar, Anda akan dapat melanjutkan dengan pengisian formulir lengkap PPDB yang mencakup:</p>
                            <ul class="list-disc list-inside mt-2">
                                <li>Data pribadi lengkap</li>
                                <li>Pemilihan jurusan</li>
                                <li>Data orang tua</li>
                                <li>Upload dokumen persyaratan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\register.blade.php ENDPATH**/ ?>