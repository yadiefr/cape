

<?php $__env->startSection('title', 'Edit Profile - SMK PGRI CIKAMPEK'); ?>

<script>
// Define functions immediately to ensure they're available for onclick
function testCameraClick() {
    console.log('=== CAMERA BUTTON CLICKED ===');
    const input = document.getElementById('foto');
    console.log('Foto input found:', !!input);
    if (input) {
        console.log('Triggering input.click()...');
        input.click();
        console.log('Input.click() called successfully');
    } else {
        console.error('❌ Foto input not found!');
    }
}

function clearFileSelection() {
    console.log('Clearing file selection');
    const fotoInput = document.getElementById('foto');
    const fileInfo = document.getElementById('fileInfo');
    if (fotoInput) fotoInput.value = '';
    if (fileInfo) fileInfo.classList.add('hidden');
}
</script>

<?php $__env->startSection('main-content'); ?>
<div class="container px-3 py-4">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-edit text-blue-600 mr-3"></i>
                    Edit Profile
                </h1>
                <p class="text-gray-600 mt-1">Perbarui informasi profil dan akun Anda</p>
            </div>
            <div>
                <a href="<?php echo e(route('guru.profile.index')); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Picture & Basic Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="text-center">
                <div class="relative inline-block group">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <?php if($user->foto): ?>
                            <img src="<?php echo e(asset('storage/' . $user->foto)); ?>" alt="Profile Picture" class="w-32 h-32 rounded-full object-cover" id="currentPhoto">
                        <?php else: ?>
                            <i class="fas fa-user text-white text-4xl" id="currentPhoto"></i>
                        <?php endif; ?>
                    </div>
                    <button type="button" onclick="testCameraClick()" class="absolute bottom-2 right-2 bg-blue-600 text-white rounded-full p-2 hover:bg-blue-700 transition-colors shadow-lg">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                <h3 class="text-xl font-semibold text-gray-800"><?php echo e($user->nama); ?></h3>
                <p class="text-gray-600"><?php echo e($user->email); ?></p>
                <div class="mt-3">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                        Guru
                    </span>
                </div>
                
                <!-- File Info Display -->
                <div id="fileInfo" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                    <div class="flex items-center justify-between">
                        <div id="fileDetails" class="text-sm text-blue-700"></div>
                        <button type="button" onclick="clearFileSelection()" class="text-red-600 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Upload Instructions -->
                <div class="mt-3 text-xs text-gray-500">
                    <p>Klik ikon kamera untuk mengubah foto</p>
                    <p>JPG, PNG, GIF maksimal 2MB</p>
                </div>
                
                <!-- Error Display -->
                <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-red-500 text-xs"><?php echo e($message); ?></p>
                    </div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('guru.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <!-- Hidden File Input -->
                <input type="file" name="foto" id="foto" accept="image/*" class="hidden">

                <!-- Personal Information -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle text-blue-600 mr-2"></i>
                        Informasi Personal
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" value="<?php echo e(old('nama', $user->nama)); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Masukkan nama lengkap" required>
                            <?php $__errorArgs = ['nama'];
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

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" id="email" value="<?php echo e(old('email', $user->email)); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Masukkan alamat email" required>
                            <?php $__errorArgs = ['email'];
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

                        <!-- Phone -->
                        <div>
                            <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" name="no_hp" id="no_hp" value="<?php echo e(old('no_hp', $user->no_hp ?? '')); ?>" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Masukkan nomor telepon">
                            <?php $__errorArgs = ['no_hp'];
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

                        <!-- Gender -->
                        <div>
                            <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">Pilih jenis kelamin</option>
                                <option value="L" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                                <option value="P" <?php echo e(old('jenis_kelamin', $user->jenis_kelamin ?? '') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                            </select>
                            <?php $__errorArgs = ['jenis_kelamin'];
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

                    <!-- Address -->
                    <div class="mt-6">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3" 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                  placeholder="Masukkan alamat lengkap"><?php echo e(old('alamat', $user->alamat ?? '')); ?></textarea>
                        <?php $__errorArgs = ['alamat'];
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

                <!-- Security Settings -->
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-shield-alt text-blue-600 mr-2"></i>
                        Keamanan Akun
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Masukkan password saat ini">
                            <?php $__errorArgs = ['current_password'];
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

                        <div></div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" id="password" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Masukkan password baru">
                            <?php $__errorArgs = ['password'];
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

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   placeholder="Konfirmasi password baru">
                        </div>
                    </div>
                    
                    <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-yellow-600 mt-0.5 mr-2"></i>
                            <div class="text-sm text-yellow-700">
                                <p class="font-medium">Catatan Keamanan:</p>
                                <ul class="mt-1 list-disc list-inside space-y-1">
                                    <li>Kosongkan field password jika tidak ingin mengubah password</li>
                                    <li>Password harus minimal 8 karakter</li>
                                    <li>Gunakan kombinasi huruf, angka, dan simbol untuk keamanan yang lebih baik</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                    <button type="reset" class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium flex items-center justify-center">
                        <i class="fas fa-undo mr-2"></i>
                        Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up photo functionality');
    
    // Get elements
    const fotoInput = document.getElementById('foto');
    const currentPhoto = document.getElementById('currentPhoto');
    const fileInfo = document.getElementById('fileInfo');
    const fileDetails = document.getElementById('fileDetails');
    
    console.log('Elements check:', {
        fotoInput: !!fotoInput,
        currentPhoto: !!currentPhoto,
        fileInfo: !!fileInfo,
        fileDetails: !!fileDetails
    });
    
    // Make sure foto input exists
    if (!fotoInput) {
        console.error('❌ Foto input not found!');
        return;
    }
    
    // Handle file selection
    fotoInput.addEventListener('change', function(e) {
        console.log('=== FILE SELECTION START ===');
        console.log('Event target:', e.target);
        console.log('Files array:', e.target.files);
        console.log('Files length:', e.target.files ? e.target.files.length : 'null');
        
        if (e.target.files && e.target.files.length > 0) {
            const file = e.target.files[0];
            console.log('✅ File selected:', {
                name: file.name,
                type: file.type,
                size: file.size + ' bytes (' + (file.size/1024/1024).toFixed(2) + ' MB)',
                lastModified: new Date(file.lastModified).toLocaleString()
            });
            
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                console.error('❌ Invalid file type:', file.type);
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau GIF.');
                e.target.value = '';
                return;
            }
            console.log('✅ File type validation passed');
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                console.error('❌ File too large:', file.size);
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                e.target.value = '';
                return;
            }
            console.log('✅ File size validation passed');
            
            // Show file info
            if (fileDetails && fileInfo) {
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                fileDetails.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-file-image text-blue-600 mr-2"></i>
                        <span class="font-medium">${file.name}</span>
                        <span class="text-gray-500 ml-2">(${fileSize} MB)</span>
                    </div>
                `;
                fileInfo.classList.remove('hidden');
                console.log('✅ File info displayed');
            }
            
            // === CRITICAL: PHOTO PREVIEW SECTION ===
            console.log('🖼️ === STARTING PHOTO PREVIEW ===');
            
            // Check photo element BEFORE FileReader
            const photoElementBefore = document.getElementById('currentPhoto');
            console.log('📍 Photo element BEFORE:', {
                found: !!photoElementBefore,
                tagName: photoElementBefore ? photoElementBefore.tagName : 'null',
                id: photoElementBefore ? photoElementBefore.id : 'null',
                className: photoElementBefore ? photoElementBefore.className : 'null',
                src: photoElementBefore && photoElementBefore.tagName === 'IMG' ? photoElementBefore.src : 'N/A',
                parent: photoElementBefore && photoElementBefore.parentNode ? photoElementBefore.parentNode.tagName : 'null'
            });
            
            // Start FileReader
            console.log('📖 Initializing FileReader...');
            const reader = new FileReader();
            
            reader.onloadstart = function() {
                console.log('📖 FileReader started loading...');
            };
            
            reader.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentLoaded = Math.round((e.loaded / e.total) * 100);
                    console.log('📖 FileReader progress:', percentLoaded + '%');
                }
            };
            
            reader.onload = function(readerEvent) {
                console.log('🎯 === FILEREADER ONLOAD TRIGGERED ===');
                
                const result = readerEvent.target.result;
                console.log('📊 FileReader result:', {
                    type: typeof result,
                    length: result ? result.length : 0,
                    isDataURL: result ? result.startsWith('data:') : false,
                    preview: result ? result.substring(0, 50) + '...' : 'null'
                });
                
                if (!result || !result.startsWith('data:')) {
                    console.error('❌ Invalid FileReader result!');
                    return;
                }
                
                // Re-find photo element AFTER FileReader completes
                const photoElement = document.getElementById('currentPhoto');
                console.log('� Photo element AFTER FileReader:', {
                    found: !!photoElement,
                    tagName: photoElement ? photoElement.tagName : 'null',
                    id: photoElement ? photoElement.id : 'null',
                    className: photoElement ? photoElement.className : 'null',
                    currentSrc: photoElement && photoElement.tagName === 'IMG' ? photoElement.src : 'N/A'
                });
                
                if (!photoElement) {
                    console.error('❌ Photo element disappeared! Searching for alternatives...');
                    
                    // Search for any element that might be our photo
                    const alternatives = [
                        document.querySelector('.w-32.h-32.rounded-full'),
                        document.querySelector('[id*="photo"]'),
                        document.querySelector('[id*="Photo"]'),
                        document.querySelector('img'),
                        document.querySelector('i.fa-user')
                    ];
                    
                    console.log('🔍 Alternative elements found:');
                    alternatives.forEach((alt, i) => {
                        if (alt) {
                            console.log(`  Alt ${i}:`, alt.tagName, alt.id, alt.className);
                        }
                    });
                    return;
                }
                
                // UPDATE PHOTO PREVIEW
                try {
                    console.log('🔄 Attempting to update photo preview...');
                    
                    if (photoElement.tagName === 'IMG') {
                        console.log('� Updating existing IMG element...');
                        console.log('  Old src:', photoElement.src);
                        
                        photoElement.src = result;
                        photoElement.alt = 'Preview Photo';
                        
                        console.log('  New src set:', photoElement.src.substring(0, 50) + '...');
                        console.log('✅ IMG element updated successfully!');
                        
                        // Force refresh
                        photoElement.style.display = 'none';
                        setTimeout(() => {
                            photoElement.style.display = '';
                            console.log('🔄 Display refreshed');
                        }, 10);
                        
                    } else {
                        console.log('🔄 Replacing', photoElement.tagName, 'with new IMG...');
                        
                        // Create new image element
                        const newImg = document.createElement('img');
                        newImg.src = result;
                        newImg.alt = 'Preview Photo';
                        newImg.className = 'w-32 h-32 rounded-full object-cover';
                        newImg.id = 'currentPhoto';
                        
                        console.log('🆕 New IMG created:', {
                            src: newImg.src.substring(0, 50) + '...',
                            className: newImg.className,
                            id: newImg.id
                        });
                        
                        // Replace element
                        const parent = photoElement.parentNode;
                        console.log('👨‍👦 Parent element:', parent.tagName, parent.className);
                        
                        parent.replaceChild(newImg, photoElement);
                        console.log('✅ Element replaced successfully!');
                        
                        // Verify replacement
                        const verifyElement = document.getElementById('currentPhoto');
                        console.log('✅ Verification - new element:', {
                            found: !!verifyElement,
                            tagName: verifyElement ? verifyElement.tagName : 'null',
                            src: verifyElement && verifyElement.src ? verifyElement.src.substring(0, 50) + '...' : 'null'
                        });
                    }
                    
                    console.log('🎉 === PHOTO PREVIEW UPDATE COMPLETED SUCCESSFULLY! ===');
                    
                } catch (error) {
                    console.error('❌ CRITICAL ERROR updating photo preview:', error);
                    console.error('Error stack:', error.stack);
                    console.error('Error name:', error.name);
                    console.error('Error message:', error.message);
                }
            };
            
            reader.onerror = function(error) {
                console.error('❌ FileReader failed:', error);
            };
            
            reader.onloadend = function() {
                console.log('📖 FileReader finished (success or error)');
            };
            
            // Start reading file
            console.log('📖 Starting readAsDataURL...');
            reader.readAsDataURL(file);
            
        } else {
            console.log('❌ No file selected or files array empty');
            console.log('Files check:', {
                hasFiles: !!e.target.files,
                filesLength: e.target.files ? e.target.files.length : 'null',
                targetValue: e.target.value
            });
        }
        
        console.log('=== FILE SELECTION END ===');
    });

    // Password validation
    const newPassword = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    if (newPassword && confirmPassword) {
        function validatePasswords() {
            if (newPassword.value && confirmPassword.value) {
                if (newPassword.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Password tidak cocok');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }
        }

        newPassword.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\profile\edit.blade.php ENDPATH**/ ?>