@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-6xl mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 flex items-center mb-6">
        <i class="fas fa-edit text-yellow-600 mr-3"></i>
        Edit Galeri - {{ $galeri->judul }}
    </h1>
    
    <!-- Display errors -->
    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Terdapat beberapa masalah dengan input Anda:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data" id="galeri-edit-form">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-heading text-blue-500 mr-2"></i>Judul Galeri *
                    </label>
                    <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('judul') border-red-300 @enderror" 
                           required placeholder="Masukkan judul galeri">
                    @error('judul')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left text-green-500 mr-2"></i>Deskripsi
                    </label>
                    <textarea name="deskripsi" rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('deskripsi') border-red-300 @enderror" 
                              placeholder="Masukkan deskripsi galeri (opsional)">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-tags text-purple-500 mr-2"></i>Kategori *
                    </label>
                    <select name="kategori" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('kategori') border-red-300 @enderror" 
                            required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="facilities" {{ old('kategori', $galeri->kategori) == 'facilities' ? 'selected' : '' }}>
                            <i class="fas fa-building"></i> Fasilitas
                        </option>
                        <option value="activities" {{ old('kategori', $galeri->kategori) == 'activities' ? 'selected' : '' }}>
                            <i class="fas fa-users"></i> Kegiatan
                        </option>
                        <option value="competitions" {{ old('kategori', $galeri->kategori) == 'competitions' ? 'selected' : '' }}>
                            <i class="fas fa-trophy"></i> Kompetisi
                        </option>
                        <option value="campus" {{ old('kategori', $galeri->kategori) == 'sekolah' ? 'selected' : '' }}>
                            <i class="fas fa-school"></i> Sekolah
                        </option>
                    </select>
                    @error('kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Stats -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Status Galeri Saat Ini
                    </h3>
                    <div class="grid grid-cols-2 gap-4 text-sm text-blue-700">
                        <div>
                            <span class="font-medium">Total Foto:</span>
                            <span class="ml-1">{{ $galeri->foto->count() }} foto</span>
                        </div>
                        <div>
                            <span class="font-medium">Thumbnail:</span>
                            <span class="ml-1">
                                @php $thumbnail = $galeri->foto->where('is_thumbnail', true)->first() @endphp
                                {{ $thumbnail ? 'Ada' : 'Tidak Ada' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Photo Management -->
            <div class="space-y-6">
                <!-- Current Photos Display -->
                @if($galeri->foto->count() > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-images text-indigo-500 mr-2"></i>
                        Foto Saat Ini ({{ $galeri->foto->count() }} foto)
                    </h3>
                    
                    <div id="current-photos" class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto border rounded-lg p-3 bg-gray-50">
                        @foreach($galeri->foto as $index => $foto)
                            <div class="relative group photo-current border-2 {{ $foto->is_thumbnail ? 'border-blue-500 bg-blue-50' : 'border-transparent' }} rounded-lg overflow-hidden" 
                                 data-photo-id="{{ $foto->id }}" data-current-index="{{ $index }}">
                                <img src="{{ asset('uploads/galeri/' . $foto->foto) }}" 
                                     class="w-full h-20 object-cover cursor-pointer" 
                                     alt="Foto {{ $index + 1 }}"
                                     onclick="selectCurrentThumbnail({{ $index }}, {{ $foto->id }})">
                                
                                <!-- Thumbnail Badge -->
                                <div class="absolute top-1 right-1 {{ $foto->is_thumbnail ? '' : 'hidden' }} thumbnail-badge-current bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                    <i class="fas fa-star"></i> Thumbnail
                                </div>
                                
                                <!-- Photo Number -->
                                <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {{ $index + 1 }}
                                </div>
                                
                                <!-- Star Button -->
                                <div class="absolute bottom-1 right-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-2 py-1 rounded cursor-pointer transition-colors" 
                                     onclick="selectCurrentThumbnail({{ $index }}, {{ $foto->id }})" 
                                     title="Klik untuk set sebagai thumbnail">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <p class="text-sm text-gray-600 mt-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i>
                        <strong>Tips:</strong> Klik ⭐ pada foto untuk mengubah thumbnail, atau upload foto baru di bawah untuk mengganti semua foto.
                    </p>
                </div>
                @endif

                <!-- Upload New Photos Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-cloud-upload-alt text-green-500 mr-2"></i>
                        {{ $galeri->foto->count() > 0 ? 'Ganti dengan Foto Baru (Opsional)' : 'Upload Foto' }}
                    </h3>

                    <!-- File Upload Area -->
                    <div id="upload-area" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer">
                        <input type="file" name="foto[]" id="foto-input" class="hidden" multiple accept="image/*">
                        
                        <div class="space-y-2">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                            <div>
                                <p class="text-lg font-medium text-gray-700">Drag & Drop foto atau klik untuk browse</p>
                                <p class="text-sm text-gray-500">Mendukung: JPG, PNG, GIF, WEBP</p>
                                <p class="text-sm text-gray-500">Maksimal: {{ $maxFileSize }}MB per file, maksimal 100 foto</p>
                            </div>
                            @if($galeri->foto->count() > 0)
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 mt-4">
                                    <p class="text-sm text-orange-800 font-medium">
                                        <i class="fas fa-exclamation-triangle text-orange-500 mr-1"></i>
                                        Perhatian: Upload foto baru akan mengganti semua foto yang ada saat ini!
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Counter -->
                    <div id="file-counter" class="hidden mt-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-images mr-2"></i>
                            <span id="file-count-text">0 foto dipilih</span>
                        </span>
                    </div>
                </div>

                <!-- New Photos Preview -->
                <div id="new-photos-preview" class="hidden space-y-4">
                    <h4 class="text-md font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-eye text-blue-500 mr-2"></i>
                        Preview Foto Baru & Pilih Thumbnail
                    </h4>
                    
                    <div id="new-photos-grid" class="grid grid-cols-2 gap-3 max-h-64 overflow-y-auto border rounded-lg p-3 bg-green-50"></div>
                    
                    <!-- Hidden input for thumbnail selection -->
                    <input type="hidden" name="thumbnail_index" id="thumbnail-index" value="0">
                    <input type="hidden" name="current_thumbnail_id" id="current-thumbnail-id" value="">
                    
                    <div class="flex justify-between items-center">
                        <button type="button" onclick="document.getElementById('foto-input').click()" 
                                class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors">
                            <i class="fas fa-plus mr-1"></i>Tambah Foto Lagi
                        </button>
                        <button type="button" onclick="clearAllNewPhotos()" 
                                class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                            <i class="fas fa-trash mr-1"></i>Hapus Semua Foto Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.galeri.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
            
            <div class="flex space-x-3">
                <button type="button" onclick="debugFormData()" 
                        class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
                    <i class="fas fa-bug mr-2"></i>Debug
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Gallery Edit Interface Initialized');
    
    const fotoInput = document.getElementById('foto-input');
    const uploadArea = document.getElementById('upload-area');
    const newPhotosPreview = document.getElementById('new-photos-preview');
    const newPhotosGrid = document.getElementById('new-photos-grid');
    const fileCounter = document.getElementById('file-counter');
    const fileCountText = document.getElementById('file-count-text');
    const thumbnailIndexInput = document.getElementById('thumbnail-index');
    const currentThumbnailIdInput = document.getElementById('current-thumbnail-id');
    
    let newFiles = [];
    let currentThumbnailSelected = {{ $galeri->foto->where('is_thumbnail', true)->first()->id ?? 'null' }};
    
    console.log('📸 Current thumbnail ID:', currentThumbnailSelected);

    // Set current thumbnail in hidden input if exists
    if (currentThumbnailSelected) {
        currentThumbnailIdInput.value = currentThumbnailSelected;
    }

    // File input change handler
    fotoInput.addEventListener('change', function(e) {
        console.log('📁 Files selected via input:', e.target.files.length);
        handleNewFiles(e.target.files);
    });

    // Drag and drop handlers
    uploadArea.addEventListener('click', function() {
        fotoInput.click();
    });

    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
        console.log('🎯 Files dropped:', e.dataTransfer.files.length);
        handleNewFiles(e.dataTransfer.files);
    });

    function handleNewFiles(files) {
        if (files.length === 0) return;
        
        console.log('🔄 Processing', files.length, 'files...');
        
        // Check if adding these files would exceed 100
        if (files.length + newFiles.length > 100) {
            alert('Maksimal 100 foto yang dapat diupload sekaligus. Anda telah memilih ' + 
                  newFiles.length + ' foto dan mencoba menambahkan ' + files.length + ' foto lagi.');
            return;
        }
        
        // Convert to array and add to existing files
        newFiles = [...newFiles, ...Array.from(files)].slice(0, 100); // Ensure max 100 files
        
        // Update file counter
        fileCounter.classList.remove('hidden');
        fileCountText.textContent = `${newFiles.length} foto dipilih`;
        
        // Show preview
        newPhotosPreview.classList.remove('hidden');
        
        // Clear existing previews
        newPhotosGrid.innerHTML = '';
        
        // Create previews
        newFiles.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const photoDiv = document.createElement('div');
                    photoDiv.className = `relative group photo-new border-2 ${index === 0 ? 'border-blue-500 bg-blue-50' : 'border-transparent'} rounded-lg overflow-hidden`;
                    photoDiv.dataset.newIndex = index;
                    
                    photoDiv.innerHTML = `
                        <img src="${e.target.result}" 
                             class="w-full h-20 object-cover cursor-pointer" 
                             alt="Preview ${index + 1}"
                             onclick="selectNewThumbnail(${index})">
                        
                        <!-- Remove Button -->
                        <button type="button" onclick="removeNewPhoto(${index})" 
                                class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-all" 
                                title="Hapus foto ini">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <!-- Thumbnail Badge -->
                        <div class="absolute top-1 right-1 ${index === 0 ? '' : 'hidden'} thumbnail-badge-new bg-blue-600 text-white text-xs px-2 py-1 rounded">
                            <i class="fas fa-star"></i> Thumbnail
                        </div>
                        
                        <!-- Photo Number -->
                        <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                            ${index + 1}
                        </div>
                        
                        <!-- Star Button -->
                        <div class="absolute bottom-1 right-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-2 py-1 rounded cursor-pointer transition-colors" 
                             onclick="selectNewThumbnail(${index})" 
                             title="Klik untuk set sebagai thumbnail">
                            <i class="fas fa-star"></i>
                        </div>
                    `;
                    
                    newPhotosGrid.appendChild(photoDiv);
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Set first photo as default thumbnail
        if (newFiles.length > 0) {
            thumbnailIndexInput.value = 0;
            currentThumbnailIdInput.value = ''; // Clear current thumbnail selection
            console.log('📌 Default thumbnail set to new photo index 0');
        }
        
        // Update the file input
        const dt = new DataTransfer();
        newFiles.forEach(file => dt.items.add(file));
        fotoInput.files = dt.files;
    }

    // Select thumbnail from current photos
    window.selectCurrentThumbnail = function(index, photoId) {
        console.log('🎯 Selecting current photo as thumbnail:', index, photoId);
        
        // Clear new thumbnail selection
        thumbnailIndexInput.value = '';
        currentThumbnailIdInput.value = photoId;
        
        // Update visual indicators for current photos
        document.querySelectorAll('.photo-current').forEach(photo => {
            photo.classList.remove('border-blue-500', 'bg-blue-50');
            photo.querySelector('.thumbnail-badge-current').classList.add('hidden');
        });
        
        // Set selected photo
        const selectedPhoto = document.querySelector(`[data-photo-id="${photoId}"]`);
        if (selectedPhoto) {
            selectedPhoto.classList.add('border-blue-500', 'bg-blue-50');
            selectedPhoto.querySelector('.thumbnail-badge-current').classList.remove('hidden');
        }
        
        // Clear new photo selections
        document.querySelectorAll('.photo-new').forEach(photo => {
            photo.classList.remove('border-blue-500', 'bg-blue-50');
            photo.querySelector('.thumbnail-badge-new').classList.add('hidden');
        });
        
        console.log('✅ Current thumbnail updated to photo ID:', photoId);
    }

    // Select thumbnail from new photos
    window.selectNewThumbnail = function(index) {
        console.log('🎯 Selecting new photo as thumbnail:', index);
        
        // Set thumbnail index
        thumbnailIndexInput.value = index;
        currentThumbnailIdInput.value = ''; // Clear current thumbnail selection
        
        // Update visual indicators for new photos
        document.querySelectorAll('.photo-new').forEach(photo => {
            photo.classList.remove('border-blue-500', 'bg-blue-50');
            photo.querySelector('.thumbnail-badge-new').classList.add('hidden');
        });
        
        // Set selected new photo
        const selectedPhoto = document.querySelector(`[data-new-index="${index}"]`);
        if (selectedPhoto) {
            selectedPhoto.classList.add('border-blue-500', 'bg-blue-50');
            selectedPhoto.querySelector('.thumbnail-badge-new').classList.remove('hidden');
        }
        
        // Clear current photo selections
        document.querySelectorAll('.photo-current').forEach(photo => {
            photo.classList.remove('border-blue-500', 'bg-blue-50');
            photo.querySelector('.thumbnail-badge-current').classList.add('hidden');
        });
        
        console.log('✅ New thumbnail updated to index:', index);
    }

    // Remove new photo
    window.removeNewPhoto = function(index) {
        console.log('🗑️ Removing new photo at index:', index);
        
        newFiles.splice(index, 1);
        
        if (newFiles.length === 0) {
            newPhotosPreview.classList.add('hidden');
            fileCounter.classList.add('hidden');
            thumbnailIndexInput.value = '';
            return;
        }
        
        // Rebuild the preview
        rebuildNewPhotosPreview();
    }

    function rebuildNewPhotosPreview() {
        console.log('🔄 Rebuilding new photos preview...');
        
        newPhotosGrid.innerHTML = '';
        fileCountText.textContent = `${newFiles.length} foto dipilih`;
        
        newFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const photoDiv = document.createElement('div');
                photoDiv.className = `relative group photo-new border-2 ${index === 0 ? 'border-blue-500 bg-blue-50' : 'border-transparent'} rounded-lg overflow-hidden`;
                photoDiv.dataset.newIndex = index;
                
                photoDiv.innerHTML = `
                    <img src="${e.target.result}" 
                         class="w-full h-20 object-cover cursor-pointer" 
                         alt="Preview ${index + 1}"
                         onclick="selectNewThumbnail(${index})">
                    
                    <!-- Remove Button -->
                    <button type="button" onclick="removeNewPhoto(${index})" 
                            class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-all" 
                            title="Hapus foto ini">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <!-- Thumbnail Badge -->
                    <div class="absolute top-1 right-1 ${index === 0 ? '' : 'hidden'} thumbnail-badge-new bg-blue-600 text-white text-xs px-2 py-1 rounded">
                        <i class="fas fa-star"></i> Thumbnail
                    </div>
                    
                    <!-- Photo Number -->
                    <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                        ${index + 1}
                    </div>
                    
                    <!-- Star Button -->
                    <div class="absolute bottom-1 right-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs px-2 py-1 rounded cursor-pointer transition-colors" 
                         onclick="selectNewThumbnail(${index})" 
                         title="Klik untuk set sebagai thumbnail">
                        <i class="fas fa-star"></i>
                    </div>
                `;
                
                newPhotosGrid.appendChild(photoDiv);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset thumbnail to first photo
        thumbnailIndexInput.value = 0;
        currentThumbnailIdInput.value = '';
        
        // Update file input
        const dt = new DataTransfer();
        newFiles.forEach(file => dt.items.add(file));
        fotoInput.files = dt.files;
        
        console.log('✅ New photos preview rebuilt');
    }

    // Clear all new photos
    window.clearAllNewPhotos = function() {
        if (newFiles.length === 0) return;
        
        if (confirm('Yakin ingin menghapus semua foto baru?')) {
            console.log('🧹 Clearing all new photos...');
            
            newFiles = [];
            newPhotosPreview.classList.add('hidden');
            fileCounter.classList.add('hidden');
            newPhotosGrid.innerHTML = '';
            thumbnailIndexInput.value = '';
            
            // Reset file input
            fotoInput.value = '';
            
            console.log('✅ All new photos cleared');
        }
    }

    // Debug function
    window.debugFormData = function() {
        console.log('🐛 FORM DEBUG INFO:');
        console.log('📋 Form Data:');
        console.log('  - Judul:', document.querySelector('input[name="judul"]').value);
        console.log('  - Kategori:', document.querySelector('select[name="kategori"]').value);
        console.log('  - New Files:', newFiles.length);
        console.log('  - Thumbnail Index (new):', thumbnailIndexInput.value);
        console.log('  - Current Thumbnail ID:', currentThumbnailIdInput.value);
        
        const formData = new FormData(document.getElementById('galeri-edit-form'));
        console.log('📨 FormData entries:');
        for (let pair of formData.entries()) {
            console.log('  -', pair[0], ':', pair[1]);
        }
    }

    console.log('✅ Gallery Edit Interface Ready!');
});
</script>

<style>
/* Custom styles for the form */
.photo-current:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}

.photo-new:hover {
    transform: scale(1.02);
    transition: transform 0.2s ease-in-out;
}

.thumbnail-badge-current,
.thumbnail-badge-new {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

/* File input styling */
input[type="file"]::-webkit-file-upload-button {
    visibility: hidden;
}

/* Loading animation for upload area */
.upload-loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .5;
    }
}
</style>
@endsection
