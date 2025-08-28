

<?php $__env->startSection('title', 'Manajemen PKL'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-briefcase mr-3 text-blue-600"></i>
                Manajemen Praktek Kerja Lapangan
            </h1>
            <p class="text-gray-600 mt-1">Kelola data PKL siswa dan monitoring kegiatan</p>
        </div>
        <a href="<?php echo e(route('admin.pkl.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-plus mr-2"></i>
            Tambah Data PKL
        </a>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Total PKL</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($praktikKerjaLapangan->total()); ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-briefcase text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Berlangsung</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($praktikKerjaLapangan->where('status', 'berlangsung')->count()); ?></p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-play-circle text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($praktikKerjaLapangan->where('status', 'selesai')->count()); ?></p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wider">Pengajuan</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($praktikKerjaLapangan->where('status', 'pengajuan')->count()); ?></p>
                </div>
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-filter mr-2 text-blue-600"></i>Filter & Pencarian
            </h3>
        </div>
        <div class="p-6">
            <form action="<?php echo e(route('admin.pkl.index')); ?>" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status PKL</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pengajuan" <?php echo e(request('status') == 'pengajuan' ? 'selected' : ''); ?>>Pengajuan</option>
                        <option value="berlangsung" <?php echo e(request('status') == 'berlangsung' ? 'selected' : ''); ?>>Berlangsung</option>
                        <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
                        <option value="gagal" <?php echo e(request('status') == 'gagal' ? 'selected' : ''); ?>>Gagal</option>
                    </select>
                </div>
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kelas</option>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="jurusan_id" class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="jurusan_id" id="jurusan_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Jurusan</option>
                        <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($j->id); ?>" <?php echo e(request('jurusan_id') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jurusan); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="nama_siswa" class="block text-sm font-medium text-gray-700 mb-2">Nama Siswa</label>
                    <input type="text" name="nama_siswa" id="nama_siswa" value="<?php echo e(request('nama_siswa')); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari berdasarkan nama siswa">
                </div>
                <div class="flex flex-col justify-end">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-search mr-1"></i>Cari
                        </button>
                        <a href="<?php echo e(route('admin.pkl.index')); ?>" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center justify-center transition-colors">
                            <i class="fas fa-undo mr-1"></i>Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-table mr-2 text-blue-600"></i>Daftar PKL Siswa
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $praktikKerjaLapangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pkl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(($praktikKerjaLapangan->currentPage() - 1) * $praktikKerjaLapangan->perPage() + $key + 1); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e(optional($pkl->siswa)->nama_lengkap ?? 'Siswa Tidak Ditemukan'); ?></div>
                                    <div class="text-sm text-gray-500">NIS: <?php echo e(optional($pkl->siswa)->nis ?? '-'); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?php echo e(optional(optional($pkl->siswa)->kelas)->nama_kelas ?? 'Kelas Tidak Ditemukan'); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e(optional(optional($pkl->siswa)->jurusan)->nama_jurusan ?? 'Jurusan Tidak Ditemukan'); ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($pkl->nama_perusahaan); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($pkl->bidang_usaha); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($pkl->tanggal_mulai->format('d/m/Y')); ?> - <?php echo e($pkl->tanggal_selesai->format('d/m/Y')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($pkl->status == 'pengajuan'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pengajuan
                                        </span>
                                    <?php elseif($pkl->status == 'berlangsung'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Berlangsung
                                        </span>
                                    <?php elseif($pkl->status == 'selesai'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    <?php elseif($pkl->status == 'gagal'): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Gagal
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('admin.pkl.show', $pkl->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.pkl.edit', $pkl->id)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('admin.pkl.destroy', $pkl->id)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data PKL ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-briefcase text-4xl text-gray-400 mb-4"></i>
                                        <p class="text-gray-500 text-lg font-medium">Tidak ada data PKL</p>
                                        <p class="text-gray-400 text-sm mt-1">Belum ada siswa yang terdaftar PKL</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($praktikKerjaLapangan->hasPages()): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($praktikKerjaLapangan->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\pkl\index.blade.php ENDPATH**/ ?>