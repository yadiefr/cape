@extends('layouts.app')

@section('title', $galeri->judul . ' - Galeri - ' . setting('nama_sekolah', 'SMK PGRI CIKAMPEK'))

@section('meta_description', $galeri->deskripsi)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('galeri.index') }}" class="hover:text-blue-600 transition-colors">Galeri</a>
                <span>/</span>
                <span class="text-gray-800 font-medium">{{ $galeri->judul }}</span>
            </div>
        </nav>

        <!-- Gallery Header -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <div class="text-center">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">{{ $galeri->judul }}</h1>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto mb-6">{{ $galeri->deskripsi }}</p>
                
                <!-- Gallery Info -->
                <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-gray-500">
                    <div class="flex items-center">
                        <i class="fas fa-tag mr-2"></i>
                        <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full">
                            {{ ucfirst(str_replace(['_', '-'], ' ', $galeri->kategori)) }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        {{ $galeri->created_at->format('d F Y') }}
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-images mr-2"></i>
                        {{ $galeri->foto->count() }} Foto
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Gallery -->
        @if($galeri->foto->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8" id="photoGallery">
                @foreach($galeri->foto as $foto)
                    <div class="group relative aspect-square overflow-hidden rounded-lg cursor-pointer bg-white shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                         onclick="openPhotoModal({{ $loop->index }})">
                        <img src="{{ asset('storage/' . $foto->foto) }}" 
                             alt="Foto {{ $loop->iteration }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                             loading="lazy">
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Photo Number -->
                        <div class="absolute top-3 left-3 bg-black/60 text-white px-2 py-1 rounded-full text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            {{ $loop->iteration }}
                        </div>
                        
                        <!-- View Icon -->
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div class="bg-white/20 backdrop-blur-sm rounded-full p-4">
                                <i class="fas fa-search-plus text-white text-xl"></i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mb-8">
                    <i class="fas fa-images text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">Tidak ada foto dalam galeri ini</h3>
                <p class="text-gray-500 mb-6">Foto-foto akan ditambahkan segera.</p>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center">
            <a href="{{ route('galeri.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Galeri
            </a>
        </div>
    </div>
</div>

<!-- Photo Modal -->
<div id="photoModal" class="fixed inset-0 bg-black/95 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <!-- Modal Content -->
        <div class="relative max-w-5xl w-full">
            <!-- Close Button -->
            <button onclick="closePhotoModal()" 
                    class="absolute top-4 right-4 z-10 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
            
            <!-- Navigation Buttons -->
            <button onclick="prevPhoto()" 
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors duration-200">
                <i class="fas fa-chevron-left text-lg"></i>
            </button>
            
            <button onclick="nextPhoto()" 
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 bg-black/50 hover:bg-black/70 text-white p-3 rounded-full transition-colors duration-200">
                <i class="fas fa-chevron-right text-lg"></i>
            </button>
            
            <!-- Photo Container -->
            <div class="flex items-center justify-center min-h-[60vh]">
                <img id="modalPhoto" 
                     src="" 
                     alt="" 
                     class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl">
            </div>
            
            <!-- Photo Info -->
            <div class="text-center mt-4">
                <p id="photoCounter" class="text-white/80 text-lg"></p>
                <div class="flex justify-center gap-4 mt-3">
                    <button onclick="downloadPhoto()" 
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>
                        Download
                    </button>
                    <button onclick="sharePhoto()" 
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                        <i class="fas fa-share mr-2"></i>
                        Share
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const photos = @json($galeri->foto->map(function($foto) { return $foto->foto; }));
    let currentPhotoIndex = 0;
    
    function openPhotoModal(index) {
        currentPhotoIndex = index;
        updateModalPhoto();
        
        const modal = document.getElementById('photoModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('show'), 10);
        document.body.style.overflow = 'hidden';
    }
    
    function closePhotoModal() {
        const modal = document.getElementById('photoModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    function updateModalPhoto() {
        const modalPhoto = document.getElementById('modalPhoto');
        const photoCounter = document.getElementById('photoCounter');
        
        modalPhoto.src = `/storage/${photos[currentPhotoIndex]}`;
        modalPhoto.alt = `Foto ${currentPhotoIndex + 1}`;
        photoCounter.textContent = `${currentPhotoIndex + 1} dari ${photos.length}`;
    }
    
    function prevPhoto() {
        currentPhotoIndex = (currentPhotoIndex - 1 + photos.length) % photos.length;
        updateModalPhoto();
    }
    
    function nextPhoto() {
        currentPhotoIndex = (currentPhotoIndex + 1) % photos.length;
        updateModalPhoto();
    }
    
    function downloadPhoto() {
        const link = document.createElement('a');
        link.href = `/storage/${photos[currentPhotoIndex]}`;
        link.download = `foto_${currentPhotoIndex + 1}.jpg`;
        link.click();
    }
    
    function sharePhoto() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $galeri->judul }}',
                text: '{{ $galeri->deskripsi }}',
                url: window.location.href
            });
        } else {
            // Fallback: copy URL to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link telah disalin ke clipboard!');
            });
        }
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('photoModal');
        if (!modal.classList.contains('hidden')) {
            switch(e.key) {
                case 'Escape':
                    closePhotoModal();
                    break;
                case 'ArrowLeft':
                    prevPhoto();
                    break;
                case 'ArrowRight':
                    nextPhoto();
                    break;
            }
        }
    });
    
    // Close modal on backdrop click
    document.getElementById('photoModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePhotoModal();
        }
    });
    
    // Add show class styles
    const style = document.createElement('style');
    style.textContent = `
        #photoModal.show {
            opacity: 1;
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection
