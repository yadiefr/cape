

<?php $__env->startSection('title', 'Edit Pendaftaran PPDB - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container px-3 py-4">    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Edit Data Pendaftaran PPDB</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.ppdb.dashboard')); ?>" class="hover:text-blue-600">PPDB</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.ppdb.index')); ?>" class="hover:text-blue-600">Data Pendaftaran</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Edit</span>
                </li>
            </ul>
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
    <?php endif; ?>    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <!-- Header Actions -->
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-edit text-blue-600 mr-2"></i>
                        Edit Data Pendaftaran
                    </h2>
                    <p class="text-sm text-gray-500 mt-1"><?php echo e($pendaftaran->nomor_pendaftaran); ?></p>
                </div>
            </div>
        </div>

        <form action="<?php echo e(route('admin.ppdb.update', $pendaftaran->id)); ?>" method="POST" class="p-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <!-- Data Pribadi -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-address-card text-blue-600 mr-2"></i>
                        Data Pribadi
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo e(old('nama_lengkap', $pendaftaran->nama_lengkap)); ?>" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nama_lengkap'];
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
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="Laki-laki" <?php echo e(old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                            <option value="Perempuan" <?php echo e(old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
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
                    
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" id="nisn" value="<?php echo e(old('nisn', $pendaftaran->nisn)); ?>" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nisn'];
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
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" value="<?php echo e(old('tempat_lahir', $pendaftaran->tempat_lahir)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['tempat_lahir'];
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
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo e(old('tanggal_lahir', $pendaftaran->tanggal_lahir ? $pendaftaran->tanggal_lahir->format('Y-m-d') : '')); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['tanggal_lahir'];
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
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama</label>
                        <input type="text" name="agama" id="agama" value="<?php echo e(old('agama', $pendaftaran->agama)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['agama'];
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
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="<?php echo e(old('telepon', $pendaftaran->telepon)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['telepon'];
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
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="<?php echo e(old('email', $pendaftaran->email)); ?>"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
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

                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="2" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"><?php echo e(old('alamat', $pendaftaran->alamat)); ?></textarea>
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

                    <div>
                        <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah</label>
                        <input type="text" name="asal_sekolah" id="asal_sekolah" value="<?php echo e(old('asal_sekolah', $pendaftaran->asal_sekolah)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['asal_sekolah'];
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
            </div>            <!-- Data Orang Tua -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Data Orang Tua
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">
                    <div>
                        <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah</label>
                        <input type="text" name="nama_ayah" id="nama_ayah" value="<?php echo e(old('nama_ayah', $pendaftaran->nama_ayah)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nama_ayah'];
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
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu</label>
                        <input type="text" name="nama_ibu" id="nama_ibu" value="<?php echo e(old('nama_ibu', $pendaftaran->nama_ibu)); ?>" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nama_ibu'];
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
                        <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="<?php echo e(old('pekerjaan_ayah', $pendaftaran->pekerjaan_ayah)); ?>"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['pekerjaan_ayah'];
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
                        <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="<?php echo e(old('pekerjaan_ibu', $pendaftaran->pekerjaan_ibu)); ?>"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['pekerjaan_ibu'];
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
                        <label for="telepon_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Telepon Orang Tua</label>
                        <input type="text" name="telepon_orangtua" id="telepon_orangtua" value="<?php echo e(old('telepon_orangtua', $pendaftaran->telepon_orangtua)); ?>"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['telepon_orangtua'];
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

                    <div class="md:col-span-2">
                        <label for="alamat_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Alamat Orang Tua</label>
                        <textarea name="alamat_orangtua" id="alamat_orangtua" rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"><?php echo e(old('alamat_orangtua', $pendaftaran->alamat_orangtua)); ?></textarea>
                        <div class="mt-1 text-sm text-gray-500">Kosongkan jika sama dengan alamat siswa</div>
                        <?php $__errorArgs = ['alamat_orangtua'];
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
            </div>            <!-- Data Akademik -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                        Data Akademik
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mb-6 bg-white border border-gray-100 rounded-lg p-4">                    <div>
                        <label for="pilihan_jurusan_1" class="block text-sm font-medium text-gray-700 mb-1">Pilihan Jurusan</label>
                        <select name="pilihan_jurusan_1" id="pilihan_jurusan_1" required 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <option value="">Pilih Jurusan</option>
                            <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($j->id); ?>" <?php echo e(old('pilihan_jurusan_1', $pendaftaran->pilihan_jurusan_1) == $j->id ? 'selected' : ''); ?>>
                                    <?php echo e($j->nama_jurusan); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['pilihan_jurusan_1'];
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
                        <label for="nilai_matematika" class="block text-sm font-medium text-gray-700 mb-1">Nilai Matematika</label>
                        <input type="number" name="nilai_matematika" id="nilai_matematika" value="<?php echo e(old('nilai_matematika', $pendaftaran->nilai_matematika)); ?>" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nilai_matematika'];
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
                        <label for="nilai_indonesia" class="block text-sm font-medium text-gray-700 mb-1">Nilai B. Indonesia</label>
                        <input type="number" name="nilai_indonesia" id="nilai_indonesia" value="<?php echo e(old('nilai_indonesia', $pendaftaran->nilai_indonesia)); ?>" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nilai_indonesia'];
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
                        <label for="nilai_inggris" class="block text-sm font-medium text-gray-700 mb-1">Nilai B. Inggris</label>
                        <input type="number" name="nilai_inggris" id="nilai_inggris" value="<?php echo e(old('nilai_inggris', $pendaftaran->nilai_inggris)); ?>" min="0" max="100" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <?php $__errorArgs = ['nilai_inggris'];
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
            </div>            <!-- Status Pendaftaran -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border border-gray-200 rounded-lg mb-4">
                    <h3 class="text-base font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-check-circle text-blue-600 mr-2"></i>
                        Status Pendaftaran
                    </h3>
                </div>                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-100 rounded-lg p-4">                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50
                            <?php if(old('status', $pendaftaran->status) == 'diterima'): ?> 
                                bg-green-50 text-green-700 border-green-300
                            <?php elseif(old('status', $pendaftaran->status) == 'ditolak'): ?>
                                bg-red-50 text-red-700 border-red-300
                            <?php elseif(old('status', $pendaftaran->status) == 'menunggu'): ?>
                                bg-yellow-50 text-yellow-700 border-yellow-300
                            <?php else: ?>
                                bg-purple-50 text-purple-700 border-purple-300
                            <?php endif; ?>">
                            <option value="menunggu" <?php echo e(old('status', $pendaftaran->status) == 'menunggu' ? 'selected' : ''); ?>>⌛ Menunggu</option>
                            <option value="diterima" <?php echo e(old('status', $pendaftaran->status) == 'diterima' ? 'selected' : ''); ?>>✅ Diterima</option>
                            <option value="ditolak" <?php echo e(old('status', $pendaftaran->status) == 'ditolak' ? 'selected' : ''); ?>>❌ Ditolak</option>
                            <option value="cadangan" <?php echo e(old('status', $pendaftaran->status) == 'cadangan' ? 'selected' : ''); ?>>⭐ Cadangan</option>
                        </select>
                        <?php $__errorArgs = ['status'];
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

                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="2"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"><?php echo e(old('keterangan', $pendaftaran->keterangan)); ?></textarea>
                        <?php $__errorArgs = ['keterangan'];
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
            </div>            <!-- Submit Button -->
            <div class="mt-8 bg-gray-50 p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex justify-between items-center">
                    <a href="<?php echo e(route('admin.ppdb.show', $pendaftaran->id)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg border border-gray-200 shadow-sm transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                    <div class="flex gap-3">
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ppdb\edit.blade.php ENDPATH**/ ?>