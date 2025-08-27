

<?php $__env->startSection('title', 'Tambah Galeri'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
        <i class="fas fa-plus text-blue-600 mr-3"></i>
        Tambah Galeri
    </h1>
    
    <!-- Error Messages -->
    <?php if($errors->any()): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 ml-4 list-disc">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <form action="<?php echo e(route('admin.galeri.store')); ?>" method="POST" enctype="multipart/form-data" id="galeri-form">
        <?php echo csrf_field(); ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Info Galeri -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Judul Galeri</label>
                    <input type="text" name="judul" value="<?php echo e(old('judul')); ?>" class="form-input w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input w-full" placeholder="Deskripsi galeri..."><?php echo e(old('deskripsi')); ?></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select name="kategori" class="form-input w-full" required>
                        <option value="">Pilih Kategori</option>
                        <option value="facilities" <?php echo e(old('kategori') == 'facilities' ? 'selected' : ''); ?>>Fasilitas</option>
                        <option value="activities" <?php echo e(old('kategori') == 'activities' ? 'selected' : ''); ?>>Kegiatan</option>
                        <option value="competitions" <?php echo e(old('kategori') == 'competitions' ? 'selected' : ''); ?>>Kompetisi</option>
                        <option value="sekolah" <?php echo e(old('kategori') == 'sekolah' ? 'selected' : ''); ?>>Sekolah</option>
                    </select>
                </div>
            </div>

            <!-- Upload Foto -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Upload Foto</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" name="foto[]" id="foto-input" class="hidden" multiple accept="image/*">
                        <div id="upload-area" class="cursor-pointer" onclick="document.getElementById('foto-input').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-2">Klik untuk upload foto atau drag & drop</p>
                            <p class="text-sm text-gray-500">Pilih satu atau lebih foto (JPG, PNG, GIF, WEBP)</p>
                            <p class="text-sm text-gray-500">Maksimal <?php echo e($maxFileSize); ?>MB per foto</p>
                        </div>
                    </div>
                    <div id="file-count" class="text-sm text-blue-600 mt-2 hidden">
                        <i class="fas fa-images mr-1"></i>
                        <span id="count-text">0 foto dipilih</span>
                    </div>
                </div>

                <!-- Preview Area -->
                <div id="preview-area" class="hidden">
                    <label class="block text-gray-700 font-medium mb-2">
                        Preview & Pilih Thumbnail 
                        <span class="text-sm text-gray-500">(Klik pada foto untuk set sebagai thumbnail)</span>
                    </label>
                    <div id="photo-previews" class="grid grid-cols-2 gap-3 max-h-64 overflow-y-auto border rounded-lg p-3 bg-gray-50"></div>
                    <input type="hidden" name="thumbnail_index" id="thumbnail-index" value="0">
                    <div class="mt-2 flex justify-between items-center">
                        <button type="button" onclick="document.getElementById('foto-input').click()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>Tambah Foto Lagi
                        </button>
                        <button type="button" onclick="clearAllPhotos()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i>Hapus Semua
                        </button>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Foto yang dipilih sebagai thumbnail akan ditandai dengan <span class="bg-blue-600 text-white px-2 py-1 rounded text-xs"><i class="fas fa-star mr-1"></i>Thumbnail</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-6 pt-6 border-t">
            <a href="<?php echo e(route('admin.galeri.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold" id="submit-btn">
                <i class="fas fa-save mr-2"></i>Simpan Galeri
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const fotoInput = document.getElementById('foto-input');
    const previewArea = document.getElementById('preview-area');
    const photoPreviewsContainer = document.getElementById('photo-previews');
    const thumbnailIndexInput = document.getElementById('thumbnail-index');
    const uploadArea = document.getElementById('upload-area');
    const galeriForm = document.getElementById('galeri-form');
    const submitBtn = document.getElementById('submit-btn');
    const fileCountDiv = document.getElementById('file-count');
    const countText = document.getElementById('count-text');
    
    // Store files and thumbnail index
    let currentFiles = [];
    let selectedThumbnailIndex = 0;
    const maxFileSize = <?php echo e($maxFileSize); ?> * 1024 * 1024; // Convert to bytes
    
    console.log('Gallery create initialized');
    console.log('Max file size:', maxFileSize, 'bytes');
    
    // File input change handler
    fotoInput.addEventListener('change', function() {
        handleFiles(this.files);
    });
    
    // Drag and drop handlers
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('bg-blue-50', 'border-blue-300');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('bg-blue-50', 'border-blue-300');
        handleFiles(e.dataTransfer.files);
    });
    
    // Form submission handler
    galeriForm.addEventListener('submit', function(e) {
        console.log('Form submitting...');
        console.log('Files count:', currentFiles.length);
        console.log('Selected thumbnail index:', selectedThumbnailIndex);
        
        if (currentFiles.length === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 foto untuk galeri');
            return false;
        }
        
        // Update form data
        updateFormFiles();
        thumbnailIndexInput.value = selectedThumbnailIndex;
        
        console.log('Final thumbnail index being submitted:', thumbnailIndexInput.value);
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    });
    
    function handleFiles(files) {
        console.log('Handling', files.length, 'files');
        
        Array.from(files).forEach(file => {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert(`File "${file.name}" bukan gambar yang valid.`);
                return;
            }
            
            // Validate file size
            if (file.size > maxFileSize) {
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                const maxSizeMB = <?php echo e($maxFileSize); ?>;
                alert(`File "${file.name}" terlalu besar (${sizeMB}MB). Maksimal ${maxSizeMB}MB.`);
                return;
            }
            
            // Add to current files
            currentFiles.push(file);
        });
        
        // Update display
        updatePreview();
        updateFileCounter();
        
        // Reset input
        fotoInput.value = '';
    }
    
    function updatePreview() {
        console.log('Updating preview for', currentFiles.length, 'files');
        
        // Clear existing previews
        photoPreviewsContainer.innerHTML = '';
        
        if (currentFiles.length === 0) {
            previewArea.classList.add('hidden');
            return;
        }
        
        // Show preview area
        previewArea.classList.remove('hidden');
        
        // Create previews
        currentFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = createPreviewElement(e.target.result, index, file.name);
                photoPreviewsContainer.appendChild(previewDiv);
                
                // Set first image as thumbnail by default
                if (index === 0 && selectedThumbnailIndex === 0) {
                    setTimeout(() => selectThumbnail(0), 100);
                } else if (index === selectedThumbnailIndex) {
                    setTimeout(() => selectThumbnail(selectedThumbnailIndex), 100);
                }
            };
            reader.readAsDataURL(file);
        });
    }
    
    function createPreviewElement(src, index, filename) {
        const div = document.createElement('div');
        div.className = 'relative group border-2 border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-all cursor-pointer';
        div.dataset.index = index;
        
        div.innerHTML = `
            <div class="relative">
                <img src="${src}" class="w-full h-24 object-cover" alt="Preview ${index + 1}" title="Klik untuk pilih sebagai thumbnail">
                <button type="button" onclick="removePhoto(${index})" class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-opacity" title="Hapus foto">
                    <i class="fas fa-times"></i>
                </button>
                <div class="thumbnail-badge absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded hidden">
                    <i class="fas fa-star mr-1"></i>Thumbnail
                </div>
                <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                    ${index + 1}
                </div>
                <div class="absolute bottom-1 right-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded max-w-20 truncate" title="${filename}">
                    ${filename.length > 8 ? filename.substring(0, 8) + '...' : filename}
                </div>
            </div>
        `;
        
        // Add click handler for thumbnail selection
        div.addEventListener('click', function() {
            selectThumbnail(index);
        });
        
        return div;
    }
    
    function selectThumbnail(index) {
        console.log('Selecting thumbnail:', index);
        
        if (index >= currentFiles.length) {
            console.error('Invalid thumbnail index:', index);
            return;
        }
        
        // Update selected index
        selectedThumbnailIndex = index;
        
        // Remove all existing badges and borders
        document.querySelectorAll('.thumbnail-badge').forEach(badge => {
            badge.classList.add('hidden');
        });
        document.querySelectorAll('#photo-previews > div').forEach(div => {
            div.classList.remove('border-blue-500');
            div.classList.add('border-gray-200');
        });
        
        // Add badge and border to selected item
        const selectedDiv = document.querySelector(`[data-index="${index}"]`);
        if (selectedDiv) {
            const badge = selectedDiv.querySelector('.thumbnail-badge');
            if (badge) {
                badge.classList.remove('hidden');
            }
            selectedDiv.classList.remove('border-gray-200');
            selectedDiv.classList.add('border-blue-500');
        }
        
        console.log('Thumbnail selected:', index);
    }
    
    function removePhoto(index) {
        console.log('Removing photo at index:', index);
        
        // Remove from array
        currentFiles.splice(index, 1);
        
        // Adjust selected thumbnail index
        if (selectedThumbnailIndex > index) {
            selectedThumbnailIndex--;
        } else if (selectedThumbnailIndex === index) {
            selectedThumbnailIndex = 0; // Reset to first image
        }
        
        // Update display
        updatePreview();
        updateFileCounter();
        updateFormFiles();
    }
    
    function clearAllPhotos() {
        if (currentFiles.length === 0) return;
        
        if (confirm('Yakin ingin menghapus semua foto?')) {
            currentFiles = [];
            selectedThumbnailIndex = 0;
            updatePreview();
            updateFileCounter();
            updateFormFiles();
        }
    }
    
    function updateFileCounter() {
        if (currentFiles.length > 0) {
            fileCountDiv.classList.remove('hidden');
            countText.textContent = `${currentFiles.length} foto dipilih`;
        } else {
            fileCountDiv.classList.add('hidden');
        }
    }
    
    function updateFormFiles() {
        // Create new DataTransfer object
        const dt = new DataTransfer();
        
        // Add all current files
        currentFiles.forEach(file => {
            dt.items.add(file);
        });
        
        // Update the file input
        fotoInput.files = dt.files;
        
        console.log('Form files updated:', fotoInput.files.length);
    }
    
    // Make functions global for onclick handlers
    window.removePhoto = removePhoto;
    window.clearAllPhotos = clearAllPhotos;
    window.selectThumbnail = selectThumbnail;
});
</script>

<style>
.form-input {
    @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}

.thumbnail-badge {
    z-index: 10;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}

#photo-previews .relative:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\galeri\create.blade.php ENDPATH**/ ?>