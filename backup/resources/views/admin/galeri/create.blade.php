@extends('layouts.admin')

@section('title', 'Tambah Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
        <i class="fas fa-plus text-blue-600 mr-3"></i>
        Tambah Galeri
    </h1>
    
    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mt-2 ml-4 list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" id="galeri-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Info Galeri -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Judul Galeri</label>
                    <input type="text" name="judul" value="{{ old('judul') }}" class="form-input w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input w-full" placeholder="Deskripsi galeri...">{{ old('deskripsi') }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select name="kategori" class="form-input w-full" required>
                        <option value="">Pilih Kategori</option>
                        <option value="facilities" {{ old('kategori') == 'facilities' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="activities" {{ old('kategori') == 'activities' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="competitions" {{ old('kategori') == 'competitions' ? 'selected' : '' }}>Kompetisi</option>
                        <option value="campus" {{ old('kategori') == 'campus' ? 'selected' : '' }}>Sekolah</option>
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
                            <p class="text-sm text-gray-500">Pilih satu atau lebih foto (JPG, PNG, GIF)</p>
                            <p class="text-sm text-gray-500">Maksimal 5MB per foto</p>
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
                        <span class="text-sm text-gray-500">(Klik pada foto untuk set sebagai thumbnail, ❌ untuk hapus)</span>
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
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-6 pt-6 border-t">
            <a href="{{ route('admin.galeri.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold">
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
    const fotoInput = document.getElementById('foto-input');
    const previewArea = document.getElementById('preview-area');
    const photoPreviewsContainer = document.getElementById('photo-previews');
    const thumbnailIndexInput = document.getElementById('thumbnail-index');
    const uploadArea = document.getElementById('upload-area');
    const galeriForm = document.getElementById('galeri-form');
    const submitBtn = document.getElementById('submit-btn');
    
    let selectedFiles = [];
    
    // Form submission validation
    galeriForm.addEventListener('submit', function(e) {
        console.log('=== FORM SUBMISSION DEBUG ===');
        console.log('Form submission triggered');
        console.log('Selected files count:', selectedFiles.length);
        console.log('Selected files:', selectedFiles.map(f => `${f.name} (${f.type})`));
        console.log('Form files count:', fotoInput.files.length);
        console.log('Form files:', Array.from(fotoInput.files).map(f => `${f.name} (${f.type})`));
        console.log('File input element:', fotoInput);
        console.log('File input value:', fotoInput.value);
        
        // Force update file input before validation
        updateFileInput();
        
        console.log('After update - Form files count:', fotoInput.files.length);
        
        // Check if photos are selected
        if (selectedFiles.length === 0 || fotoInput.files.length === 0) {
            e.preventDefault();
            console.log('ERROR: No files selected');
            console.log('selectedFiles.length:', selectedFiles.length);
            console.log('fotoInput.files.length:', fotoInput.files.length);
            alert('Silakan pilih minimal 1 foto untuk galeri');
            // Focus on upload area to show user where to upload
            uploadArea.scrollIntoView({ behavior: 'smooth', block: 'center' });
            uploadArea.style.borderColor = '#ef4444';
            setTimeout(() => {
                uploadArea.style.borderColor = '';
            }, 3000);
            return false;
        }
        
        // Check if all required fields are filled
        const judul = document.querySelector('[name="judul"]').value.trim();
        const kategori = document.querySelector('[name="kategori"]').value;
        
        if (!judul) {
            e.preventDefault();
            alert('Judul galeri harus diisi');
            document.querySelector('[name="judul"]').focus();
            return false;
        }
        
        if (!kategori) {
            e.preventDefault();
            alert('Kategori harus dipilih');
            document.querySelector('[name="kategori"]').focus();
            return false;
        }
        
        console.log('Form validation passed, submitting...');
        console.log('Final check - files being submitted:', Array.from(fotoInput.files).map(f => f.name));
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
        
        // Allow form to submit normally
        return true;
    });
    
    // Drag and drop functionality
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
        const files = e.dataTransfer.files;
        addFiles(files);
    });
    
    fotoInput.addEventListener('change', function() {
        console.log('File input changed, files:', this.files.length);
        addFiles(this.files);
    });
    
    function addFiles(newFiles) {
        if (newFiles.length === 0) return;
        
        console.log('=== ADDING FILES ===');
        console.log('Adding files:', newFiles.length);
        console.log('New files:', Array.from(newFiles).map(f => `${f.name} (${f.type})`));
        console.log('Current selectedFiles before adding:', selectedFiles.length);
        
        previewArea.classList.remove('hidden');
        
        Array.from(newFiles).forEach((file) => {
            if (file.type.startsWith('image/')) {
                selectedFiles.push(file);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentIndex = selectedFiles.length - 1;
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group border-2 border-transparent rounded-lg overflow-hidden';
                    previewDiv.dataset.index = currentIndex;
                    
                    previewDiv.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" class="w-full h-24 object-cover cursor-pointer hover:opacity-80 transition-opacity" alt="Preview ${currentIndex + 1}" onclick="selectThumbnail(${currentIndex})" title="Klik untuk set sebagai thumbnail">
                            <button type="button" onclick="removePhoto(${currentIndex})" class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-opacity" title="Hapus foto">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded thumbnail-badge ${currentIndex === 0 && selectedFiles.length === 1 ? '' : 'hidden'}">
                                <i class="fas fa-star mr-1"></i>Thumbnail
                            </div>
                            <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded">
                                ${currentIndex + 1}
                            </div>
                        </div>
                    `;
                    
                    photoPreviewsContainer.appendChild(previewDiv);
                    
                    // Set first image as default thumbnail if no thumbnail selected yet
                    if (selectedFiles.length === 1) {
                        selectThumbnail(0);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Update file input using a more reliable method
        updateFileInput();
        
        console.log('Files added successfully. Total selectedFiles:', selectedFiles.length);
        console.log('All selected files:', selectedFiles.map(f => f.name));
        
        // Update counter
        updateFileCounter();
        
        // Reset the input value to allow selecting the same files again
        fotoInput.value = '';
    }
    
    function updateFileInput() {
        // Create new DataTransfer object
        const dt = new DataTransfer();
        
        // Add all selected files to DataTransfer
        selectedFiles.forEach((file, index) => {
            console.log(`Adding file ${index}: ${file.name} (${file.type}, ${file.size} bytes)`);
            dt.items.add(file);
        });
        
        // Update the file input
        fotoInput.files = dt.files;
        
        console.log('Updated file input with', fotoInput.files.length, 'files');
        console.log('Selected files array has', selectedFiles.length, 'files');
        console.log('File input files:', Array.from(fotoInput.files).map(f => `${f.name} (${f.type})`));
    }
    
    function selectThumbnail(index) {
        // Remove thumbnail badge from all images
        document.querySelectorAll('.thumbnail-badge').forEach(badge => {
            badge.classList.add('hidden');
        });
        
        // Remove selected border from all previews
        document.querySelectorAll('#photo-previews > div').forEach(div => {
            div.classList.remove('border-blue-500');
        });
        
        // Add thumbnail badge and border to selected image
        const selectedDiv = document.querySelector(`[data-index="${index}"]`);
        if (selectedDiv) {
            selectedDiv.querySelector('.thumbnail-badge').classList.remove('hidden');
            selectedDiv.classList.add('border-blue-500');
            thumbnailIndexInput.value = index;
        }
    }
    
    function removePhoto(index) {
        console.log('Removing photo at index:', index);
        // Remove from selectedFiles array
        selectedFiles.splice(index, 1);
        
        // Update file input
        updateFileInput();
        
        // Rebuild preview
        rebuildPreview();
    }
    
    function rebuildPreview() {
        photoPreviewsContainer.innerHTML = '';
        
        if (selectedFiles.length === 0) {
            previewArea.classList.add('hidden');
            thumbnailIndexInput.value = '0';
            updateFileCounter();
            return;
        }
        
        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative group border-2 border-transparent rounded-lg overflow-hidden';
                previewDiv.dataset.index = index;
                
                previewDiv.innerHTML = `
                    <div class="relative">
                        <img src="${e.target.result}" class="w-full h-24 object-cover cursor-pointer hover:opacity-80 transition-opacity" alt="Preview ${index + 1}" onclick="selectThumbnail(${index})" title="Klik untuk set sebagai thumbnail">
                        <button type="button" onclick="removePhoto(${index})" class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-opacity" title="Hapus foto">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded thumbnail-badge hidden">
                            <i class="fas fa-star mr-1"></i>Thumbnail
                        </div>
                        <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded">
                            ${index + 1}
                        </div>
                    </div>
                `;
                
                photoPreviewsContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset thumbnail selection to first image
        setTimeout(() => {
            selectThumbnail(0);
        }, 100);
        
        // Update counter
        updateFileCounter();
    }
    
    function updateFileCounter() {
        const fileCountDiv = document.getElementById('file-count');
        const countText = document.getElementById('count-text');
        
        if (selectedFiles.length > 0) {
            fileCountDiv.classList.remove('hidden');
            countText.textContent = `${selectedFiles.length} foto dipilih`;
        } else {
            fileCountDiv.classList.add('hidden');
        }
    }
    
    function clearAllPhotos() {
        if (selectedFiles.length === 0) return;
        
        if (confirm('Yakin ingin menghapus semua foto?')) {
            selectedFiles = [];
            updateFileInput();
            photoPreviewsContainer.innerHTML = '';
            previewArea.classList.add('hidden');
            thumbnailIndexInput.value = '0';
            updateFileCounter();
        }
    }
    
    // Make functions global so they can be called from onclick
    window.selectThumbnail = selectThumbnail;
    window.removePhoto = removePhoto;
    window.clearAllPhotos = clearAllPhotos;
});
</script>

<style>
.form-input {
    @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}
</style>
@endsection
