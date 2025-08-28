

<?php $__env->startSection('title', 'Detail Guru'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full px-3 py-4">
    <!-- Breadcrumb and actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="<?php echo e(route('admin.guru.index')); ?>" class="hover:text-blue-600">Guru</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <span>Detail</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-chalkboard-teacher text-blue-500 mr-3"></i> Detail Guru
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.guru.edit', $guru)); ?>" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="<?php echo e(route('admin.guru.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Column 1: Profile Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-blue-100">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32 relative">
                <?php if($guru->is_wali_kelas): ?>
                    <div class="absolute top-4 right-4">
                        <span class="px-3 py-1 rounded-full bg-white text-blue-700 text-xs font-semibold">
                            <i class="fas fa-user-shield mr-1"></i> Wali Kelas
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="relative px-6 pb-6">
                <!-- Profile Photo -->
                <div class="absolute -top-16 left-1/2 transform -translate-x-1/2">
                    <div class="ring-4 ring-white rounded-full">
                        <img src="<?php echo e($guru->foto_url); ?>" alt="<?php echo e($guru->nama); ?>" 
                            class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-md">
                    </div>
                </div>
                
                <!-- Teacher Identity -->
                <div class="pt-20 text-center">
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo e($guru->nama); ?></h2>
                    <?php if($guru->nip): ?>
                        <p class="text-blue-600 font-medium mb-2">NIP: <?php echo e($guru->nip); ?></p>
                    <?php else: ?>
                        <p class="text-gray-400 font-medium mb-2">NIP: Belum diisi</p>
                    <?php endif; ?>
                    
                    <!-- Status -->
                    <div class="inline-flex mb-4 mt-1">
                        <?php if($guru->is_active): ?>
                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i> Aktif
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium border border-red-200">
                                <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Contact Buttons -->
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <a href="mailto:<?php echo e($guru->email); ?>" class="flex items-center justify-center py-2 px-3 bg-blue-50 rounded-lg text-blue-700 hover:bg-blue-100 transition-all">
                            <i class="fas fa-envelope mr-2"></i> Email
                        </a>
                        <a href="tel:<?php echo e($guru->no_hp); ?>" class="flex items-center justify-center py-2 px-3 bg-green-50 rounded-lg text-green-700 hover:bg-green-100 transition-all">
                            <i class="fas fa-phone mr-2"></i> Telepon
                        </a>
                    </div>
                    
                    <!-- Delete Action -->
                    <form action="<?php echo e(route('admin.guru.destroy', $guru)); ?>" method="POST" 
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru <?php echo e($guru->nama); ?>?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 px-4 py-2 rounded-lg flex items-center justify-center transition-all border border-red-200">
                            <i class="fas fa-trash mr-2"></i> Hapus Data Guru
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Column 2-3: Information cards -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i> Informasi Dasar
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="font-medium text-gray-800"><?php echo e($guru->nama); ?></p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">NIP</p>
                                <p class="font-medium text-gray-800"><?php echo e($guru->nip ?: 'Belum diisi'); ?></p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-800 break-all"><?php echo e($guru->email); ?></p>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Jenis Kelamin</p>
                                <p class="font-medium text-gray-800"><?php echo e($guru->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">No. HP</p>
                                <p class="font-medium text-gray-800"><?php echo e($guru->no_hp); ?></p>
                            </div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Status Wali Kelas</p>
                                <p class="font-medium text-gray-800">
                                    <?php if($guru->is_wali_kelas): ?>
                                        <span class="text-blue-600"><i class="fas fa-check-circle mr-1"></i> Wali Kelas</span>
                                    <?php else: ?>
                                        <span class="text-gray-600"><i class="fas fa-times-circle mr-1"></i> Bukan Wali Kelas</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Alamat -->
                    <div class="border-t border-gray-100 pt-4 mt-2">
                        <p class="text-sm text-gray-500 mb-1">Alamat</p>
                        <p class="text-gray-800"><?php echo e($guru->alamat ?: 'Tidak ada alamat yang tercatat.'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Account Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800">
                        <i class="fas fa-user-shield text-blue-500 mr-2"></i> Informasi Akun
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Status Akun</p>
                                <div class="mt-1">
                                    <?php if($guru->is_active): ?>
                                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                            <i class="fas fa-check-circle mr-1"></i> Aktif
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-medium">
                                            <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Reset Password</p>
                                <button type="button" class="mt-1 px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm">
                                    <i class="fas fa-key mr-1"></i> Reset Password
                                </button>
                            </div>
                        </div>
                        <div>
                            <div class="mb-4">
                                <p class="text-sm text-gray-500">Terdaftar Pada</p>
                                <p class="font-medium text-gray-800">
                                    <i class="far fa-calendar-alt text-blue-500 mr-1"></i>
                                    <?php echo e($guru->created_at->format('d M Y H:i')); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Terakhir Diperbarui</p>
                                <p class="font-medium text-gray-800">
                                    <i class="far fa-clock text-blue-500 mr-1"></i>
                                    <?php echo e($guru->updated_at->format('d M Y H:i')); ?>

                                </p>
                            </div>
                        </div>                    </div>
                </div>
            </div>
            
            <!-- Teaching Information (can be expanded in the future) -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-semibold text-gray-800">
                        <i class="fas fa-chalkboard text-blue-500 mr-2"></i> Informasi Mengajar
                    </h3>
                </div>
                <div class="p-6">
                    <!-- Can add fields like assigned subjects, classes taught, etc. -->
                    
                    <?php if($guru->is_wali_kelas): ?>
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-blue-700 mb-3">Kelas yang Dibimbing</h4>
                            
                            <?php 
                                // Get kelas yang dibimbing by this guru as wali kelas
                                $walikelas = $guru->is_wali_kelas 
                                    ? App\Models\Kelas::where('wali_kelas', $guru->id)
                                                      ->with('jurusan')
                                                      ->first() 
                                    : null; 
                            ?>
                            
                            <?php if(!empty($walikelas)): ?>
                                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 rounded-lg p-3">
                                            <i class="fas fa-school text-blue-600 text-xl"></i>
                                        </div>
                                        <div class="ml-4">
                                            <h5 class="font-medium text-gray-800"><?php echo e($walikelas->nama_kelas ?? 'Nama kelas tidak tersedia'); ?></h5>
                                            <p class="text-sm text-gray-600">Jurusan: <?php echo e($walikelas->jurusan->nama_jurusan ?? $walikelas->jurusan->nama ?? 'Jurusan tidak tersedia'); ?></p>
                                            <p class="text-sm text-gray-600">Tahun Ajaran: <?php echo e($walikelas->tahun_ajaran ?? 'Tidak diketahui'); ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 flex items-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                                    <span class="text-gray-700">Guru ini ditandai sebagai Wali Kelas namun belum memiliki kelas yang dibimbing.</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 mb-3">Mata Pelajaran yang Diampu</h4>
                        
                        <?php 
                            // Get the actual subjects data from jadwal_pelajaran (assignments only, not scheduled items)
                            $jadwalPelajaran = App\Models\JadwalPelajaran::where('guru_id', $guru->id)
                                                                      ->with(['mapel', 'kelas', 'kelas.jurusan'])
                                                                      ->whereHas('mapel') // Only get jadwal with valid mapel
                                                                      ->whereHas('kelas') // Only get jadwal with valid kelas
                                                                      ->assignments() // Only get assignment entries, not scheduled
                                                                      ->get();
                            $mapels = $jadwalPelajaran->groupBy('mapel.id')->filter(function($group) {
                                return $group->first() && $group->first()->mapel; // Filter out null mapel groups
                            });
                        ?>
                        
                        <?php if($mapels->count() > 0): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <?php $__currentLoopData = $mapels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapelId => $jadwalGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $firstJadwal = $jadwalGroup->first();
                                        $mapel = $firstJadwal ? $firstJadwal->mapel : null;
                                        $kelasNames = $jadwalGroup->filter(function($jadwal) {
                                            return $jadwal->kelas && $jadwal->kelas->nama_kelas;
                                        })->pluck('kelas.nama_kelas')->unique()->implode(', ');
                                        $kelasNames = $kelasNames ?: 'Kelas tidak tersedia';
                                    ?>
                                    <?php if($mapel): ?>
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="flex items-center">
                                            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                                <i class="fas fa-book text-blue-700"></i>
                                            </div>
                                            <div>
                                                <h5 class="font-medium text-gray-800"><?php echo e($mapel->nama ?? 'Mata pelajaran tidak tersedia'); ?></h5>
                                                <p class="text-xs text-gray-600">Kelas: <?php echo e($kelasNames); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo e($jadwalGroup->count()); ?> jadwal</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                <p class="text-gray-600">Belum ada mata pelajaran yang diampu.</p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-4 flex justify-end">
                            <a href="<?php echo e(route('admin.guru.assign-subjects', $guru)); ?>" 
                               class="bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-700 px-4 py-2 rounded-lg flex items-center text-sm transition-all border border-blue-200">
                                <i class="fas fa-plus-circle mr-2"></i> Kelola Mata Pelajaran
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\guru\show.blade.php ENDPATH**/ ?>