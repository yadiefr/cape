

<?php $__env->startSection('title', 'Detail Materi - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50">
    <!-- Full Width Container -->
    <div class="w-full px-6 py-6">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <a href="<?php echo e(route('siswa.materi.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-white text-gray-600 hover:text-blue-600 rounded-lg shadow-sm border border-gray-200 hover:border-blue-300 transition-all duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span class="font-medium">Kembali</span>
                </a>
                
                <!-- Breadcrumb on the right -->
                <nav class="flex items-center space-x-2 text-sm text-gray-500">
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Dashboard
                    </a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="<?php echo e(route('siswa.materi.index')); ?>" class="hover:text-blue-600 transition-colors">Materi & Tugas</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-700 font-medium">Detail Materi</span>
                </nav>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Main Content - Left Column (2/3) -->
            <div class="xl:col-span-2">
                <!-- Material Header Card -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-6">
                    <!-- Header Card -->
                    <div class="relative bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-700 text-white p-6">
                        <div class="absolute top-0 right-0 w-24 h-24 opacity-10">
                            <i class="fas fa-book-open text-4xl"></i>
                        </div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-3">
                                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-3 py-1">
                                    <i class="fas fa-graduation-cap mr-2 text-sm"></i>
                                    <span class="font-semibold text-sm"><?php echo e($materi->mataPelajaran ? $materi->mataPelajaran->nama : 'Mata Pelajaran'); ?></span>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs opacity-90 mb-1">Diunggah</div>
                                    <div class="font-medium text-sm"><?php echo e($materi->created_at->isoFormat('D MMM Y')); ?></div>
                                </div>
                            </div>
                            
                            <h1 class="text-2xl font-bold mb-3 leading-tight"><?php echo e($materi->judul); ?></h1>
                            
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center mr-2">
                                    <i class="fas fa-user-tie text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-xs opacity-90">Pengajar</div>
                                    <div class="font-semibold text-sm"><?php echo e($materi->guru ? $materi->guru->nama_lengkap : 'Guru'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Section -->
                    <div class="p-6">
                        <!-- Description -->
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-align-left text-blue-600 text-sm"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Deskripsi Materi</h2>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 border-l-4 border-blue-500">
                                <div class="text-gray-700 leading-relaxed text-sm">
                                    <?php echo nl2br(e($materi->deskripsi)); ?>

                                </div>
                            </div>
                        </div>

                        <!-- File Attachment -->
                        <?php if($materi->file_path): ?>
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-6 h-6 bg-green-100 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-paperclip text-green-600 text-sm"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">File Lampiran</h2>
                            </div>
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl p-4 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-file-download text-green-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800 text-sm mb-1">
                                                <?php echo e($materi->judul); ?>.<?php echo e(pathinfo($materi->file_path, PATHINFO_EXTENSION)); ?>

                                            </h3>
                                            <p class="text-xs text-gray-600">Klik tombol download untuk mengunduh file</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo e(route('siswa.materi.download', $materi->id)); ?>" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transform hover:scale-105 transition-all duration-200 shadow-md">
                                        <i class="fas fa-download mr-2 text-sm"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Video Link -->
                        <?php if($materi->link_video): ?>
                        <div class="mb-6">
                            <div class="flex items-center mb-3">
                                <div class="w-6 h-6 bg-red-100 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-play text-red-600 text-sm"></i>
                                </div>
                                <h2 class="text-lg font-bold text-gray-800">Video Pembelajaran</h2>
                            </div>
                            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 border border-red-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-video text-red-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-gray-800 text-sm mb-1">Video Materi</h3>
                                            <p class="text-xs text-gray-600">Tonton video pembelajaran terkait materi ini</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo e($materi->link_video); ?>" target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transform hover:scale-105 transition-all duration-200 shadow-md">
                                        <i class="fas fa-external-link-alt mr-2 text-sm"></i>
                                        Tonton Video
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar - Info & Related Tasks -->
            <div class="xl:col-span-1">
                <!-- Material Info Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 text-white p-4">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            <h3 class="font-bold">Informasi Materi</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-calendar text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Tanggal Upload</div>
                                <div class="font-semibold text-sm"><?php echo e($materi->created_at->isoFormat('D MMMM Y')); ?></div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-clock text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Waktu Upload</div>
                                <div class="font-semibold text-sm"><?php echo e($materi->created_at->isoFormat('HH:mm')); ?> WIB</div>
                            </div>
                        </div>
                        <?php if($materi->file_path): ?>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">File Tersedia</div>
                                <div class="font-semibold text-sm text-green-600">Ya</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($materi->link_video): ?>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-video text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Video Tersedia</div>
                                <div class="font-semibold text-sm text-red-600">Ya</div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

        <?php
            $relatedTugas = collect();
            // Try to find related tugas based on same kelas and mapel
            if ($materi->kelas_id && $materi->mapel_id) {
                $relatedTugas = \App\Models\Tugas::where('kelas_id', $materi->kelas_id)
                                                ->where('mapel_id', $materi->mapel_id)
                                                ->where('is_active', true)
                                                ->get();
            } elseif ($materi->mataPelajaran && $materi->mataPelajaran->jadwalPelajaran) {
                // Fallback using jadwal relationships
                $jadwalIds = $materi->mataPelajaran->jadwalPelajaran->pluck('id');
                $relatedTugas = \App\Models\Tugas::whereIn('jadwal_id', $jadwalIds)
                                                ->where('is_active', true)
                                                ->get();
            }
        ?>
        
        <?php if($relatedTugas->isNotEmpty()): ?>
                <!-- Related Tasks Card -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tasks mr-2"></i>
                                <h3 class="font-bold">Tugas Terkait</h3>
                            </div>
                            <div class="bg-white/20 px-2 py-1 rounded-full text-xs font-semibold">
                                <?php echo e($relatedTugas->count()); ?>

                            </div>
                        </div>
                    </div>
                    <div class="p-4 max-h-96 overflow-y-auto">
                        <div class="space-y-3">
                            <?php $__currentLoopData = $relatedTugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-200 rounded-lg p-3 hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                <div class="flex items-start justify-between mb-2">
                                    <h4 class="font-semibold text-gray-800 text-sm leading-tight"><?php echo e(Str::limit($tugas->judul, 40)); ?></h4>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ml-2 <?php echo e($tugas->isExpired() ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'); ?>">
                                        <i class="fas fa-<?php echo e($tugas->isExpired() ? 'times-circle' : 'check-circle'); ?> mr-1"></i>
                                        <?php echo e($tugas->isExpired() ? 'Berakhir' : 'Aktif'); ?>

                                    </span>
                                </div>
                                
                                <p class="text-gray-600 text-xs mb-3 leading-relaxed"><?php echo e(Str::limit($tugas->deskripsi, 80)); ?></p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1 text-amber-500"></i>
                                        <span>
                                            <?php if($tugas->deadline): ?>
                                                <?php echo e($tugas->deadline->isoFormat('D MMM Y')); ?>

                                            <?php elseif($tugas->tanggal_deadline): ?>
                                                <?php echo e(\Carbon\Carbon::parse($tugas->tanggal_deadline)->isoFormat('D MMM Y')); ?>

                                            <?php else: ?>
                                                No deadline
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <a href="<?php echo e(route('siswa.tugas.show', $tugas->id)); ?>" 
                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition-colors text-xs">
                                        <i class="fas fa-eye mr-1"></i>
                                        Lihat
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
        <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\materi\show.blade.php ENDPATH**/ ?>