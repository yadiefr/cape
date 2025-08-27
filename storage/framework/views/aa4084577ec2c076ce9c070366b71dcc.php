

<?php $__env->startSection('title', 'Edit Jurusan - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
    <div class="container px-3 py-4">
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Edit Jurusan</h1>
                    <div class="text-sm breadcrumbs">
                        <ul class="flex items-center space-x-2 text-gray-500">
                            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600 transition-colors"><i class="fas fa-home mr-1"></i> Dashboard</a></li>
                            <li class="flex items-center space-x-2">
                                <span class="text-gray-400">/</span>
                                <a href="<?php echo e(route('admin.jurusan.index')); ?>" class="hover:text-blue-600 transition-colors">Jurusan</a>
                            </li>
                            <li class="flex items-center space-x-2">
                                <span class="text-gray-400">/</span>
                                <span>Edit Jurusan</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="<?php echo e(route('admin.jurusan.show', $jurusan->id)); ?>" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center">
                    <?php if($jurusan->logo): ?>
                        <img src="<?php echo e(Storage::url($jurusan->logo)); ?>" alt="Logo <?php echo e($jurusan->nama_jurusan); ?>" class="h-10 w-10 object-contain rounded mr-3">
                    <?php else: ?>
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <i class="fas fa-building text-blue-600"></i>
                        </div>
                    <?php endif; ?>
                    <h3 class="font-semibold text-lg text-gray-800">Edit <?php echo e($jurusan->nama_jurusan); ?></h3>
                </div>
            </div>

            <form action="<?php echo e(route('admin.jurusan.update', $jurusan->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Kolom Kiri - Informasi Dasar -->
                        <div class="lg:col-span-2 space-y-6">
                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Dasar</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="kode_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kode Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-code text-gray-400"></i>
                                            </div>
                                            <input type="text" name="kode_jurusan" id="kode_jurusan" value="<?php echo e(old('kode_jurusan', $jurusan->kode_jurusan)); ?>" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['kode_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Mis: TKR, RPL, TMI">
                                        </div>
                                        <?php $__errorArgs = ['kode_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <p class="text-xs text-gray-500 mt-1">Kode jurusan harus unik dan maksimal 10 karakter.</p>
                                    </div>

                                    <div>
                                        <label for="nama_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Nama Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-graduation-cap text-gray-400"></i>
                                            </div>
                                            <input type="text" name="nama_jurusan" id="nama_jurusan" value="<?php echo e(old('nama_jurusan', $jurusan->nama_jurusan)); ?>" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['nama_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Mis: Teknik Kendaraan Ringan">
                                        </div>
                                        <?php $__errorArgs = ['nama_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div>
                                        <label for="kepala_jurusan" class="block text-sm font-medium text-gray-700 mb-1">Kepala Jurusan <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-user-tie text-gray-400"></i>
                                            </div>
                                            <select name="kepala_jurusan" id="kepala_jurusan" class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['kepala_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <option value="">-- Pilih Kepala Jurusan --</option>
                                                <?php $__currentLoopData = $guru_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $guru): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($guru->id); ?>" <?php echo e(old('kepala_jurusan', $jurusan->kepala_jurusan) == $guru->id ? 'selected' : ''); ?>>
                                                        <?php echo e($guru->nama); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <?php $__errorArgs = ['kepala_jurusan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div>
                                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                        <div class="mt-2">
                                            <label class="inline-flex items-center p-2 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-50 cursor-pointer transition-colors">
                                                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" <?php echo e(old('is_active', $jurusan->is_active) ? 'checked' : ''); ?>>
                                                <span class="ml-2 text-sm text-gray-700">Aktif</span>
                                            </label>
                                            <p class="text-xs text-gray-500 mt-1">Jurusan yang tidak aktif tidak akan ditampilkan di situs publik.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Informasi Akademis</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-red-500">*</span></label>
                                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Tuliskan deskripsi program keahlian..."><?php echo e(old('deskripsi', $jurusan->deskripsi)); ?></textarea>
                                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="visi" class="block text-sm font-medium text-gray-700 mb-1">Visi</label>
                                            <textarea name="visi" id="visi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['visi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Visi jurusan..."><?php echo e(old('visi', $jurusan->visi)); ?></textarea>
                                            <?php $__errorArgs = ['visi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div>
                                            <label for="misi" class="block text-sm font-medium text-gray-700 mb-1">Misi</label>
                                            <textarea name="misi" id="misi" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['misi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Misi jurusan..."><?php echo e(old('misi', $jurusan->misi)); ?></textarea>
                                            <?php $__errorArgs = ['misi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="prospek_karir" class="block text-sm font-medium text-gray-700 mb-1">Prospek Karir</label>
                                        <textarea name="prospek_karir" id="prospek_karir" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['prospek_karir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Prospek karir lulusan..."><?php echo e(old('prospek_karir', $jurusan->prospek_karir)); ?></textarea>
                                        <?php $__errorArgs = ['prospek_karir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan - Media -->
                        <div class="space-y-6">
                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Media & Gambar</h4>
                                
                                <!-- Logo -->
                                <div class="mb-6">
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Logo Jurusan</label>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-dashed border-gray-300">
                                        <?php if($jurusan->logo): ?>
                                            <div class="mb-4 flex justify-center">
                                                <img src="<?php echo e(Storage::url($jurusan->logo)); ?>" alt="Logo <?php echo e($jurusan->nama_jurusan); ?>" class="h-28 w-auto object-contain border p-2 rounded-lg bg-white shadow-sm">
                                            </div>
                                        <?php else: ?>
                                            <div class="mb-4 flex justify-center">
                                                <div class="h-28 w-28 flex items-center justify-center rounded-lg bg-gray-200 text-gray-500">
                                                    <i class="fas fa-image text-3xl"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <label class="cursor-pointer">
                                            <div class="relative">
                                                <input type="file" name="logo" id="logo" class="hidden">
                                                <div class="py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg inline-flex items-center transition-colors cursor-pointer">
                                                    <i class="fas fa-upload mr-2"></i> Upload Logo
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maks. 2MB.</p>
                                    </div>
                                </div>
                                
                                <!-- Gambar Header -->
                                <div>
                                    <label for="gambar_header" class="block text-sm font-medium text-gray-700 mb-2">Gambar Header</label>
                                    <div class="bg-gray-50 p-4 rounded-lg text-center border border-dashed border-gray-300">
                                        <?php if($jurusan->gambar_header): ?>
                                            <div class="mb-4 flex justify-center">
                                                <img src="<?php echo e(Storage::url($jurusan->gambar_header)); ?>" alt="Header <?php echo e($jurusan->nama_jurusan); ?>" class="h-28 w-full object-cover rounded-lg shadow-sm">
                                            </div>
                                        <?php else: ?>
                                            <div class="mb-4 flex justify-center">
                                                <div class="h-28 w-full flex items-center justify-center rounded-lg bg-gray-200 text-gray-500">
                                                    <i class="fas fa-panorama text-3xl"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <label class="cursor-pointer">
                                            <div class="relative">
                                                <input type="file" name="gambar_header" id="gambar_header" class="hidden">
                                                <div class="py-2 px-4 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg inline-flex items-center transition-colors cursor-pointer">
                                                    <i class="fas fa-upload mr-2"></i> Upload Header
                                                </div>
                                            </div>
                                        </label>
                                        
                                        <?php $__errorArgs = ['gambar_header'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG. Maks. 2MB. Ukuran optimal: 1200x400px.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg border border-gray-100 shadow-sm p-5">
                                <h4 class="font-medium text-gray-800 mb-4 pb-2 border-b border-gray-100">Statistik Jurusan</h4>
                                
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full mr-3">
                                                <i class="fas fa-door-open"></i>
                                            </span>
                                            <span class="text-sm text-blue-800">Kelas</span>
                                        </div>
                                        <span class="font-bold text-blue-800"><?php echo e($jurusan->kelas ? $jurusan->kelas->count() : 0); ?></span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-green-100 text-green-600 rounded-full mr-3">
                                                <i class="fas fa-users"></i>
                                            </span>
                                            <span class="text-sm text-green-800">Siswa</span>
                                        </div>
                                        <span class="font-bold text-green-800"><?php echo e($jurusan->siswa ? $jurusan->siswa->count() : 0); ?></span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="w-8 h-8 flex items-center justify-center bg-purple-100 text-purple-600 rounded-full mr-3">
                                                <i class="fas fa-book"></i>
                                            </span>
                                            <span class="text-sm text-purple-800">Mata Pelajaran</span>
                                        </div>
                                        <span class="font-bold text-purple-800"><?php echo e($jurusan->mata_pelajaran ? $jurusan->mata_pelajaran->count() : 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                    <a href="<?php echo e(route('admin.jurusan.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors shadow-sm">
                        <i class="fas fa-save mr-2"></i> Perbarui Jurusan
                    </button>                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Fungsi untuk preview gambar sebelum upload (jika diperlukan)
    // Tambahkan skrip tambahan jika diperlukan
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\jurusan\edit.blade.php ENDPATH**/ ?>