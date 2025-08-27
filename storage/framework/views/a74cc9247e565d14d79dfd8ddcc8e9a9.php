<?php $__env->startSection('title', 'Manajemen Guru'); ?>

<?php use Illuminate\Support\Str; ?>

<?php $__env->startSection('content'); ?>
<div class="w-full px-3 py-4">
    <!-- Header with stats -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-blue-100">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-chalkboard-teacher text-blue-500 mr-3"></i> Manajemen Guru
                </h1>
                <p class="text-gray-600 mt-1">Kelola data guru, wali kelas dan staf pengajar</p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Import Button -->
                <button onclick="toggleImportModal()" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all hover:shadow">
                    <i class="fas fa-upload mr-2"></i> Import Guru
                </button>
                
                <!-- Download Template Button -->
                <a href="<?php echo e(route('admin.guru.download-template')); ?>" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all hover:shadow">
                    <i class="fas fa-download mr-2"></i> Template Excel
                </a>
                
                <a href="<?php echo e(route('admin.guru.create')); ?>" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg flex items-center shadow-sm transition-all hover:shadow">
                    <i class="fas fa-plus mr-2"></i> Tambah Guru
                </a>
            </div>
        </div>
        
        <!-- Stats cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 border border-blue-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Total Guru</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($guru->count()); ?></h3>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <i class="fas fa-users text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Wali Kelas</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($guru->where('is_wali_kelas', true)->count()); ?></h3>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <i class="fas fa-user-tie text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Guru Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?php echo e($guru->where('is_active', true)->count()); ?></h3>
                    </div>
                    <div class="bg-white p-3 rounded-lg shadow-sm">
                        <i class="fas fa-user-check text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter text-blue-500 mr-2"></i> Filter dan Pencarian
        </h2>
        <form action="<?php echo e(route('admin.guru.index')); ?>" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="<?php echo e(request('search')); ?>" 
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 pl-10"
                        placeholder="Cari berdasarkan nama atau NIP...">
                </div>
            </div>
            <div class="w-[200px]">
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                <select name="jenis_kelamin" id="jenis_kelamin" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="L" <?php echo e(request('jenis_kelamin') == 'L' ? 'selected' : ''); ?>>Laki-laki</option>
                    <option value="P" <?php echo e(request('jenis_kelamin') == 'P' ? 'selected' : ''); ?>>Perempuan</option>
                </select>
            </div>
            <div class="w-[200px]">
                <label for="is_wali_kelas" class="block text-sm font-medium text-gray-700 mb-1">Status Wali Kelas</label>
                <select name="is_wali_kelas" id="is_wali_kelas" 
                    class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua</option>
                    <option value="1" <?php echo e(request('is_wali_kelas') == '1' ? 'selected' : ''); ?>>Wali Kelas</option>
                    <option value="0" <?php echo e(request('is_wali_kelas') == '0' ? 'selected' : ''); ?>>Bukan Wali Kelas</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="<?php echo e(route('admin.guru.index')); ?>" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Tampilan Card Guru -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-users text-blue-500 mr-2"></i> Daftar Guru
        </h2>
        
        <?php if($guru->isEmpty()): ?>
            <div class="bg-white rounded-xl p-8 shadow-sm text-center border border-gray-100">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-500 mb-4">
                    <i class="fas fa-user-slash text-xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800 mb-2">Tidak ada data guru</h3>
                <p class="text-gray-600 mb-4">Belum ada data guru yang tersedia.</p>
                <a href="<?php echo e(route('admin.guru.create')); ?>" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i> Tambah Guru Baru
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $guru; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-all group">
                        <div class="relative">
                            <!-- Cover Image - a light gradient background -->
                            <div class="h-24 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                            
                            <!-- Teacher Avatar -->
                            <div class="absolute bottom-0 translate-y-1/2 left-6">
                                <div class="ring-4 ring-white rounded-full">
                                    <?php if(!empty($g->foto)): ?>
                                        <img src="<?php echo e(asset('storage/'.$g->foto)); ?>" alt="<?php echo e($g->nama); ?>" 
                                            class="h-20 w-20 rounded-full object-cover border-2 border-white">
                                    <?php else: ?>
                                        <div class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-xl border-2 border-white">
                                            <?php echo e(Str::limit($g->nama, 2, '')); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Status Badge -->
                            <?php if($g->is_active): ?>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold border border-green-200">
                                        <i class="fas fa-check-circle mr-1"></i> Aktif
                                    </span>
                                </div>
                            <?php else: ?>
                                <div class="absolute top-4 right-4">
                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold border border-red-200">
                                        <i class="fas fa-times-circle mr-1"></i> Tidak Aktif
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Teacher Details -->
                        <div class="pt-14 px-6 pb-5">
                            <div class="flex items-center mb-1">
                                <h3 class="text-lg font-semibold text-gray-900"><?php echo e($g->nama); ?></h3>
                                <?php if($g->is_wali_kelas): ?>
                                    <span class="ml-2 inline-block px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        <i class="fas fa-user-shield mr-1"></i> Wali Kelas
                                    </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">NIP: <?php echo e($g->nip ?: 'Belum diisi'); ?></p>
                            
                            <!-- Kelas yang dipimpin (jika wali kelas) -->
                            <?php if($g->is_wali_kelas && $g->kelasWali): ?>
                                <div class="mb-3 p-2 bg-blue-50 rounded-lg border border-blue-200">
                                    <div class="flex items-center text-blue-700">
                                        <i class="fas fa-school mr-2"></i>
                                        <span class="text-sm font-medium">Wali Kelas: <?php echo e($g->kelasWali->nama_kelas); ?></span>
                                    </div>
                                </div>
                            <?php elseif($g->is_wali_kelas && !$g->kelasWali): ?>
                                <div class="mb-3 p-2 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center text-yellow-700">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <span class="text-sm">Belum ditugaskan ke kelas</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Additional Info -->
                            <div class="grid grid-cols-1 gap-2 mb-4 text-sm">
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-envelope w-5 text-gray-500 mr-2"></i>
                                    <span class="truncate"><?php echo e($g->email); ?></span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-phone-alt w-5 text-gray-500 mr-2"></i>
                                    <span><?php echo e($g->no_hp); ?></span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-venus-mars w-5 text-gray-500 mr-2"></i>
                                    <span><?php echo e($g->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan'); ?></span>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <a href="<?php echo e(route('admin.guru.show', $g)); ?>" 
                                    class="text-blue-600 hover:text-blue-800 transition-colors">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                <div class="flex items-center space-x-2">
                                    <a href="<?php echo e(route('admin.guru.edit', $g)); ?>" 
                                        class="p-2 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('admin.guru.destroy', $g)); ?>" method="POST" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus <?php echo e($g->nama); ?>?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Import Data Guru</h3>
                <button onclick="toggleImportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="<?php echo e(route('admin.guru.import')); ?>" method="POST" enctype="multipart/form-data" id="importForm">
                <?php echo csrf_field(); ?>
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        File Excel (.xlsx, .xls, .csv)
                    </label>
                    <input type="file" 
                           name="file" 
                           id="file" 
                           accept=".xlsx,.xls,.csv"
                           required
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <h4 class="text-sm font-medium text-blue-800 mb-2">Petunjuk Import:</h4>
                    <ul class="text-xs text-blue-700 space-y-1">
                        <li>• Download template Excel terlebih dahulu</li>
                        <li>• Isi data sesuai dengan format yang tersedia</li>
                        <li>• Pastikan NIP tidak duplikat (jika diisi)</li>
                        <li>• Password default akan sama dengan NIP (jika ada), atau nama jika NIP kosong</li>
                        <li>• Kolom yang wajib diisi: Nama</li>
                    </ul>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="toggleImportModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-upload mr-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleImportModal() {
    const modal = document.getElementById('importModal');
    modal.classList.toggle('hidden');
}

// Close modal when clicking outside
document.getElementById('importModal').addEventListener('click', function(e) {
    if (e.target === this) {
        toggleImportModal();
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views/admin/guru/index.blade.php ENDPATH**/ ?>