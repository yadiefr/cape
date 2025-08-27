

<?php $__env->startSection('title', 'Daftar Tugas - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Daftar Tugas</h1>
        <nav class="text-sm" aria-label="Breadcrumb">
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
                    <span class="text-gray-500">Tugas</span>
                </li>
            </ol>
        </nav>
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

    <!-- Task List -->
    <div class="bg-white rounded-xl shadow-sm">
        <!-- Header with statistics -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Tugas Belum Dikerjakan</div>
                    <div class="text-2xl font-semibold text-blue-600">
                        <?php echo e($tugas->where('pengumpulanTugas', '[]')->count()); ?>

                    </div>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Tugas Selesai</div>
                    <div class="text-2xl font-semibold text-green-600">
                        <?php echo e($tugas->where('pengumpulanTugas', '!=', '[]')->count()); ?>

                    </div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">Total Tugas</div>
                    <div class="text-2xl font-semibold text-yellow-600">
                        <?php echo e($tugas->count()); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Task List -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mata Pelajaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tugas
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Guru
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Deadline
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $tugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?php echo e($t->mapel ? $t->mapel->nama : '-'); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900"><?php echo e($t->judul); ?></div>
                                <div class="text-sm text-gray-500">
                                    <?php echo e(Str::limit($t->deskripsi, 50)); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?php echo e($t->guru->name); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?php echo e($t->deadline->format('d M Y H:i')); ?>

                                </div>
                                <?php if($t->deadline < now()): ?>
                                    <span class="text-xs text-red-600">Telah berakhir</span>
                                <?php else: ?>
                                    <span class="text-xs text-gray-500">
                                        <?php echo e($t->deadline->diffForHumans()); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                    $submission = $t->pengumpulanTugas->first();
                                ?>
                                <?php if(!$submission): ?>
                                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">
                                        Belum Dikerjakan
                                    </span>
                                <?php elseif($submission->status == 'submitted'): ?>
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        Sudah Dikumpulkan
                                    </span>
                                <?php elseif($submission->status == 'graded'): ?>
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                        Sudah Dinilai: <?php echo e($submission->nilai); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="<?php echo e(route('siswa.tugas.show', $t->id)); ?>" 
                                   class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-3 py-1 rounded-lg text-sm">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <img src="<?php echo e(asset('images/no-tasks.svg')); ?>" alt="Tidak ada tugas" class="w-32 h-32 mx-auto mb-4">
                                <p>Belum ada tugas yang perlu dikerjakan</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            <?php echo e($tugas->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\tugas\index.blade.php ENDPATH**/ ?>