

<?php $__env->startSection('title', 'Detail Mata Pelajaran - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<!-- Page Content -->
<div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex flex-col space-y-6">
            <!-- Page Header -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 relative overflow-hidden">
                <div class="absolute right-0 -top-12 h-40 w-40 bg-amber-100 opacity-50 rounded-full"></div>
                <div class="absolute -right-8 top-20 h-20 w-20 bg-amber-200 opacity-30 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                        <div>
                            <div class="flex items-center">
                                <a href="<?php echo e(route('admin.matapelajaran.index')); ?>" class="text-gray-500 hover:text-gray-700 mr-2">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-book text-amber-600 mr-3"></i>
                                    Detail Mata Pelajaran
                                </h1>
                            </div>
                            <p class="text-gray-600 mt-1">Informasi lengkap tentang mata pelajaran</p>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-3">                        
                            <a href="<?php echo e(route('admin.matapelajaran.edit', $mapel->kode)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                            <form action="<?php echo e(route('admin.matapelajaran.destroy', $mapel->kode)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-sm hover:shadow-md transition-all flex items-center" onclick="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')">
                                    <i class="fas fa-trash-alt mr-2"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mata Pelajaran Information -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">                        
                            <div class="p-2 bg-amber-100 rounded-lg text-amber-600">
                                <i class="fas fa-book"></i>
                            </div>
                            <h2 class="text-xl font-bold text-gray-800"><?php echo e($mapel->nama); ?></h2>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full font-semibold">
                            <?php echo e($mapel->status); ?>

                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                    
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kode Mata Pelajaran</h3>
                            <p class="text-gray-800 font-medium"><?php echo e($mapel->kode); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Guru Pengajar</h3>
                            <?php if($assignedGuru && $assignedGuru->count() > 0): ?>
                                <div class="space-y-1">
                                    <?php $__currentLoopData = $assignedGuru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-1 mb-1">
                                            <?php echo e($guru->nama); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <p class="text-gray-500 italic">Belum ada guru yang ter-assign</p>
                            <?php endif; ?>
                        </div>                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Kelas</h3>
                            <div class="mb-3">
                                <p class="text-xs text-gray-600 mb-2">Kelas yang terdaftar:</p>
                                <?php
                                    $kelasList = explode(', ', $mapel->kelas);
                                    $expandedKelasNames = [];
                                    foreach ($kelasList as $kelas) {
                                        foreach ($kelasMapping as $tingkat => $daftarKelas) {
                                            foreach ($daftarKelas as $namaKelas) {
                                                if ((strcasecmp($kelas, $namaKelas) == 0 || stripos($namaKelas, $kelas) !== false) && !in_array($namaKelas, $expandedKelasNames)) {
                                                    $expandedKelasNames[] = $namaKelas;
                                                }
                                            }
                                        }
                                    }
                                    if (empty($expandedKelasNames)) $expandedKelasNames = $kelasList;
                                    $totalKelas = count($expandedKelasNames);
                                    $halfPoint = ceil($totalKelas / 2);
                                    if ($totalKelas > 4) {
                                        $firstRow = array_slice($expandedKelasNames, 0, $halfPoint);
                                        $secondRow = array_slice($expandedKelasNames, $halfPoint);
                                ?>
                                    <div class="flex flex-wrap gap-1 mb-1">
                                        <?php $__currentLoopData = $firstRow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs"><?php echo e($kelas); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $secondRow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs"><?php echo e($kelas); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php
                                    } else {
                                        // Display all classes in one row with badges
                                ?>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $expandedKelasNames; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md text-xs"><?php echo e($kelas); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php
                                    }
                                ?>
                            </div>
                            
                            <?php if($assignedKelas && $assignedKelas->count() > 0): ?>
                                <div>
                                    <p class="text-xs text-gray-600 mb-2">Kelas yang ter-assign dengan guru:</p>
                                    <div class="flex flex-wrap gap-1">
                                        <?php $__currentLoopData = $assignedKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-md text-xs">
                                                <?php echo e($kelas->nama_kelas); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Jenis</h3>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                   <?php echo e($mapel->jenis == 'Wajib' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800'); ?>">
                                <?php echo e($mapel->jenis); ?>

                            </span>
                        </div>                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Jenis Mata Pelajaran</h3>
                            <p class="text-gray-800 font-medium">
                                <?php if($mapel->jenis == 'Kejuruan'): ?>
                                    <span class="inline-flex items-center">
                                        <span class="w-2 h-2 inline-block bg-amber-500 rounded-full mr-2"></span>
                                        Mata Pelajaran Kejuruan
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center">
                                        <span class="w-2 h-2 inline-block bg-blue-500 rounded-full mr-2"></span>
                                        Mata Pelajaran Wajib
                                    </span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Tahun Ajaran</h3>
                            <p class="text-gray-800 font-medium"><?php echo e($mapel->tahun_ajaran); ?></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Jam Pelajaran per Minggu</h3>
                            <p class="text-gray-800 font-medium"><?php echo e($mapel->jam_pelajaran); ?> Jam</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">KKM</h3>
                            <p class="text-gray-800 font-medium"><?php echo e($mapel->kkm); ?></p>
                        </div>
                    </div>                
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi Mata Pelajaran</h3>
                        <p class="text-gray-700 text-sm">
                            <?php echo e($mapel->deskripsi ?? 'Belum ada deskripsi untuk mata pelajaran ini.'); ?>

                        </p>
                    </div>

                    <?php if($mapel->materi_pokok): ?>
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">Materi Pokok</h3>
                        <ul class="list-disc pl-5 text-gray-700 text-sm space-y-1">
                            <?php $__currentLoopData = explode("\n", $mapel->materi_pokok); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e(trim($materi)); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Info Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Informasi Tambahan</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center text-sm">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                    <i class="fas fa-clock"></i>
                                </div>                            
                                <div class="ml-3">
                                    <p class="text-gray-500">Terakhir Diperbarui</p>
                                    <p class="font-medium text-gray-800"><?php echo e($mapel->updated_at->format('d M Y')); ?></p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500">Diperbarui Oleh</p>
                                    <p class="font-medium text-gray-800">Admin Kurikulum</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500">Total Pertemuan</p>
                                    <p class="font-medium text-gray-800">16 Pertemuan</p>
                                </div>
                            </div>
                            <div class="flex items-center text-sm">
                                <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-500">Komponen Penilaian</p>
                                    <p class="font-medium text-gray-800">Tugas, UTS, UAS, Praktikum</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Related Actions -->
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl shadow-sm p-6 border border-amber-200">
                        <h3 class="text-lg font-medium text-amber-800 mb-4">Tindakan Terkait</h3>
                        
                        <div class="space-y-3">
                            <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-sm">
                                <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                <span>Lihat Silabus</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-sm">
                                <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                <span>Lihat RPP</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-sm">
                                <i class="fas fa-book-open text-green-500 mr-3"></i>
                                <span>Lihat Materi Pembelajaran</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-sm">
                                <i class="fas fa-users text-purple-500 mr-3"></i>
                                <span>Daftar Siswa</span>
                            </a>
                            <a href="#" class="flex items-center p-3 bg-white rounded-lg shadow-sm hover:shadow-md transition-all text-sm">
                                <i class="fas fa-chart-line text-amber-600 mr-3"></i>
                                <span>Laporan Nilai</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Materials -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-800">Materi Pembelajaran</h3>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 flex items-center">
                        <span>Lihat Semua</span>
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <p class="text-sm font-medium text-gray-800">Pengenalan Konsep AI.pdf</p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Minggu 1</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Uploaded on: 10 Mei 2025</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">2.5 MB</span>
                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-powerpoint text-orange-500 mr-2"></i>
                                <p class="text-sm font-medium text-gray-800">Dasar Machine Learning.pptx</p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Minggu 2</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Uploaded on: 17 Mei 2025</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">5.1 MB</span>
                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <i class="fas fa-file-code text-green-500 mr-2"></i>
                                <p class="text-sm font-medium text-gray-800">Praktikum_Python_AI.zip</p>
                            </div>
                            <span class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Minggu 3</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">Uploaded on: 24 Mei 2025</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">8.7 MB</span>
                            <a href="#" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\matapelajaran\show.blade.php ENDPATH**/ ?>