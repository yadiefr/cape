

<?php $__env->startSection('title', 'Profil Saya'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Profil Saya</h1>
        <p class="text-gray-600">Informasi personal dan akun Anda</p>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                <p><?php echo e(session('success')); ?></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden mb-4 border-4 border-blue-100">
                        <img src="<?php echo e($user->photo ? asset('storage/'.$user->photo) : asset('assets/images/faces/1.jpg')); ?>" 
                             alt="Profile Photo"
                             class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-1"><?php echo e($user->name); ?></h3>
                    <p class="text-sm text-gray-500"><?php echo e(ucfirst($user->role ?? 'Administrator')); ?></p>
                </div>
            </div>
        </div>

        <!-- Profile Info -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <!-- Profile Details -->
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Akun</h3>
                        <a href="<?php echo e(route('admin.profile.edit')); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profil
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nama Lengkap</label>
                            <p class="text-gray-800"><?php echo e(Auth::user()->name); ?></p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                            <p class="text-gray-800"><?php echo e(Auth::user()->email); ?></p>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Nomor Telepon</label>
                            <p class="text-gray-800"><?php echo e(Auth::user()->phone ?? '-'); ?></p>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Alamat</label>
                            <p class="text-gray-800"><?php echo e(Auth::user()->address ?? '-'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Last Login Info -->
                <div class="border-t border-gray-100 px-6 py-4">
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-2"></i>
                        Login terakhir: <?php echo e(Auth::user()->last_login ? Auth::user()->last_login->diffForHumans() : 'Belum pernah login'); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\profile\index.blade.php ENDPATH**/ ?>