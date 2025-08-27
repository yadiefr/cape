<?php $__env->startSection('title', 'Kas Masuk Harian - Wali Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kas Masuk Harian</h1>
            </div>
        </div>
    </div>

    <!-- Info Kelas dan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Info Kelas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Kelas</h3>
            <p class="text-gray-700">
                <span class="font-medium">Kelas:</span> <?php echo e($kelas->nama_kelas); ?>

            </p>
            <p class="text-gray-700">
                <span class="font-medium">Jurusan:</span> <?php echo e($kelas->jurusan->nama_jurusan); ?>

            </p>
        </div>

        <!-- Total Kas Masuk Bulan -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
                <div>
                    <p class="text-green-100">Total Kas Masuk</p>
                    <p class="text-2xl font-bold">Rp <?php echo e(number_format($totalKasMasukBulan, 0, ',', '.')); ?></p>
                    <p class="text-green-100 text-sm"><?php echo e($tanggalAwal->locale('id')->monthName); ?> <?php echo e($tahun); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 text-white">
            <div class="flex items-center">
                <div class="p-3 bg-white bg-opacity-20 rounded-full mr-4">
                    <i class="fas fa-list text-2xl"></i>
                </div>
                <div>
                    <p class="text-blue-100">Total Transaksi</p>
                    <p class="text-2xl font-bold"><?php echo e($totalTransaksiBulan); ?></p>
                    <p class="text-blue-100 text-sm"><?php echo e($tanggalAwal->locale('id')->monthName); ?> <?php echo e($tahun); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if(session('success')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('info')): ?>
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-info-circle mr-2"></i><?php echo e(session('info')); ?>

    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>

    <!-- Navigasi Bulan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="flex items-center justify-between">
            <a href="<?php echo e(route('guru.wali-kelas.bendahara.kas-masuk', ['bulan' => $bulanSebelumnya->month, 'tahun' => $bulanSebelumnya->year])); ?>" 
               class="flex items-center text-gray-600 hover:text-blue-600 transition duration-200">
                <i class="fas fa-chevron-left mr-2"></i>
                <?php echo e($bulanSebelumnya->locale('id')->monthName); ?> <?php echo e($bulanSebelumnya->year); ?>

            </a>
            
            <h2 class="text-xl font-bold text-gray-800">
                <?php echo e($tanggalAwal->locale('id')->monthName); ?> <?php echo e($tahun); ?>

            </h2>
            
            <a href="<?php echo e(route('guru.wali-kelas.bendahara.kas-masuk', ['bulan' => $bulanSelanjutnya->month, 'tahun' => $bulanSelanjutnya->year])); ?>" 
               class="flex items-center text-gray-600 hover:text-blue-600 transition duration-200">
                <?php echo e($bulanSelanjutnya->locale('id')->monthName); ?> <?php echo e($bulanSelanjutnya->year); ?>

                <i class="fas fa-chevron-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Tabel Kas Masuk Harian -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                Kas Masuk Harian - <?php echo e($tanggalAwal->locale('id')->monthName); ?> <?php echo e($tahun); ?>

            </h3>
        </div>
        
        <!-- Desktop Table (Hidden on Mobile) -->
        <div class="desktop-table hidden lg:block overflow-x-auto" style="display: block !important;">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hari</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaksi</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Masuk</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $hariDalamBulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 <?php echo e($hari['total'] > 0 ? 'bg-green-50' : ''); ?>">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo e($hari['tanggal_formatted']); ?>

                            </div>
                            <div class="text-xs text-gray-500">
                                <?php echo e(\Carbon\Carbon::parse($hari['tanggal'])->format('d/m/Y')); ?>

                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-700"><?php echo e($hari['hari_nama']); ?></span>
                        </td>
                        <td class="px-4 py-4">
                            <?php if($hari['jumlah_transaksi'] > 0): ?>
                                <div class="bg-gray-100 rounded p-2 text-xs">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <span class="font-medium text-green-700"><?php echo e($hari['jumlah_transaksi']); ?> transaksi</span>
                                            <div class="text-gray-600 mt-1">Total kas masuk hari ini</div>
                                        </div>
                                        <span class="text-green-700 font-medium ml-2">
                                            Rp <?php echo e(number_format($hari['total'], 0, ',', '.')); ?>

                                        </span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm italic">Tidak ada transaksi</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right">
                            <?php if($hari['total'] > 0): ?>
                                <span class="text-lg font-bold text-green-600">
                                    Rp <?php echo e(number_format($hari['total'], 0, ',', '.')); ?>

                                </span>
                                <div class="text-xs text-gray-500">
                                    <?php echo e($hari['jumlah_transaksi']); ?> transaksi
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (Hidden on Desktop) -->
        <div class="mobile-cards lg:hidden p-4 space-y-4">
            <?php $__currentLoopData = $hariDalamBulan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hari): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border border-gray-200 rounded-lg p-4 <?php echo e($hari['total'] > 0 ? 'bg-green-50 border-green-200' : 'bg-white'); ?>">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900"><?php echo e($hari['tanggal_formatted']); ?></p>
                        <p class="text-xs text-gray-500"><?php echo e(\Carbon\Carbon::parse($hari['tanggal'])->format('d/m/Y')); ?> - <?php echo e($hari['hari_nama']); ?></p>
                    </div>
                    <?php if($hari['total'] > 0): ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            <?php echo e($hari['jumlah_transaksi']); ?> transaksi
                        </span>
                    <?php else: ?>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">
                            Belum ada
                        </span>
                    <?php endif; ?>
                </div>

                <?php if($hari['jumlah_transaksi'] > 0): ?>
                    <div class="mb-3 p-3 bg-white rounded border">
                        <p class="text-sm text-gray-700 mb-1">Total kas masuk hari ini</p>
                        <p class="text-lg font-bold text-green-600">Rp <?php echo e(number_format($hari['total'], 0, ',', '.')); ?></p>
                    </div>
                <?php else: ?>
                    <div class="mb-3 p-3 bg-gray-50 rounded border">
                        <p class="text-sm text-gray-500 italic">Tidak ada transaksi</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>

<style>
/* Mobile Responsive Styles for Kas Masuk */
@media (max-width: 1023px) {
    /* COMPLETELY REMOVE desktop tables from layout on mobile/tablet */
    .desktop-table {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        position: absolute !important;
        left: -9999px !important;
    }

    .hidden.lg\\:block {
        display: none !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* FORCE SHOW mobile cards on mobile/tablet */
    .mobile-cards {
        display: block !important;
        visibility: visible !important;
    }

    .lg\\:hidden {
        display: block !important;
    }
}

/* Desktop styles */
@media (min-width: 1024px) {
    /* FORCE SHOW desktop tables */
    .desktop-table {
        display: block !important;
        visibility: visible !important;
    }

    .hidden.lg\\:block {
        display: block !important;
    }

    /* FORCE HIDE mobile cards */
    .mobile-cards {
        display: none !important;
        visibility: hidden !important;
    }

    .lg\\:hidden {
        display: none !important;
    }
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .space-y-4 > * + * {
        margin-top: 1rem;
    }

    /* Ensure mobile cards are visible and properly styled */
    .mobile-cards > div {
        display: block !important;
        margin-bottom: 1rem;
    }

    /* Additional mobile-specific styling */
    .mobile-cards .bg-white {
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    /* Remove any empty space from hidden desktop table */
    .mobile-cards {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }

    /* Ensure no spacing issues between desktop table and mobile cards */
    .desktop-table + .mobile-cards {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\kas-masuk.blade.php ENDPATH**/ ?>