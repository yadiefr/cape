

<?php $__env->startSection('title', 'Dashboard Siswa'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Mobile-First Interactive Enhancements */
    .mobile-card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .mobile-card-hover:active {
        transform: scale(0.95);
    }
    
    .stat-progress {
        animation: progressFill 2s ease-out;
    }
    
    @keyframes progressFill {
        from { width: 0%; }
    }
    
    .tab-indicator {
        position: absolute;
        bottom: 0;
        height: 2px;
        background: linear-gradient(90deg, #3B82F6, #1D4ED8);
        transition: all 0.3s ease;
    }
    
    .pulse-ring {
        animation: pulse-ring 1.25s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    
    @keyframes pulse-ring {
        0% {
            transform: scale(0.33);
        }
        40%, 50% {
            opacity: 1;
        }
        100% {
            opacity: 0;
            transform: scale(1.2);
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-lg overflow-hidden mb-6 sm:mb-8">
        <div class="relative p-4 sm:p-6 lg:p-8">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 h-32 w-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute bottom-0 right-1/4 -mb-12 h-20 w-20 bg-white opacity-10 rounded-full"></div>
            
            <div class="relative z-10">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white mb-2">
                    Selamat <?php echo e(now()->format('H') < 10 ? 'Pagi' : (now()->format('H') < 15 ? 'Siang' : (now()->format('H') < 18 ? 'Sore' : 'Malam'))); ?>, <?php echo e(Auth::guard('siswa')->user()->nama_lengkap ?? 'Siswa'); ?>!
                </h1>
                <p class="text-blue-100 text-sm sm:text-base"><?php echo e(\Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y')); ?></p>
                
                <?php if(Auth::guard('siswa')->user()->kelas || Auth::guard('siswa')->user()->jurusan): ?>
                <div class="mt-3 sm:mt-4 inline-flex items-center px-3 sm:px-4 py-2 bg-white bg-opacity-20 text-white rounded-lg text-sm">
                    <i class="fas fa-user-graduate mr-2"></i>
                    <span>
                        <?php if(Auth::guard('siswa')->user()->kelas): ?>
                            <?php echo e(Auth::guard('siswa')->user()->kelas->nama_kelas); ?>

                        <?php endif; ?>
                        <?php if(Auth::guard('siswa')->user()->jurusan): ?>
                            <?php echo e(Auth::guard('siswa')->user()->kelas ? ' - ' : ''); ?><?php echo e(Auth::guard('siswa')->user()->jurusan->nama_jurusan); ?>

                        <?php endif; ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Mobile Layout - Jadwal Hari Ini -->
        <div class="lg:hidden mb-6">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-4 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                        Jadwal Hari Ini
                        <span class="ml-2 text-sm px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">
                            <?php echo e(\Carbon\Carbon::now()->locale('id')->dayName); ?>

                        </span>
                    </h2>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <?php
                            // Group jadwal by mapel
                            $groupedJadwal = $jadwalHariIni->groupBy(function($item) {
                                return $item->mapel->id ?? 'unknown';
                            })->map(function($group) {
                                $firstJadwal = $group->first();
                                $startTimes = $group->pluck('jam_mulai')->map(function($time) {
                                    return \Carbon\Carbon::parse($time);
                                })->sort();
                                $endTimes = $group->pluck('jam_selesai')->map(function($time) {
                                    return \Carbon\Carbon::parse($time);
                                })->sort();
                                
                                return (object)[
                                    'mapel' => $firstJadwal->mapel,
                                    'guru' => $firstJadwal->guru,
                                    'ruangan' => $firstJadwal->ruangan,
                                    'jam_mulai' => $startTimes->first()->format('H:i'),
                                    'jam_selesai' => $endTimes->last()->format('H:i'),
                                    'jam_count' => $group->count(),
                                    'original_start' => $startTimes->first(),
                                    'original_end' => $endTimes->last(),
                                ];
                            });
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $groupedJadwal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-blue-50 rounded-lg p-3 overflow-hidden">
                            <div class="flex items-start space-x-3">
                                <?php
                                    $now = \Carbon\Carbon::now();
                                    $startTime = $jadwal->original_start;
                                    $endTime = $jadwal->original_end;
                                    $isCurrentClass = $now->between($startTime, $endTime);
                                    $isUpcoming = $now->lt($startTime);
                                    $isPast = $now->gt($endTime);

                                    $statusClass = '';
                                    if ($isCurrentClass) {
                                        $statusClass = 'bg-green-500';
                                    } elseif ($isUpcoming) {
                                        $statusClass = 'bg-blue-500';
                                    } else {
                                        $statusClass = 'bg-gray-400';
                                    }
                                ?>
                                <div class="flex flex-col items-center flex-shrink-0">
                                    <div class="w-3 h-3 <?php echo e($statusClass); ?> rounded-full"></div>
                                    <div class="w-0.5 h-8 <?php echo e($statusClass); ?> bg-opacity-30 mt-1"></div>
                                </div>
                                <div class="flex-1 min-w-0 overflow-hidden">
                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2 leading-tight"><?php echo e($jadwal->mapel->nama ?? 'Mata Pelajaran'); ?></h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <?php echo e($jadwal->jam_mulai); ?> - <?php echo e($jadwal->jam_selesai); ?>

                                        <?php if($jadwal->jam_count > 1): ?>
                                        <span class="text-blue-600 font-medium">(<?php echo e($jadwal->jam_count); ?> JP)</span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if($jadwal->guru && $jadwal->guru->nama): ?>
                                    <div class="flex items-center mt-1 overflow-hidden">
                                        <div class="flex items-center justify-center w-4 h-4 bg-blue-500 text-white rounded-full text-xs mr-1 flex-shrink-0">
                                            <?php echo e(substr($jadwal->guru->nama, 0, 1)); ?>

                                        </div>
                                        <p class="text-xs text-gray-700 truncate"><?php echo e($jadwal->guru->nama); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if($isCurrentClass): ?>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded whitespace-nowrap">
                                        Live
                                    </span>
                                    <?php elseif($isUpcoming): ?>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded whitespace-nowrap">
                                        <?php echo e($startTime->diffForHumans()); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded whitespace-nowrap">
                                        Selesai
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-6">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-times text-2xl mb-2"></i>
                                <p class="text-sm">Tidak ada jadwal hari ini</p>
                                <p class="text-xs text-gray-400 mt-1">Silahkan cek jadwal lengkap</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo e(route('siswa.jadwal.index')); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span>Lihat Semua Jadwal</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Stats Cards - Mobile 2x2, Desktop 4x1 -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-8">
            <!-- Total Mata Pelajaran -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group cursor-pointer" onclick="window.location.href='<?php echo e(route('siswa.jadwal.index')); ?>'">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book text-blue-600 text-lg sm:text-xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Mata Pelajaran</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($totalMataPelajaran); ?></p>
                    <div class="flex items-center text-xs text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>Aktif</span>
                    </div>
                </div>
                <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full animate-pulse" style="width: 85%"></div>
                </div>
            </div>

            <!-- Tugas Pending with Animation -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group cursor-pointer" onclick="window.location.href='<?php echo e(route('siswa.materi.index')); ?>#tugas'">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-yellow-100 to-amber-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform relative">
                        <i class="fas fa-tasks text-yellow-600 text-lg sm:text-xl"></i>
                        <?php if($tugasPending > 0): ?>
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full flex items-center justify-center">
                            <span class="text-white text-xs font-bold"><?php echo e($tugasPending > 9 ? '9+' : $tugasPending); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Tugas Pending</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($tugasPending); ?></p>
                    <div class="flex items-center text-xs <?php echo e($tugasPending > 0 ? 'text-red-600' : 'text-green-600'); ?>">
                        <i class="fas <?php echo e($tugasPending > 0 ? 'fa-exclamation-triangle' : 'fa-check'); ?> mr-1"></i>
                        <span><?php echo e($tugasPending > 0 ? 'Perlu Dikerjakan' : 'Semua Selesai'); ?></span>
                    </div>
                </div>
                <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-yellow-500 to-amber-600 rounded-full" style="width: <?php echo e($tugasPending > 0 ? min($tugasPending * 20, 100) : 0); ?>%"></div>
                </div>
            </div>

            <!-- Rata-rata Nilai dengan Progress -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group cursor-pointer" onclick="window.location.href='<?php echo e(route('siswa.nilai.index')); ?>'">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-green-100 to-emerald-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-green-600 text-lg sm:text-xl"></i>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Rata-rata Nilai</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($rataNilai > 0 ? number_format($rataNilai, 1) : '-'); ?></p>
                    <?php if($rataNilai > 0): ?>
                    <div class="flex items-center text-xs <?php echo e($rataNilai >= 80 ? 'text-green-600' : ($rataNilai >= 70 ? 'text-yellow-600' : 'text-red-600')); ?>">
                        <i class="fas <?php echo e($rataNilai >= 80 ? 'fa-thumbs-up' : ($rataNilai >= 70 ? 'fa-meh' : 'fa-thumbs-down')); ?> mr-1"></i>
                        <span><?php echo e($rataNilai >= 80 ? 'Excellent' : ($rataNilai >= 70 ? 'Good' : 'Perlu Perbaikan')); ?></span>
                    </div>
                    <?php else: ?>
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span>Belum Ada Nilai</span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-green-500 to-emerald-600 rounded-full transition-all duration-1000" style="width: <?php echo e($rataNilai > 0 ? $rataNilai : 0); ?>%"></div>
                </div>
            </div>

            <!-- Absensi dengan Real-time Animation -->
            <div class="bg-white rounded-xl shadow-sm p-4 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group cursor-pointer" onclick="window.location.href='<?php echo e(route('siswa.absensi')); ?>'">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-br from-purple-100 to-violet-200 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform relative">
                        <i class="fas fa-calendar-check text-purple-600 text-lg sm:text-xl"></i>
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                    <p class="text-xs sm:text-sm font-medium text-gray-600 mb-1">Kehadiran</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900 mb-1"><?php echo e($kehadiran); ?>%</p>
                    <div class="flex items-center text-xs <?php echo e($kehadiran >= 90 ? 'text-green-600' : ($kehadiran >= 80 ? 'text-yellow-600' : 'text-gray-500')); ?>">
                        <i class="fas <?php echo e($kehadiran >= 90 ? 'fa-check-circle' : ($kehadiran >= 80 ? 'fa-exclamation-circle' : 'fa-info-circle')); ?> mr-1"></i>
                        <span><?php echo e($kehadiran >= 90 ? 'Sangat Baik' : ($kehadiran >= 80 ? 'Baik' : 'Belum ada Kehadiran')); ?></span>
                    </div>
                </div>
                <div class="mt-3 h-1 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-purple-500 to-violet-600 rounded-full transition-all duration-1000" style="width: <?php echo e($kehadiran); ?>%"></div>
                </div>
            </div>
        </div>
        
        
        
        <!-- Desktop Layout -->
        <div class="hidden lg:grid lg:grid-cols-2 gap-4 mb-4 lg:mb-6">
            <!-- Jadwal Hari Ini -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-calendar-day text-blue-500 mr-2"></i>
                        Jadwal Hari Ini
                        <span class="ml-2 text-sm px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">
                            <?php echo e(\Carbon\Carbon::now()->locale('id')->dayName); ?>

                        </span>
                    </h2>
                </div>
                <div class="p-4 md:p-6">
                    <div class="space-y-3">
                        <?php
                            // Group jadwal by mapel for desktop view
                            $groupedJadwalDesktop = $jadwalHariIni->groupBy(function($item) {
                                return $item->mapel->id ?? 'unknown';
                            })->map(function($group) {
                                $firstJadwal = $group->first();
                                $startTimes = $group->pluck('jam_mulai')->map(function($time) {
                                    return \Carbon\Carbon::parse($time);
                                })->sort();
                                $endTimes = $group->pluck('jam_selesai')->map(function($time) {
                                    return \Carbon\Carbon::parse($time);
                                })->sort();
                                
                                return (object)[
                                    'mapel' => $firstJadwal->mapel,
                                    'guru' => $firstJadwal->guru,
                                    'ruangan' => $firstJadwal->ruangan,
                                    'jam_mulai' => $startTimes->first()->format('H:i'),
                                    'jam_selesai' => $endTimes->last()->format('H:i'),
                                    'jam_count' => $group->count(),
                                    'original_start' => $startTimes->first(),
                                    'original_end' => $endTimes->last(),
                                ];
                            });
                        ?>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $groupedJadwalDesktop; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jadwal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-blue-50 rounded-lg p-3 hover:bg-blue-100 transition-colors overflow-hidden">
                            <div class="flex items-start space-x-3">
                                <?php
                                    $now = \Carbon\Carbon::now();
                                    $startTime = $jadwal->original_start;
                                    $endTime = $jadwal->original_end;
                                    $isCurrentClass = $now->between($startTime, $endTime);
                                    $isUpcoming = $now->lt($startTime);
                                    $isPast = $now->gt($endTime);

                                    $statusClass = '';
                                    if ($isCurrentClass) {
                                        $statusClass = 'bg-green-500';
                                    } elseif ($isUpcoming) {
                                        $statusClass = 'bg-blue-500';
                                    } else {
                                        $statusClass = 'bg-gray-400';
                                    }
                                ?>
                                <div class="flex flex-col items-center flex-shrink-0">
                                    <div class="w-3 h-3 <?php echo e($statusClass); ?> rounded-full"></div>
                                    <div class="w-0.5 h-12 <?php echo e($statusClass); ?> bg-opacity-30 mt-1"></div>
                                </div>
                                <div class="flex-1 min-w-0 overflow-hidden">
                                    <h4 class="text-sm font-medium text-gray-900 line-clamp-2 leading-tight"><?php echo e($jadwal->mapel->nama ?? 'Mata Pelajaran'); ?></h4>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <?php echo e($jadwal->jam_mulai); ?> - <?php echo e($jadwal->jam_selesai); ?>

                                        <?php if($jadwal->jam_count > 1): ?>
                                        <span class="text-blue-600 font-medium">(<?php echo e($jadwal->jam_count); ?> JP)</span>
                                        <?php endif; ?>
                                    </p>
                                    <?php if($jadwal->guru && $jadwal->guru->nama): ?>
                                    <div class="flex items-center mt-1 overflow-hidden">
                                        <div class="flex items-center justify-center w-5 h-5 bg-blue-500 text-white rounded-full text-xs mr-1 flex-shrink-0">
                                            <?php echo e(substr($jadwal->guru->nama, 0, 1)); ?>

                                        </div>
                                        <p class="text-xs text-gray-700 truncate"><?php echo e($jadwal->guru->nama); ?></p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-shrink-0">
                                    <?php if($isCurrentClass): ?>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded whitespace-nowrap">
                                        Live
                                    </span>
                                    <?php elseif($isUpcoming): ?>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded whitespace-nowrap">
                                        <?php echo e($startTime->diffForHumans()); ?>

                                    </span>
                                    <?php else: ?>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded whitespace-nowrap">
                                        Selesai
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8">
                            <div class="text-gray-500">
                                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                                <p class="text-sm">Tidak ada jadwal pelajaran hari ini</p>
                                <p class="text-xs text-gray-400 mt-1">Silahkan cek jadwal lengkap di menu Jadwal Pelajaran</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo e(route('siswa.jadwal.index')); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span>Lihat Semua Jadwal</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pengumuman Terbaru -->
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bullhorn text-red-500 mr-2"></i>
                        Pengumuman Terbaru
                    </h2>
                </div>
                <div class="p-4 md:p-6">
                    <div class="space-y-4">
                        <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-l-4 border-<?php echo e($loop->index % 3 == 0 ? 'red' : ($loop->index % 3 == 1 ? 'blue' : 'green')); ?>-500 pl-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($p->judul); ?></h4>
                            <p class="text-xs text-gray-600 mb-2"><?php echo e(Str::limit($p->isi, 80)); ?></p>
                            <span class="text-xs text-gray-500"><?php echo e($p->created_at->diffForHumans()); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8">
                            <div class="text-gray-500">
                                <i class="fas fa-bullhorn text-3xl mb-3"></i>
                                <p class="text-sm">Belum ada pengumuman terbaru</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo e(route('siswa.pengumuman.index')); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span>Lihat Semua Pengumuman</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tugas dan Materi Terbaru -->
        <div class="bg-white rounded-xl shadow-sm mb-6">
            <div class="px-4 md:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-2 sm:mb-0">
                        <i class="fas fa-tasks text-yellow-500 mr-2"></i>
                        Tugas Terbaru
                    </h2>
                </div>
            </div>
            <div class="p-4 md:p-6">
                <!-- Mobile Card/List -->
                <div class="space-y-3 sm:hidden">
                    <?php $__empty_1 = true; $__currentLoopData = $tugasTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4 flex flex-col gap-2 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="font-semibold text-gray-900"><?php echo e($tugas->judul); ?></div>
                            <div class="text-xs <?php echo e($tugas->is_overdue ? 'text-red-600' : ($tugas->is_near_deadline ? 'text-yellow-600' : 'text-green-600')); ?> font-bold">
                                <?php echo e($tugas->tanggal_deadline ? $tugas->tanggal_deadline->format('d M Y') : ($tugas->deadline ? $tugas->deadline->format('d M Y') : '-')); ?>

                            </div>
                        </div>
                        <div class="flex items-center text-xs text-gray-500 gap-2">
                            <span><?php echo e($tugas->mapel->nama); ?></span>
                            <span class="hidden xs:inline">|</span>
                            <?php if($tugas->status_pengerjaan == 'submitted'): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-upload mr-1"></i> Dikumpulkan
                                </span>
                            <?php elseif($tugas->status_pengerjaan == 'graded'): ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i> Dinilai (<?php echo e($tugas->nilai); ?>)
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($tugas->is_overdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                    <i class="fas fa-clock mr-1"></i> <?php echo e($tugas->is_overdue ? 'Terlambat' : 'Pending'); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-end mt-2">
                            <?php if($tugas->status_pengerjaan == 'submitted' || $tugas->status_pengerjaan == 'graded'): ?>
                                <a href="<?php echo e(route('siswa.tugas.show', $tugas->id)); ?>" class="text-green-600 hover:text-green-700 font-medium text-sm">Lihat</a>
                            <?php else: ?>
                                <a href="<?php echo e(route('siswa.tugas.show', $tugas->id)); ?>" class="text-blue-600 hover:text-blue-700 font-medium text-sm">Kerjakan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-6">
                        <div class="text-gray-500">
                            <i class="fas fa-tasks text-3xl mb-2"></i>
                            <p class="text-sm">Belum ada tugas terbaru</p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="mt-4">
                        <a href="<?php echo e(route('siswa.materi.index')); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span>Lihat Semua Tugas</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
                <!-- Desktop Table -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 md:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php $__empty_1 = true; $__currentLoopData = $tugasTerbaru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($tugas->judul); ?></div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-sm text-gray-600"><?php echo e($tugas->mapel->nama); ?></div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <div class="text-sm <?php echo e($tugas->is_overdue ? 'text-red-600' : ($tugas->is_near_deadline ? 'text-yellow-600' : 'text-green-600')); ?> font-medium">
                                        <?php echo e($tugas->tanggal_deadline ? $tugas->tanggal_deadline->format('d M Y') : ($tugas->deadline ? $tugas->deadline->format('d M Y') : '-')); ?>

                                    </div>
                                </td>
                                <td class="px-4 md:px-6 py-4">
                                    <?php if($tugas->status_pengerjaan == 'submitted'): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-upload mr-1"></i>
                                            Dikumpulkan
                                        </span>
                                    <?php elseif($tugas->status_pengerjaan == 'graded'): ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Dinilai (<?php echo e($tugas->nilai); ?>)
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($tugas->is_overdue ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                            <i class="fas fa-clock mr-1"></i>
                                            <?php echo e($tugas->is_overdue ? 'Terlambat' : 'Pending'); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 md:px-6 py-4 text-sm">
                                    <?php if($tugas->status_pengerjaan == 'submitted' || $tugas->status_pengerjaan == 'graded'): ?>
                                        <a href="<?php echo e(route('siswa.tugas.show', $tugas->id)); ?>" class="text-green-600 hover:text-green-700 font-medium">
                                            Lihat
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('siswa.tugas.show', $tugas->id)); ?>" class="text-blue-600 hover:text-blue-700 font-medium">
                                            Kerjakan
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-4 md:px-6 py-8 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-tasks text-4xl mb-4"></i>
                                        <p class="text-sm">Belum ada tugas terbaru</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mobile Layout - Pengumuman -->
        <div class="lg:hidden mb-6">
            <div class="bg-white rounded-xl shadow-sm">
                <div class="px-4 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bullhorn text-red-500 mr-2"></i>
                        Pengumuman Terbaru
                    </h2>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-l-4 border-<?php echo e($loop->index % 3 == 0 ? 'red' : ($loop->index % 3 == 1 ? 'blue' : 'green')); ?>-500 pl-3 py-2">
                            <h4 class="text-sm font-medium text-gray-900 mb-1"><?php echo e($p->judul); ?></h4>
                            <p class="text-xs text-gray-600 mb-2 line-clamp-2"><?php echo e(Str::limit($p->isi, 60)); ?></p>
                            <span class="text-xs text-gray-500"><?php echo e($p->created_at->diffForHumans()); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-6">
                            <div class="text-gray-500">
                                <i class="fas fa-bullhorn text-2xl mb-2"></i>
                                <p class="text-sm">Belum ada pengumuman</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?php echo e(route('siswa.pengumuman.index')); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <span>Lihat Semua Pengumuman</span>
                            <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add haptic feedback for mobile cards (if supported)
    if ('vibrate' in navigator) {
        document.querySelectorAll('.mobile-card-hover').forEach(card => {
            card.addEventListener('touchstart', () => {
                navigator.vibrate(10); // Very light haptic feedback
            });
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\dashboard.blade.php ENDPATH**/ ?>