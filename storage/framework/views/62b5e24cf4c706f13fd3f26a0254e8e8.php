

<?php $__env->startSection('title', 'Dashboard Bendahara Kas Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full h-full" x-data="dashboardBendahara">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Dashboard Bendahara Kas Kelas</h1>
                <p class="text-gray-600"><?php echo e($kelas->nama_kelas); ?> - <?php echo e($kelas->jurusan->nama_jurusan); ?></p>
            </div>
        </div>
    </div>

    <!-- Statistik Kas Kelas - Mobile First 2x2 Layout -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Kas Masuk -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Kas Masuk</p>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($totalKasMasuk, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Kas Keluar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-red-100 text-red-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Kas Keluar</p>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-gray-800">Rp <?php echo e(number_format($totalKasKeluar, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Saldo Kas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full <?php echo e($saldoKas >= 0 ? 'bg-blue-100 text-blue-600' : 'bg-red-100 text-red-600'); ?> mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Saldo Kas</p>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold <?php echo e($saldoKas >= 0 ? 'text-blue-600' : 'text-red-600'); ?>">
                        Rp <?php echo e(number_format($saldoKas, 0, ',', '.')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Total Transaksi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Transaksi</p>
                    <p class="text-sm sm:text-lg lg:text-2xl font-bold text-gray-800"><?php echo e($jumlahTransaksi); ?></p>
                    <p class="text-xs text-gray-400">Bulan ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions - Mobile First 2x2 Layout -->
    <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <button @click="tambahKasMasuk" 
                class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 p-3 sm:p-4 rounded-lg text-center transition-all duration-200 shadow-sm hover:shadow-md min-h-[100px] sm:min-h-[120px] flex flex-col justify-center">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span class="text-xs sm:text-sm font-medium">Kas Masuk</span>
        </button>
        
        <button @click="tambahKasKeluar" 
                class="bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 p-3 sm:p-4 rounded-lg text-center transition-all duration-200 shadow-sm hover:shadow-md min-h-[100px] sm:min-h-[120px] flex flex-col justify-center">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
            <span class="text-xs sm:text-sm font-medium">Kas Keluar</span>
        </button>
        
        <button @click="lihatLaporan" 
                class="bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 p-3 sm:p-4 rounded-lg text-center transition-all duration-200 shadow-sm hover:shadow-md min-h-[100px] sm:min-h-[120px] flex flex-col justify-center">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="text-xs sm:text-sm font-medium">Laporan Kas</span>
        </button>
        
        <button class="w-full bg-violet-50 hover:bg-violet-100 text-violet-700 border border-violet-200 p-3 sm:p-4 rounded-lg text-center transition-all duration-200 shadow-sm hover:shadow-md min-h-[100px] sm:min-h-[120px] flex flex-col justify-center">
                <a href="<?php echo e(route('siswa.bendahara.export-kas', ['format' => 'excel', 'periode' => 'bulan'])); ?>"</a>
                <svg class="w-6 h-6 sm:w-8 sm:h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            <span class="text-xs sm:text-sm font-medium">Export Data</span>
        </button>        
    </div>

    <!-- Transaksi Kas Bulan Ini -->
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Transaksi Kas Bulan Ini</h3>
            <div class="text-sm text-gray-600">
                Total: <span class="font-semibold"><?php echo e($transaksiKasBulanIni->count()); ?></span> transaksi
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nominal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $transaksiKasBulanIni->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php if(isset($transaksi['tanggal_terakhir'])): ?>
                                <?php echo e(Carbon\Carbon::parse($transaksi['tanggal_terakhir'])->format('d/m/Y')); ?>

                                <div class="text-xs text-gray-500">Terakhir bayar</div>
                            <?php else: ?>
                                <?php echo e(Carbon\Carbon::parse($transaksi['tanggal'])->format('d/m/Y')); ?>

                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <?php if(isset($transaksi['siswa'])): ?>
                                <div class="font-medium"><?php echo e($transaksi['siswa']['nama_lengkap']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo e($transaksi['siswa']['nis']); ?></div>
                            <?php else: ?>
                                <span class="text-gray-400 italic">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div><?php echo e($transaksi['keterangan']); ?></div>
                            <?php if(isset($transaksi['siswa']) && isset($transaksi['jumlah_transaksi']) && $transaksi['jumlah_transaksi'] > 1): ?>
                                <div class="text-xs text-gray-500 mt-1"><?php echo e($transaksi['jumlah_transaksi']); ?> kali pembayaran</div>
                            <?php elseif(!isset($transaksi['siswa'])): ?>
                                <?php if($transaksi['tipe'] == 'masuk'): ?>
                                    <div class="text-xs text-gray-500">Kas lainnya</div>
                                <?php else: ?>
                                    <div class="text-xs text-gray-500">Pengeluaran kas</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-semibold <?php echo e($transaksi['tipe'] == 'masuk' ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e($transaksi['tipe'] == 'masuk' ? '+' : '-'); ?> Rp <?php echo e(number_format($transaksi['total_nominal'] ?? $transaksi['nominal'], 0, ',', '.')); ?>

                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Belum ada transaksi kas bulan ini
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($transaksiKasBulanIni->count() > 10): ?>
        <div class="mt-4 text-center" x-data="{ showAll: false }">
            <button @click="showAll = !showAll" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                <span x-show="!showAll">Lihat Semua Transaksi (<?php echo e($transaksiKasBulanIni->count()); ?>)</span>
                <span x-show="showAll">Tampilkan Lebih Sedikit</span>
            </button>
            
            <div x-show="showAll" x-transition class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $transaksiKasBulanIni->skip(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaksi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if(isset($transaksi['tanggal_terakhir'])): ?>
                                    <?php echo e(Carbon\Carbon::parse($transaksi['tanggal_terakhir'])->format('d/m/Y')); ?>

                                    <div class="text-xs text-gray-500">Terakhir bayar</div>
                                <?php else: ?>
                                    <?php echo e(Carbon\Carbon::parse($transaksi['tanggal'])->format('d/m/Y')); ?>

                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?php if(isset($transaksi['siswa'])): ?>
                                    <div class="font-medium"><?php echo e($transaksi['siswa']['nama_lengkap']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo e($transaksi['siswa']['nis']); ?></div>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div><?php echo e($transaksi['keterangan']); ?></div>
                                <?php if(isset($transaksi['siswa']) && isset($transaksi['jumlah_transaksi']) && $transaksi['jumlah_transaksi'] > 1): ?>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo e($transaksi['jumlah_transaksi']); ?> kali pembayaran</div>
                                <?php elseif(!isset($transaksi['siswa'])): ?>
                                    <?php if($transaksi['tipe'] == 'masuk'): ?>
                                        <div class="text-xs text-gray-500">Kas lainnya</div>
                                    <?php else: ?>
                                        <div class="text-xs text-gray-500">Pengeluaran kas</div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-semibold <?php echo e($transaksi['tipe'] == 'masuk' ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($transaksi['tipe'] == 'masuk' ? '+' : '-'); ?> Rp <?php echo e(number_format($transaksi['total_nominal'] ?? $transaksi['nominal'], 0, ',', '.')); ?>

                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardBendahara', () => ({
        init() {
            console.log('Dashboard Bendahara Kas Kelas loaded');
        },
        
        tambahKasMasuk() {
            window.location.href = '<?php echo e(route("siswa.bendahara.kas-masuk")); ?>';
        },
        
        tambahKasKeluar() {
            window.location.href = '<?php echo e(route("siswa.bendahara.kas-keluar")); ?>';
        },
        
        lihatLaporan() {
            window.location.href = '<?php echo e(route('siswa.bendahara.laporan-kas')); ?>';
        }
    }));
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\bendahara\dashboard.blade.php ENDPATH**/ ?>