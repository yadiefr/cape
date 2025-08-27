

<?php $__env->startSection('title', 'Bank Soal'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4 py-6">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="<?php echo e(route('admin.ujian.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-home w-4 h-4"></i>
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <span class="text-gray-500">Bank Soal</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Bank Soal</h1>
            <p class="text-gray-600 mt-1">Manajemen dan koleksi soal-soal ujian</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('admin.ujian.bank-soal.import')); ?>" 
               class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-file-excel w-4 h-4 mr-2"></i>
                Import Soal
            </a>
            <a href="<?php echo e(route('admin.ujian.bank-soal.create')); ?>" 
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                <i class="fas fa-plus w-4 h-4 mr-2"></i>
                Tambah Soal
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-question-circle text-blue-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Soal</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($soal) ? $soal->total() : 0); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Soal Aktif</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($totalAktif) ? $totalAktif : 0); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-list text-yellow-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pilihan Ganda</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($totalPilihanGanda) ? $totalPilihanGanda : 0); ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-edit text-purple-600 w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Essay</p>
                    <p class="text-2xl font-bold text-gray-800"><?php echo e(isset($totalEssay) ? $totalEssay : 0); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Main Table Section -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">Daftar Bank Soal</h3>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-500"><?php echo e(isset($soal) ? $soal->total() : 0); ?> soal tersedia</span>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                            <select id="mata_pelajaran" name="mata_pelajaran" 
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Mata Pelajaran</option>
                                <?php if(isset($mataPelajaran)): ?>
                                    <?php $__currentLoopData = $mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($mp->id); ?>" <?php echo e(request('mapel') == $mp->id ? 'selected' : ''); ?>>
                                            <?php echo e($mp->nama); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                            <select id="kelas" name="kelas"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Kelas</option>
                                <?php if(isset($kelas)): ?>
                                    <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas') == $k->id ? 'selected' : ''); ?>>
                                            <?php echo e($k->nama_kelas); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Soal</label>
                            <select id="jenis_soal" name="jenis_soal"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Semua Jenis</option>
                                <option value="pilihan_ganda" <?php echo e(request('jenis') == 'pilihan_ganda' ? 'selected' : ''); ?>>Pilihan Ganda</option>
                                <option value="essay" <?php echo e(request('jenis') == 'essay' ? 'selected' : ''); ?>>Essay</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="filter" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-search w-4 h-4 mr-2"></i>
                                Filter
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Soal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pertanyaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if(isset($soal) && $soal->count() > 0): ?>
                                <?php $__currentLoopData = $soal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo e(($soal->currentPage() - 1) * $soal->perPage() + $index + 1); ?>

                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($item->mataPelajaran->nama ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo e($item->kelas->nama_kelas ?? '-'); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            <?php echo e($item->jenis_soal == 'pilihan_ganda' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'); ?>">
                                            <?php echo e($item->jenis_soal == 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-md truncate" title="<?php echo e(strip_tags($item->pertanyaan)); ?>">
                                            <?php echo e(Str::limit(strip_tags($item->pertanyaan), 60)); ?>

                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            <?php echo e($item->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'); ?>">
                                            <?php echo e($item->aktif ? 'Aktif' : 'Tidak Aktif'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="<?php echo e(route('admin.ujian.bank-soal.show', $item->id)); ?>" 
                                               class="text-blue-600 hover:text-blue-700 transition-colors duration-150" title="Detail">
                                                <i class="fas fa-eye w-4 h-4"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.ujian.bank-soal.edit', $item->id)); ?>" 
                                               class="text-green-600 hover:text-green-700 transition-colors duration-150" title="Edit">
                                                <i class="fas fa-edit w-4 h-4"></i>
                                            </a>
                                            <button onclick="confirmDelete('<?php echo e($item->id); ?>')" 
                                                    class="text-red-600 hover:text-red-700 transition-colors duration-150" title="Hapus">
                                                <i class="fas fa-trash w-4 h-4"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-question-circle w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-2">Belum ada soal</p>
                                        <p class="text-sm mb-4">Mulai dengan menambahkan soal pertama</p>
                                        <a href="<?php echo e(route('admin.ujian.bank-soal.create')); ?>" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200">
                                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                                            Tambah Soal
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if(isset($soal) && $soal->hasPages()): ?>
                <div class="bg-white px-6 py-3 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-700">
                            Menampilkan <span class="font-medium"><?php echo e($soal->firstItem()); ?></span> sampai 
                            <span class="font-medium"><?php echo e($soal->lastItem()); ?></span> dari 
                            <span class="font-medium"><?php echo e($soal->total()); ?></span> hasil
                        </div>
                        <div>
                            <?php echo e($soal->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="lg:col-span-1">
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h4>
                    <div class="space-y-3">
                        <a href="<?php echo e(route('admin.ujian.bank-soal.create')); ?>" 
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                            <i class="fas fa-plus w-4 h-4 mr-2"></i>
                            Tambah Soal
                        </a>
                        <a href="<?php echo e(route('admin.ujian.bank-soal.import')); ?>" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 text-center">
                            <i class="fas fa-file-excel w-4 h-4 mr-2"></i>
                            Import Soal
                        </a>
                        <button onclick="window.print()" 
                                class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-print w-4 h-4 mr-2"></i>
                            Print Laporan
                        </button>
                    </div>
                </div>

                <!-- Filter Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Ringkasan Filter</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Mata Pelajaran:</span>
                            <span class="font-medium text-gray-800" id="filter-mapel">Semua</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kelas:</span>
                            <span class="font-medium text-gray-800" id="filter-kelas">Semua</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Soal:</span>
                            <span class="font-medium text-gray-800" id="filter-jenis">Semua</span>
                        </div>
                    </div>
                    <button onclick="clearFilters()" 
                            class="w-full mt-4 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors duration-200">
                        <i class="fas fa-times w-4 h-4 mr-2"></i>
                        Reset Filter
                    </button>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h4>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-gray-800 font-medium">Soal Matematika ditambahkan</p>
                                <p class="text-gray-500 text-xs">2 jam yang lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-gray-800 font-medium">Import 25 soal Bahasa Indonesia</p>
                                <p class="text-gray-500 text-xs">1 hari yang lalu</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-gray-800 font-medium">Soal IPA diperbarui</p>
                                <p class="text-gray-500 text-xs">3 hari yang lalu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50"
     x-data="{ open: false }" 
     x-show="open" 
     x-transition:enter="ease-out duration-300" 
     x-transition:enter-start="opacity-0" 
     x-transition:enter-end="opacity-100" 
     x-transition:leave="ease-in duration-200" 
     x-transition:leave-start="opacity-100" 
     x-transition:leave-end="opacity-0">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
                <button type="button" onclick="closeDeleteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times w-5 h-5"></i>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus soal ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex items-center justify-end space-x-3 px-7 py-3">
                <button type="button" 
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-lg transition-colors duration-200">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // Filter functionality
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtn = document.getElementById('filter');
        const mataPelajaranSelect = document.getElementById('mata_pelajaran');
        const kelasSelect = document.getElementById('kelas');
        const jenisSoalSelect = document.getElementById('jenis_soal');

        // Update filter summary
        function updateFilterSummary() {
            const mapelText = mataPelajaranSelect.options[mataPelajaranSelect.selectedIndex].text;
            const kelasText = kelasSelect.options[kelasSelect.selectedIndex].text;
            const jenisText = jenisSoalSelect.options[jenisSoalSelect.selectedIndex].text;

            document.getElementById('filter-mapel').textContent = mapelText || 'Semua';
            document.getElementById('filter-kelas').textContent = kelasText || 'Semua';
            document.getElementById('filter-jenis').textContent = jenisText || 'Semua';
        }

        // Apply filters
        filterBtn.addEventListener('click', function() {
            const mapel = mataPelajaranSelect.value;
            const kelas = kelasSelect.value;
            const jenis = jenisSoalSelect.value;

            const url = new URL(window.location.href);
            url.searchParams.delete('mapel');
            url.searchParams.delete('kelas');
            url.searchParams.delete('jenis');

            if (mapel) url.searchParams.set('mapel', mapel);
            if (kelas) url.searchParams.set('kelas', kelas);
            if (jenis) url.searchParams.set('jenis', jenis);

            window.location.href = url.toString();
        });

        // Update filter summary on page load
        updateFilterSummary();

        // Update filter summary when selections change
        [mataPelajaranSelect, kelasSelect, jenisSoalSelect].forEach(select => {
            select.addEventListener('change', updateFilterSummary);
        });
    });

    // Clear filters
    function clearFilters() {
        const url = new URL(window.location.href);
        url.searchParams.delete('mapel');
        url.searchParams.delete('kelas');
        url.searchParams.delete('jenis');
        window.location.href = url.toString();
    }

    // Delete confirmation
    function confirmDelete(id) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        form.action = `<?php echo e(route('admin.ujian.bank-soal.destroy', '')); ?>/${id}`;
        modal.classList.remove('hidden');
        modal.querySelector('[x-data]').__x.$data.open = true;
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.querySelector('[x-data]').__x.$data.open = false;
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
    }

    // Close modal on backdrop click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.ujian', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\bank-soal\index.blade.php ENDPATH**/ ?>