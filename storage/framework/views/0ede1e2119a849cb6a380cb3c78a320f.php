

<?php $__env->startSection('title', 'Galeri Foto - ' . setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>

<?php $__env->startSection('meta_description', 'Galeri foto kegiatan, fasilitas, dan kehidupan siswa di ' . setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-16">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
                <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                    Galeri Foto
                </span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Kumpulan foto kegiatan, fasilitas, dan momen-momen berharga di <?php echo e(setting('nama_sekolah', 'SMK PGRI CIKAMPEK')); ?>

            </p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <form method="GET" action="<?php echo e(route('galeri.index')); ?>" class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               value="<?php echo e($search); ?>"
                               placeholder="Cari galeri foto..." 
                               class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="md:w-60">
                    <select name="kategori" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all" <?php echo e($kategori === 'all' ? 'selected' : ''); ?>>Semua</option>
                        <?php $__currentLoopData = $kategoris; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kat); ?>" <?php echo e($kategori === $kat ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst(str_replace(['_', '-'], ' ', $kat))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </form>
        </div>

        <!-- Gallery Grid -->
        <?php if($galeri->count() > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                <?php $__currentLoopData = $galeri; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                        <!-- Image Container -->
                        <div class="relative aspect-square overflow-hidden">
                            <img src="<?php echo e(asset('uploads/galeri/' . $item->gambar)); ?>" 
                                 alt="<?php echo e($item->judul); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Action Buttons -->
                            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <!-- View Gallery Button -->
                                <a href="<?php echo e(route('galeri.show', $item->id)); ?>" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-lg transition-colors duration-200"
                                   title="Lihat Galeri">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Quick View Button -->
                                <button onclick="openQuickView(<?php echo e($item->id); ?>, '<?php echo e(str_replace("'", "\\'", $item->judul)); ?>', '<?php echo e(str_replace("'", "\\'", $item->deskripsi)); ?>')"
                                        class="bg-purple-600 hover:bg-purple-700 text-white p-2 rounded-full shadow-lg transition-colors duration-200"
                                        title="Quick View">
                                    <i class="fas fa-images"></i>
                                </button>
                            </div>
                            
                            <!-- Photo Count Badge -->
                            <div class="absolute bottom-4 left-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm flex items-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-camera mr-1"></i>
                                <?php echo e($item->foto->count()); ?> Foto
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2"><?php echo e($item->judul); ?></h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-3"><?php echo e($item->deskripsi); ?></p>
                            
                            <!-- Category and Date -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full">
                                    <?php echo e(ucfirst(str_replace(['_', '-'], ' ', $item->kategori))); ?>

                                </span>
                                <span>
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    <?php echo e($item->created_at->format('d M Y')); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                <?php echo e($galeri->withQueryString()->links()); ?>

            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="mb-8">
                    <i class="fas fa-images text-6xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">Tidak ada galeri ditemukan</h3>
                <p class="text-gray-500 mb-6">
                    <?php if($search || $kategori !== 'all'): ?>
                        Coba ubah kata kunci pencarian atau filter kategori Anda.
                    <?php else: ?>
                        Galeri foto belum tersedia saat ini.
                    <?php endif; ?>
                </p>
                <?php if($search || $kategori !== 'all'): ?>
                    <a href="<?php echo e(route('galeri.index')); ?>" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-refresh mr-2"></i>
                        Lihat Semua Galeri
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-hidden transform scale-95 transition-transform duration-300" id="quickViewContent">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <div>
                    <h3 id="quickViewTitle" class="text-xl font-bold text-gray-800"></h3>
                    <p id="quickViewDescription" class="text-gray-600 mt-1"></p>
                </div>
                <button onclick="closeQuickView()" class="text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6 max-h-[60vh] overflow-y-auto">
                <div id="quickViewPhotos" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Photos will be loaded here -->
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="flex justify-end gap-3 p-6 border-t bg-gray-50">
                <button onclick="closeQuickView()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    Tutup
                </button>
                <button id="viewFullGallery" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Lihat Galeri Lengkap
                </button>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    #quickViewModal.show {
        opacity: 1;
    }
    
    #quickViewModal.show #quickViewContent {
        transform: scale(1);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let currentGalleryId = null;
    
    function openQuickView(galleryId, title, description) {
        currentGalleryId = galleryId;
        
        document.getElementById('quickViewTitle').textContent = title;
        document.getElementById('quickViewDescription').textContent = description;
        document.getElementById('viewFullGallery').onclick = function() {
            window.location.href = `/galeri/${galleryId}`;
        };
        
        // Show modal
        const modal = document.getElementById('quickViewModal');
        modal.classList.remove('hidden');
        setTimeout(() => modal.classList.add('show'), 10);
        document.body.style.overflow = 'hidden';
        
        // Load photos
        const photosContainer = document.getElementById('quickViewPhotos');
        photosContainer.innerHTML = '<div class="col-span-full text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i><br><span class="text-gray-500 mt-2">Memuat foto...</span></div>';
        
        fetch(`/api/galeri/${galleryId}/photos`)
            .then(response => response.json())
            .then(photos => {
                photosContainer.innerHTML = '';
                
                if (photos.length === 0) {
                    photosContainer.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500"><i class="fas fa-images text-4xl mb-2"></i><br>Tidak ada foto dalam galeri ini</div>';
                } else {
                    photos.forEach((photo, index) => {
                        const photoDiv = document.createElement('div');
                        photoDiv.className = 'relative aspect-square overflow-hidden rounded-lg cursor-pointer hover:scale-105 transition-transform duration-200';
                        photoDiv.innerHTML = `
                            <img src="/uploads/galeri/${photo.foto}" alt="Foto ${index + 1}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-search-plus text-white opacity-0 hover:opacity-100 transition-opacity duration-200"></i>
                            </div>
                        `;
                        photoDiv.onclick = function() {
                            window.open(`/uploads/galeri/${photo.foto}`, '_blank');
                        };
                        photosContainer.appendChild(photoDiv);
                    });
                }
            })
            .catch(error => {
                photosContainer.innerHTML = '<div class="col-span-full text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-4xl mb-2"></i><br>Gagal memuat foto</div>';
            });
    }
    
    function closeQuickView() {
        const modal = document.getElementById('quickViewModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
    
    // Close modal on backdrop click
    document.getElementById('quickViewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQuickView();
        }
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('quickViewModal').classList.contains('hidden')) {
            closeQuickView();
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views/galeri/index.blade.php ENDPATH**/ ?>