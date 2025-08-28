

<?php $__env->startSection('title', 'Formulir Pendaftaran PPDB - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto py-8 px-4">
    <div class="max-w-5xl mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-blue-600 text-white">
            <h1 class="text-2xl font-bold">Formulir Pendaftaran Peserta Didik Baru (PPDB)</h1>
            <p class="text-sm">Tahun Ajaran <?php echo e($ppdb_year); ?></p>
            <p class="text-sm mt-1">Periode Pendaftaran: <?php echo e(\Carbon\Carbon::parse($ppdb_start_date)->format('d F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($ppdb_end_date)->format('d F Y')); ?></p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 my-4 mx-6" role="alert">
                <p><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4 mx-6" role="alert">
                <p><?php echo e(session('error')); ?></p>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 my-4 mx-6" role="alert">
                <p class="font-bold">Terdapat kesalahan pada formulir:</p>
                <ul class="mt-2 list-disc list-inside">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('pendaftaran.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6">
            <?php echo csrf_field(); ?>            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Data Pribadi Siswa</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-venus-mars"></i>
                            </span>
                            <select name="jenis_kelamin" id="jenis_kelamin" required
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" <?php echo e(old('jenis_kelamin') == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo e(old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text" name="nisn" id="nisn" value="<?php echo e(old('nisn')); ?>" required pattern="[0-9]{10}"
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Nomor Induk Siswa Nasional (10 digit)</p>
                    </div>
                    
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-map-marker-alt"></i>
                            </span>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="<?php echo e(old('tempat_lahir')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-calendar-alt"></i>
                            </span>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">Agama <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-pray"></i>
                            </span>
                            <select name="agama" id="agama" required
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Agama</option>
                                <option value="Islam" <?php echo e(old('agama') == 'Islam' ? 'selected' : ''); ?>>Islam</option>
                                <option value="Kristen" <?php echo e(old('agama') == 'Kristen' ? 'selected' : ''); ?>>Kristen</option>
                                <option value="Katolik" <?php echo e(old('agama') == 'Katolik' ? 'selected' : ''); ?>>Katolik</option>
                                <option value="Hindu" <?php echo e(old('agama') == 'Hindu' ? 'selected' : ''); ?>>Hindu</option>
                                <option value="Buddha" <?php echo e(old('agama') == 'Buddha' ? 'selected' : ''); ?>>Buddha</option>
                                <option value="Konghucu" <?php echo e(old('agama') == 'Konghucu' ? 'selected' : ''); ?>>Konghucu</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-home"></i>
                            </span>
                            <textarea name="alamat" id="alamat" rows="3" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('alamat')); ?></textarea>
                        </div>
                    </div>
                    
                    <div>
                        <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" name="telepon" id="telepon" value="<?php echo e(old('telepon')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email')); ?>"
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="asal_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Asal Sekolah <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-school"></i>
                            </span>
                            <input type="text" name="asal_sekolah" id="asal_sekolah" value="<?php echo e(old('asal_sekolah')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </div>
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Data Orang Tua</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nama_ayah" class="block text-sm font-medium text-gray-700 mb-1">Nama Ayah <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="nama_ayah" id="nama_ayah" value="<?php echo e(old('nama_ayah')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="nama_ibu" class="block text-sm font-medium text-gray-700 mb-1">Nama Ibu <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" name="nama_ibu" id="nama_ibu" value="<?php echo e(old('nama_ibu')); ?>" required 
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="pekerjaan_ayah" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ayah</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah" value="<?php echo e(old('pekerjaan_ayah')); ?>"
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="pekerjaan_ibu" class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan Ibu</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-briefcase"></i>
                            </span>
                            <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu" value="<?php echo e(old('pekerjaan_ibu')); ?>"
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div>
                        <label for="telepon_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon Orang Tua <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="tel" name="telepon_orangtua" id="telepon_orangtua" value="<?php echo e(old('telepon_orangtua')); ?>" required
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="alamat_orangtua" class="block text-sm font-medium text-gray-700 mb-1">Alamat Orang Tua</label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-home"></i>
                            </span>
                            <textarea name="alamat_orangtua" id="alamat_orangtua" rows="3"
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500"><?php echo e(old('alamat_orangtua')); ?></textarea>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika sama dengan alamat siswa</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Data Akademik</h2>
                    
                    <div>
                        <label for="pilihan_jurusan_1" class="block text-sm font-medium text-gray-700 mb-1">Pilihan Jurusan <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex rounded-md shadow-sm">
                            <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                <i class="fas fa-graduation-cap"></i>
                            </span>
                            <select name="pilihan_jurusan_1" id="pilihan_jurusan_1" required
                                class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Pilih Jurusan</option>
                                <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($j->id); ?>" <?php echo e(old('pilihan_jurusan_1') == $j->id ? 'selected' : ''); ?>><?php echo e($j->nama_jurusan); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Nilai Ujian Nasional</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="nilai_matematika" class="block text-sm font-medium text-gray-700 mb-1">Nilai Matematika</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-calculator"></i>
                                    </span>
                                    <input type="number" name="nilai_matematika" id="nilai_matematika" value="<?php echo e(old('nilai_matematika')); ?>" min="0" max="100" step="0.01"
                                        class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="nilai_indonesia" class="block text-sm font-medium text-gray-700 mb-1">Nilai Bahasa Indonesia</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-book"></i>
                                    </span>
                                    <input type="number" name="nilai_indonesia" id="nilai_indonesia" value="<?php echo e(old('nilai_indonesia')); ?>" min="0" max="100" step="0.01"
                                        class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <label for="nilai_inggris" class="block text-sm font-medium text-gray-700 mb-1">Nilai Bahasa Inggris</label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500">
                                        <i class="fas fa-language"></i>
                                    </span>
                                    <input type="number" name="nilai_inggris" id="nilai_inggris" value="<?php echo e(old('nilai_inggris')); ?>" min="0" max="100" step="0.01"
                                        class="flex-1 min-w-0 block w-full border-gray-300 rounded-none rounded-r-md focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
              <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 border-b pb-2 mb-4">Dokumen Persyaratan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="dokumen_foto" class="block text-sm font-medium text-gray-700 mb-1">Pas Foto 3x4 <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-image text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="dokumen_foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Unggah Foto</span>
                                            <input type="file" name="dokumen_foto" id="dokumen_foto" required class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Format: JPG, PNG. Ukuran maks: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="dokumen_ijazah" class="block text-sm font-medium text-gray-700 mb-1">Scan Ijazah (jika ada)</label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="dokumen_ijazah" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Unggah Ijazah</span>
                                            <input type="file" name="dokumen_ijazah" id="dokumen_ijazah" class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Ukuran maks: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="dokumen_skhun" class="block text-sm font-medium text-gray-700 mb-1">Scan SKHUN (jika ada)</label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="dokumen_skhun" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Unggah SKHUN</span>
                                            <input type="file" name="dokumen_skhun" id="dokumen_skhun" class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Ukuran maks: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="dokumen_kk" class="block text-sm font-medium text-gray-700 mb-1">Scan Kartu Keluarga <span class="text-red-500">*</span></label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="dokumen_kk" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Unggah KK</span>
                                            <input type="file" name="dokumen_kk" id="dokumen_kk" required class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Ukuran maks: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="dokumen_ktp_ortu" class="block text-sm font-medium text-gray-700 mb-1">Scan KTP Orang Tua</label>
                        <div class="mt-1">
                            <div class="flex items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-alt text-gray-400 text-3xl mb-3"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="dokumen_ktp_ortu" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Unggah KTP</span>
                                            <input type="file" name="dokumen_ktp_ortu" id="dokumen_ktp_ortu" class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">Format: PDF, JPG, PNG. Ukuran maks: 2MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-blue-800">Perhatian!</h4>
                            <p class="mt-2 text-sm text-blue-700">
                                Pastikan semua data yang Anda masukkan sudah benar sebelum mengirimkan formulir pendaftaran. 
                                Setelah pendaftaran berhasil, Anda akan mendapatkan nomor pendaftaran yang dapat digunakan untuk melihat status pendaftaran Anda.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
              <div class="pt-6">
                <div class="flex items-center justify-between">
                    <a href="<?php echo e(url('/')); ?>" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="max-w-5xl mx-auto mt-6 bg-gray-50 rounded-lg border border-gray-200 p-4 text-center">
        <p class="text-sm text-gray-600">
            Sudah mendaftar? <a href="<?php echo e(route('pendaftaran.check')); ?>" class="font-medium text-blue-600 hover:text-blue-500">Cek Status Pendaftaran</a>
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\index.blade.php ENDPATH**/ ?>