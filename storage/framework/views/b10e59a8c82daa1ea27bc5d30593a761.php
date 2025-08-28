

<?php $__env->startSection('title', 'Detail Kelas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="w-full px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($jadwal->mapel->nama ?? 'Tidak ada'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($jadwal->guru->nama ?? 'Belum ditentukan'); ?>

                                    </td>il Kelas <?php echo e($kelas->nama_kelas); ?></h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.kelas.index')); ?>" class="hover:text-blue-600">Manajemen Kelas</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Detail Kelas</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Informasi Kelas -->
        <div class="lg:w-1/3 w-full">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-blue-500 text-white">
                    <h3 class="font-semibold">Informasi Kelas</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Nama Kelas</span>
                            <span class="text-lg font-semibold"><?php echo e($kelas->nama_kelas); ?></span>
                        </div>
                        
                        <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Jurusan</span>
                            <span class="font-medium"><?php echo e($kelas->jurusan->nama_jurusan ?? 'Belum ditentukan'); ?></span>
                        </div>
                          <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Tingkat</span>
                            <span class="font-medium">
                                <?php if($kelas->tingkat == 1 || $kelas->tingkat == 10): ?>
                                    Kelas X (Sepuluh)
                                <?php elseif($kelas->tingkat == 2 || $kelas->tingkat == 11): ?>
                                    Kelas XI (Sebelas)
                                <?php elseif($kelas->tingkat == 3 || $kelas->tingkat == 12): ?>
                                    Kelas XII (Dua Belas)
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </span>
                        </div>
                        
                        <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Wali Kelas</span>
                            <span class="font-medium"><?php echo e($kelas->wali->nama ?? 'Belum ditentukan'); ?></span>
                        </div>
                        
                        <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Tahun Ajaran</span>
                            <span class="font-medium"><?php echo e($kelas->tahun_ajaran); ?></span>
                        </div>
                        
                        <div class="flex flex-col space-y-1">
                            <span class="text-sm text-gray-500">Status</span>
                            <?php if($kelas->is_active): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="mr-1.5 h-2 w-2 text-green-500" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="mr-1.5 h-2 w-2 text-red-500" fill="currentColor" viewBox="0 0 8 8">
                                        <circle cx="4" cy="4" r="3"></circle>
                                    </svg>
                                    Tidak Aktif
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between">
                        <a href="<?php echo e(route('admin.kelas.edit', $kelas->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-edit mr-2"></i> Edit Kelas
                        </a>
                        
                        <form action="<?php echo e(route('admin.kelas.destroy', $kelas->id)); ?>" method="POST" class="inline-block">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" 
                                onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                <i class="fas fa-trash mr-2"></i> Hapus Kelas
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Statistik Kelas -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                <div class="p-4 border-b border-gray-100 bg-blue-500 text-white">
                    <h3 class="font-semibold">Statistik Kelas</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <span class="text-2xl font-bold text-blue-600"><?php echo e($totalSiswa); ?></span>
                            <p class="text-sm text-gray-600">Total Siswa</p>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <span class="text-2xl font-bold text-blue-600"><?php echo e($siswaLaki); ?></span>
                            <p class="text-sm text-gray-600">Laki-laki</p>
                        </div>
                        
                        <div class="bg-blue-50 rounded-lg p-4 text-center">
                            <span class="text-2xl font-bold text-blue-600"><?php echo e($siswaPerempuan); ?></span>
                            <p class="text-sm text-gray-600">Perempuan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Daftar Siswa dan Jadwal -->
        <div class="lg:w-2/3 w-full">
            <!-- Daftar Siswa -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-blue-500 text-white flex justify-between items-center">
                    <h3 class="font-semibold">Daftar Siswa</h3>
                    <a href="<?php echo e(route('admin.siswa.create', ['kelas_id' => $kelas->id])); ?>" class="bg-white text-blue-500 hover:bg-gray-100 text-sm px-3 py-1 rounded-md">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Siswa
                    </a>
                </div>
                <div class="p-4">
                    <?php if($kelas->siswa->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS/NISN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $kelas->siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($siswa->name); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($siswa->nis ?? '-'); ?>/<?php echo e($siswa->nisn ?? '-'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                        <a href="<?php echo e(route('admin.siswa.show', $siswa->id)); ?>" class="text-blue-500 hover:text-blue-700 mr-3">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.siswa.edit', $siswa->id)); ?>" class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada siswa</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai tambahkan data siswa ke kelas ini.</p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('admin.siswa.create', ['kelas_id' => $kelas->id])); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Tambah Siswa Baru
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Jadwal Pelajaran -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mt-6">
                <div class="p-4 border-b border-gray-100 bg-blue-500 text-white flex justify-between items-center">
                    <h3 class="font-semibold">Jadwal Pelajaran</h3>
                    <a href="<?php echo e(route('admin.jadwal.create', ['kelas_id' => $kelas->id])); ?>" class="bg-white text-blue-500 hover:bg-gray-100 text-sm px-3 py-1 rounded-md">
                        <i class="fas fa-plus-circle mr-1"></i> Tambah Jadwal
                    </a>
                </div>
                <div class="p-4">
                    <?php if($kelas->jadwal->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $kelas->jadwal->sortBy('hari')->sortBy('jam_mulai'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">                                        <?php echo e($jadwal->hari); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e(substr($jadwal->jam_mulai, 0, 5)); ?> - <?php echo e(substr($jadwal->jam_selesai, 0, 5)); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($jadwal->mapel->nama ?? 'Tidak ada'); ?>

                                    </td>                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo e($jadwal->guru->nama ?? 'Belum ditentukan'); ?>

                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada jadwal</h3>
                        <p class="mt-1 text-sm text-gray-500">Tambahkan jadwal pelajaran untuk kelas ini.</p>
                        <div class="mt-6">
                            <a href="<?php echo e(route('admin.jadwal.create', ['kelas_id' => $kelas->id])); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-plus-circle mr-2"></i>
                                Tambah Jadwal Baru
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\kelas\show.blade.php ENDPATH**/ ?>