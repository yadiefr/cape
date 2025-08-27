

<?php $__env->startSection('title', 'Absensi Kelas ' . $kelas->nama_kelas . ' - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6 lg:p-8">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Absensi <?php echo e($kelas->nama_kelas); ?></h2>
                <p class="mt-1 text-sm text-gray-500"><?php echo e($kelas->jurusan->nama_jurusan); ?></p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-2">
                <a href="<?php echo e(route('guru.absensi.create', ['kelas_id' => $kelas->id])); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i>
                    Input Absensi Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Siswa</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900"><?php echo e($siswa->count()); ?></p>
                </div>
                <div class="p-3 bg-blue-50 rounded-full">
                    <i class="fas fa-users text-blue-500"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-gray-600">Mata Pelajaran</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900"><?php echo e($mapelDiajar->count()); ?></p>
                </div>
                <div class="p-3 bg-green-50 rounded-full">
                    <i class="fas fa-book text-green-500"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List with Attendance Records -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Rekap Kehadiran Siswa</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NISN</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Izin</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sakit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alpha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <?php if (isset($component)) { $__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.student-avatar','data' => ['student' => $s,'size' => 'md']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('student-avatar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['student' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($s),'size' => 'md']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a)): ?>
<?php $attributes = $__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a; ?>
<?php unset($__attributesOriginalc9f0c2af5ee448021f79b9e9b22af84a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a)): ?>
<?php $component = $__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a; ?>
<?php unset($__componentOriginalc9f0c2af5ee448021f79b9e9b22af84a); ?>
<?php endif; ?>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($s->nama_lengkap); ?></div>
                                    <div class="text-sm text-gray-500"><?php echo e($s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($s->nisn); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                <?php echo e($rekapAbsensi[$s->id]['hadir']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                <?php echo e($rekapAbsensi[$s->id]['izin']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                <?php echo e($rekapAbsensi[$s->id]['sakit']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                <?php echo e($rekapAbsensi[$s->id]['alpha']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="<?php echo e(route('guru.absensi.show', $s->id)); ?>" 
                               class="text-blue-600 hover:text-blue-800">Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\kelas.blade.php ENDPATH**/ ?>