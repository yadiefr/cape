

<?php $__env->startSection('title', 'Detail Tugas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-6">
        <nav class="text-sm mb-2" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center text-gray-500">
                    <a href="<?php echo e(route('siswa.dashboard')); ?>" class="text-gray-500 hover:text-blue-600">
                        Dashboard
                    </a>
                    <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center text-gray-500">
                    <a href="<?php echo e(route('siswa.tugas')); ?>" class="text-gray-500 hover:text-blue-600">
                        Tugas
                    </a>
                    <svg class="fill-current w-3 h-3 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512">
                        <path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"/>
                    </svg>
                </li>
                <li class="flex items-center text-gray-500">
                    <span class="text-gray-500">Detail Tugas</span>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-800"><?php echo e($tugas->judul); ?></h1>
    </div>

    <!-- Status Messages -->
    <?php if(session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    <!-- Task Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h2 class="text-xl font-semibold mb-4">Deskripsi Tugas</h2>
                <div class="prose max-w-none">
                    <?php echo nl2br(e($tugas->deskripsi)); ?>

                </div>

                <?php if($tugas->file_path): ?>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">File Lampiran</h3>
                        <a href="<?php echo e(Storage::url($tugas->file_path)); ?>" 
                           class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                            </svg>
                            Download Lampiran
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Submission Form -->
                <?php if(!$pengumpulan && $tugas->deadline > now()): ?>
                    <div class="mt-6">
                        <h3 class="text-lg font-medium mb-4">Kumpulkan Tugas</h3>
                        <form action="<?php echo e(route('siswa.tugas.submit', $tugas->id)); ?>" 
                              method="POST" 
                              enctype="multipart/form-data"
                              class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload File
                                </label>
                                <input type="file" 
                                       name="file" 
                                       required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    File maksimal 10MB
                                </p>
                            </div>
                            <button type="submit" 
                                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Kumpulkan
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Submission Details -->
                <?php if($pengumpulan): ?>
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium mb-4">Status Pengumpulan</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-500">Waktu Pengumpulan</div>
                                <div class="text-gray-900">
                                    <?php echo e($pengumpulan->tanggal_submit->format('d M Y H:i')); ?>

                                </div>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-500">Status</div>
                                <?php if($pengumpulan->status == 'submitted'): ?>
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                        Sudah Dikumpulkan
                                    </span>
                                <?php elseif($pengumpulan->status == 'graded'): ?>
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                        Sudah Dinilai
                                    </span>
                                <?php endif; ?>
                            </div>
                            <?php if($pengumpulan->nilai): ?>
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Nilai</div>
                                    <div class="text-gray-900"><?php echo e($pengumpulan->nilai); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if($pengumpulan->komentar): ?>
                                <div>
                                    <div class="text-sm font-medium text-gray-500">Komentar Guru</div>
                                    <div class="text-gray-900"><?php echo e($pengumpulan->komentar); ?></div>
                                </div>
                            <?php endif; ?>
                            <?php if($pengumpulan->file_path): ?>
                                <div>
                                    <div class="text-sm font-medium text-gray-500">File yang Dikumpulkan</div>
                                    <a href="<?php echo e(Storage::url($pengumpulan->file_path)); ?>" 
                                       class="inline-flex items-center text-blue-600 hover:text-blue-800 mt-1">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z"/>
                                        </svg>
                                        Download File
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <!-- Subject Info -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Mata Pelajaran</h3>
                    <p class="text-gray-900">
                        <?php echo e($tugas->mapel ? $tugas->mapel->nama : ($tugas->jadwal && $tugas->jadwal->mapel ? $tugas->jadwal->mapel->nama : '-')); ?>

                    </p>
                </div>

                <!-- Teacher Info -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Guru</h3>
                    <p class="text-gray-900">
                        <?php echo e($tugas->guru ? $tugas->guru->nama : ($tugas->jadwal && $tugas->jadwal->guru ? $tugas->jadwal->guru->nama : '-')); ?>

                    </p>
                </div>

                <!-- Deadline -->
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Batas Pengumpulan</h3>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-gray-900"><?php echo e($tugas->deadline->format('d M Y H:i')); ?></p>
                    </div>
                    <?php if($tugas->deadline < now()): ?>
                        <p class="text-red-600 text-sm mt-1">Batas waktu telah berakhir</p>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm mt-1"><?php echo e($tugas->deadline->diffForHumans()); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Action -->
                <div>
                    <a href="<?php echo e(route('siswa.materi.index')); ?>#tugas" 
                       class="block w-full text-center bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                        Kembali ke Daftar Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\tugas\show.blade.php ENDPATH**/ ?>