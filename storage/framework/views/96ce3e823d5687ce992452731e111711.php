

<?php $__env->startSection('title', 'Detail Kehadiran - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Detail Kehadiran</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.absensi.index')); ?>" class="hover:text-blue-600">Kehadiran</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Detail</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Header Info -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Kelas</h3>
                    <dl class="space-y-2">
                        <div class="flex">
                            <dt class="w-32 text-gray-500">Kelas</dt>
                            <dd class="flex-1 text-gray-900"><?php echo e($absensi->first()->siswa->kelas->nama_kelas); ?></dd>
                        </div>
                        <div class="flex">
                            <dt class="w-32 text-gray-500">Mata Pelajaran</dt>
                            <dd class="flex-1 text-gray-900"><?php echo e($absensi->first()->mapel ? $absensi->first()->mapel->nama : '-'); ?></dd>
                        </div>
                        <div class="flex">
                            <dt class="w-32 text-gray-500">Guru</dt>
                            <dd class="flex-1 text-gray-900"><?php echo e($absensi->first()->guru->name); ?></dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Informasi Absensi</h3>
                    <dl class="space-y-2">
                        <div class="flex">
                            <dt class="w-32 text-gray-500">Tanggal</dt>
                            <dd class="flex-1 text-gray-900"><?php echo e($absensi->first()->tanggal->format('d F Y')); ?></dd>
                        </div>
                        <div class="flex">
                            <dt class="w-32 text-gray-500">Jumlah Siswa</dt>
                            <dd class="flex-1 text-gray-900"><?php echo e($absensi->count()); ?> Siswa</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Statistik -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-green-600">Hadir</p>
                            <p class="text-2xl font-bold text-green-800"><?php echo e($stats['hadir']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-yellow-600">Izin</p>
                            <p class="text-2xl font-bold text-yellow-800"><?php echo e($stats['izin']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-orange-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-orange-500 rounded-lg p-3">
                            <i class="fas fa-procedures text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-orange-600">Sakit</p>
                            <p class="text-2xl font-bold text-orange-800"><?php echo e($stats['sakit']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <i class="fas fa-times text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-red-600">Alpha</p>
                            <p class="text-2xl font-bold text-red-800"><?php echo e($stats['alpha']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e($data->siswa->name); ?></div>
                            <div class="text-xs text-gray-500">NIS: <?php echo e($data->siswa->nis); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo e($data->status === 'hadir' ? 'bg-green-100 text-green-800' : 
                                   ($data->status === 'izin' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($data->status === 'sakit' ? 'bg-orange-100 text-orange-800' : 
                                   'bg-red-100 text-red-800'))); ?>">
                                <?php echo e(ucfirst($data->status)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($data->keterangan ?: '-'); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <a href="<?php echo e(route('admin.absensi.edit', $data->id)); ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\absensi\show.blade.php ENDPATH**/ ?>