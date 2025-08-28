

<?php $__env->startSection('title', 'Dashboard Admin - SMK PGRI CIKAMPEK'); ?>

<?php
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
?>

<?php $__env->startSection('main-content'); ?>
    <!-- Clear floats wrapper -->
    <div class="clear-floats">
        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 rounded-xl shadow-xl p-6 mb-6 text-white relative overflow-hidden transform transition-all duration-300 hover:shadow-blue-200 group">
                    <!-- Decorative elements -->
                    <div class="absolute -right-10 -top-10 h-40 w-40 bg-white opacity-10 rounded-full group-hover:animate-pulse"></div>
                    <div class="absolute right-20 bottom-0 h-24 w-24 bg-white opacity-10 rounded-full group-hover:scale-110 transition-transform"></div>
                    <div class="absolute left-1/3 top-1/2 h-16 w-16 bg-white opacity-10 rounded-full group-hover:opacity-20 transition-opacity"></div>
                    
                    <!-- Geometric decorations -->
                    <div class="absolute bottom-4 left-1/2 h-6 w-6 bg-gradient-to-tr from-pink-400 to-purple-500 opacity-50 rounded-md transform rotate-45"></div>
                    <div class="absolute top-12 right-1/4 h-4 w-12 bg-gradient-to-r from-yellow-400 to-orange-500 opacity-30 rounded-full"></div>
                    
                    <div class="flex flex-col md:flex-row justify-between items-center relative z-10">
                        <div class="md:max-w-2xl">
                            <h2 class="text-3xl font-bold mb-2 flex items-center">
                                <span class="animate-wave inline-block mr-2">👋</span>
                                Selamat Datang, <?php echo e(Auth::user()->name ?? 'Admin'); ?>!
                            </h2>
                            <div class="flex items-center text-blue-100 mb-4 bg-blue-800/30 backdrop-blur-sm rounded-lg px-3 py-1.5 max-w-max">
                                <i class="far fa-calendar-alt mr-2"></i>
                                <span><?php echo e(now()->format('l, d F Y')); ?></span>
                                <span class="mx-2 opacity-50">•</span>
                                <span>SMK PGRI CIKAMPEK</span>
                            </div>
                            
                            <p class="text-blue-100 mb-3 max-w-xl text-sm">Selamat datang di dashboard admin. Anda dapat mengelola konten website, informasi akademik, dan pengaturan sistem dari sini.</p>
                            
                            <div class="flex space-x-3 mt-5">
                                <a href="<?php echo e(route('admin.analytics')); ?>" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white py-2 px-4 rounded-lg transition-all flex items-center group">
                                    <i class="fas fa-chart-line mr-2 group-hover:animate-pulse"></i>
                                    Analytics
                                </a>
                                <a href="<?php echo e(route('admin.pengumuman.create')); ?>" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white py-2 px-4 rounded-lg transition-all flex items-center group">
                                    <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform"></i>
                                    Pengumuman
                                </a>
                                <a href="<?php echo e(route('admin.berita.create')); ?>" class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white py-2 px-4 rounded-lg transition-all flex items-center group">
                                    <i class="fas fa-newspaper mr-2"></i>
                                    Berita
                                </a>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex flex-col items-end">
                            <a href="/" target="_blank" class="bg-white/90 text-blue-700 py-2.5 px-5 rounded-lg shadow-sm hover:bg-white transition-all flex items-center font-medium">
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Lihat Website
                            </a>
                            <div class="text-xs text-blue-200 mt-3 text-right">
                                <span class="bg-blue-800/40 py-1 px-2 rounded-md">Terakhir update: <?php echo e(now()->subHours(3)->format('H:i')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Dashboard Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition-all group overflow-hidden relative">
                        <div class="absolute -right-6 -bottom-6 h-24 w-24 bg-blue-500 opacity-10 rounded-full group-hover:scale-125 transition-transform"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-user-graduate text-blue-600 text-lg"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Total Siswa</p>
                                </div>
                                <p class="text-3xl font-bold text-blue-700 mt-3"><?php echo e(number_format($totalSiswa)); ?></p>
                                <?php
                                    $lastYearCount = Siswa::whereYear('created_at', now()->subYear()->year)->count();
                                    $growth = $lastYearCount > 0 ? (($totalSiswa - $lastYearCount) / $lastYearCount) * 100 : 0;
                                ?>
                                <p class="text-<?php echo e($growth >= 0 ? 'green' : 'red'); ?>-600 text-xs flex items-center mt-2 font-semibold">
                                    <i class="fas fa-arrow-<?php echo e($growth >= 0 ? 'up' : 'down'); ?> mr-1"></i> 
                                    <?php echo e(number_format(abs($growth), 1)); ?>% dari tahun lalu
                                </p>
                            </div>
                            <div class="flex items-end h-full self-end">
                                <div class="space-y-1 flex items-end h-16">
                                    <div class="w-2 h-6 bg-blue-200 rounded-sm"></div>
                                    <div class="w-2 h-10 bg-blue-300 rounded-sm mx-1"></div>
                                    <div class="w-2 h-8 bg-blue-400 rounded-sm"></div>
                                    <div class="w-2 h-12 bg-blue-500 rounded-sm mx-1"></div>
                                    <div class="w-2 h-16 bg-blue-600 rounded-sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition-all group overflow-hidden relative">
                        <div class="absolute -right-6 -bottom-6 h-24 w-24 bg-green-500 opacity-10 rounded-full group-hover:scale-125 transition-transform"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="fas fa-chalkboard-teacher text-green-600 text-lg"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Total Guru</p>
                                </div>
                                <p class="text-3xl font-bold text-green-700 mt-3"><?php echo e(number_format($totalGuru)); ?></p>
                                <?php
                                    $lastYearTeachers = Guru::whereYear('created_at', now()->subYear()->year)->count();
                                    $growthGuru = $lastYearTeachers > 0 ? (($totalGuru - $lastYearTeachers) / $lastYearTeachers) * 100 : 0;
                                ?>
                                <p class="text-<?php echo e($growthGuru >= 0 ? 'green' : 'red'); ?>-600 text-xs flex items-center mt-2 font-semibold">
                                    <i class="fas fa-arrow-<?php echo e($growthGuru >= 0 ? 'up' : 'down'); ?> mr-1"></i> 
                                    <?php echo e(number_format(abs($growthGuru), 1)); ?>% dari tahun lalu
                                </p>
                            </div>
                            <div class="flex items-end h-full self-end">
                                <div class="flex h-16 items-end">
                                    <div class="w-10 h-10 rounded-full border-4 border-green-200 flex items-center justify-center">
                                        <div class="w-6 h-6 rounded-full bg-green-400"></div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full border-4 border-green-300 flex items-center justify-center -ml-2 z-10">
                                        <div class="w-6 h-6 rounded-full bg-green-500"></div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full border-4 border-green-400 flex items-center justify-center -ml-2 z-20">
                                        <div class="w-6 h-6 rounded-full bg-green-600"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-amber-500 hover:shadow-md transition-all group overflow-hidden relative">
                        <div class="absolute -right-6 -bottom-6 h-24 w-24 bg-amber-500 opacity-10 rounded-full group-hover:scale-125 transition-transform"></div>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <div class="p-2 bg-amber-100 rounded-lg">
                                        <i class="fas fa-book text-amber-600 text-lg"></i>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">Mata Pelajaran</p>
                                </div>
                                <p class="text-3xl font-bold text-amber-700 mt-3"><?php echo e(number_format(MataPelajaran::count())); ?></p>
                                <?php
                                    $totalMapel = MataPelajaran::count();
                                    $lastYearSubjects = MataPelajaran::whereYear('created_at', now()->subYear()->year)->count();
                                    $subjectGrowth = $lastYearSubjects > 0 ? (($totalMapel - $lastYearSubjects) / $lastYearSubjects) * 100 : 0;
                                ?>
                                <p class="text-<?php echo e($subjectGrowth >= 0 ? 'amber' : 'red'); ?>-600 text-xs flex items-center mt-2 font-semibold">
                                    <i class="fas fa-arrow-<?php echo e($subjectGrowth >= 0 ? 'up' : 'down'); ?> mr-1"></i> 
                                    <?php echo e(number_format(abs($subjectGrowth), 1)); ?>% dari tahun lalu
                                </p>
                            </div>
                            <div class="flex items-end h-full self-end">
                                <div class="h-16 w-16 overflow-hidden relative">
                                    <div class="absolute inset-0 flex flex-wrap">
                                        <div class="w-1/2 h-1/2 bg-amber-200"></div>
                                        <div class="w-1/2 h-1/2 bg-amber-300"></div>
                                        <div class="w-1/2 h-1/2 bg-amber-400"></div>
                                        <div class="w-1/2 h-1/2 bg-amber-500"></div>
                                    </div>
                                    <div class="absolute inset-1 bg-white rounded-sm flex items-center justify-center">
                                        <span class="text-amber-600 text-xs font-bold">MP</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                 <!-- Website Content Management -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 relative overflow-hidden group hover:shadow-md transition-all">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-50 group-hover:opacity-70 transition-opacity"></div>
                    <div class="absolute -right-12 -top-12 h-48 w-48 bg-blue-100 opacity-30 rounded-full"></div>
                    <div class="absolute -left-20 -bottom-20 h-56 w-56 bg-indigo-100 opacity-30 rounded-full"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm">
                                    <i class="fas fa-palette text-white text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Manajemen Konten Website</h2>
                        </div>
                        <a href="<?php echo e(route('admin.analytics')); ?>" class="text-sm text-blue-600 hover:text-blue-700 flex items-center group bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                            <span>Data Analitik</span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <a href="<?php echo e(route('admin.hero.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-image text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Hero Banner</p>
                            <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full mt-2">Halaman Utama</span>
                        </a>
                        <a href="<?php echo e(route('admin.jurusan.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-graduation-cap text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Program Keahlian</p>
                            <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full mt-2">5 Jurusan</span>
                        </a>
                        <a href="<?php echo e(route('admin.keunggulan.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-yellow-100 to-yellow-200 rounded-xl flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-star text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Keunggulan</p>
                            <span class="bg-yellow-100 text-yellow-600 text-xs px-2 py-0.5 rounded-full mt-2">Fitur Sekolah</span>
                        </a>
                        <a href="<?php echo e(route('admin.agenda.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Agenda</p>
                            <span class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded-full mt-2">Kalender Akademik</span>
                        </a>
                        <a href="<?php echo e(route('admin.galeri.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-images text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Galeri</p>
                            <span class="bg-purple-100 text-purple-600 text-xs px-2 py-0.5 rounded-full mt-2">Foto Kegiatan</span>
                        </a>
                        <a href="<?php echo e(route('admin.berita.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-newspaper text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Berita</p>
                            <span class="bg-indigo-100 text-indigo-600 text-xs px-2 py-0.5 rounded-full mt-2">Artikel Berita</span>
                        </a>
                        <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-red-100 to-red-200 rounded-xl flex items-center justify-center text-red-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Pengumuman</p>
                            <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full mt-2">Informasi Penting</span>
                        </a>
                        <a href="<?php echo e(route('admin.matapelajaran.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-blue-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-blue-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-book text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-blue-700">Mata Pelajaran</p>
                            <span class="bg-amber-100 text-amber-600 text-xs px-2 py-0.5 rounded-full mt-2">Kurikulum</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Academic Management -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-transparent opacity-50 group-hover:opacity-70 transition-opacity"></div>
                <div class="absolute -right-12 -top-12 h-48 w-48 bg-emerald-100 opacity-30 rounded-full"></div>
                <div class="absolute -left-20 -bottom-20 h-56 w-56 bg-teal-100 opacity-30 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl shadow-sm">
                                <i class="fas fa-graduation-cap text-white text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Manajemen Akademik</h2>
                        </div>
                        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="text-sm text-emerald-600 hover:text-emerald-700 flex items-center group bg-emerald-50 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-colors">
                            <span>Data Jadwal</span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-calendar-week text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Jadwal Pelajaran</p>
                            <span class="bg-emerald-100 text-emerald-600 text-xs px-2 py-0.5 rounded-full mt-2">
                                <?php
                                    $totalJadwal = JadwalPelajaran::where('is_active', true)->count();
                                ?>
                                <?php echo e($totalJadwal); ?> Jadwal
                            </span>
                        </a>
                        <a href="<?php echo e(route('admin.jadwal.by-class')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Jadwal per Kelas</p>
                            <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full mt-2"><?php echo e($totalKelas); ?> Kelas</span>
                        </a>
                        <a href="<?php echo e(route('admin.jadwal.by-teacher')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-chalkboard-teacher text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Jadwal per Guru</p>
                            <span class="bg-green-100 text-green-600 text-xs px-2 py-0.5 rounded-full mt-2"><?php echo e($totalGuru); ?> Guru</span>
                        </a>
                        <a href="<?php echo e(route('admin.kelas.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-school text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Data Kelas</p>
                            <span class="bg-purple-100 text-purple-600 text-xs px-2 py-0.5 rounded-full mt-2">Manajemen Kelas</span>
                        </a>
                        <a href="<?php echo e(route('admin.guru.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-orange-100 to-orange-200 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-user-tie text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Data Guru</p>
                            <span class="bg-orange-100 text-orange-600 text-xs px-2 py-0.5 rounded-full mt-2">Manajemen Guru</span>
                        </a>
                        <a href="<?php echo e(route('admin.siswa.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-user-graduate text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Data Siswa</p>
                            <span class="bg-indigo-100 text-indigo-600 text-xs px-2 py-0.5 rounded-full mt-2"><?php echo e(number_format($totalSiswa)); ?> Siswa</span>
                        </a>
                        <a href="<?php echo e(route('admin.jadwal.create-table')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-pink-100 to-pink-200 rounded-xl flex items-center justify-center text-pink-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-plus-circle text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Tambah Jadwal</p>
                            <span class="bg-pink-100 text-pink-600 text-xs px-2 py-0.5 rounded-full mt-2">Quick Add</span>
                        </a>
                        <a href="<?php echo e(route('admin.jadwal.index')); ?>" class="relative flex flex-col items-center justify-center p-5 bg-white rounded-xl hover:bg-emerald-50 transition-colors shadow-sm hover:shadow-md border border-gray-200 hover:border-emerald-200 group">
                            <div class="absolute w-full h-1 bg-gradient-to-r from-emerald-400 to-emerald-600 top-0 left-0 rounded-t-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="h-14 w-14 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center text-cyan-600 group-hover:scale-110 transition-transform mb-3 shadow-sm">
                                <i class="fas fa-bell text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-center text-gray-700 group-hover:text-emerald-700">Bel Sekolah</p>
                            <span class="bg-cyan-100 text-cyan-600 text-xs px-2 py-0.5 rounded-full mt-2">Pengaturan Bel</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- School Bell Management -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6 relative overflow-hidden group hover:shadow-md transition-all">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 to-transparent opacity-50 group-hover:opacity-70 transition-opacity"></div>
                <div class="absolute -right-12 -top-12 h-48 w-48 bg-cyan-100 opacity-30 rounded-full"></div>
                <div class="absolute -left-20 -bottom-20 h-56 w-56 bg-blue-100 opacity-30 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-gradient-to-r from-cyan-500 to-cyan-600 rounded-xl shadow-sm">
                                <i class="fas fa-bell text-white text-lg"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800">Manajemen Bel Sekolah</h2>
                        </div>
                        <a href="<?php echo e(route('admin.bel.index')); ?>" class="text-sm text-cyan-600 hover:text-cyan-700 flex items-center group bg-cyan-50 px-3 py-1.5 rounded-lg hover:bg-cyan-100 transition-colors">
                            <span>Pengaturan Detail</span>
                            <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    
                    <!-- Current Bell Schedule -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Today's Bell Schedule -->
                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 p-4 rounded-xl border border-cyan-100">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-calendar-day text-cyan-500 mr-2"></i>
                                    Jadwal Bel Hari Ini
                                </h3>
                                <span class="bg-white text-cyan-600 px-3 py-1 rounded-full text-xs font-medium shadow-sm">
                                    <?php
                                        $englishDay = now()->format('l');
                                        $indonesianDays = [
                                            'Sunday' => 'Minggu',
                                            'Monday' => 'Senin',
                                            'Tuesday' => 'Selasa',
                                            'Wednesday' => 'Rabu',
                                            'Thursday' => 'Kamis',
                                            'Friday' => 'Jumat',
                                            'Saturday' => 'Sabtu',
                                        ];
                                    ?>
                                    <?php echo e($indonesianDays[$englishDay] ?? $englishDay); ?>

                                </span>
                            </div>
                            <div class="space-y-3 max-h-80 overflow-auto pr-2 scrollbar-thin">
                                <div class="bg-white p-3 rounded-lg shadow-sm flex items-center justify-between border-l-4 border-cyan-500">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-<?php echo e($todayBells->count() > 0 ? str_replace('#', '', $todayBells[0]->kode_warna ?? 'cyan') : 'cyan'); ?>-100 h-10 w-10 rounded-lg flex items-center justify-center text-<?php echo e($todayBells->count() > 0 ? str_replace('#', '', $todayBells[0]->kode_warna ?? 'cyan') : 'cyan'); ?>-600">
                                            <i class="fas fa-<?php echo e($todayBells->count() > 0 ? $todayBells[0]->ikon ?? 'bell' : 'bell'); ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm"><?php echo e($todayBells->count() > 0 ? $todayBells[0]->nama : 'Bel Masuk'); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 0 ? ($todayBells[0]->deskripsi ?? 'Jam Pertama') : 'Jam Pertama'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800"><?php echo e($todayBells->count() > 0 ? date('H:i', strtotime($todayBells[0]->waktu)) : '07:00'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 0 ? ($todayBells[0]->aktif ? 'Aktif' : 'Non-aktif') : 'Aktif'); ?></p>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-3 rounded-lg shadow-sm flex items-center justify-between border-l-4 border-amber-500">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-<?php echo e($todayBells->count() > 1 ? str_replace('#', '', $todayBells[1]->kode_warna ?? 'amber') : 'amber'); ?>-100 h-10 w-10 rounded-lg flex items-center justify-center text-<?php echo e($todayBells->count() > 1 ? str_replace('#', '', $todayBells[1]->kode_warna ?? 'amber') : 'amber'); ?>-600">
                                            <i class="fas fa-<?php echo e($todayBells->count() > 1 ? $todayBells[1]->ikon ?? 'bell' : 'bell'); ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm"><?php echo e($todayBells->count() > 1 ? $todayBells[1]->nama : 'Bel Istirahat'); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 1 ? ($todayBells[1]->deskripsi ?? 'Istirahat Pertama') : 'Istirahat Pertama'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800"><?php echo e($todayBells->count() > 1 ? date('H:i', strtotime($todayBells[1]->waktu)) : '09:30'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 1 ? ($todayBells[1]->aktif ? 'Aktif' : 'Non-aktif') : 'Aktif'); ?></p>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-3 rounded-lg shadow-sm flex items-center justify-between border-l-4 border-cyan-500">
                                    <div class="flex items-center space-x-2">
                                        <div class="bg-<?php echo e($todayBells->count() > 2 ? str_replace('#', '', $todayBells[2]->kode_warna ?? 'cyan') : 'cyan'); ?>-100 h-10 w-10 rounded-lg flex items-center justify-center text-<?php echo e($todayBells->count() > 2 ? str_replace('#', '', $todayBells[2]->kode_warna ?? 'cyan') : 'cyan'); ?>-600">
                                            <i class="fas fa-<?php echo e($todayBells->count() > 2 ? $todayBells[2]->ikon ?? 'bell' : 'bell'); ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm"><?php echo e($todayBells->count() > 2 ? $todayBells[2]->nama : 'Bel Masuk'); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 2 ? ($todayBells[2]->deskripsi ?? 'Setelah Istirahat') : 'Setelah Istirahat'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800"><?php echo e($todayBells->count() > 2 ? date('H:i', strtotime($todayBells[2]->waktu)) : '10:00'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 2 ? ($todayBells[2]->aktif ? 'Aktif' : 'Non-aktif') : 'Aktif'); ?></p>
                                    </div>
                                </div>
                                
                                <div class="bg-white p-3 rounded-lg shadow-sm flex items-center justify-between border-l-4 border-red-500">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-<?php echo e($todayBells->count() > 3 ? str_replace('#', '', $todayBells[3]->kode_warna ?? 'red') : 'red'); ?>-100 h-10 w-10 rounded-lg flex items-center justify-center text-<?php echo e($todayBells->count() > 3 ? str_replace('#', '', $todayBells[3]->kode_warna ?? 'red') : 'red'); ?>-600">
                                            <i class="fas fa-<?php echo e($todayBells->count() > 3 ? $todayBells[3]->ikon ?? 'bell' : 'bell'); ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-sm"><?php echo e($todayBells->count() > 3 ? $todayBells[3]->nama : 'Bel Pulang'); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 3 ? ($todayBells[3]->deskripsi ?? 'Akhir KBM') : 'Akhir KBM'); ?></p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-800"><?php echo e($todayBells->count() > 3 ? date('H:i', strtotime($todayBells[3]->waktu)) : '14:30'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo e($todayBells->count() > 3 ? ($todayBells[3]->aktif ? 'Aktif' : 'Non-aktif') : 'Aktif'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Bell Control -->
                        <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-4 rounded-xl border border-blue-100">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-sliders-h text-blue-500 mr-2"></i>
                                    Kontrol Bel Manual
                                </h3>
                                <span class="bg-white text-blue-600 px-3 py-1 rounded-full text-xs font-medium shadow-sm">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo e(now()->format('H:i')); ?>

                                </span>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-3">
                                    <button class="bg-white p-4 rounded-lg shadow-sm flex flex-col items-center justify-center border border-gray-200 hover:bg-cyan-50 hover:border-cyan-200 transition-colors group">
                                        <div class="bg-cyan-100 h-12 w-12 rounded-full flex items-center justify-center text-cyan-600 mb-2 group-hover:bg-cyan-200 transition-colors">
                                            <i class="fas fa-bell text-xl"></i>
                                        </div>
                                        <p class="font-medium text-sm text-gray-800">Bel Masuk</p>
                                    </button>
                                    
                                    <button class="bg-white p-4 rounded-lg shadow-sm flex flex-col items-center justify-center border border-gray-200 hover:bg-amber-50 hover:border-amber-200 transition-colors group">
                                        <div class="bg-amber-100 h-12 w-12 rounded-full flex items-center justify-center text-amber-600 mb-2 group-hover:bg-amber-200 transition-colors">
                                            <i class="fas fa-coffee text-xl"></i>
                                        </div>
                                        <p class="font-medium text-sm text-gray-800">Bel Istirahat</p>
                                    </button>
                                    
                                    <button class="bg-white p-4 rounded-lg shadow-sm flex flex-col items-center justify-center border border-gray-200 hover:bg-purple-50 hover:border-purple-200 transition-colors group">
                                        <div class="bg-purple-100 h-12 w-12 rounded-full flex items-center justify-center text-purple-600 mb-2 group-hover:bg-purple-200 transition-colors">
                                            <i class="fas fa-book-open text-xl"></i>
                                        </div>
                                        <p class="font-medium text-sm text-gray-800">Bel Ujian</p>
                                    </button>
                                    
                                    <button class="bg-white p-4 rounded-lg shadow-sm flex flex-col items-center justify-center border border-gray-200 hover:bg-red-50 hover:border-red-200 transition-colors group">
                                        <div class="bg-red-100 h-12 w-12 rounded-full flex items-center justify-center text-red-600 mb-2 group-hover:bg-red-200 transition-colors">
                                            <i class="fas fa-home text-xl"></i>
                                        </div>
                                        <p class="font-medium text-sm text-gray-800">Bel Pulang</p>
                                    </button>
                                </div>
                                
                                <div class="mt-4">
                                    <a href="<?php echo e(route('admin.bel.index')); ?>" class="bg-gradient-to-r from-blue-500 to-cyan-500 text-white w-full py-3 rounded-lg hover:shadow-md transition-all flex items-center justify-center font-medium">
                                        <i class="fas fa-cog mr-2"></i>
                                        Pengaturan Jadwal Bel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities and Calendar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden hover:shadow-md transition-all">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative z-10">
                        <div class="flex justify-between items-center mb-5">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-sm">
                                    <i class="fas fa-history text-white"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Aktivitas Website Terbaru</h2>
                            </div>
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-700 flex items-center group bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors">
                                <span>Lihat Semua</span>
                                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                        <div class="space-y-4">
                            <?php $__empty_1 = true; $__currentLoopData = $activities->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-0 hover:bg-gray-50 rounded-lg transition-colors border border-transparent hover:border-gray-200">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="h-10 w-10 rounded-full bg-<?php echo e($activity['color']); ?>-100 flex items-center justify-center">
                                            <i class="fas fa-<?php echo e($activity['icon']); ?> text-<?php echo e($activity['color']); ?>-600 text-lg"></i>
                                        </span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-gray-900">
                                                <?php echo e($activity['title']); ?>

                                            </p>
                                            <div class="text-sm text-gray-500">
                                                <?php echo e($activity['date']->diffForHumans()); ?>

                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            <?php switch($activity['type']):
                                                case ('berita'): ?>
                                                    Berita baru ditambahkan
                                                    <?php break; ?>
                                                <?php case ('pengumuman'): ?>
                                                    Pengumuman baru diterbitkan
                                                    <?php break; ?>
                                                <?php case ('agenda'): ?>
                                                    Agenda baru ditambahkan
                                                    <?php break; ?>
                                                <?php case ('galeri'): ?>
                                                    Foto baru ditambahkan ke galeri
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    Aktivitas baru
                                            <?php endswitch; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <a href="<?php echo e($activity['url']); ?>" class="text-gray-400 hover:text-<?php echo e($activity['color']); ?>-500">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-0 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="far fa-clock text-3xl text-gray-400"></i>
                                </div>
                                <p class="font-medium text-lg mb-1">Belum ada aktivitas terbaru</p>
                                <p class="text-sm text-gray-400">Aktivitas akan muncul saat ada perubahan pada website</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden hover:shadow-md transition-all">
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-green-100 to-transparent rounded-tr-full opacity-50"></div>
                    <div class="relative z-10">
                        <!-- Header Section -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-3">
                                <div class="p-2.5 bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-sm">
                                    <i class="fas fa-calendar-check text-white"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Agenda Mendatang</h2>
                            </div>
                            <a href="<?php echo e(route('admin.agenda.create')); ?>" class="text-green-600 hover:text-green-700 bg-green-100 p-2 rounded-lg group hover:bg-green-200 transition-colors">
                                <i class="fas fa-plus"></i>
                            </a>
                        </div>

                        <!-- Calendar Header -->
                        <div class="flex items-center space-x-4 mb-6 bg-gradient-to-r from-green-500 to-green-600 p-4 rounded-xl text-white">
                            <div class="w-16 h-16 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center text-white font-bold border border-white/30">
                                <?php echo e(date('d')); ?>

                            </div>
                            <div>
                                <p class="text-xl font-medium text-white"><?php echo e(date('F Y')); ?></p>
                                <p class="text-sm text-green-100 mt-1"><?php echo e(date('l')); ?></p>
                            </div>
                        </div>

                        <!-- Agenda List -->
                        <div class="space-y-3">
                            <?php $__empty_1 = true; $__currentLoopData = $agenda->take (4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded-xl transition-colors border border-transparent hover:border-gray-200 group">
                                <div class="h-16 w-16 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex flex-col items-center justify-center text-green-600 font-medium group-hover:scale-110 transition-transform shadow-sm">
                                    <span class="text-2xl font-bold"><?php echo e($event->tanggal_mulai->format('d')); ?></span>
                                    <span class="text-sm"><?php echo e($event->tanggal_mulai->format('M')); ?></span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <p class="font-medium text-sm text-gray-800"><?php echo e($event->judul); ?></p>
                                        <a href="<?php echo e(route('admin.agenda.edit', $event)); ?>" class="text-gray-400 hover:text-green-500 transition-colors">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                    </div>
                                    <div class="flex flex-col text-xs text-gray-500 mt-1 space-y-1">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar mr-2 text-green-500"></i>
                                            <span><?php echo e($event->tanggal_mulai->format('d F Y')); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-clock mr-2 text-green-500"></i>
                                            <span><?php echo e($event->tanggal_mulai->format('H:i')); ?> - <?php echo e($event->tanggal_selesai ? $event->tanggal_selesai->format('H:i') : 'Selesai'); ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <i class="fas fa-map-marker-alt mr-2 text-green-500"></i>
                                            <span><?php echo e($event->lokasi); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-10 text-gray-500">
                                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-calendar-alt text-3xl text-green-500"></i>
                                </div>
                                <p class="font-medium text-lg mb-2">Belum ada agenda</p>
                                <p class="text-sm text-gray-400">Klik tombol + untuk menambahkan agenda baru</p>
                            </div>
                            <?php endif; ?>

                            <a href="<?php echo e(route('admin.agenda.index')); ?>" class="block w-full py-3.5 mt-6 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:shadow-md transition-all text-sm text-center font-medium">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                Lihat Semua Agenda
                            </a>
                        </div>
                    </div>
                </div>
            </div>            
            

                <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100 hover:shadow-md transition-all">
                    <div class="flex justify-between items-center mb-5">
                        <div class="flex items-center space-x-3">
                            <div class="p-2.5 bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-sm">
                                <i class="fas fa-bullhorn text-white"></i>
                            </div>
                            <h2 class="text-lg font-bold text-gray-800">Pengumuman Terbaru</h2>
                        </div>
                        <div class="flex space-x-2">
                            <a href="<?php echo e(route('admin.pengumuman.create')); ?>" class="text-blue-600 hover:text-blue-700 bg-blue-100 p-2 rounded-lg group hover:bg-blue-200 transition-colors">
                                <i class="fas fa-plus"></i>
                            </a>
                            <a href="<?php echo e(route('admin.pengumuman.index')); ?>" class="text-gray-600 hover:text-gray-700 bg-gray-100 p-2 rounded-lg group hover:bg-gray-200 transition-colors">
                                <i class="fas fa-list"></i>
                            </a>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $pengumuman; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-l-4 border-red-500 pl-4 hover:bg-gray-50 p-3 rounded-r-lg transition-colors group hover:shadow-sm">
                            <p class="font-medium text-gray-800 group-hover:text-red-700 transition-colors"><?php echo e($announcement->judul); ?></p>
                            <div class="text-sm text-gray-500 mt-1 mb-2 line-clamp-2">
                                <?php echo e(Str::limit($announcement->isi, 100)); ?>

                            </div>
                            <div class="flex justify-between text-xs mt-2">
                                <div class="text-gray-500 flex items-center space-x-3">
                                    <span class="flex items-center">
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        <?php echo e($announcement->tanggal_mulai->format('d M Y')); ?>

                                    </span>
                                    <span class="flex items-center">
                                        <i class="far fa-user mr-1"></i>
                                        <?php echo e($announcement->author->name); ?>

                                    </span>
                                </div>
                                <span class="bg-red-100 text-red-600 px-2 py-0.5 rounded-full capitalize"><?php echo e($announcement->target_role); ?></span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($pengumuman->isEmpty()): ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-bullhorn text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada pengumuman</p>
                        </div>
                        <?php endif; ?>

                        <a href="<?php echo e(route('admin.pengumuman.create')); ?>" class="block w-full py-3 mt-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl hover:shadow-md transition-all text-sm text-center font-medium">
                            <i class="fas fa-plus mr-2"></i>
                            Buat Pengumuman Baru
                        </a>
                    </div>
                </div>
            </div>
        </main>                
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .bg-pattern {
        background-color: #f8fafc;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23bfdbfe' fill-opacity='0.4'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h
        background-color: #f8fafc;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23bfdbfe' fill-opacity='0.4'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23bfdbfe' fill-opacity='0.4'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    /* Animation untuk welcome banner */
    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(8deg); }
        75% { transform: rotate(-8deg); }
    }
    
    .animate-wave {
        animation: wave 2.5s ease-in-out infinite;
        transform-origin: 70% 70%;
    }
    
    /* Hover effects */
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.03);
    }
    
    /* Card styling */
    .card-gradient {
        background: linear-gradient(145deg, #ffffff, #f3f4f6);
        box-shadow: 5px 5px 10px #e1e4e8, -5px -5px 10px #ffffff;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Visitor Statistics Chart
        const visitorOptions = {
            chart: {
                type: 'area',
                height: 240,
                toolbar: {show: false},
                zoom: {enabled: false},
                fontFamily: 'Outfit, sans-serif',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                },
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 0,
                    blur: 4,
                    opacity: 0.1
                }
            },
            colors: ["#3b82f6"],
            stroke: {curve: "smooth", width: 3},
            dataLabels: {enabled: false},
            grid: {
                borderColor: "#f3f4f6",
                strokeDashArray: 5,
                xaxis: {
                    lines: {show: true}
                },
                yaxis: {
                    lines: {show: true}
                }
            },
            xaxis: {
                categories: ["Sen", "Sel", "Rab", "Kam", "Jum", "Sab", "Min"],
                labels: {
                    style: {
                        colors: "#64748b", 
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: 500
                    }
                },
                axisBorder: {show: false},
                axisTicks: {show: false}
            },
            yaxis: {
                labels: {
                    style: {
                        colors: "#64748b", 
                        fontFamily: 'Outfit, sans-serif',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return value.toFixed(0);
                    }
                }
            },
            tooltip: {
                theme: "light",
                style: {
                    fontFamily: 'Outfit, sans-serif'
                },
                y: {
                    formatter: function(value) {
                        return value + " pengunjung";
                    }
                },
                marker: {show: true}
            },
            series: [{
                name: "Pengunjung",
                data: [320, 420, 395, 450, 380, 290, 410]
            }],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.2,
                    stops: [0, 90, 100]
                }
            },
            markers: {
                size: 5,
                colors: ["#3b82f6"],
                strokeWidth: 0,
                hover: {
                    size: 7
                }
            }
        };
        
        // Error handling untuk ApexCharts
        const visitorChartElement = document.querySelector('#visitorChart');
        if (visitorChartElement) {
            try {
                const visitorChart = new ApexCharts(visitorChartElement, visitorOptions);
                visitorChart.render();
            } catch (error) {
                console.warn('ApexCharts failed to render:', error);
                // Fallback: hide chart container or show message
                if (visitorChartElement.parentElement) {
                    visitorChartElement.parentElement.innerHTML = '<div class="text-center text-gray-500 p-4">Chart not available</div>';
                }
            }
        } else {
            console.warn('Chart element #visitorChart not found');
        }
    });
</script>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>