@extends('layouts.admin')

@section('title', 'Edit Galeri')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center mb-6">
        <i class="fas fa-edit text-yellow-600 mr-3"></i>
        Edit Galeri - {{ $galeri->judul }}
    </h1>
    
    <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Info Galeri -->
            <div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Judul Galeri</label>
                    <input type="text" name="judul" value="{{ old('judul', $galeri->judul) }}" class="form-input w-full" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4" class="form-input w-full" placeholder="Deskripsi galeri...">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Kategori</label>
                    <select name="kategori" class="form-input w-full" required>
                        <option value="">Pilih Kategori</option>
                        <option value="facilities" {{ old('kategori', $galeri->kategori) == 'facilities' ? 'selected' : '' }}>Fasilitas</option>
                        <option value="activities" {{ old('kategori', $galeri->kategori) == 'activities' ? 'selected' : '' }}>Kegiatan</option>
                        <option value="competitions" {{ old('kategori', $galeri->kategori) == 'competitions' ? 'selected' : '' }}>Kompetisi</option>
                        <option value="campus" {{ old('kategori', $galeri->kategori) == 'campus' ? 'selected' : '' }}>Sekolah</option>
                    </select>
                </div>
            </div>

            <!-- Current Photos & Upload New -->
            <div>
                @if($galeri->foto->count() > 0)
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Foto Saat Ini ({{ $galeri->foto->count() }} foto)</label>
                        <div class="grid grid-cols-3 gap-2 max-h-48 overflow-y-auto border rounded-lg p-3">
                            @foreach($galeri->foto as $foto)
                                <div class="relative">
                                    <img src="{{ asset('uploads/galeri/' . $foto->foto) }}" class="w-full h-16 object-cover rounded" alt="Foto">
                                    @if($foto->is_thumbnail)
                                        <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs px-1 rounded-bl">
                                            Thumbnail
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Upload foto baru di bawah untuk mengganti semua foto yang ada</p>
                    </div>
                @endif

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">
                        {{ $galeri->foto->count() > 0 ? 'Upload Foto Baru (Opsional)' : 'Upload Foto' }}
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition-colors">
                        <input type="file" name="foto[]" id="foto-input-edit" class="hidden" multiple accept="image/*">
                        <div id="upload-area-edit" class="cursor-pointer" onclick="document.getElementById('foto-input-edit').click()">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-2">Klik untuk upload foto atau drag & drop</p>
                            <p class="text-sm text-gray-500">Pilih satu atau lebih foto (JPG, PNG, GIF)</p>
                            <p class="text-sm text-gray-500">Maksimal 5MB per foto</p>
                            @if($galeri->foto->count() > 0)
                                <p class="text-sm text-orange-600 mt-2">⚠️ Foto baru akan mengganti semua foto yang ada</p>
                            @endif
                        </div>
                    </div>
                    <div id="file-count-edit" class="text-sm text-blue-600 mt-2 hidden">
                        <i class="fas fa-images mr-1"></i>
                        <span id="count-text-edit">0 foto dipilih</span>
                    </div>
                </div>

                <!-- Preview Area for New Photos -->
                <div id="preview-area-edit" class="hidden">
                    <label class="block text-gray-700 font-medium mb-2">
                        Preview & Pilih Thumbnail 
                        <span class="text-sm text-gray-500">(Klik ⭐ untuk set thumbnail, ❌ untuk hapus)</span>
                    </label>
                    <div id="photo-previews-edit" class="grid grid-cols-2 gap-3 max-h-64 overflow-y-auto border rounded-lg p-3 bg-gray-50"></div>
                    <input type="hidden" name="thumbnail_index" id="thumbnail-index-edit" value="0">
                    <div class="mt-2 flex justify-between items-center">
                        <button type="button" onclick="document.getElementById('foto-input-edit').click()" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-plus mr-1"></i>Tambah Foto Lagi
                        </button>
                        <button type="button" onclick="clearAllPhotosEdit()" class="text-red-600 hover:text-red-700 text-sm font-medium">
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
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('foto-input-edit');
    const previewArea = document.getElementById('preview-area-edit');
    const photoPreviewsContainer = document.getElementById('photo-previews-edit');
    const thumbnailIndexInput = document.getElementById('thumbnail-index-edit');
    const uploadArea = document.getElementById('upload-area-edit');
    
    let selectedFiles = [];
    let fileDataTransfer = new DataTransfer();
    
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
        addFiles(this.files);
    });
    
    function addFiles(newFiles) {
        if (newFiles.length === 0) return;
        
        previewArea.classList.remove('hidden');
        
        Array.from(newFiles).forEach((file) => {
            if (file.type.startsWith('image/')) {
                selectedFiles.push(file);
                fileDataTransfer.items.add(file);
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentIndex = selectedFiles.length - 1;
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'relative group border-2 border-transparent rounded-lg overflow-hidden';
                    previewDiv.dataset.index = currentIndex;
                    
                    previewDiv.innerHTML = `
                        <div class="relative">
                            <img src="${e.target.result}" class="w-full h-24 object-cover cursor-pointer" alt="Preview ${currentIndex + 1}" onclick="selectThumbnailEdit(${currentIndex})">
                            <button type="button" onclick="removePhotoEdit(${currentIndex})" class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-opacity" title="Hapus foto">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded thumbnail-badge ${currentIndex === 0 && selectedFiles.length === 1 ? '' : 'hidden'}">
                                Thumbnail
                            </div>
                            <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded">
                                ${currentIndex + 1}
                            </div>
                            <div class="absolute bottom-1 right-1 bg-green-600 text-white text-xs px-1 rounded cursor-pointer" onclick="selectThumbnailEdit(${currentIndex})" title="Klik untuk set thumbnail">
                                <i class="fas fa-star text-xs"></i>
                            </div>
                        </div>
                    `;
                    
                    photoPreviewsContainer.appendChild(previewDiv);
                    
                    // Set first image as default thumbnail if no thumbnail selected yet
                    if (selectedFiles.length === 1) {
                        selectThumbnailEdit(0);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Update file input
        fotoInput.files = fileDataTransfer.files;
        
        // Update counter
        updateFileCounterEdit();
        
        // Reset the input value to allow selecting the same files again
        fotoInput.value = '';
    }
    
    function selectThumbnailEdit(index) {
        // Remove thumbnail badge from all images
        document.querySelectorAll('.thumbnail-badge').forEach(badge => {
            badge.classList.add('hidden');
        });
        
        // Remove selected border from all previews
        document.querySelectorAll('#photo-previews-edit > div').forEach(div => {
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
    
    function removePhotoEdit(index) {
        // Remove from selectedFiles array
        selectedFiles.splice(index, 1);
        
        // Rebuild DataTransfer object
        fileDataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            fileDataTransfer.items.add(file);
        });
        
        // Update file input
        fotoInput.files = fileDataTransfer.files;
        
        // Rebuild preview
        rebuildPreviewEdit();
    }
    
    function rebuildPreviewEdit() {
        photoPreviewsContainer.innerHTML = '';
        
        if (selectedFiles.length === 0) {
            previewArea.classList.add('hidden');
            thumbnailIndexInput.value = '0';
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
                        <img src="${e.target.result}" class="w-full h-24 object-cover cursor-pointer" alt="Preview ${index + 1}" onclick="selectThumbnailEdit(${index})">
                        <button type="button" onclick="removePhotoEdit(${index})" class="absolute top-1 left-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-80 hover:opacity-100 transition-opacity" title="Hapus foto">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="absolute top-1 right-1 bg-blue-600 text-white text-xs px-2 py-1 rounded thumbnail-badge hidden">
                            Thumbnail
                        </div>
                        <div class="absolute bottom-1 left-1 bg-black bg-opacity-75 text-white text-xs px-1 rounded">
                            ${index + 1}
                        </div>
                        <div class="absolute bottom-1 right-1 bg-green-600 text-white text-xs px-1 rounded cursor-pointer" onclick="selectThumbnailEdit(${index})" title="Klik untuk set thumbnail">
                            <i class="fas fa-star text-xs"></i>
                        </div>
                    </div>
                `;
                
                photoPreviewsContainer.appendChild(previewDiv);
            };
            reader.readAsDataURL(file);
        });
        
        // Reset thumbnail selection to first image
        setTimeout(() => {
            selectThumbnailEdit(0);
        }, 100);
        
        // Update counter
        updateFileCounterEdit();
    }
    
    function updateFileCounterEdit() {
        const fileCountDiv = document.getElementById('file-count-edit');
        const countText = document.getElementById('count-text-edit');
        
        if (selectedFiles.length > 0) {
            fileCountDiv.classList.remove('hidden');
            countText.textContent = `${selectedFiles.length} foto dipilih`;
        } else {
            fileCountDiv.classList.add('hidden');
        }
    }
    
    function clearAllPhotosEdit() {
        if (selectedFiles.length === 0) return;
        
        if (confirm('Yakin ingin menghapus semua foto baru?')) {
            selectedFiles = [];
            fileDataTransfer = new DataTransfer();
            fotoInput.files = fileDataTransfer.files;
            photoPreviewsContainer.innerHTML = '';
            previewArea.classList.add('hidden');
            thumbnailIndexInput.value = '0';
            updateFileCounterEdit();
        }
    }
    
    // Make functions global so they can be called from onclick
    window.selectThumbnailEdit = selectThumbnailEdit;
    window.removePhotoEdit = removePhotoEdit;
    window.clearAllPhotosEdit = clearAllPhotosEdit;
});
</script>

<style>
.form-input {
    @apply border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}
</style>
@endsection
