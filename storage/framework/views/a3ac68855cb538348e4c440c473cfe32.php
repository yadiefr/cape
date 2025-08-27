

<?php $__env->startSection('title', 'Daftar Kelas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .kelas-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }
    
    .kelas-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .kelas-card.wali-kelas {
        border-left-color: #10b981;
        background: linear-gradient(135deg, #ecfdf5 0%, #ffffff 100%);
    }
    
    .kelas-card.mengajar {
        border-left-color: #3b82f6;
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
    }
    
    .badge-wali {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    
    .badge-mengajar {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Kelas</h1>
                <p class="mt-2 text-sm text-gray-600">Kelola kelas yang Anda ajar dan wali kelas</p>
            </div>
            <div class="mt-4 sm:mt-0 flex items-center space-x-3">
                <div class="flex items-center px-3 py-2 bg-blue-50 rounded-lg">
                    <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>
                    <span class="text-sm font-medium text-blue-700"><?php echo e($kelasDiajar->count()); ?> Kelas Diajar</span>
                </div>
                <?php if($kelasWali->count() > 0): ?>
                <div class="flex items-center px-3 py-2 bg-green-50 rounded-lg">
                    <i class="fas fa-user-tie text-green-500 mr-2"></i>
                    <span class="text-sm font-medium text-green-700"><?php echo e($kelasWali->count()); ?> Wali Kelas</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Kelas Wali (if any) -->
    <?php if($kelasWali->count() > 0): ?>
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Kelas Wali</h2>
            <span class="ml-2 px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                <?php echo e($kelasWali->count()); ?> kelas
            </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $kelasWali; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="kelas-card wali-kelas bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?php echo e($kelas->nama_kelas); ?></h3>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($kelas->jurusan->nama_jurusan ?? 'Jurusan tidak ditemukan'); ?></p>
                    </div>
                    <span class="badge-wali px-3 py-1 rounded-full text-xs font-medium">
                        <i class="fas fa-crown mr-1"></i>
                        Wali Kelas
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($kelas->siswa->count()); ?></div>
                        <div class="text-xs text-gray-600">Siswa</div>
                    </div>
                    <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900"><?php echo e($kelas->tingkat); ?></div>
                        <div class="text-xs text-gray-600">Tingkat</div>
                    </div>
                </div>
                
                <div class="flex space-x-2">
                    <a href="<?php echo e(route('guru.kelas.show', $kelas->id)); ?>" 
                       class="flex-1 bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-eye mr-1"></i>
                        Lihat Detail
                    </a>
                    <a href="<?php echo e(route('guru.wali-kelas.dashboard')); ?>" 
                       class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg transition-colors text-sm font-medium">
                        <i class="fas fa-tachometer-alt mr-1"></i>
                        Dashboard Wali
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Kelas yang Diajar -->
    <div>
        <div class="flex items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Kelas yang Diajar</h2>
            <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                <?php echo e($kelasDiajar->count()); ?> kelas
            </span>
        </div>
        
        <?php if($kelasDiajar->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $kelasDiajar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="kelas-card mengajar bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900"><?php echo e($kelas->nama_kelas); ?></h3>
                        <p class="text-sm text-gray-600 mt-1"><?php echo e($kelas->jurusan->nama_jurusan ?? 'Jurusan tidak ditemukan'); ?></p>
                    </div>
                    <div class="flex flex-col items-end space-y-1">
                        <span class="badge-mengajar px-3 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Mengajar
                        </span>
                        <?php if(isset($statistikKelas[$kelas->id]) && $statistikKelas[$kelas->id]['is_wali']): ?>
                        <span class="badge-wali px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-crown mr-1"></i>
                            Wali
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                        <div class="text-xl font-bold text-gray-900"><?php echo e($statistikKelas[$kelas->id]['total_siswa'] ?? 0); ?></div>
                        <div class="text-xs text-gray-600">Siswa</div>
                    </div>
                    <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                        <div class="text-xl font-bold text-gray-900"><?php echo e($statistikKelas[$kelas->id]['total_jadwal'] ?? 0); ?></div>
                        <div class="text-xs text-gray-600">Jadwal</div>
                    </div>
                    <div class="text-center p-3 bg-white bg-opacity-50 rounded-lg">
                        <div class="text-xl font-bold text-gray-900"><?php echo e($kelas->tingkat); ?></div>
                        <div class="text-xs text-gray-600">Tingkat</div>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <a href="<?php echo e(route('guru.kelas.show', $kelas->id)); ?>" 
                       class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded-lg transition-colors text-sm font-medium block">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail Kelas
                    </a>
                    
                    <div class="grid grid-cols-2 gap-2">
                        <a href="<?php echo e(route('guru.absensi.index')); ?>?kelas_id=<?php echo e($kelas->id); ?>" 
                           class="bg-green-50 hover:bg-green-100 text-green-700 text-center py-2 px-3 rounded-lg transition-colors text-xs font-medium">
                            <i class="fas fa-clipboard-check mr-1"></i>
                            Absensi
                        </a>
                        <a href="<?php echo e(route('guru.nilai.show', $kelas->id)); ?>" 
                           class="bg-purple-50 hover:bg-purple-100 text-purple-700 text-center py-2 px-3 rounded-lg transition-colors text-xs font-medium">
                            <i class="fas fa-star mr-1"></i>
                            Nilai
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <div class="max-w-md mx-auto">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-chalkboard-teacher text-gray-400 text-xl"></i>
                </div>
                <h3 class="text-sm font-medium text-gray-900 mb-2">Belum Ada Kelas</h3>
                <p class="text-sm text-gray-500 mb-4">Anda belum memiliki jadwal mengajar di kelas manapun.</p>
                <a href="<?php echo e(route('guru.jadwal.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Lihat Jadwal
                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Add any JavaScript functionality here if needed
    document.addEventListener('DOMContentLoaded', function() {
        // Animation for cards
        const cards = document.querySelectorAll('.kelas-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\kelas\index.blade.php ENDPATH**/ ?>