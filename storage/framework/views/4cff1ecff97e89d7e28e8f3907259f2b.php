

<?php $__env->startSection('title', 'Kelola Kelas - ' . $ruangan->nama_ruangan); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 px-2 py-3">
    <div class="max-w-full mx-auto">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm bg-white rounded-lg px-4 py-3 mb-3 shadow-sm border border-gray-200" aria-label="Breadcrumb">
            <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.index')); ?>" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <i class="fas fa-door-open mr-2"></i>
                <span>Ruangan</span>
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.show', $ruangan)); ?>" class="text-gray-600 hover:text-gray-900 transition-colors">
                <span><?php echo e($ruangan->nama_ruangan); ?></span>
            </a>
            <i class="fas fa-chevron-right text-gray-400"></i>
            <span class="text-gray-900 font-medium">Kelola Kelas</span>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg mr-3">
                    <i class="fas fa-users-cog text-lg text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Kelola Kelas: <?php echo e($ruangan->nama_ruangan); ?></h1>
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Kelola penugasan kelas ke ruangan ujian
                    </p>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-3 flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-3 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <!-- Full Width Content Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-users mr-3 p-2 bg-white rounded-lg text-blue-600 shadow-sm"></i>
                    Manajemen Kelas
                </h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6">
                <!-- Main Content - Takes 4 columns -->
                <div class="lg:col-span-4">
                    <!-- Add New Class Form -->
                    <?php if($availableKelas->count() > 0): ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
                            Tugaskan Kelas Baru
                        </h3>
                        <form action="<?php echo e(route('admin.ujian.pengaturan.ruangan.assign-kelas', $ruangan)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas</label>
                                    <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                            id="kelas_id" 
                                            name="kelas_id"
                                            required>
                                        <option value="">Pilih Kelas yang Tersedia</option>
                                        <?php $__currentLoopData = $availableKelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($kelas->id); ?>" <?php echo e(old('kelas_id') == $kelas->id ? 'selected' : ''); ?>>
                                                <?php echo e($kelas->nama_kelas); ?> - <?php echo e($kelas->jurusan->nama_jurusan ?? 'Umum'); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div>
                                    <label for="kapasitas_maksimal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kapasitas Maksimal <span class="text-gray-500 text-xs">(Opsional)</span>
                                    </label>
                                    <input type="number" 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['kapasitas_maksimal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                           id="kapasitas_maksimal" 
                                           name="kapasitas_maksimal" 
                                           value="<?php echo e(old('kapasitas_maksimal')); ?>"
                                           min="1" 
                                           max="<?php echo e($ruangan->kapasitas); ?>"
                                           placeholder="<?php echo e($ruangan->kapasitas); ?>">
                                    <?php $__errorArgs = ['kapasitas_maksimal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Kosongkan untuk menggunakan kapasitas ruangan (<?php echo e($ruangan->kapasitas); ?>)
                                    </p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Tugaskan Kelas
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>
                    <!-- Assigned Classes List -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                Daftar Kelas yang Ditugaskan
                            </h3>
                        </div>
                        
                        <?php if($ruangan->kelas->count() > 0): ?>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-blue-600 text-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-medium">#</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium">Nama Kelas</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium">Jurusan</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium">Kapasitas</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium">Status</th>
                                            <th class="px-4 py-3 text-center text-sm font-medium">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php $__currentLoopData = $ruangan->kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-4 text-center">
                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"><?php echo e($index + 1); ?></span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-graduation-cap text-blue-600 mr-3"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900"><?php echo e($kelas->nama_kelas); ?></div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            <i class="fas fa-calendar-alt mr-1"></i>
                                                            <?php echo e($kelas->pivot->created_at->format('d M Y')); ?>

                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <?php echo e($kelas->jurusan->nama_jurusan ?? 'Umum'); ?>

                                                </span>
                                            </td>
                                            <td class="px-4 py-4">
                                                <form action="<?php echo e(route('admin.ujian.pengaturan.ruangan.update-kelas', [$ruangan, $kelas])); ?>" 
                                                      method="POST" 
                                                      class="flex items-center space-x-2 update-capacity-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <input type="number" 
                                                           class="w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                                           name="kapasitas_maksimal" 
                                                           value="<?php echo e($kelas->pivot->kapasitas_maksimal); ?>"
                                                           min="1" 
                                                           max="<?php echo e($ruangan->kapasitas); ?>">
                                                    <button type="submit" 
                                                            class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm"
                                                            title="Update kapasitas">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" 
                                                           class="sr-only peer toggle-kelas-status" 
                                                           data-ruangan-id="<?php echo e($ruangan->id); ?>"
                                                           data-kelas-id="<?php echo e($kelas->id); ?>"
                                                           <?php echo e($kelas->pivot->is_active ? 'checked' : ''); ?>>
                                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <form action="<?php echo e(route('admin.ujian.pengaturan.ruangan.remove-kelas', [$ruangan, $kelas])); ?>" 
                                                      method="POST" 
                                                      class="inline remove-kelas-form">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="button" 
                                                            class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm remove-kelas-btn"
                                                            data-kelas-name="<?php echo e($kelas->nama_kelas); ?>"
                                                            title="Hapus dari ruangan">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-2xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Kelas yang Ditugaskan</h3>
                                <p class="text-gray-600 mb-4">Ruangan ini belum memiliki kelas yang ditugaskan. Silakan tugaskan kelas untuk memulai.</p>
                                <?php if($availableKelas->count() > 0): ?>
                                    <p class="text-blue-600 text-sm">
                                        <i class="fas fa-arrow-up mr-1"></i>Gunakan form di atas untuk menugaskan kelas
                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($ruangan->kelas->count() > 0): ?>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Total: <strong><?php echo e($ruangan->kelas->count()); ?></strong> kelas ditugaskan
                                </span>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.show', $ruangan)); ?>" class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                                    </a>
                                    <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.index')); ?>" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                        <i class="fas fa-list mr-1"></i>Semua Ruangan
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Sidebar - Takes 1 column -->
                <div class="lg:col-span-1">
                    <div class="sticky top-6 space-y-4">
                        <!-- Room Information Card -->
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-4 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-door-open mr-2 text-blue-600"></i>
                                Informasi Ruangan
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-tag mr-1"></i>Kode Ruangan
                                    </span>
                                    <span class="text-sm bg-white px-2 py-1 rounded border border-gray-200"><?php echo e($ruangan->kode_ruangan); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-home mr-1"></i>Nama Ruangan
                                    </span>
                                    <span class="text-sm font-semibold text-gray-900"><?php echo e($ruangan->nama_ruangan); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-users mr-1"></i>Kapasitas Total
                                    </span>
                                    <span class="text-sm font-bold text-gray-900"><?php echo e($ruangan->formatted_kapasitas ?? $ruangan->kapasitas . ' orang'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2 border-b border-blue-200">
                                    <span class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-map-marker-alt mr-1"></i>Lokasi
                                    </span>
                                    <span class="text-sm text-gray-900"><?php echo e($ruangan->lokasi ?? 'Belum ditentukan'); ?></span>
                                </div>
                                <div class="flex justify-between items-center py-2">
                                    <span class="text-sm text-gray-700 font-medium">
                                        <i class="fas fa-info-circle mr-1"></i>Status
                                    </span>
                                    <?php if($ruangan->status == 'tersedia'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                                        </span>
                                    <?php elseif($ruangan->status == 'terpakai'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Terpakai
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-tools mr-1"></i>Maintenance
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics Card -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 shadow-sm">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-chart-pie mr-2 text-green-600"></i>
                                Statistik Kelas
                            </h3>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-white rounded-lg p-3 text-center border border-green-100">
                                    <div class="text-2xl font-bold text-gray-900 mb-1"><?php echo e($ruangan->kelas->count()); ?></div>
                                    <div class="text-xs text-gray-600">Total Kelas</div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border border-green-100">
                                    <div class="text-2xl font-bold text-green-600 mb-1"><?php echo e($ruangan->kelas->where('pivot.is_active', true)->count()); ?></div>
                                    <div class="text-xs text-gray-600">Kelas Aktif</div>
                                </div>
                            </div>
                            
                            <?php if($ruangan->kelas->count() > 0): ?>
                                <div class="bg-white rounded-lg p-3 border border-green-100">
                                    <h4 class="text-xs font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-chart-bar mr-1"></i>Penggunaan Kapasitas
                                    </h4>
                                    <?php
                                        $totalCapacity = $ruangan->kelas->sum('pivot.kapasitas_maksimal');
                                        $usage = ($totalCapacity / $ruangan->kapasitas) * 100;
                                        $progressClass = $usage <= 70 ? 'bg-green-500' : ($usage <= 90 ? 'bg-yellow-500' : 'bg-red-500');
                                    ?>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-3">
                                        <div class="<?php echo e($progressClass); ?> h-2 rounded-full transition-all duration-300" 
                                             style="width: <?php echo e(min($usage, 100)); ?>%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-600">
                                            <i class="fas fa-users mr-1"></i>Terisi: <?php echo e($totalCapacity); ?> siswa
                                        </span>
                                        <span class="text-gray-600">
                                            <i class="fas fa-chair mr-1"></i>Total: <?php echo e($ruangan->kapasitas); ?> kursi
                                        </span>
                                    </div>
                                    <div class="text-center mt-2">
                                        <span class="text-xs font-semibold text-gray-700"><?php echo e(number_format($usage, 1)); ?>%</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="bg-white rounded-lg p-3 text-center border border-green-100">
                                    <i class="fas fa-chart-bar text-2xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-600 font-medium">Belum ada data penggunaan</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Status Information -->
                        <?php if($availableKelas->count() == 0 && $ruangan->kelas->count() > 0): ?>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-blue-900 mb-1">Informasi</h4>
                                        <p class="text-xs text-blue-800">Semua kelas yang ada sudah ditugaskan ke ruangan ini.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($availableKelas->count() > 0): ?>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-plus-circle text-green-600 mr-3"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-green-900 mb-1">Kelas Tersedia</h4>
                                        <p class="text-xs text-green-800">Terdapat <strong><?php echo e($availableKelas->count()); ?></strong> kelas yang dapat ditugaskan ke ruangan ini.</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Actions -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-bolt mr-2 text-gray-600"></i>Aksi Cepat
                                </h4>
                            </div>
                            <div class="p-4 space-y-2">
                                <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.show', $ruangan)); ?>" 
                                   class="w-full flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    <i class="fas fa-eye mr-2"></i>Detail Ruangan
                                </a>
                                <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.edit', $ruangan)); ?>" 
                                   class="w-full flex items-center justify-center px-3 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                    <i class="fas fa-edit mr-2"></i>Edit Ruangan
                                </a>
                                <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.index')); ?>" 
                                   class="w-full flex items-center justify-center px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm">
                                    <i class="fas fa-list mr-2"></i>Semua Ruangan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced toggle kelas status with visual feedback
    document.querySelectorAll('.toggle-kelas-status').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const ruanganId = this.getAttribute('data-ruangan-id');
            const kelasId = this.getAttribute('data-kelas-id');
            const isChecked = this.checked;
            const row = this.closest('tr');
            
            // Add loading visual feedback
            row.style.opacity = '0.7';
            row.classList.add('bg-yellow-50');
            
            fetch(`/admin/ujian/pengaturan/ruangan/${ruanganId}/kelas/${kelasId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success feedback
                    showNotification(data.message || 'Status kelas berhasil diubah', 'success');
                    
                    // Visual success animation
                    row.classList.remove('bg-yellow-50');
                    row.classList.add('bg-green-50');
                    setTimeout(() => {
                        row.classList.remove('bg-green-50');
                        row.style.opacity = '1';
                    }, 2000);
                    
                    // Update statistics if needed
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    // Error handling
                    showNotification(data.message || 'Gagal mengubah status kelas', 'error');
                    this.checked = !isChecked;
                    row.classList.remove('bg-yellow-50');
                    row.style.opacity = '1';
                }
            })
            .catch(error => {
                // Network error handling
                showNotification('Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
                this.checked = !isChecked;
                row.classList.remove('bg-yellow-50');
                row.style.opacity = '1';
                
                console.error('Toggle error:', error);
            });
        });
    });

    // Enhanced remove kelas confirmation
    document.querySelectorAll('.remove-kelas-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const kelasName = this.getAttribute('data-kelas-name');
            const form = this.closest('form');
            const row = this.closest('tr');
            
            if (confirm(`Apakah Anda yakin ingin menghapus kelas "${kelasName}" dari ruangan ini?\n\nTindakan ini akan menghapus penugasan kelas ke ruangan.`)) {
                // Add visual feedback before form submission
                row.style.opacity = '0.6';
                row.classList.add('bg-red-50');
                
                setTimeout(() => {
                    form.submit();
                }, 500);
            }
        });
    });

    // Enhanced capacity update with validation
    document.querySelectorAll('.update-capacity-form').forEach(function(form) {
        const input = form.querySelector('input[name="kapasitas_maksimal"]');
        const button = form.querySelector('button[type="submit"]');
        const originalValue = input.value;
        
        // Real-time validation
        input.addEventListener('input', function() {
            const currentValue = this.value;
            const maxCapacity = this.getAttribute('max');
            
            if (currentValue && (parseInt(currentValue) > parseInt(maxCapacity) || parseInt(currentValue) < 1)) {
                this.classList.add('border-red-500', 'bg-red-50');
                this.classList.remove('border-gray-300');
                button.disabled = true;
            } else {
                this.classList.remove('border-red-500', 'bg-red-50');
                this.classList.add('border-gray-300');
                button.disabled = currentValue === originalValue;
            }
        });
        
        // Auto-submit on Enter key
        input.addEventListener('keypress', function(e) {
            if (e.which === 13 && !button.disabled) {
                e.preventDefault();
                submitCapacityForm(form);
            }
        });
        
        // Manual submit
        button.addEventListener('click', function(e) {
            e.preventDefault();
            submitCapacityForm(form);
        });
    });
    
    function submitCapacityForm(form) {
        const button = form.querySelector('button[type="submit"]');
        const input = form.querySelector('input[name="kapasitas_maksimal"]');
        const row = form.closest('tr');
        
        // Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        row.classList.add('bg-blue-50');
        
        const formData = new FormData(form);
        
        fetch(form.getAttribute('action'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            showNotification('Kapasitas berhasil diperbarui', 'success');
            row.classList.remove('bg-blue-50');
            row.classList.add('bg-green-50');
            setTimeout(() => {
                row.classList.remove('bg-green-50');
            }, 2000);
            
            // Update statistics
            setTimeout(() => {
                location.reload();
            }, 2000);
        })
        .catch(error => {
            let errorMessage = 'Gagal memperbarui kapasitas';
            showNotification(errorMessage, 'error');
            row.classList.remove('bg-blue-50');
        })
        .finally(() => {
            button.innerHTML = '<i class="fas fa-save"></i>';
            button.disabled = false;
        });
    }

    // Form validation for assign class
    const kelasSelect = document.getElementById('kelas_id');
    const capacityInput = document.getElementById('kapasitas_maksimal');
    const assignForm = kelasSelect ? kelasSelect.closest('form') : null;
    const submitButton = assignForm ? assignForm.querySelector('button[type="submit"]') : null;
    
    function validateAssignForm() {
        if (!kelasSelect || !submitButton) return true;
        
        const hasKelas = kelasSelect.value !== '';
        const capacityValue = capacityInput.value;
        const maxCapacity = capacityInput.getAttribute('max');
        
        let isValid = hasKelas;
        
        if (capacityValue && (parseInt(capacityValue) > parseInt(maxCapacity) || parseInt(capacityValue) < 1)) {
            isValid = false;
        }
        
        submitButton.disabled = !isValid;
        
        return isValid;
    }
    
    if (kelasSelect) {
        kelasSelect.addEventListener('change', function() {
            if (this.value) {
                this.classList.add('border-green-500');
                this.classList.remove('border-red-500');
            } else {
                this.classList.remove('border-green-500', 'border-red-500');
            }
            validateAssignForm();
        });
    }
    
    if (capacityInput) {
        capacityInput.addEventListener('input', function() {
            const value = this.value;
            const max = this.getAttribute('max');
            
            if (value && (parseInt(value) > parseInt(max) || parseInt(value) < 1)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-green-500');
            } else if (value) {
                this.classList.add('border-green-500');
                this.classList.remove('border-red-500');
            } else {
                this.classList.remove('border-green-500', 'border-red-500');
            }
            validateAssignForm();
        });
    }
    
    // Enhanced form submission with loading state
    if (assignForm) {
        assignForm.addEventListener('submit', function() {
            if (validateAssignForm() && submitButton) {
                submitButton.disabled = true;
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menugaskan...';
            }
        });
    }

    // Initialize validation
    validateAssignForm();
});

// Notification function
function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 max-w-sm w-full p-4 rounded-lg shadow-lg transition-all duration-300 transform ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
            <span class="font-medium text-sm">${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.add('translate-y-0');
        notification.classList.remove('translate-y-2', 'opacity-0');
    }, 100);
    
    // Remove after delay
    setTimeout(() => {
        notification.classList.add('translate-y-2', 'opacity-0');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.ujian', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\pengaturan\ruangan\manage-kelas.blade.php ENDPATH**/ ?>