

<?php $__env->startSection('title', 'Rekap Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-chart-bar mr-3 text-purple-600"></i>
                Rekap Absensi
            </h1>
            <p class="text-gray-600 mt-1">Laporan rekapitulasi kehadiran siswa per kelas dan periode</p>
        </div>
        <a href="<?php echo e(route('admin.absensi.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Filter Rekap -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
        <form action="<?php echo e(route('admin.absensi.rekap')); ?>" method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                <select name="kelas_id" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Pilih Kelas</option>
                    <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>>
                        <?php echo e($k->nama_kelas); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m); ?>" <?php echo e(request('bulan', date('n')) == $m ? 'selected' : ''); ?>>
                        <?php echo e(date('F', mktime(0, 0, 0, $m, 1))); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" required>
                    <?php $__currentLoopData = range(date('Y'), 2020); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($y); ?>" <?php echo e(request('tahun', date('Y')) == $y ? 'selected' : ''); ?>>
                        <?php echo e($y); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i> Tampilkan
                </button>
                <?php if(isset($rekap) && count($rekap) > 0): ?>            <a href="<?php echo e(route('admin.absensi.rekap.export', request()->all())); ?>" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-file-excel mr-2"></i> Export
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <?php if(isset($rekap) && count($rekap) > 0): ?>
    <!-- Tabel Rekap -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-800">
                Rekap Absensi 
                <?php if($kelas_info): ?>
                    Kelas <?php echo e($kelas_info->nama_kelas); ?> - 
                <?php endif; ?>
                <?php echo e(date('F Y', mktime(0, 0, 0, request('bulan', date('n')), 1, request('tahun', date('Y'))))); ?>

            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th colspan="4" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Kehadiran</th>
                        <th rowspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase Hadir</th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">H</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">I</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">S</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">A</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $rekap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo e($data['nama']); ?></div>
                            <div class="text-xs text-gray-500">NIS: <?php echo e($data['nis']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <?php echo e($data['hadir']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <?php echo e($data['izin']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                <?php echo e($data['sakit']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <?php echo e($data['alpha']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                <?php echo e($data['persentase'] >= 90 ? 'bg-green-100 text-green-800' :
                                   ($data['persentase'] >= 80 ? 'bg-yellow-100 text-yellow-800' : 
                                   'bg-red-100 text-red-800')); ?>">
                                <?php echo e(number_format($data['persentase'], 1)); ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php elseif(request()->has('kelas_id')): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-circle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Tidak ada data absensi untuk periode yang dipilih.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\absensi\rekap.blade.php ENDPATH**/ ?>