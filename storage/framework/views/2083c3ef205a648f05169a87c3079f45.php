

<?php $__env->startSection('title', 'Edit Guru - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb and actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center text-sm text-gray-600 mb-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <a href="<?php echo e(route('admin.guru.index')); ?>" class="hover:text-blue-600">Data Guru</a>
                <i class="fas fa-chevron-right text-gray-400 mx-2 text-xs"></i>
                <span>Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-user-edit text-blue-500 mr-3"></i> Edit Data Guru
            </h1>
            <p class="text-gray-600 mt-1">Edit informasi guru <?php echo e($guru->nama); ?></p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.guru.show', $guru)); ?>" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all">
                <i class="fas fa-eye mr-2"></i> Lihat Detail
            </a>
            <a href="<?php echo e(route('admin.guru.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg flex items-center transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3"></i>
            <p><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg flex items-center shadow-sm">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <p><?php echo e(session('error')); ?></p>
        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('admin.guru.update', $guru)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Column 1: Profile Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-blue-100">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-32 relative rounded-t-xl">
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
                        
                        <!-- Foto Upload -->
                        <div class="mb-4">
                            <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Ubah Foto</label>
                            <input type="file" name="foto" id="foto" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG. Max 2MB</p>
                            <?php $__errorArgs = ['foto'];
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
                        
                        <!-- Contact Info -->
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <div class="flex items-center justify-center py-2 px-3 bg-blue-50 rounded-lg text-blue-700">
                                <i class="fas fa-envelope mr-2"></i> 
                                <span class="text-xs"><?php echo e(Str::limit($guru->email, 15)); ?></span>
                            </div>
                            <div class="flex items-center justify-center py-2 px-3 bg-green-50 rounded-lg text-green-700">
                                <i class="fas fa-phone mr-2"></i> 
                                <span class="text-xs"><?php echo e($guru->no_hp); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Column 2-3: Form Fields -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Pribadi -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-user text-blue-500 mr-2"></i> Data Pribadi
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- NIP -->
                            <div>
                                <label for="nip" class="block text-sm font-medium text-gray-700 mb-1">NIP <span class="text-gray-400">(Opsional)</span></label>
                                <input type="text" name="nip" id="nip" value="<?php echo e(old('nip', $guru->nip)); ?>" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Masukkan NIP guru">
                                <?php $__errorArgs = ['nip'];
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

                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" value="<?php echo e(old('nama', $guru->nama)); ?>" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['nama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <?php $__errorArgs = ['nama'];
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

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="email" value="<?php echo e(old('email', $guru->email)); ?>" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <?php $__errorArgs = ['email'];
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

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" id="password" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Kosongkan jika tidak ingin mengubah password">
                                <?php $__errorArgs = ['password'];
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

                            <!-- Jenis Kelamin -->
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" 
                                    class="w-full h-11 px-3 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?php echo e(old('jenis_kelamin', $guru->jenis_kelamin) == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                    <option value="P" <?php echo e(old('jenis_kelamin', $guru->jenis_kelamin) == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                                </select>
                                <?php $__errorArgs = ['jenis_kelamin'];
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

                            <!-- No. HP -->
                            <div>
                                <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                                <input type="text" name="no_hp" id="no_hp" value="<?php echo e(old('no_hp', $guru->no_hp)); ?>" 
                                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    required>
                                <?php $__errorArgs = ['no_hp'];
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
                        </div>
                        
                        <!-- Alamat -->
                        <div class="mt-6">
                            <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Masukkan alamat guru (opsional)"><?php echo e(old('alamat', $guru->alamat)); ?></textarea>
                            <?php $__errorArgs = ['alamat'];
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
                    </div>
                </div>

                <!-- Status dan Peran -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="font-semibold text-gray-800">
                            <i class="fas fa-user-shield text-blue-500 mr-2"></i> Status dan Peran
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Wali Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Status Wali Kelas</label>
                                <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-blue-50">
                                    <input type="checkbox" name="is_wali_kelas" id="is_wali_kelas" value="1" 
                                        class="rounded border-gray-300 text-blue-600 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                                        <?php echo e(old('is_wali_kelas', $guru->is_wali_kelas ?? false) ? 'checked' : ''); ?>

                                        onchange="toggleKelasSelection()">
                                    <label for="is_wali_kelas" class="ml-3 flex items-center">
                                        <i class="fas fa-user-shield text-blue-600 mr-2"></i>
                                        <span class="text-gray-700">Jadikan sebagai Wali Kelas</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Wali kelas memiliki tanggung jawab khusus untuk membimbing satu kelas</p>
                                
                                <!-- Pilihan Kelas (muncul jika wali kelas dicentang) -->
                                <div id="kelas-selection" class="mt-4" style="display: <?php echo e(old('is_wali_kelas', $guru->is_wali_kelas ?? false) ? 'block' : 'none'); ?>">
                                    <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas yang Dipimpin</label>
                                    <select name="wali_kelas_id" id="wali_kelas_id" 
                                        class="w-full h-11 px-3 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 <?php $__errorArgs = ['wali_kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">-- Pilih Kelas --</option>
                                        <?php if(isset($kelas) && $kelas->count() > 0): ?>
                                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelasItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $hasWaliKelas = !is_null($kelasItem->wali_kelas);
                                                    $isCurrentGuruWali = $kelasItem->wali_kelas == $guru->id;
                                                    $hasOtherWali = $hasWaliKelas && !$isCurrentGuruWali;
                                                ?>
                                                <option value="<?php echo e($kelasItem->id); ?>" 
                                                    <?php echo e(old('wali_kelas_id', $guru->kelasWali->id ?? '') == $kelasItem->id ? 'selected' : ''); ?>

                                                    <?php echo e($hasOtherWali ? 'disabled' : ''); ?>>
                                                    <?php echo e($kelasItem->nama_kelas); ?>

                                                    <?php if($isCurrentGuruWali): ?>
                                                        (Wali kelas saat ini)
                                                    <?php elseif($hasOtherWali): ?>
                                                        (Sudah ada wali kelas)
                                                    <?php endif; ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <option value="" disabled>Tidak ada kelas yang tersedia</option>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['wali_kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <p class="text-xs text-gray-500 mt-1">Kelas yang sudah memiliki wali kelas tidak dapat dipilih</p>
                                </div>
                            </div>

                            <!-- Status Aktif -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Status Guru</label>
                                <div class="flex items-center p-4 border border-gray-200 rounded-lg bg-green-50">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                        class="rounded border-gray-300 text-green-600 focus:ring focus:ring-green-200 focus:ring-opacity-50" 
                                        <?php echo e(old('is_active', $guru->is_active ?? true) ? 'checked' : ''); ?>>
                                    <label for="is_active" class="ml-3 flex items-center">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        <span class="text-gray-700">Status Aktif</span>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">Guru yang tidak aktif tidak dapat mengakses sistem</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Pastikan semua data telah terisi dengan benar sebelum menyimpan
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all">
                                    <i class="fas fa-save mr-2"></i> 
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function toggleKelasSelection() {
    const checkbox = document.getElementById('is_wali_kelas');
    const kelasSelection = document.getElementById('kelas-selection');
    const kelasSelect = document.getElementById('wali_kelas_id');
    
    if (checkbox.checked) {
        kelasSelection.style.display = 'block';
        kelasSelect.required = true;
    } else {
        kelasSelection.style.display = 'none';
        kelasSelect.required = false;
        kelasSelect.value = ''; // Reset selection
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleKelasSelection();
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\guru\edit.blade.php ENDPATH**/ ?>