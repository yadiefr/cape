

<?php $__env->startSection('title', 'Tambah Ruangan Ujian'); ?>

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
            <span class="text-gray-900 font-medium">Tambah Ruangan</span>
        </nav>

        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3">
            <div class="flex items-center">
                <div class="p-2 bg-blue-50 rounded-lg mr-3">
                    <i class="fas fa-plus-circle text-lg text-blue-600"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 mb-1">Tambah Ruangan Ujian Baru</h1>
                    <p class="text-gray-600 text-sm">
                        <i class="fas fa-info-circle mr-1"></i>
                        Lengkapi form di bawah untuk menambahkan ruangan ujian baru
                    </p>
                </div>
            </div>
        </div>

        <!-- Full Width Form Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-door-open mr-3 p-2 bg-white rounded-lg text-blue-600 shadow-sm"></i>
                    Informasi Ruangan
                </h2>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-6">
                <!-- Main Form - Takes 4 columns -->
                <div class="lg:col-span-4">
                    <form action="<?php echo e(route('admin.ujian.pengaturan.ruangan.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="kode_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kode Ruangan
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['kode_ruangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="kode_ruangan" 
                                       name="kode_ruangan" 
                                       value="<?php echo e(old('kode_ruangan')); ?>"
                                       placeholder="R001, LAB-1">
                                <?php $__errorArgs = ['kode_ruangan'];
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
                                    <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>Akan dibuat otomatis jika kosong
                                </p>
                            </div>
                            
                            <div>
                                <label for="nama_ruangan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Ruangan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['nama_ruangan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="nama_ruangan" 
                                       name="nama_ruangan" 
                                       value="<?php echo e(old('nama_ruangan')); ?>"
                                       placeholder="Ruang Kelas 1A"
                                       required>
                                <?php $__errorArgs = ['nama_ruangan'];
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
                                <label for="kapasitas" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kapasitas Siswa <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['kapasitas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="kapasitas" 
                                       name="kapasitas" 
                                       value="<?php echo e(old('kapasitas')); ?>"
                                       min="1" 
                                       max="1000"
                                       placeholder="30"
                                       required>
                                <?php $__errorArgs = ['kapasitas'];
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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                        id="status" 
                                        name="status"
                                        required>
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia" <?php echo e(old('status') == 'tersedia' ? 'selected' : ''); ?>>Tersedia</option>
                                    <option value="terpakai" <?php echo e(old('status') == 'terpakai' ? 'selected' : ''); ?>>Terpakai</option>
                                    <option value="maintenance" <?php echo e(old('status') == 'maintenance' ? 'selected' : ''); ?>>Maintenance</option>
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
                            
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i>Lokasi
                                </label>
                                <input type="text" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       id="lokasi" 
                                       name="lokasi" 
                                       value="<?php echo e(old('lokasi')); ?>"
                                       placeholder="Lantai 2, Gedung A">
                                <?php $__errorArgs = ['lokasi'];
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

                        <div class="mt-6">
                            <label for="fasilitas" class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-cogs text-gray-500 mr-1"></i>Fasilitas Ruangan
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="fasilitasContainer">
                                <?php if(old('fasilitas')): ?>
                                    <?php $__currentLoopData = old('fasilitas'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $fasilitas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="fasilitas-item">
                                            <div class="flex">
                                                <input type="text" 
                                                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                       name="fasilitas[]" 
                                                       value="<?php echo e($fasilitas); ?>"
                                                       placeholder="Proyektor, AC, Wifi, Papan Tulis">
                                                <button type="button" class="px-4 py-3 bg-red-500 text-white rounded-r-lg hover:bg-red-600 transition-colors remove-fasilitas">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <div class="fasilitas-item">
                                        <div class="flex">
                                            <input type="text" 
                                                   class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                                   name="fasilitas[]" 
                                                   placeholder="Proyektor, AC, Wifi, Papan Tulis">
                                            <button type="button" class="px-4 py-3 bg-red-500 text-white rounded-r-lg hover:bg-red-600 transition-colors remove-fasilitas">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="mt-3 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors" id="addFasilitas">
                                <i class="fas fa-plus mr-2"></i>Tambah Fasilitas
                            </button>
                        </div>

                        <div class="mt-6">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-alt text-gray-500 mr-1"></i>Deskripsi
                            </label>
                            <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="3"
                                      placeholder="Deskripsi tambahan tentang ruangan..."><?php echo e(old('deskripsi')); ?></textarea>
                            <?php $__errorArgs = ['deskripsi'];
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

                        <div class="flex justify-between items-center pt-6 border-t border-gray-200 mt-8">
                            <a href="<?php echo e(route('admin.ujian.pengaturan.ruangan.index')); ?>" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-save mr-2"></i>Simpan Ruangan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Sidebar - Takes 1 column -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 space-y-4">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                            <h3 class="text-sm font-semibold text-blue-900 mb-3">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                Panduan
                            </h3>
                            <div class="space-y-3">
                                <div class="bg-white rounded-lg p-3 border border-blue-100">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">
                                        <i class="fas fa-lightbulb mr-1"></i>Tips:
                                    </h4>
                                    <ul class="text-xs text-blue-700 space-y-1">
                                        <li>• Kode ruangan harus unik</li>
                                        <li>• Sesuaikan kapasitas dengan jumlah kursi</li>
                                        <li>• Tambahkan fasilitas lengkap</li>
                                        <li>• Status "Tersedia" untuk ruangan siap ujian</li>
                                    </ul>
                                </div>
                                
                                <div class="bg-white rounded-lg p-3 border border-yellow-100">
                                    <h4 class="text-sm font-medium text-yellow-800 mb-2">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Perhatian:
                                    </h4>
                                    <ul class="text-xs text-yellow-700 space-y-1">
                                        <li>• Kapasitas maksimal 1000 siswa</li>
                                        <li>• Lokasi harus jelas dan detail</li>
                                        <li>• Fasilitas membantu pemilihan ruangan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Live Preview -->
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                            <h3 class="text-sm font-semibold text-green-900 mb-3">
                                <i class="fas fa-eye mr-2 text-green-500"></i>
                                Preview
                            </h3>
                            <div id="preview-content" class="bg-white rounded-lg p-3 border border-green-100">
                                <p class="text-gray-500 text-center text-xs">
                                    <i class="fas fa-edit text-lg mb-2"></i><br>
                                    Preview akan muncul saat mengisi form
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Panduan
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <h4 class="text-sm font-medium text-blue-800 mb-2">
                                    <i class="fas fa-lightbulb mr-1"></i>Tips:
                                </h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• Kode ruangan harus unik</li>
                                    <li>• Sesuaikan kapasitas dengan jumlah kursi</li>
                                    <li>• Tambahkan fasilitas lengkap</li>
                                    <li>• Status "Tersedia" untuk ruangan siap ujian</li>
                                </ul>
                            </div>
                            
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <h4 class="text-sm font-medium text-yellow-800 mb-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>Perhatian:
                                </h4>
                                <ul class="text-sm text-yellow-700 space-y-1">
                                    <li>• Kapasitas maksimal 1000 siswa</li>
                                    <li>• Lokasi harus jelas dan detail</li>
                                    <li>• Fasilitas membantu pemilihan ruangan</li>
                                </ul>
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
    // Add fasilitas functionality
    document.getElementById('addFasilitas').addEventListener('click', function() {
        const container = document.getElementById('fasilitasContainer');
        const newItem = document.createElement('div');
        newItem.className = 'fasilitas-item';
        newItem.innerHTML = `
            <div class="flex">
                <input type="text" 
                       class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                       name="fasilitas[]" 
                       placeholder="Proyektor, AC, Wifi, Papan Tulis">
                <button type="button" class="px-4 py-3 bg-red-500 text-white rounded-r-lg hover:bg-red-600 transition-colors remove-fasilitas">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        container.appendChild(newItem);
    });

    // Remove fasilitas functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-fasilitas')) {
            const items = document.querySelectorAll('.fasilitas-item');
            if (items.length > 1) {
                e.target.closest('.fasilitas-item').remove();
                updatePreview();
            } else {
                alert('Minimal harus ada satu fasilitas');
            }
        }
    });

    // Generate kode ruangan otomatis
    document.getElementById('nama_ruangan').addEventListener('input', function() {
        const namaRuangan = this.value;
        const kodeRuangan = document.getElementById('kode_ruangan').value;
        
        if (namaRuangan && !kodeRuangan) {
            // Generate simple code from room name
            let code = namaRuangan.substring(0, 3).toUpperCase();
            const numbers = Math.floor(Math.random() * 100) + 1;
            code += numbers.toString().padStart(2, '0');
            document.getElementById('kode_ruangan').value = code;
        }
        updatePreview();
    });

    // Update preview on form changes
    const formElements = document.querySelectorAll('input, select, textarea');
    formElements.forEach(element => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    function updatePreview() {
        const kodeRuangan = document.getElementById('kode_ruangan').value || '-';
        const namaRuangan = document.getElementById('nama_ruangan').value || 'Belum diisi';
        const kapasitas = document.getElementById('kapasitas').value || '0';
        const lokasi = document.getElementById('lokasi').value || 'Belum diisi';
        const status = document.getElementById('status').value || 'belum dipilih';
        
        const statusBadge = {
            'tersedia': '<span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Tersedia</span>',
            'terpakai': '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Terpakai</span>',
            'maintenance': '<span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Maintenance</span>'
        }[status] || '<span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-medium">Belum dipilih</span>';

        const previewHtml = `
            <div class="space-y-3">
                <div class="flex justify-between">
                    <strong class="text-gray-700">Kode:</strong> 
                    <span class="px-2 py-1 bg-indigo-100 text-indigo-800 rounded text-xs font-medium">${kodeRuangan}</span>
                </div>
                <div class="flex justify-between">
                    <strong class="text-gray-700">Nama:</strong> 
                    <span class="text-gray-900">${namaRuangan}</span>
                </div>
                <div class="flex justify-between">
                    <strong class="text-gray-700">Kapasitas:</strong> 
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">${kapasitas} siswa</span>
                </div>
                <div class="flex justify-between">
                    <strong class="text-gray-700">Lokasi:</strong> 
                    <span class="text-gray-900">${lokasi}</span>
                </div>
                <div class="flex justify-between">
                    <strong class="text-gray-700">Status:</strong> 
                    ${statusBadge}
                </div>
            </div>
        `;
        
        document.getElementById('preview-content').innerHTML = previewHtml;
    }

    // Initialize preview
    updatePreview();
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.ujian', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\pengaturan\ruangan\create.blade.php ENDPATH**/ ?>