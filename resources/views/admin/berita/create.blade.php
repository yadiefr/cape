@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('main-content')
<div class="bg-white rounded-xl shadow-md p-6 mb-6 max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Tambah Berita</h1>
    <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Berita</label>
            <input type="text" name="judul" value="{{ old('judul') }}" class="form-input w-full" required>
            @error('judul')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Foto Thumbnail</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="foto" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                            <span>Upload foto</span>
                            <input id="foto" name="foto" type="file" class="sr-only" accept="image/*">
                        </label>
                        <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, JPEG hingga 2MB</p>
                </div>
            </div>
            @error('foto')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Isi Berita</label>
            <textarea name="isi" rows="6" class="form-input w-full" required>{{ old('isi') }}</textarea>
            @error('isi')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="form-input w-full">
            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, atau gambar hingga 2MB</p>
            @error('lampiran')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-between">
            <a href="{{ route('admin.berita.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-semibold inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold">
                <i class="fas fa-save mr-2"></i> Simpan
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
            // Buat preview image
            const preview = document.createElement('img');
            preview.src = e.target.result;
            preview.className = 'mt-4 h-32 w-48 object-cover rounded-lg border border-gray-300';
            preview.alt = 'Preview foto';
            
            // Hapus preview sebelumnya jika ada
            const existingPreview = document.querySelector('.foto-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Tambahkan class untuk identifikasi
            preview.classList.add('foto-preview');
            
            // Insert preview setelah drop area
            const dropArea = document.querySelector('[for="foto"]').closest('.border-dashed');
            dropArea.parentNode.insertBefore(preview, dropArea.nextSibling);
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
