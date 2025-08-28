

<?php $__env->startSection('title', 'Materi & Tugas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Materi & Tugas</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Materi & Tugas</span>
                </li>
            </ul>
        </div>
    </div>

    
    <!-- Search and Filter Section -->
    <div class="mb-6">
        <form action="<?php echo e(route('siswa.materi.index')); ?>" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                               placeholder="Cari materi...">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="<?php echo e(route('siswa.materi.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Tab Navigation -->
    <div class="mb-6" x-data="{ 
        activeTab: window.location.hash === '#tugas' ? 'tugas' : 'materi' 
    }" x-init="
        // Update hash when tab changes
        $watch('activeTab', value => {
            window.location.hash = value;
        });
        
        // Listen for hash changes
        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.substring(1);
            if (hash === 'tugas' || hash === 'materi') {
                activeTab = hash;
            }
        });
    ">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b border-gray-200">
                <button @click="activeTab = 'materi'" 
                        :class="activeTab === 'materi' ? 'bg-blue-50 text-blue-600 border-b-2 border-blue-600' : 'text-gray-600 hover:text-blue-600'"
                        class="flex-1 px-6 py-4 text-sm font-medium transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-book mr-2"></i>
                    Materi Pembelajaran
                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-600 rounded-full"><?php echo e(count($materi)); ?></span>
                </button>
                <button @click="activeTab = 'tugas'" 
                        :class="activeTab === 'tugas' ? 'bg-green-50 text-green-600 border-b-2 border-green-600' : 'text-gray-600 hover:text-green-600'"
                        class="flex-1 px-6 py-4 text-sm font-medium transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-tasks mr-2"></i>
                    Tugas & Kuis
                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full"><?php echo e(count($tugas)); ?></span>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Materi Tab -->
                <div x-show="activeTab === 'materi'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-blue-100 text-blue-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        <?php echo e($item->mataPelajaran->nama ?? 'Mata Pelajaran'); ?>

                                    </div>
                                    <span class="text-xs text-gray-500">
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo e($item->judul); ?></h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo e($item->deskripsi); ?></p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                                        <?php echo e($item->guru->nama_lengkap ?? 'Guru'); ?>

                                    </div>
                                    <a href="<?php echo e(route('siswa.materi.show', $item->id)); ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full">
                            <div class="bg-white rounded-xl p-8 text-center border border-gray-100">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-lg mb-4">
                                    <i class="fas fa-book text-2xl text-blue-500"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Belum Ada Materi</h3>
                                <p class="text-gray-500">Belum ada materi yang tersedia untuk ditampilkan.</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tugas Tab -->
                <div x-show="activeTab === 'tugas'" x-transition class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $tugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-all duration-200">
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="bg-green-100 text-green-600 text-xs font-semibold px-2.5 py-1 rounded-lg">
                                        <?php echo e($item->mapel->nama ?? 'Mata Pelajaran'); ?>

                                    </div>
                                    <span class="text-xs text-gray-500">
                                        <?php echo e($item->created_at->diffForHumans()); ?>

                                    </span>
                                </div>

                                <h3 class="text-lg font-semibold text-gray-800 mb-2"><?php echo e($item->judul); ?></h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo e($item->deskripsi); ?></p>

                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user-tie mr-2 text-green-500"></i>
                                        <?php echo e($item->guru->nama_lengkap ?? 'Guru'); ?>

                                    </div>
                                    <?php if($item->deadline): ?>
                                    <div class="text-xs text-red-600 font-medium">
                                        <i class="fas fa-clock mr-1"></i>
                                        <?php echo e(\Carbon\Carbon::parse($item->deadline)->format('d M Y')); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="text-xs">
                                        <?php if($item->deadline): ?>
                                            <?php
                                                $deadline = \Carbon\Carbon::parse($item->deadline);
                                                $now = \Carbon\Carbon::now();
                                                $isOverdue = $now->gt($deadline);
                                                $isUrgent = $now->diffInDays($deadline) <= 2 && !$isOverdue;
                                            ?>
                                            <?php if($isOverdue): ?>
                                                <span class="px-2 py-1 bg-red-100 text-red-600 rounded">Terlambat</span>
                                            <?php elseif($isUrgent): ?>
                                                <span class="px-2 py-1 bg-yellow-100 text-yellow-600 rounded">Urgent</span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 bg-green-100 text-green-600 rounded">Aktif</span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded">Tanpa Deadline</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo e(route('siswa.tugas.show', $item->id)); ?>" 
                                       class="inline-flex items-center text-green-600 hover:text-green-700 text-sm font-medium">
                                        Lihat Detail <i class="fas fa-arrow-right ml-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full">
                            <div class="bg-white rounded-xl p-8 text-center border border-gray-100">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-50 rounded-lg mb-4">
                                    <i class="fas fa-tasks text-2xl text-green-500"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-1">Belum Ada Tugas</h3>
                                <p class="text-gray-500">Belum ada tugas yang tersedia untuk ditampilkan.</p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\materi\index.blade.php ENDPATH**/ ?>