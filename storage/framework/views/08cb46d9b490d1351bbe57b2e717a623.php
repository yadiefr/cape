

<?php $__env->startSection('title', 'Profil Guru'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-3"></i>
                    Profil Guru
                </h1>
                <p class="text-gray-600 mt-1">Informasi lengkap profil dan data kepegawaian</p>
            </div>
            <div>
                <a href="<?php echo e(route('guru.profile.edit')); ?>" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Profil
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Content -->
        <div class="p-6">
            <div class="grid lg:grid-cols-4 gap-6">
                <!-- Photo Section -->
                <div class="lg:col-span-1">
                    <div class="text-center">
                        <?php if($user->foto): ?>
                            <img class="mx-auto h-40 w-40 rounded-full object-cover border-4 border-gray-200 shadow-md" 
                                 src="<?php echo e(Storage::url($user->foto)); ?>" 
                                 alt="Foto profil">
                        <?php else: ?>
                            <div class="mx-auto h-40 w-40 rounded-full bg-gray-200 flex items-center justify-center border-4 border-gray-200 shadow-md">
                                <i class="fas fa-user text-5xl text-gray-400"></i>
                            </div>
                        <?php endif; ?>
                        <h2 class="mt-4 text-xl font-semibold text-gray-900"><?php echo e($user->nama); ?></h2>
                        <p class="text-sm text-gray-600">NIP: <?php echo e($user->nip ?? '-'); ?></p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-circle text-green-400 mr-1" style="font-size: 6px;"></i>
                                <?php echo e(ucfirst($user->status ?? 'aktif')); ?>

                            </span>
                        </div>
                        
                        <!-- Quick Stats -->
                        <div class="mt-6 space-y-3">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600"><?php echo e($totalKelas ?? 0); ?></div>
                                <div class="text-sm text-gray-600">Kelas Diampu</div>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="text-2xl font-bold text-green-600"><?php echo e($totalMapel ?? 0); ?></div>
                                <div class="text-sm text-gray-600">Mata Pelajaran</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="lg:col-span-3">
                    <div class="grid lg:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-user text-blue-500 mr-2"></i>
                                    Informasi Personal
                                </h3>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->nama ?? '-'); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">NIP</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->nip ?? '-'); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                                        <p class="text-gray-900 font-medium">
                                            <?php if($user->jenis_kelamin === 'L'): ?>
                                                Laki-laki
                                            <?php elseif($user->jenis_kelamin === 'P'): ?>
                                                Perempuan
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tempat, Tanggal Lahir</label>
                                        <p class="text-gray-900 font-medium">
                                            <?php if($user->tempat_lahir || $user->tanggal_lahir): ?>
                                                <?php echo e($user->tempat_lahir ?? ''); ?><?php if($user->tempat_lahir && $user->tanggal_lahir): ?>, <?php endif; ?><?php echo e($user->tanggal_lahir ? $user->tanggal_lahir->format('d F Y') : ''); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-phone text-green-500 mr-2"></i>
                                    Informasi Kontak
                                </h3>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->email ?? '-'); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">No. Telepon</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->telepon ?? '-'); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->alamat ?? '-'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Professional Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-briefcase text-purple-500 mr-2"></i>
                                    Informasi Kepegawaian
                                </h3>
                                <div class="space-y-4">
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                                        <p class="text-gray-900 font-medium"><?php echo e(ucfirst($user->status ?? 'aktif')); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Bergabung</label>
                                        <p class="text-gray-900 font-medium"><?php echo e($user->created_at ? $user->created_at->format('d F Y') : '-'); ?></p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <label class="block text-sm font-medium text-gray-600 mb-1">Wali Kelas</label>
                                        <p class="text-gray-900 font-medium">
                                            <?php if($user->is_wali_kelas): ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-check mr-1"></i> Ya
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-times mr-1"></i> Tidak
                                                </span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Teaching Schedule -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i class="fas fa-calendar text-orange-500 mr-2"></i>
                                    Jadwal Mengajar
                                </h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <div class="text-center py-4">
                                        <i class="fas fa-calendar-week text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-sm text-gray-600">Jadwal mengajar akan ditampilkan di sini</p>
                                        <a href="<?php echo e(route('guru.jadwal.index')); ?>" class="inline-flex items-center mt-2 text-sm text-blue-600 hover:text-blue-700">
                                            <i class="fas fa-external-link-alt mr-1"></i>
                                            Lihat Jadwal Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\profile\index.blade.php ENDPATH**/ ?>