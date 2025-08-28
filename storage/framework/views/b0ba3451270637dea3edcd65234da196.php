

<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full px-6 py-6">
    <div class="flex flex-col space-y-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Profil Saya</h1>
    </div>

    <!-- Success/Error Messages -->
    <?php if(session('success')): ?>
    <div class="mb-6">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="mb-6">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 text-center bg-gradient-to-b from-blue-50 to-white">
                    <div class="mb-4">
                        <?php if($siswa->foto): ?>
                            <img src="<?php echo e(asset('storage/' . $siswa->foto)); ?>" 
                                 class="rounded-full w-32 h-32 mx-auto border-4 border-white shadow-md object-cover"
                                 alt="Foto Profil">
                        <?php else: ?>
                            <div class="rounded-full w-32 h-32 mx-auto border-4 border-white shadow-md bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-user text-4xl text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h4 class="font-semibold text-gray-800 mb-1"><?php echo e($siswa->nama_lengkap); ?></h4>
                    <p class="text-gray-600 text-sm mb-2"><?php echo e($siswa->nis); ?></p>
                    <div class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                        <?php echo e($siswa->kelas ? $siswa->kelas->nama_kelas : 'Belum ada kelas'); ?>

                    </div>
                </div>
                <div class="p-4 space-y-3">
                    <a href="<?php echo e(route('siswa.profile.edit')); ?>" 
                       class="block text-center bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </a>
                    
                    <!-- Logout Button -->
                    <form method="POST" action="<?php echo e(route('siswa.logout')); ?>" class="w-full">
                        <?php echo csrf_field(); ?>
                        <button type="submit" 
                                class="block w-full text-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-colors"
                                onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pribadi</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Info -->
                        <div>
                            <dl>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->nama_lengkap); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">NIS</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->nis); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">NISN</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->nisn); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Contact Info -->
                        <div>
                            <dl>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Email</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->email ?: '-'); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Nomor Telepon</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->telepon ?: '-'); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Alamat</dt>
                                    <dd class="text-gray-900"><?php echo e($siswa->alamat ?: '-'); ?></dd>
                                </div>
                                <div class="mb-4">
                                    <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($siswa->status == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e(ucfirst($siswa->status)); ?>

                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akademik</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <dl>
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Kelas</dt>
                                <dd class="text-gray-900"><?php echo e($siswa->kelas ? $siswa->kelas->nama_kelas : '-'); ?></dd>
                            </div>
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Jurusan</dt>
                                <dd class="text-gray-900"><?php echo e($siswa->jurusan ? $siswa->jurusan->nama_jurusan : '-'); ?></dd>
                            </div>
                        </dl>
                        <dl>
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Tahun Masuk</dt>
                                <dd class="text-gray-900"><?php echo e($siswa->tahun_masuk ?: '-'); ?></dd>
                            </div>
                            <div class="mb-4">
                                <dt class="text-sm font-medium text-gray-500 mb-1">Semester</dt>
                                <dd class="text-gray-900"><?php echo e(App\Models\Settings::getValue('semester_aktif') ?: '-'); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\profile\index.blade.php ENDPATH**/ ?>