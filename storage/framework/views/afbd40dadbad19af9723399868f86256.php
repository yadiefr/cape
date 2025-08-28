

<?php $__env->startSection('title', 'Dashboard Wali Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-3 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Dashboard Wali Kelas</h1>
        </div>
    </div>

    <?php if($kelas): ?>
    <!-- Info Kelas -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                <h5 class="text-lg font-semibold mb-0">
                    <i class="fas fa-users mr-2"></i> Informasi Kelas
                </h5>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <h4 class="text-xl sm:text-2xl font-bold text-blue-600 mb-1"><?php echo e($kelas->nama_kelas); ?></h4>
                        <p class="text-gray-600 mb-1"><?php echo e($kelas->jurusan->nama_jurusan); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-6 mb-6">
        <!-- Total Siswa -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-500">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Siswa</div>
                        <div class="text-lg sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($totalSiswa); ?></div>
                        <div class="text-xs text-gray-500">
                            <span class="block">Aktif: <?php echo e($siswaAktif); ?></span>
                            <span class="block">L: <?php echo e($siswaLaki); ?> | P: <?php echo e($siswaPerempuan); ?></span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-lg sm:text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Absensi Hari Ini -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-500">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-1">Absensi Hari Ini</div>
                        <div class="text-lg sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($hadir); ?>/<?php echo e($totalSiswa); ?></div>
                        <div class="text-xs text-gray-500">
                            <span class="block">Hadir: <?php echo e($hadir); ?></span>
                            <span class="block">Tidak Hadir: <?php echo e($tidakHadir); ?></span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-clipboard-check text-lg sm:text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Persentase Kehadiran -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-cyan-500">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-cyan-600 uppercase tracking-wide mb-1">Persentase Kehadiran</div>
                        <div class="flex items-center">
                            <div class="text-lg sm:text-2xl font-bold text-gray-900 mr-2">
                                <?php echo e(number_format($persentaseKehadiran, 1)); ?>%
                            </div>
                            <div class="flex-1">
                                <div class="w-full bg-gray-200 rounded-full h-1.5 sm:h-2">
                                    <div class="bg-cyan-500 h-1.5 sm:h-2 rounded-full transition-all duration-300" 
                                         style="width: <?php echo e(min($persentaseKehadiran, 100)); ?>%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            <span class="block"><?php echo e($totalKehadiranAktual); ?>/<?php echo e($totalKehadiranSeharusnya); ?></span>
                            <span class="block"><?php echo e($totalHariKerja); ?> hari kerja</span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-pie text-lg sm:text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tunggakan Keuangan -->
        <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-500">
            <div class="p-3 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-1">
                        <div class="text-xs font-semibold text-yellow-600 uppercase tracking-wide mb-1">Tunggakan Bulan Ini</div>
                        <div class="text-lg sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($belumLunas); ?></div>
                        <div class="text-xs text-gray-500">
                            Siswa dengan tunggakan
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-lg sm:text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Quick Actions & Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-6">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                <h6 class="text-lg font-semibold text-gray-900">Quick Actions</h6>
            </div>
            <div class="p-3 sm:p-6">
                <div class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                    <a href="<?php echo e(route('guru.wali-kelas.absensi')); ?>" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 text-blue-700 hover:text-blue-800 transition-colors">
                        <i class="fas fa-clipboard-check text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                        <span class="text-xs sm:text-sm font-medium text-center">Absen Harian</span>
                    </a>
                    <a href="<?php echo e(route('guru.wali-kelas.rekap-absensi')); ?>" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-cyan-50 hover:bg-cyan-100 rounded-lg border border-cyan-200 text-cyan-700 hover:text-cyan-800 transition-colors">
                        <i class="fas fa-chart-bar text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                        <span class="text-xs sm:text-sm font-medium text-center">Rekap Absensi</span>
                    </a>
                    <a href="<?php echo e(route('guru.wali-kelas.rekap-keuangan')); ?>" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 text-green-700 hover:text-green-800 transition-colors">
                        <i class="fas fa-money-bill-wave text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                        <span class="text-xs sm:text-sm font-medium text-center">Rekap Keuangan</span>
                    </a>
                    <a href="<?php echo e(route('guru.wali-kelas.bendahara')); ?>" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-emerald-50 hover:bg-emerald-100 rounded-lg border border-emerald-200 text-emerald-700 hover:text-emerald-800 transition-colors">
                        <i class="fas fa-wallet text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                        <span class="text-xs sm:text-sm font-medium text-center">Bendahara Kelas</span>
                    </a>
                    <a href="<?php echo e(route('guru.wali-kelas.siswa.index')); ?>" class="flex flex-col items-center justify-center p-2 sm:p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 text-gray-700 hover:text-gray-800 transition-colors">
                        <i class="fas fa-user-graduate text-lg sm:text-2xl mb-1 sm:mb-2"></i>
                        <span class="text-xs sm:text-sm font-medium text-center">Data Siswa</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <div class="border-b border-gray-200 px-4 sm:px-6 py-4">
                <h6 class="text-lg font-semibold text-gray-900">Informasi</h6>
            </div>
            <div class="p-3 sm:p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h6 class="flex items-center text-blue-800 font-semibold mb-3">
                        <i class="fas fa-info-circle mr-2"></i> Fitur Wali Kelas
                    </h6>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Absensi harian untuk seluruh siswa kelas
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Monitoring kehadiran dan ketidakhadiran siswa
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Rekap absensi bulanan dan tahunan
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Pantau status keuangan siswa
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check text-blue-500 mr-2 mt-0.5 text-xs"></i>
                            Detail informasi lengkap setiap siswa
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<div class="p-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
            <h5 class="text-xl font-semibold text-gray-900 mb-2">Tidak Ada Kelas Wali</h5>
            <p class="text-gray-600">Anda belum ditunjuk sebagai wali kelas atau belum ada kelas yang ditetapkan.</p>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    const timeElement = document.getElementById('current-time');
    if (timeElement) {
        timeElement.textContent = timeString;
    }
}

// Update time every second
setInterval(updateTime, 1000);
updateTime(); // Initial call
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\dashboard.blade.php ENDPATH**/ ?>