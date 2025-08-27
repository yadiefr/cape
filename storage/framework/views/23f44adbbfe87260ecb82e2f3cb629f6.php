

<?php $__env->startSection('title', 'Formulir Pendaftaran PPDB - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('content'); ?>
<div class="ppdb-card">
    <!-- Header -->
    <div class="ppdb-section-header">
        <h2 class="text-center mb-0">Formulir Pendaftaran PPDB Online</h2>
    </div>

    <div class="mt-4">
        <div class="ppdb-title">Pendaftaran PPDB Online</div>
        <p class="ppdb-subtitle">
            SMK PGRI CIKAMPEK - Tahun Ajaran <?php echo e($ppdb_year); ?>

        </p>
        
        <div class="text-center mb-4">
            <span class="badge bg-primary px-3 py-2 rounded-pill fw-normal d-inline-flex align-items-center">
                <i class="fas fa-calendar-alt me-2"></i>
                Periode: <?php echo e(\Carbon\Carbon::parse($ppdb_start_date)->format('d F Y')); ?> - <?php echo e(\Carbon\Carbon::parse($ppdb_end_date)->format('d F Y')); ?>

            </span>
        </div>
    </div>

    <!-- Informasi Status -->
    <div class="help-section mb-4">
        <div class="d-flex flex-column flex-md-row align-items-center gap-3">
            <div class="flex-shrink-0">
                <div class="help-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
            </div>
            <div>
                <h4 class="mb-1">Sudah mendaftar?</h4>
                <p class="mb-2 text-secondary">
                    Anda dapat memantau status pendaftaran secara berkala melalui halaman status
                </p>
                <a href="<?php echo e(route('pendaftaran.check')); ?>" 
                   class="btn btn-primary btn-sm d-inline-flex align-items-center">
                    <i class="fas fa-search me-2"></i>
                    Cek Status Pendaftaran
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <p class="mb-3">
                Silakan isi formulir di bawah ini dengan data yang benar dan lengkap. Pastikan semua kolom yang bertanda <span class="text-danger">*</span> diisi.
            </p>

            <!-- Alert Messages -->
            <?php if(session('success')): ?>
                <div class="alert alert-success d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-check-circle me-3 fa-lg"></i>
                    <div>
                        <?php echo e(session('success')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-3 fa-lg"></i>
                    <div>
                        <?php echo e(session('error')); ?>

                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <div class="d-flex mb-2">
                        <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                        <div class="fw-bold">Mohon periksa kembali formulir:</div>
                    </div>
                    <ul class="ms-4 mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
            
    <!-- Form -->
    <form action="<?php echo e(route('pendaftaran.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        
        <!-- Data Pribadi -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-circle fa-lg me-2"></i>
                    <h5 class="mb-0">Data Pribadi Siswa</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_lengkap" value="<?php echo e(old('nama_lengkap')); ?>" required
                                class="form-control <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['nama_lengkap'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            NISN <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-id-card"></i></span>
                            <input type="text" name="nisn" value="<?php echo e(old('nisn')); ?>" required
                                class="form-control <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['nisn'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-hint">Masukkan 10 digit NISN</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Jenis Kelamin
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-venus-mars"></i></span>
                            <select name="jenis_kelamin" 
                                class="form-control form-select <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih</option>
                                <option value="Laki-laki" <?php echo e(old('jenis_kelamin') == 'Laki-laki' ? 'selected' : ''); ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo e(old('jenis_kelamin') == 'Perempuan' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                            <?php $__errorArgs = ['jenis_kelamin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Agama
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-pray"></i></span>
                            <select name="agama" 
                                class="form-control form-select <?php $__errorArgs = ['agama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih</option>
                                <option value="Islam" <?php echo e(old('agama') == 'Islam' ? 'selected' : ''); ?>>Islam</option>
                                <option value="Kristen" <?php echo e(old('agama') == 'Kristen' ? 'selected' : ''); ?>>Kristen</option>
                                <option value="Katolik" <?php echo e(old('agama') == 'Katolik' ? 'selected' : ''); ?>>Katolik</option>
                                <option value="Hindu" <?php echo e(old('agama') == 'Hindu' ? 'selected' : ''); ?>>Hindu</option>
                                <option value="Buddha" <?php echo e(old('agama') == 'Buddha' ? 'selected' : ''); ?>>Buddha</option>
                                <option value="Konghucu" <?php echo e(old('agama') == 'Konghucu' ? 'selected' : ''); ?>>Konghucu</option>
                            </select>
                            <?php $__errorArgs = ['agama'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tempat Lahir
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="tempat_lahir" value="<?php echo e(old('tempat_lahir')); ?>" 
                                class="form-control <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['tempat_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Tanggal Lahir
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-calendar"></i></span>
                            <input type="date" name="tanggal_lahir" value="<?php echo e(old('tanggal_lahir')); ?>" 
                                class="form-control <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['tanggal_lahir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            No. Handphone
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-phone"></i></span>
                            <input type="text" name="telepon" value="<?php echo e(old('telepon')); ?>" 
                                class="form-control <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['telepon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Email
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" value="<?php echo e(old('email')); ?>" 
                                class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Alamat Lengkap
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-home"></i></span>
                            <textarea name="alamat" rows="2" 
                                class="form-control <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Masukkan alamat lengkap"><?php echo e(old('alamat')); ?></textarea>
                            <?php $__errorArgs = ['alamat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Asal Sekolah
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-school"></i></span>
                            <input type="text" name="asal_sekolah" value="<?php echo e(old('asal_sekolah')); ?>" 
                                class="form-control <?php $__errorArgs = ['asal_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Masukkan nama sekolah asal">
                            <?php $__errorArgs = ['asal_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Data Orang Tua -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users fa-lg me-2"></i>
                    <h5 class="mb-0">Data Orang Tua</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Ayah
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_ayah" value="<?php echo e(old('nama_ayah')); ?>" 
                                class="form-control <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['nama_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Nama Ibu
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-user"></i></span>
                            <input type="text" name="nama_ibu" value="<?php echo e(old('nama_ibu')); ?>" 
                                class="form-control <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['nama_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Pekerjaan Ayah
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-briefcase"></i></span>
                            <input type="text" name="pekerjaan_ayah" value="<?php echo e(old('pekerjaan_ayah')); ?>" 
                                class="form-control <?php $__errorArgs = ['pekerjaan_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['pekerjaan_ayah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            Pekerjaan Ibu
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-briefcase"></i></span>
                            <input type="text" name="pekerjaan_ibu" value="<?php echo e(old('pekerjaan_ibu')); ?>" 
                                class="form-control <?php $__errorArgs = ['pekerjaan_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__errorArgs = ['pekerjaan_ibu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">
                            No. HP Orang Tua
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="telepon_orangtua" value="<?php echo e(old('telepon_orangtua')); ?>" 
                                class="form-control <?php $__errorArgs = ['telepon_orangtua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Contoh: 08123456789">
                            <?php $__errorArgs = ['telepon_orangtua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">
                            Alamat Orang Tua
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-home"></i></span>
                            <textarea name="alamat_orangtua" rows="2" 
                                class="form-control <?php $__errorArgs = ['alamat_orangtua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="Kosongkan jika sama dengan alamat siswa"><?php echo e(old('alamat_orangtua')); ?></textarea>
                            <?php $__errorArgs = ['alamat_orangtua'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pilihan Jurusan -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-graduation-cap fa-lg me-2"></i>
                    <h5 class="mb-0">Pilihan Jurusan</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <label class="form-label">
                            Jurusan yang Dipilih <span class="text-danger">*</span>
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-list"></i></span>
                            <select name="pilihan_jurusan_1" 
                                class="form-control form-select <?php $__errorArgs = ['pilihan_jurusan_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">Pilih Jurusan</option>
                                <?php $__currentLoopData = $jurusan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($j->id); ?>" <?php echo e(old('pilihan_jurusan_1') == $j->id ? 'selected' : ''); ?>>
                                        <?php echo e($j->nama_jurusan); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['pilihan_jurusan_1'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Nilai Ujian -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-bar fa-lg me-2"></i>
                    <h5 class="mb-0">Nilai Ujian</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            Matematika
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-calculator"></i></span>
                            <input type="number" name="nilai_matematika" value="<?php echo e(old('nilai_matematika')); ?>" 
                                min="0" max="100" step="0.01" 
                                class="form-control <?php $__errorArgs = ['nilai_matematika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            <?php $__errorArgs = ['nilai_matematika'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Bahasa Indonesia
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-book"></i></span>
                            <input type="number" name="nilai_indonesia" value="<?php echo e(old('nilai_indonesia')); ?>" 
                                min="0" max="100" step="0.01" 
                                class="form-control <?php $__errorArgs = ['nilai_indonesia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            <?php $__errorArgs = ['nilai_indonesia'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">
                            Bahasa Inggris
                        </label>
                        <div class="input-group mb-0">
                            <span class="input-group-text" style="background-color: #4facfe; color: white;"><i class="fas fa-language"></i></span>
                            <input type="number" name="nilai_inggris" value="<?php echo e(old('nilai_inggris')); ?>" 
                                min="0" max="100" step="0.01" 
                                class="form-control <?php $__errorArgs = ['nilai_inggris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                placeholder="0-100">
                            <span class="input-group-text">/100</span>
                            <?php $__errorArgs = ['nilai_inggris'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Upload Dokumen -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-secondary bg-gradient text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-upload fa-lg me-2"></i>
                    <h5 class="mb-0">Upload Dokumen</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label mb-2">
                            Pas Foto 3x4
                        </label>
                        <div class="upload-box">
                            <input type="file" name="dokumen_foto" id="dokumen_foto" class="upload-input <?php $__errorArgs = ['dokumen_foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*">
                            <label for="dokumen_foto" class="upload-label">
                                <i class="fas fa-image mb-3 fa-3x text-secondary"></i>
                                <span class="upload-title">Upload Foto</span>
                                <span class="upload-desc">Format: JPG, PNG. Ukuran maks: 2MB</span>
                            </label>
                            <?php $__errorArgs = ['dokumen_foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mb-2">
                            Kartu Keluarga
                        </label>
                        <div class="upload-box">
                            <input type="file" name="dokumen_kk" id="dokumen_kk" class="upload-input <?php $__errorArgs = ['dokumen_kk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,image/*">
                            <label for="dokumen_kk" class="upload-label">
                                <i class="fas fa-id-card mb-3 fa-3x text-secondary"></i>
                                <span class="upload-title">Upload KK</span>
                                <span class="upload-desc">Format: PDF, JPG, PNG. Ukuran maks: 2MB</span>
                            </label>
                            <?php $__errorArgs = ['dokumen_kk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mb-2">
                            Ijazah/SKL
                        </label>
                        <div class="upload-box">
                            <input type="file" name="dokumen_ijazah" id="dokumen_ijazah" class="upload-input <?php $__errorArgs = ['dokumen_ijazah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,image/*">
                            <label for="dokumen_ijazah" class="upload-label">
                                <i class="fas fa-file-pdf mb-3 fa-3x text-secondary"></i>
                                <span class="upload-title">Upload Ijazah</span>
                                <span class="upload-desc">Format: PDF, JPG, PNG. Ukuran maks: 2MB</span>
                            </label>
                            <?php $__errorArgs = ['dokumen_ijazah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label mb-2">
                            KTP Orang Tua
                        </label>
                        <div class="upload-box">
                            <input type="file" name="dokumen_ktp_ortu" id="dokumen_ktp_ortu" class="upload-input <?php $__errorArgs = ['dokumen_ktp_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,image/*">
                            <label for="dokumen_ktp_ortu" class="upload-label">
                                <i class="fas fa-address-card mb-3 fa-3x text-secondary"></i>
                                <span class="upload-title">Upload KTP</span>
                                <span class="upload-desc">Format: PDF, JPG, PNG. Ukuran maks: 2MB</span>
                            </label>
                            <?php $__errorArgs = ['dokumen_ktp_ortu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Submit -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0 d-flex align-items-center">
                        <div class="me-2">
                            <i class="fas fa-info-circle text-primary fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-secondary">
                                <span class="text-danger fw-bold">*</span> Wajib diisi
                            </span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-paper-plane me-2"></i>
                        Kirim Pendaftaran
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .form-label {
        font-weight: 500;
        color: #555;
    }
    
    .form-hint {
        font-size: 0.8rem;
        color: #888;
        margin-top: 0.25rem;
    }
    
    .upload-box {
        position: relative;
        border: 2px dashed #ddd;
        border-radius: 8px;
        text-align: center;
        padding: 25px 15px;
        background: #f9f9f9;
        transition: all 0.3s ease;
    }
    
    .upload-box:hover {
        border-color: #4facfe;
        background: #f0f7ff;
    }
    
    .upload-input {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
        z-index: 10;
    }
    
    .upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        cursor: pointer;
    }
    
    .upload-title {
        font-weight: 500;
        color: #4facfe;
        margin-bottom: 5px;
    }
    
    .upload-desc {
        font-size: 0.8rem;
        color: #888;
    }
    
    .card-header {
        border-radius: 8px 8px 0 0 !important;
        border: none;
    }
    
    .card {
        border-radius: 8px;
        overflow: hidden;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app-ppdb', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\ppdb\form.blade.php ENDPATH**/ ?>