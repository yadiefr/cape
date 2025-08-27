

<?php $__env->startSection('title', 'Rekap Keuangan Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="rekapKeuangan">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Rekap Keuangan Kelas</h1>
                <p class="text-gray-600"><?php echo e($kelas->nama_kelas); ?> - <?php echo e(DateTime::createFromFormat('!m', $bulan)->format('F')); ?> <?php echo e($tahun); ?></p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('siswa.bendahara.dashboard')); ?>" 
                   class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select name="bulan" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php for($i = 1; $i <= 12; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                        <?php echo e(DateTime::createFromFormat('!m', $i)->format('F')); ?>

                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    <?php for($i = date('Y') - 2; $i <= date('Y') + 1; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($tahun == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>

    <!-- Statistik Ringkasan -->
    <?php
        $totalTagihanKeseluruhan = collect($detailKeuangan)->sum('total_tagihan');
        $totalDibayarKeseluruhan = collect($detailKeuangan)->sum('total_dibayar');
        $totalTunggakanKeseluruhan = collect($detailKeuangan)->sum('tunggakan');
        $totalPembayaranBulan = collect($detailKeuangan)->sum('pembayaran_bulan');
        $siswaLunas = collect($detailKeuangan)->where('status', 'Lunas')->count();
        $siswaBelumLunas = collect($detailKeuangan)->where('status', 'Belum Lunas')->count();
    ?>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6">
        <!-- Total Tagihan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-blue-100 text-blue-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Total Tagihan</p>
                    <p class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800">Rp <?php echo e(number_format($totalTagihanKeseluruhan, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Terbayar -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-green-100 text-green-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Total Terbayar</p>
                    <p class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800">Rp <?php echo e(number_format($totalDibayarKeseluruhan, 0, ',', '.')); ?></p>
                    <p class="text-xs text-gray-400"><?php echo e($totalTagihanKeseluruhan > 0 ? number_format(($totalDibayarKeseluruhan / $totalTagihanKeseluruhan) * 100, 1) : 0); ?>%</p>
                </div>
            </div>
        </div>

        <!-- Pembayaran Bulan Ini -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-purple-100 text-purple-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Bulan Ini</p>
                    <p class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800">Rp <?php echo e(number_format($totalPembayaranBulan, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Status Siswa -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3 sm:p-4 lg:p-6 hover:shadow-md transition-shadow">
            <div class="flex flex-col lg:flex-row lg:items-center">
                <div class="p-2 sm:p-3 rounded-full bg-orange-100 text-orange-600 mb-2 lg:mb-0 self-center lg:self-auto">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="lg:ml-4 text-center lg:text-left">
                    <p class="text-xs sm:text-sm text-gray-500">Siswa Lunas</p>
                    <p class="text-sm sm:text-lg lg:text-xl font-bold text-gray-800"><?php echo e($siswaLunas); ?></p>
                    <p class="text-xs text-gray-400">dari <?php echo e($siswaLunas + $siswaBelumLunas); ?> siswa</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Detail Keuangan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Detail Keuangan per Siswa</h3>
            <div class="flex space-x-2">
                <button @click="exportData" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Export Excel
                </button>
                <button @click="printReport" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Print
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tagihan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Dibayar</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tunggakan</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bayar Bulan Ini</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $detailKeuangan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo e($data['siswa']['nama']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($data['siswa']['nis']); ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                            Rp <?php echo e(number_format($data['total_tagihan'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-green-600">
                            Rp <?php echo e(number_format($data['total_dibayar'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium <?php echo e($data['tunggakan'] > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                            Rp <?php echo e(number_format($data['tunggakan'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-blue-600">
                            Rp <?php echo e(number_format($data['pembayaran_bulan'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo e($data['status'] == 'Lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                <?php echo e($data['status']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="<?php echo e(route('siswa.bendahara.detail-siswa', $data['siswa']['id'])); ?>" 
                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        <!-- Summary Footer -->
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                <div class="text-center">
                    <p class="font-medium text-gray-700">Total Tagihan Keseluruhan</p>
                    <p class="text-lg font-bold text-blue-600">Rp <?php echo e(number_format($totalTagihanKeseluruhan, 0, ',', '.')); ?></p>
                </div>
                <div class="text-center">
                    <p class="font-medium text-gray-700">Total Terbayar</p>
                    <p class="text-lg font-bold text-green-600">Rp <?php echo e(number_format($totalDibayarKeseluruhan, 0, ',', '.')); ?></p>
                </div>
                <div class="text-center">
                    <p class="font-medium text-gray-700">Total Tunggakan</p>
                    <p class="text-lg font-bold text-red-600">Rp <?php echo e(number_format($totalTunggakanKeseluruhan, 0, ',', '.')); ?></p>
                </div>
                <div class="text-center">
                    <p class="font-medium text-gray-700">Pembayaran Bulan Ini</p>
                    <p class="text-lg font-bold text-purple-600">Rp <?php echo e(number_format($totalPembayaranBulan, 0, ',', '.')); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rekapKeuangan', () => ({
        init() {
            console.log('Rekap Keuangan loaded');
        },
        
        exportData() {
            // Implementasi export Excel
            alert('Fitur export akan segera tersedia');
        },
        
        printReport() {
            window.print();
        }
    }));
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\bendahara\rekap-keuangan.blade.php ENDPATH**/ ?>