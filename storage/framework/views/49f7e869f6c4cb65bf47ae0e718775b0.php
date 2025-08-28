

<?php $__env->startSection('title', 'Rekap Absensi Kelas'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-3 sm:p-6">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 space-y-4 sm:space-y-0">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-1">Rekap Absensi Kelas</h1>
        </div>
    </div>

    <!-- Info Kelas -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-blue-200">
            <div class="bg-blue-600 text-white px-4 sm:px-6 py-4 rounded-t-lg">
                <h5 class="text-base sm:text-lg font-semibold mb-0">
                    <i class="fas fa-users mr-2"></i> <?php echo e($kelas->nama_kelas); ?> - <?php echo e($kelas->jurusan->nama_jurusan); ?>

                </h5>
            </div>
            <div class="p-4 sm:p-6">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center space-y-4 lg:space-y-0">
                    <div>
                        <p class="mb-1 text-gray-700"><strong>Period:</strong> 
                            <?php echo e(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y')); ?>

                        </p>
                        <p class="mb-0 text-gray-500 text-sm">Rekap kehadiran siswa dalam satu bulan</p>
                    </div>
                    <div class="w-full lg:w-auto">
                        <form method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2" id="filterForm">
                            <select name="bulan" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="submitFilter()">
                                <?php for($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo e($i); ?>" <?php echo e($bulan == $i ? 'selected' : ''); ?>>
                                        <?php echo e(\Carbon\Carbon::createFromDate(null, $i, 1)->format('F')); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                            <select name="tahun" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="submitFilter()">
                                <?php for($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                                    <option value="<?php echo e($y); ?>" <?php echo e($tahun == $y ? 'selected' : ''); ?>>
                                        <?php echo e($y); ?>

                                    </option>
                                <?php endfor; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rekap Absensi -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="border-b border-gray-200 px-6 py-4">
            <h6 class="text-lg font-semibold text-gray-900 mb-0">
                <i class="fas fa-chart-bar mr-2"></i> Rekap Absensi - <?php echo e(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y')); ?>

            </h6>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="rekapTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12 sm:w-16">No</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                            <th class="hidden lg:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                            <th class="hidden sm:table-cell px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-3 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">%</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $rekapData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900"><?php echo e($loop->iteration); ?></td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <?php if (isset($component)) { $__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.student-avatar','data' => ['student' => $data['siswa'],'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('student-avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['student' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data['siswa']),'size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a)): ?>
<?php $attributes = $__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a; ?>
<?php unset($__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a)): ?>
<?php $component = $__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a; ?>
<?php unset($__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a); ?>
<?php endif; ?>
                                </div>
                            </td>
                            <td class="px-3 sm:px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                        <a href="<?php echo e(route('guru.wali-kelas.siswa.detail', $data['siswa']->id)); ?>" 
                                           class="text-blue-600 hover:text-blue-800">
                                            <?php echo e($data['siswa']->nama_lengkap); ?>

                                        </a>
                                    </div>
                                    <div class="text-xs sm:text-sm text-gray-500"><?php echo e($data['siswa']->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></div>
                                    <!-- Mobile info: show absensi data on small screens -->
                                    <div class="md:hidden mt-2 space-y-1">
                                        <div class="flex justify-between text-xs">
                                            <span class="text-green-600">Hadir: <?php echo e($data['hadir']); ?></span>
                                            <span class="text-yellow-600">Sakit: <?php echo e($data['sakit']); ?></span>
                                        </div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-blue-600">Izin: <?php echo e($data['izin']); ?></span>
                                            <span class="text-red-600">Alpha: <?php echo e($data['alpha']); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <?php echo e($data['siswa']->nisn ?? $data['siswa']->nis ?? '-'); ?>

                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> <?php echo e($data['hadir']); ?>

                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-thermometer-half mr-1"></i> <?php echo e($data['sakit']); ?>

                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-file-alt mr-1"></i> <?php echo e($data['izin']); ?>

                                </span>
                            </td>
                            <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times mr-1"></i> <?php echo e($data['alpha']); ?>

                                </span>
                            </td>
                            <td class="hidden sm:table-cell px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-semibold text-gray-900"><?php echo e($data['total']); ?></span>
                            </td>
                            <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-center">
                                <?php
                                    $persentase = $data['total'] > 0 ? ($data['hadir'] / $data['total']) * 100 : 0;
                                    $badgeColor = $persentase >= 90 ? 'green' : ($persentase >= 75 ? 'yellow' : 'red');
                                ?>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo e($badgeColor); ?>-100 text-<?php echo e($badgeColor); ?>-800">
                                        <?php echo e(number_format($persentase, 0)); ?>%
                                    </span>
                                    <!-- Show total on mobile -->
                                    <div class="sm:hidden text-xs text-gray-500 mt-1">
                                        <?php echo e($data['total']); ?> hari
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="px-3 sm:px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-info-circle text-xl sm:text-2xl mb-2"></i>
                                <div class="text-sm sm:text-base">Tidak ada data absensi untuk bulan ini</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    
                    <?php if(count($rekapData) > 0): ?>
                    <tfoot class="bg-gray-100">
                        <tr class="border-t-2 border-gray-300">
                            <th colspan="3" class="px-3 sm:px-6 py-3 text-left text-xs sm:text-sm font-semibold text-gray-900">Total Keseluruhan</th>
                            <th class="hidden lg:table-cell px-6 py-3"></th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-sm font-semibold text-gray-900"><?php echo e(collect($rekapData)->sum('hadir')); ?></th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-sm font-semibold text-gray-900"><?php echo e(collect($rekapData)->sum('sakit')); ?></th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-sm font-semibold text-gray-900"><?php echo e(collect($rekapData)->sum('izin')); ?></th>
                            <th class="hidden md:table-cell px-6 py-3 text-center text-sm font-semibold text-gray-900"><?php echo e(collect($rekapData)->sum('alpha')); ?></th>
                            <th class="hidden sm:table-cell px-6 py-3 text-center text-sm font-semibold text-gray-900"><?php echo e(collect($rekapData)->sum('total')); ?></th>
                            <th class="px-3 sm:px-6 py-3 text-center">
                                <?php
                                    $totalHadir = collect($rekapData)->sum('hadir');
                                    $totalSemua = collect($rekapData)->sum('total');
                                    $avgPersentase = $totalSemua > 0 ? ($totalHadir / $totalSemua) * 100 : 0;
                                    $avgBadgeColor = $avgPersentase >= 90 ? 'green' : ($avgPersentase >= 75 ? 'yellow' : 'red');
                                ?>
                                <div class="text-center">
                                    <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium bg-<?php echo e($avgBadgeColor); ?>-100 text-<?php echo e($avgBadgeColor); ?>-800">
                                        <?php echo e(number_format($avgPersentase, 0)); ?>%
                                    </span>
                                    <!-- Mobile summary -->
                                    <div class="md:hidden text-xs text-gray-600 mt-2 space-y-1">
                                        <div>Total: <?php echo e(collect($rekapData)->sum('total')); ?> hari</div>
                                        <div class="flex justify-between text-xs">
                                            <span class="text-green-600">H: <?php echo e(collect($rekapData)->sum('hadir')); ?></span>
                                            <span class="text-yellow-600">S: <?php echo e(collect($rekapData)->sum('sakit')); ?></span>
                                            <span class="text-blue-600">I: <?php echo e(collect($rekapData)->sum('izin')); ?></span>
                                            <span class="text-red-600">A: <?php echo e(collect($rekapData)->sum('alpha')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <?php if(count($rekapData) > 0): ?>
            <!-- Statistik Visual -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">
                <div class="bg-white rounded-lg shadow-sm border border-green-200">
                    <div class="bg-green-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-lg">
                        <h6 class="text-base sm:text-lg font-semibold mb-0"><i class="fas fa-chart-pie mr-2"></i> Statistik Kehadiran</h6>
                    </div>
                    <div class="p-4 sm:p-6">
                        <?php
                            $totalHadir = collect($rekapData)->sum('hadir');
                            $totalSakit = collect($rekapData)->sum('sakit');
                            $totalIzin = collect($rekapData)->sum('izin');
                            $totalAlpha = collect($rekapData)->sum('alpha');
                            $grandTotal = $totalHadir + $totalSakit + $totalIzin + $totalAlpha;
                        ?>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 text-center">
                            <div class="p-3 sm:p-4">
                                <i class="fas fa-check text-xl sm:text-2xl text-green-600 mb-2"></i>
                                <div class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($totalHadir); ?></div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-2">Hadir</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" 
                                         style="width: <?php echo e($grandTotal > 0 ? ($totalHadir / $grandTotal) * 100 : 0); ?>%"></div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4">
                                <i class="fas fa-thermometer-half text-xl sm:text-2xl text-yellow-600 mb-2"></i>
                                <div class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($totalSakit); ?></div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-2">Sakit</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" 
                                         style="width: <?php echo e($grandTotal > 0 ? ($totalSakit / $grandTotal) * 100 : 0); ?>%"></div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4">
                                <i class="fas fa-file-alt text-xl sm:text-2xl text-blue-600 mb-2"></i>
                                <div class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($totalIzin); ?></div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-2">Izin</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" 
                                         style="width: <?php echo e($grandTotal > 0 ? ($totalIzin / $grandTotal) * 100 : 0); ?>%"></div>
                                </div>
                            </div>
                            <div class="p-3 sm:p-4">
                                <i class="fas fa-times text-xl sm:text-2xl text-red-600 mb-2"></i>
                                <div class="text-lg sm:text-2xl font-bold text-gray-900"><?php echo e($totalAlpha); ?></div>
                                <div class="text-xs sm:text-sm text-gray-500 mb-2">Alpha</div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" 
                                         style="width: <?php echo e($grandTotal > 0 ? ($totalAlpha / $grandTotal) * 100 : 0); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow-sm border border-blue-200">
                    <div class="bg-blue-600 text-white px-4 sm:px-6 py-3 sm:py-4 rounded-t-lg">
                        <h6 class="text-base sm:text-lg font-semibold mb-0"><i class="fas fa-info-circle mr-2"></i> Informasi</h6>
                    </div>
                    <div class="p-4 sm:p-6">
                        <ul class="space-y-3">
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-users text-blue-600 mr-3 mt-0.5 sm:mt-0 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base"><strong>Total Siswa:</strong> <?php echo e(count($rekapData)); ?> siswa</span>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-calendar text-green-600 mr-3 mt-0.5 sm:mt-0 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base"><strong>Periode:</strong> <?php echo e(\Carbon\Carbon::createFromDate($tahun, $bulan, 1)->format('F Y')); ?></span>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-percentage text-yellow-600 mr-3 mt-0.5 sm:mt-0 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base"><strong>Rata-rata Kehadiran:</strong> 
                                <?php echo e($grandTotal > 0 ? number_format(($totalHadir / $grandTotal) * 100, 1) : 0); ?>%</span>
                            </li>
                            <li class="flex items-start sm:items-center">
                                <i class="fas fa-exclamation-triangle text-red-600 mr-3 mt-0.5 sm:mt-0 flex-shrink-0"></i>
                                <span class="text-sm sm:text-base"><strong>Siswa Bermasalah:</strong> 
                                <?php echo e(collect($rekapData)->filter(function($data) {
                                    $persentase = $data['total'] > 0 ? ($data['hadir'] / $data['total']) * 100 : 0;
                                    return $persentase < 75;
                                })->count()); ?> siswa</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function submitFilter() {
    document.getElementById('filterForm').submit();
}

// DataTable initialization
$(document).ready(function() {
    $('#rekapTable').DataTable({
        "paging": false,
        "searching": true,
        "ordering": true,
        "info": false,
        "responsive": true,
        "language": {
            "search": "Cari:",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "emptyTable": "Tidak ada data dalam tabel"
        },
        "columnDefs": [
            { "orderable": false, "targets": [1] } // Disable sorting for photo column
        ]
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\wali-kelas\rekap-absensi.blade.php ENDPATH**/ ?>