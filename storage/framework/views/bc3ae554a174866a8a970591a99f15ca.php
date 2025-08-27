

<?php $__env->startSection('title', 'Daftar Kas Keluar - Bendahara'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex-1">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Daftar Kas Keluar</h1>
                    <nav class="text-xs sm:text-sm text-gray-600">
                        <a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
                        <span class="mx-1 sm:mx-2">></span>
                        <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>" class="hover:text-blue-600">Bendahara</a>
                        <span class="mx-1 sm:mx-2">></span>
                        <span class="text-blue-600">Kas Keluar</span>
                    </nav>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <a href="<?php echo e(route('siswa.bendahara.input-kas-keluar')); ?>"
                       class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg transition duration-200 text-sm sm:text-base text-center">
                        <i class="fas fa-plus mr-2"></i>Input Kas Keluar
                    </a>
                    <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg transition duration-200 text-sm sm:text-base text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Kas -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
            <!-- Saldo Kas -->
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Saldo Kas</p>
                        <p class="text-2xl font-bold <?php echo e($saldoKas >= 0 ? 'text-green-600' : 'text-red-600'); ?>">
                            Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <div class="p-3 rounded-full <?php echo e($saldoKas >= 0 ? 'bg-green-100' : 'bg-red-100'); ?>">
                        <i class="fas fa-wallet <?php echo e($saldoKas >= 0 ? 'text-green-600' : 'text-red-600'); ?> text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Kas Keluar Bulan Ini -->
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kas Keluar Bulan Ini</p>
                        <p class="text-2xl font-bold text-red-600">
                            Rp <?php echo e(number_format($totalKasKeluarBulan, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Jumlah Transaksi -->
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Transaksi Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($jumlahTransaksiBulan); ?></p>
                        <p class="text-sm text-gray-500">transaksi</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter dan Search -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <form method="GET" action="<?php echo e(route('siswa.bendahara.kas-keluar')); ?>" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-60">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                                <?php echo e(\Carbon\Carbon::createFromDate(null, $i, 1)->format('F')); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex-1 min-w-32">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <?php for($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                            <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabel Kas Keluar -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    Data Kas Keluar - <?php echo e(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y')); ?>

                </h3>
            </div>

            <?php if($kasKeluarBulanIni->count() > 0): ?>
                <!-- Desktop Table -->
                <!-- Desktop Table -->
                <div class="desktop-table hidden lg:block overflow-x-auto" style="display: none !important;">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Keterangan
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nominal
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Input Oleh
                                </th>
                                <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $kasKeluarBulanIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e(\Carbon\Carbon::parse($kas->tanggal)->format('d M Y')); ?>

                                    </td>
                                    <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">
                                        <?php echo e($kas->keterangan); ?>

                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        Rp <?php echo e(number_format($kas->nominal, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e($kas->siswa->nama_lengkap ?? 'System'); ?>

                                    </td>
                                    <td class="px-4 lg:px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Kas Keluar
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="mobile-cards lg:hidden space-y-4" style="display: block !important;">
                    <?php $__currentLoopData = $kasKeluarBulanIni; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900"><?php echo e($kas->keterangan); ?></h4>
                                    <p class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($kas->tanggal)->format('d M Y')); ?></p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <span class="font-medium">Input oleh:</span> <?php echo e($kas->siswa->nama_lengkap ?? 'System'); ?>

                                    </p>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 ml-2">
                                    Kas Keluar
                                </span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">Nominal</span>
                                <span class="text-lg font-bold text-red-600">
                                    Rp <?php echo e(number_format($kas->nominal, 0, ',', '.')); ?>

                                </span>
                            </div>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600"><?php echo e($kas->siswa->nama_lengkap ?? 'System'); ?></span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Kas Keluar
                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="p-8 text-center">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-inbox text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data kas keluar</h3>
                    <p class="text-gray-600 mb-4">
                        Belum ada transaksi kas keluar untuk <?php echo e(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y')); ?>

                    </p>
                    <a href="<?php echo e(route('siswa.bendahara.input-kas-keluar')); ?>" 
                       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Input Kas Keluar Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Info -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                <div class="text-sm text-blue-700">
                    <strong>Informasi:</strong> Data kas keluar menampilkan semua pengeluaran kas kelas. 
                    Pastikan untuk mencatat setiap pengeluaran dengan detail yang jelas.
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Mobile Responsive Styles for Siswa Bendahara Pages */
@media (max-width: 1023px) {
    /* Force hide desktop tables */
    .desktop-table {
        display: none !important;
        visibility: hidden !important;
    }

    /* Force show mobile cards */
    .mobile-cards {
        display: block !important;
        visibility: visible !important;
    }
}

/* Desktop styles */
@media (min-width: 1024px) {
    .desktop-table {
        display: block !important;
        visibility: visible !important;
    }

    .mobile-cards {
        display: none !important;
        visibility: hidden !important;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Auto submit form when filter changes
    document.querySelectorAll('select[name="bulan"], select[name="tahun"]').forEach(function(select) {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\bendahara\kas-keluar-list.blade.php ENDPATH**/ ?>