<?php $__env->startSection('title', 'Kas Keluar - Wali Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="h-full bg-gray-50 p-3 sm:p-4 lg:p-6">
    <div class="w-full h-full">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Kas Keluar</h1>
                <nav class="text-sm text-gray-600">
                    <a href="<?php echo e(route('guru.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
                    <span class="mx-2">></span>
                    <a href="<?php echo e(route('guru.wali-kelas.bendahara')); ?>" class="hover:text-blue-600">Bendahara</a>
                    <span class="mx-2">></span>
                    <span class="text-blue-600">Kas Keluar</span>
                </nav>
            </div>
            <a href="<?php echo e(route('guru.wali-kelas.bendahara')); ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1">
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select name="bulan" id="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                            <?php echo e(\Carbon\Carbon::create()->month($i)->locale('id')->monthName); ?>

                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex-1">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" id="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                        <option value="<?php echo e($i); ?>" <?php echo e($tahun == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Summary -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-red-600"><?php echo e($transaksiKasKeluar->count()); ?></div>
                <div class="text-sm text-gray-600">Total Transaksi</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-orange-600">Rp <?php echo e(number_format($totalKasKeluarBulan, 0, ',', '.')); ?></div>
                <div class="text-sm text-gray-600">Total Kas Keluar</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">
                    <?php if($transaksiKasKeluar->count() > 0): ?>
                        Rp <?php echo e(number_format($totalKasKeluarBulan / $transaksiKasKeluar->count(), 0, ',', '.')); ?>

                    <?php else: ?>
                        Rp 0
                    <?php endif; ?>
                </div>
                <div class="text-sm text-gray-600">Rata-rata per Transaksi</div>
            </div>
        </div>
    </div>

    <!-- Tabel Kas Keluar -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">
                Kas Keluar <?php echo e(\Carbon\Carbon::create()->month($bulan)->locale('id')->monthName); ?> <?php echo e($tahun); ?>

            </h2>
        </div>
        
        <?php if($transaksiKasKeluar->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diinput Oleh</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $transaksiKasKeluar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($transaksi->tanggal->format('d/m/Y')); ?>

                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo e($transaksi->tanggal->format('H:i')); ?>

                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($transaksi->keterangan); ?></div>
                                <?php if($transaksi->catatan): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo e($transaksi->catatan); ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-right">
                                <div class="text-sm font-medium text-red-600">
                                    Rp <?php echo e(number_format($transaksi->nominal, 0, ',', '.')); ?>

                                </div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php echo e($transaksi->createdBy ? $transaksi->createdBy->nama_lengkap : 'System'); ?>

                                </div>
                                <?php if($transaksi->createdBy): ?>
                                    <div class="text-xs text-gray-500">
                                        <?php echo e($transaksi->createdBy->nis); ?>

                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <?php if($transaksi->bukti_transaksi): ?>
                                    <a href="<?php echo e(Storage::url($transaksi->bukti_transaksi)); ?>" 
                                       target="_blank"
                                       class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200">
                                        <i class="fas fa-file-image mr-1"></i>Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-minus mr-1"></i>Tidak Ada
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-8 text-center">
                <i class="fas fa-receipt text-gray-400 text-4xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Transaksi Kas Keluar</h3>
                <p class="text-gray-600">
                    Belum ada transaksi kas keluar untuk bulan <?php echo e(\Carbon\Carbon::create()->month($bulan)->locale('id')->monthName); ?> <?php echo e($tahun); ?>.
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Info untuk Wali Kelas -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
        <h3 class="font-semibold text-blue-800 mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Informasi untuk Wali Kelas
        </h3>
        <div class="text-sm text-blue-700 space-y-1">
            <p>• Data kas keluar dikelola oleh bendahara kelas melalui akun siswa mereka</p>
            <p>• Setiap transaksi kas keluar harus disertai dengan bukti transaksi</p>
            <p>• Anda dapat melihat semua pengeluaran kas kelas yang telah diinput</p>
            <p>• Untuk mengedit atau menambah data, hubungi bendahara kelas</p>
            <p>• Data ini terintegrasi dengan sistem keuangan sekolah</p>
        </div>
    </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\kas-keluar.blade.php ENDPATH**/ ?>