

<?php $__env->startSection('title', 'Detail Absensi'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Absensi</h1>
        </div>

        <!-- Informasi Sesi Absensi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h2 class="text-lg font-semibold mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Informasi Sesi Absensi
                </h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 gap-3">
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium text-gray-600">
                                <i class="fas fa-users mr-1"></i>Kelas:
                            </span>
                            <span class="font-semibold"><?php echo e($referenceAbsensi->kelas->nama_kelas ?? 'Tidak diketahui'); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium text-gray-600">
                                <i class="fas fa-book mr-1"></i>Mata Pelajaran:
                            </span>
                            <span class="font-semibold"><?php echo e($referenceAbsensi->mapel->nama ?? 'Tidak diketahui'); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium text-gray-600">
                                <i class="fas fa-calendar mr-1"></i>Tanggal:
                            </span>
                            <span class="font-semibold"><?php echo e(\Carbon\Carbon::parse($referenceAbsensi->tanggal)->format('d F Y')); ?></span>
                        </div>
                        <div class="flex justify-between border-b pb-2">
                            <span class="font-medium text-gray-600">
                                <i class="fas fa-chalkboard-teacher mr-1"></i>Guru:
                            </span>
                            <span class="font-semibold"><?php echo e($referenceAbsensi->guru->nama ?? 'Tidak diketahui'); ?></span>
                        </div>
                        <?php if($referenceAbsensi->guru && $referenceAbsensi->guru->nip): ?>
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">
                                <i class="fas fa-id-card mr-1"></i>NIP Guru:
                            </span>
                            <span class="text-gray-700"><?php echo e($referenceAbsensi->guru->nip); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Statistik Kehadiran -->
            <div>
                <h2 class="text-lg font-semibold mb-3 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                    Statistik Kehadiran
                </h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600"><?php echo e($stats['hadir']); ?></div>
                            <div class="text-sm text-gray-600">Hadir</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600"><?php echo e($stats['izin']); ?></div>
                            <div class="text-sm text-gray-600">Izin</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600"><?php echo e($stats['sakit']); ?></div>
                            <div class="text-sm text-gray-600">Sakit</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600"><?php echo e($stats['alpha']); ?></div>
                            <div class="text-sm text-gray-600">Alpha</div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 text-center">
                        <div class="text-lg font-semibold text-gray-800">Total: <?php echo e($stats['total']); ?> Siswa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="mb-8">
            <h2 class="text-lg font-semibold mb-4 flex items-center">
                <i class="fas fa-list mr-2 text-purple-600"></i>
                Daftar Kehadiran Siswa
            </h2>

            <?php if(isset($absensi) && $absensi->count() > 0): ?>
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-sm text-blue-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Menampilkan <?php echo e($absensi->count()); ?> siswa untuk sesi ini
                    </p>
                </div>
            <?php else: ?>
                <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Tidak ada data absensi ditemukan
                    </p>
                </div>
            <?php endif; ?>

            <!-- Desktop Table View -->
            <div id="desktop-view" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white rounded-lg shadow">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?php echo e($data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Tidak diketahui'); ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($data->siswa->nis ?? '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if($data->status == 'hadir'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hadir</span>
                                <?php elseif($data->status == 'izin'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Izin</span>
                                <?php elseif($data->status == 'sakit'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                <?php elseif($data->status == 'alpha'): ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Alpha</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500"><?php echo e($data->keterangan ?: '-'); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div id="mobile-view" class="hidden w-full">
                <div class="flex flex-col space-y-3 w-full">
                    <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="w-full bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <!-- Header dengan nomor, nama, dan status -->
                        <div class="flex items-start justify-between w-full">
                            <div class="flex items-center flex-1 min-w-0">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3 flex-shrink-0">
                                    <?php echo e($index + 1); ?>

                                </span>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-sm font-semibold text-gray-900 break-words">
                                        <?php echo e($data->siswa->nama_lengkap ?? $data->siswa->nama ?? 'Tidak diketahui'); ?>

                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-id-badge mr-1"></i>
                                        NIS: <?php echo e($data->siswa->nis ?? '-'); ?>

                                    </p>
                                </div>
                            </div>

                            <!-- Status badge di kanan -->
                            <div class="flex-shrink-0 ml-3">
                                <?php if($data->status == 'hadir'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Hadir
                                    </span>
                                <?php elseif($data->status == 'izin'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        <i class="fas fa-info-circle mr-1"></i>Izin
                                    </span>
                                <?php elseif($data->status == 'sakit'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-thermometer-half mr-1"></i>Sakit
                                    </span>
                                <?php elseif($data->status == 'alpha'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Alpha
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Keterangan jika ada -->
                        <?php if($data->keterangan): ?>
                        <div class="mt-3 w-full">
                            <div class="flex items-start w-full">
                                <span class="text-xs font-medium text-gray-500 w-20 flex-shrink-0">Keterangan:</span>
                                <span class="text-xs text-gray-700 flex-1 break-words"><?php echo e($data->keterangan); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <a href="<?php echo e(route('guru.absensi.edit', $absensi->first()->id)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors text-center">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="<?php echo e(route('guru.absensi.destroy', $absensi->first()->id)); ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data absensi ini?')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors text-center">
                <i class="fas fa-trash mr-2"></i>Hapus
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Responsive control for desktop/mobile views */
@media (min-width: 768px) {
    #desktop-view {
        display: block !important;
    }
    #mobile-view {
        display: none !important;
    }
}

@media (max-width: 767px) {
    #desktop-view {
        display: none !important;
    }
    #mobile-view {
        display: block !important;
    }
}

/* Ensure table is visible */
#desktop-view table {
    display: table !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Responsive view control
    function handleResponsiveView() {
        const desktopView = document.getElementById('desktop-view');
        const mobileView = document.getElementById('mobile-view');

        if (window.innerWidth >= 768) {
            if (desktopView) desktopView.style.display = 'block';
            if (mobileView) mobileView.style.display = 'none';
        } else {
            if (desktopView) desktopView.style.display = 'none';
            if (mobileView) mobileView.style.display = 'block';
        }
    }

    // Initial call and resize listener
    handleResponsiveView();
    window.addEventListener('resize', handleResponsiveView);
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\show.blade.php ENDPATH**/ ?>