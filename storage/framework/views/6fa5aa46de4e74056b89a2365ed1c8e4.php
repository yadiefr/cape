

<?php $__env->startSection('title', 'Edit Berita'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Edit Berita</h1>
    <form action="<?php echo e(route('admin.berita.update', $berita->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Berita</label>
            <input type="text" name="judul" value="<?php echo e(old('judul', $berita->judul)); ?>" class="form-input w-full" required>
            <?php $__errorArgs = ['judul'];
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
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Foto Thumbnail</label>
            <?php if($berita->foto): ?>
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                    <img src="<?php echo e(asset('storage/' . $berita->foto)); ?>" 
                         alt="Foto berita" 
                         class="h-32 w-48 object-cover rounded-lg border border-gray-300"
                         onerror="this.onerror=null; this.src='<?php echo e(asset('images/no-image.png')); ?>';">
                </div>
            <?php endif; ?>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span><?php echo e($berita->foto ? 'Ganti foto' : 'Upload foto'); ?></span>
                            <input id="foto" name="foto" type="file" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                </div>
            </div>
            <?php $__errorArgs = ['foto'];
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
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Isi Berita</label>
            <textarea name="isi" rows="6" class="form-input w-full" required><?php echo e(old('isi', $berita->isi)); ?></textarea>
            <?php $__errorArgs = ['isi'];
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
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Lampiran (opsional)</label>
            <?php if($berita->lampiran): ?>
                <div class="mb-2">
                    <a href="<?php echo e(asset('storage/' . $berita->lampiran)); ?>" target="_blank" class="text-blue-600 underline">Lihat Lampiran Saat Ini</a>
                </div>
            <?php endif; ?>
            <input type="file" name="lampiran" class="form-input w-full">
            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, atau gambar hingga 2MB</p>
            <?php $__errorArgs = ['lampiran'];
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
        <div class="flex justify-between">
            <a href="<?php echo e(route('admin.berita.index')); ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
// Preview foto yang akan diupload
document.getElementById('foto').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Cari existing preview atau foto lama
            let existingImage = document.querySelector('.existing-foto');
            if (!existingImage) {
                existingImage = document.querySelector('.foto-preview');
            }
            
            if (existingImage) {
                // Update existing image
                existingImage.src = e.target.result;
                existingImage.classList.add('foto-preview');
            } else {
                // Buat preview image baru
                const preview = document.createElement('img');
                preview.src = e.target.result;
                preview.className = 'mt-4 h-32 w-48 object-cover rounded-lg border border-gray-300 foto-preview';
                preview.alt = 'Preview foto';
                
                // Insert preview setelah drop area
                const dropArea = document.querySelector('[for="foto"]').closest('.border-dashed');
                dropArea.parentNode.insertBefore(preview, dropArea.nextSibling);
            }
        };
        reader.readAsDataURL(file);
    }
});

// Add class to existing foto for identification
document.addEventListener('DOMContentLoaded', function() {
    const existingFoto = document.querySelector('img[alt="Foto berita"]');
    if (existingFoto) {
        existingFoto.classList.add('existing-foto');
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\berita\edit.blade.php ENDPATH**/ ?>