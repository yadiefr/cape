<?php $__env->startSection('title', 'Dashboard Bendahara Kas Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full h-full">
    <?php if($bendahara): ?>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Total Kas Masuk -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Kas Masuk</p>
                        <p class="text-2xl font-bold text-gray-900">Rp <?php echo e(number_format($totalKasMasuk, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Total Kas Keluar -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Kas Keluar</p>
                        <p class="text-2xl font-bold text-gray-900">Rp <?php echo e(number_format($totalKasKeluar, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Saldo Kas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-wallet text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Saldo Kas</p>
                        <p class="text-2xl font-bold text-gray-900">Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">

            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="<?php echo e(route('guru.wali-kelas.bendahara.kas-masuk')); ?>" class="bg-green-50 hover:bg-green-100 border border-green-200 p-4 rounded-lg transition-colors flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-arrow-down text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">Kas Masuk</h3>
                            <p class="text-sm text-green-600">Lihat data kas masuk harian</p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('guru.wali-kelas.bendahara.kas-keluar')); ?>" class="bg-red-50 hover:bg-red-100 border border-red-200 p-4 rounded-lg transition-colors flex items-center">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-arrow-up text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-red-800">Kas Keluar</h3>
                            <p class="text-sm text-red-600">Lihat data kas keluar</p>
                        </div>
                    </a>

                    <a href="<?php echo e(route('guru.wali-kelas.bendahara.laporan-kas')); ?>" class="bg-purple-50 hover:bg-purple-100 border border-purple-200 p-4 rounded-lg transition-colors flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-file-alt text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-purple-800">Laporan Kas</h3>
                            <p class="text-sm text-purple-600">Laporan kas lengkap</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h2>
            </div>
            <div class="p-6">
                <?php if($transaksiTerbaru->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $transaksiTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 <?php echo e($transaksi->tipe == 'masuk' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'); ?>">
                                    <i class="fas <?php echo e($transaksi->tipe == 'masuk' ? 'fa-plus' : 'fa-minus'); ?>"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900"><?php echo e($transaksi->keterangan); ?></p>
                                    <p class="text-sm text-gray-500">
                                        <?php echo e($transaksi->tanggal->format('d/m/Y')); ?> • 
                                        <?php echo e($transaksi->createdBy ? $transaksi->createdBy->nama_lengkap : 'System'); ?>

                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold <?php echo e($transaksi->tipe == 'masuk' ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($transaksi->tipe == 'masuk' ? '+' : '-'); ?>Rp <?php echo e(number_format($transaksi->nominal, 0, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-receipt text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">Belum ada transaksi kas kelas</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
    <?php else: ?>
        <!-- Belum ada bendahara -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-8 text-center">
                <i class="fas fa-user-plus text-gray-400 text-5xl mb-6"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Belum Ada Bendahara</h3>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">
                    Kelas <?php echo e($kelas->nama_kelas); ?> belum memiliki bendahara yang ditunjuk. 
                    Silakan tunjuk salah satu siswa sebagai bendahara kelas.
                </p>
                <a href="<?php echo e(route('guru.wali-kelas.siswa.index')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-user-cog mr-2"></i>
                    Tunjuk Bendahara Kelas
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\bendahara.blade.php ENDPATH**/ ?>