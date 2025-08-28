

<?php $__env->startSection('title', 'Analytics - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Analytics Dashboard</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Analytics</span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Siswa -->
        <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-sm group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300">
                <i class="fas fa-user-graduate text-[100px]"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-blue-100">Total Siswa</p>
                        <div class="flex items-center gap-2">
                            <h3 class="text-2xl font-bold mt-1"><?php echo e($stats['total_students']); ?></h3>
                            <?php if($stats['new_students'] > 0): ?>
                            <span class="text-xs bg-green-400/20 text-green-100 px-2 py-0.5 rounded-full flex items-center">
                                <i class="fas fa-arrow-up text-[10px] mr-1"></i>
                                +<?php echo e($stats['new_students']); ?>

                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center mt-2 text-xs text-blue-100">
                            <span class="flex items-center">
                                <i class="fas fa-user-check mr-1"></i>
                                <?php echo e($stats['active_students']); ?> aktif
                            </span>
                            <span class="mx-2">•</span>
                            <span class="flex items-center">
                                <i class="fas fa-user-plus mr-1"></i>
                                <?php echo e($stats['new_students']); ?> baru
                            </span>
                        </div>
                    </div>
                    <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total Kelas -->
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white shadow-sm group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300">
                <i class="fas fa-school text-[100px]"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-emerald-100">Total Kelas</p>
                        <h3 class="text-2xl font-bold mt-1"><?php echo e($stats['total_classes']); ?></h3>
                        <div class="flex items-center mt-2 text-xs text-emerald-100">
                            <i class="fas fa-users mr-1"></i>
                            <span><?php echo e(round($stats['total_students'] / ($stats['total_classes'] ?: 1))); ?> siswa/kelas rata-rata</span>
                        </div>
                    </div>
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-sm group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300">
                <i class="fas fa-bullhorn text-[100px]"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-purple-100">Pengumuman Aktif</p>
                        <h3 class="text-2xl font-bold mt-1"><?php echo e($stats['total_announcements']); ?></h3>
                        <div class="flex items-center mt-2 text-xs text-purple-100">
                            <i class="fas fa-clock mr-1"></i>
                            <span><?php echo e(now()->format('F Y')); ?></span>
                        </div>
                    </div>
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kehadiran -->
        <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl p-4 text-white shadow-sm group hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
            <div class="absolute -right-8 -bottom-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300">
                <i class="fas fa-chart-pie text-[100px]"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-amber-100">Tingkat Kehadiran</p>
                        <h3 class="text-2xl font-bold mt-1"><?php echo e($attendanceData['today']['attendance_rate']); ?>%</h3>
                        <div class="flex items-center mt-2 text-xs text-amber-100">
                            <i class="fas fa-user-check mr-1"></i>
                            <span><?php echo e($attendanceData['today']['hadir']); ?> hadir hari ini</span>
                        </div>
                    </div>
                    <div class="bg-white/20 p-2 rounded-lg">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Tren Bulanan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Bulanan</h3>
                    <p class="text-sm text-gray-500">Statistik 6 bulan terakhir</p>
                </div>
                <div class="flex space-x-2">
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span>
                        <span class="text-xs text-gray-600">Pendaftaran</span>
                    </div>
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full bg-purple-500 mr-1"></span>
                        <span class="text-xs text-gray-600">Pengumuman</span>
                    </div>
                </div>
            </div>
            <div class="p-4">
                <canvas id="monthlyTrendsChart" height="300"></canvas>
            </div>
        </div>

        <!-- Statistik Kehadiran -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-800">Statistik Kehadiran</h3>
                <p class="text-sm text-gray-500">Overview kehadiran siswa</p>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <!-- Hari Ini -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-blue-600 mb-2 flex items-center">
                            <i class="fas fa-calendar-day mr-1"></i> Hari Ini
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Hadir</span>
                                <span class="font-medium px-2 py-0.5 bg-green-100 text-green-700 rounded">
                                    <?php echo e($attendanceData['today']['hadir']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Izin</span>
                                <span class="font-medium px-2 py-0.5 bg-blue-100 text-blue-700 rounded">
                                    <?php echo e($attendanceData['today']['izin']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Sakit</span>
                                <span class="font-medium px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded">
                                    <?php echo e($attendanceData['today']['sakit']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Alpha</span>
                                <span class="font-medium px-2 py-0.5 bg-red-100 text-red-700 rounded">
                                    <?php echo e($attendanceData['today']['alpha']); ?>

                                </span>
                            </div>
                            <div class="h-px bg-blue-100 my-2"></div>
                            <div class="flex justify-between font-medium text-sm">
                                <span class="text-gray-800">Total</span>
                                <span class="text-blue-600"><?php echo e($attendanceData['today']['total']); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Minggu Ini -->
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-emerald-600 mb-2 flex items-center">
                            <i class="fas fa-calendar-week mr-1"></i> Minggu Ini
                        </h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Hadir</span>
                                <span class="font-medium px-2 py-0.5 bg-green-100 text-green-700 rounded">
                                    <?php echo e($attendanceData['week']['hadir']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Izin</span>
                                <span class="font-medium px-2 py-0.5 bg-blue-100 text-blue-700 rounded">
                                    <?php echo e($attendanceData['week']['izin']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Sakit</span>
                                <span class="font-medium px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded">
                                    <?php echo e($attendanceData['week']['sakit']); ?>

                                </span>
                            </div>
                            <div class="flex justify-between text-sm items-center">
                                <span class="text-gray-600">Alpha</span>
                                <span class="font-medium px-2 py-0.5 bg-red-100 text-red-700 rounded">
                                    <?php echo e($attendanceData['week']['alpha']); ?>

                                </span>
                            </div>
                            <div class="h-px bg-green-100 my-2"></div>
                            <div class="flex justify-between font-medium text-sm">
                                <span class="text-gray-800">Total Minggu Ini</span>
                                <span class="text-emerald-600"><?php echo e($attendanceData['week']['total']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Kehadiran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Grafik Tren Kehadiran -->
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-700">Tren Kehadiran</h3>
                <div class="flex gap-2">
                    <button class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" onclick="updateChart('week')">Minggu Ini</button>
                    <button class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors" onclick="updateChart('month')">Bulan Ini</button>
                </div>
            </div>
            <canvas id="attendanceTrendChart" class="w-full" style="height: 300px"></canvas>
        </div>

        <!-- Statistik Detail -->
        <div class="bg-white rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-700 mb-4">Detail Kehadiran</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                            <i class="fas fa-check text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Hadir</p>
                            <p class="font-semibold text-gray-800"><?php echo e($stats['attendance_today']); ?> siswa</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Persentase</p>
                        <p class="font-semibold text-green-600"><?php echo e($stats['attendance_rate']); ?>%</p>
                    </div>
                </div>
                <!-- Tambahkan statistik lain seperti Izin, Sakit, dll -->
            </div>
        </div>
    </div>

    <!-- Google Analytics Integration -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start space-x-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fab fa-google text-blue-600 text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Google Analytics (GA4)</h3>
                <p class="text-gray-600 mb-4">
                    Tracking ID: <code class="bg-gray-100 px-2 py-1 rounded font-mono text-sm">G-6KZ23B7ECV</code>
                </p>
                <div class="space-y-2 text-sm text-gray-600">
                    <p class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Cross-domain tracking diaktifkan untuk domain smk305ckp.my.id
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Debug mode aktif untuk monitoring implementasi
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        Cookie settings dikonfigurasi dengan SameSite=None;Secure
                    </p>
                </div>
                <div class="mt-4">
                    <a href="https://analytics.google.com" target="_blank" 
                        class="inline-flex items-center text-sm text-blue-600 hover:text-blue-700 hover:underline">
                        <i class="fas fa-external-link-alt mr-1"></i>
                        Buka Google Analytics Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const monthlyTrendsCtx = document.getElementById('monthlyTrendsChart').getContext('2d');
    new Chart(monthlyTrendsCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthlyTrends['months'], 15, 512) ?>,
            datasets: [{
                label: 'Pendaftaran Siswa',
                data: <?php echo json_encode($monthlyTrends['registrations'], 15, 512) ?>,
                borderColor: '#3b82f6',
                backgroundColor: '#3b82f620',
                tension: 0.4,
                fill: true
            }, {
                label: 'Pengumuman',
                data: <?php echo json_encode($monthlyTrends['announcements'], 15, 512) ?>,
                borderColor: '#8b5cf6',
                backgroundColor: '#8b5cf620',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'bar',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
            datasets: [{
                label: 'Minggu Ini',
                data: [
                    <?php echo e($attendanceData['week']['hadir']); ?>,
                    <?php echo e($attendanceData['week']['izin']); ?>,
                    <?php echo e($attendanceData['week']['sakit']); ?>,
                    <?php echo e($attendanceData['week']['alpha']); ?>

                ],
                backgroundColor: [
                    '#10b98180',
                    '#3b82f680',
                    '#f59e0b80',
                    '#ef444480'
                ],
                borderColor: [
                    '#10b981',
                    '#3b82f6',
                    '#f59e0b',
                    '#ef4444'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Attendance Trend Chart
    const attendanceTrendCtx = document.getElementById('attendanceTrendChart').getContext('2d');
    const attendanceTrendChart = new Chart(attendanceTrendCtx, {
        type: 'line',
        data: {
            labels: [], // Akan diisi dengan data dari server
            datasets: [{
                label: 'Kehadiran',
                data: [], // Akan diisi dengan data dari server
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: true,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        });

    window.updateChart = function(period) {
        // Implementasi logika update chart berdasarkan periode
        // Akan menggunakan AJAX untuk mengambil data dari server
    }
});
</script>
<script>
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    let attendanceChart;

    function initChart(data) {
        if (attendanceChart) {
            attendanceChart.destroy();
        }

        attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Kehadiran',
                    data: data.data,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function updateChart(period) {
        fetch(`/admin/analytics/attendance-data?period=${period}`)
            .then(response => response.json())
            .then(data => {
                initChart(data);
            })
            .catch(error => console.error('Error:', error));
    }

    // Load initial data
    updateChart('week');
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\analytics\index.blade.php ENDPATH**/ ?>