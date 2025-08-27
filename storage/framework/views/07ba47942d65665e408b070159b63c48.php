

<?php $__env->startSection('title', 'Absensi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Mobile-first responsive design for absensi */
    @media (max-width: 767px) {
        /* Header optimizations */
        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header-actions {
            display: flex;
            width: 100%;
            gap: 0.5rem;
        }

        .mobile-header-actions a {
            flex: 1;
            justify-content: center;
            font-size: 0.875rem;
            padding: 0.75rem;
        }

        /* Filter form mobile */
        .mobile-filter-grid {
            grid-template-columns: 1fr !important;
            gap: 0.75rem !important;
        }

        .mobile-filter-actions {
            display: flex !important;
            gap: 0.5rem !important;
            width: 100% !important;
        }

        .mobile-filter-actions button,
        .mobile-filter-actions a {
            flex: 1 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            min-height: 2rem !important;
        }

        /* Header actions mobile - VISIBILITY FIX */
        .mobile-header-actions {
            display: flex !important;
            width: 100% !important;
            gap: 0.5rem !important;
        }

        .mobile-header-actions a {
            flex: 1 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            font-size: 0.875rem !important;
            padding: 0.75rem !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        /* Table mobile - convert to cards */
        .mobile-table-container {
            display: none;
        }

        .mobile-cards-container {
            display: block;
        }

        .mobile-absensi-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .mobile-card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
        }

        .mobile-card-date {
            font-size: 0.75rem;
            color: #6b7280;
            background: #f3f4f6;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }

        .mobile-card-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .mobile-info-icon {
            width: 1.5rem;
            height: 1.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .mobile-stats-row {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .mobile-stat-item {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
        }

        .mobile-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
        }

        .mobile-actions a,
        .mobile-actions button {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            text-decoration: none;
        }

        /* Typography mobile */
        .mobile-title {
            font-size: 1.5rem;
            line-height: 1.3;
        }

        /* Spacing adjustments */
        .mobile-spacing {
            padding: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-section-spacing {
            margin-bottom: 1.5rem;
        }
    }

    /* Desktop table display */
    @media (min-width: 768px) {
        .mobile-cards-container {
            display: none;
        }

        .mobile-table-container {
            display: block;
        }
    }

    /* Enhanced hover effects */
    .mobile-absensi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
        transform: scale(1.02);
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <div class="bg-white rounded-lg shadow-lg mobile-spacing md:p-6">
        <div class="flex mobile-header md:justify-between md:items-center mobile-section-spacing md:mb-6">
            <div class="flex-1">
                <h1 class="mobile-title md:text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-clipboard-list text-blue-600 mr-2 md:mr-3"></i>
                    Data Absensi
                </h1>
                <p class="text-sm md:text-base text-gray-600 mt-1">Kelola data kehadiran siswa</p>
            </div>
            <div class="mobile-header-actions md:flex md:space-x-3">
                <a href="<?php echo e(route('guru.absensi.create')); ?>" class="action-btn bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Tambah </span>Absensi
                </a>
                <a href="<?php echo e(route('guru.absensi.rekap')); ?>" class="action-btn bg-green-600 hover:bg-green-700 text-white px-3 md:px-4 py-2 rounded-lg transition-colors flex items-center">
                    <i class="fas fa-chart-bar mr-1 md:mr-2"></i>
                    <span class="hidden sm:inline">Lihat </span>Rekap
                </a>
            </div>
        </div>

        <div class="mobile-section-spacing md:mb-8">
            <form method="GET" action="<?php echo e(route('guru.absensi.index')); ?>" class="space-y-3 md:space-y-4">
                <div class="grid mobile-filter-grid md:grid-cols-3 gap-3 md:gap-4">
                    <div>
                        <label for="kelas_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                        <select name="kelas_id" id="kelas_filter" class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kelas</option>
                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="mapel_filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Mata Pelajaran</label>
                        <select name="mapel_id" id="mapel_filter" class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Mata Pelajaran</option>
                            <?php $__currentLoopData = $mapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>" <?php echo e(request('mapel_id') == $m->id ? 'selected' : ''); ?>><?php echo e($m->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label for="tanggal_filter" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal_filter" value="<?php echo e(request('tanggal')); ?>" class="w-full px-3 py-2 md:py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <div class="flex mobile-filter-actions md:items-center md:space-x-2 pt-3 md:pt-4">
                    <button type="submit" class="action-btn bg-blue-600 hover:bg-blue-700 text-white px-3 md:px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-search mr-1 md:mr-2"></i> Filter
                    </button>
                    <a href="<?php echo e(route('guru.absensi.index')); ?>" class="action-btn bg-gray-500 hover:bg-gray-600 text-white px-3 md:px-4 py-2 rounded-lg transition-colors flex items-center">
                        <i class="fas fa-refresh mr-1 md:mr-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Filter Status Indicator -->
        <?php if(request()->hasAny(['kelas_id', 'mapel_id', 'tanggal'])): ?>
        <div class="mb-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-filter text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Filter Aktif</h3>
                        <div class="mt-1 text-sm text-blue-700">
                            <?php if(request('kelas_id')): ?>
                                <?php $selectedKelas = $kelas->where('id', request('kelas_id'))->first(); ?>
                                Menampilkan data untuk kelas: <strong><?php echo e($selectedKelas->nama_kelas ?? 'Kelas tidak ditemukan'); ?></strong>
                                <?php if(request('mapel_id') || request('tanggal')): ?>
                                    <br>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if(request('mapel_id')): ?>
                                <?php $selectedMapel = $mapel->where('id', request('mapel_id'))->first(); ?>
                                Mata pelajaran: <strong><?php echo e($selectedMapel->nama ?? 'Mata pelajaran tidak ditemukan'); ?></strong>
                                <?php if(request('tanggal')): ?>
                                    <br>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if(request('tanggal')): ?>
                                Tanggal: <strong><?php echo e(\Carbon\Carbon::parse(request('tanggal'))->format('d/m/Y')); ?></strong>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="ml-auto">
                        <a href="<?php echo e(route('guru.absensi.index')); ?>" class="inline-flex items-center px-3 py-1.5 border border-blue-300 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors">
                            <i class="fas fa-times mr-1"></i>
                            Hapus Filter
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Mobile Cards Container -->
        <div class="mobile-cards-container">
            <?php $__empty_1 = true; $__currentLoopData = $absensi_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mobile-absensi-card">
                    <div class="mobile-card-header">
                        <div>
                            <h3 class="mobile-card-title"><?php echo e($absensi->kelas->nama_kelas ?? '-'); ?></h3>
                            <p class="text-sm text-gray-600"><?php echo e($absensi->mapel->nama ?? '-'); ?></p>
                        </div>
                        <div class="mobile-card-date">
                            <?php echo e(\Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')); ?>

                        </div>
                    </div>

                    <div class="mobile-stats-row">
                        <div class="mobile-stat-item bg-blue-50 text-blue-700">
                            <div class="font-semibold"><?php echo e($absensi->total_siswa); ?></div>
                            <div>Total</div>
                        </div>
                        <div class="mobile-stat-item bg-green-50 text-green-700">
                            <div class="font-semibold"><?php echo e($absensi->hadir); ?></div>
                            <div>Hadir</div>
                        </div>
                        <div class="mobile-stat-item bg-red-50 text-red-700">
                            <div class="font-semibold"><?php echo e($absensi->tidak_hadir); ?></div>
                            <div>Tidak Hadir</div>
                        </div>
                    </div>

                    <div class="mobile-actions">
                        <a href="<?php echo e(route('guru.absensi.show', $absensi->id)); ?>" class="bg-blue-100 text-blue-700 hover:bg-blue-200">
                            <i class="fas fa-eye mr-1"></i> Detail
                        </a>
                        <a href="<?php echo e(route('guru.absensi.edit', $absensi->id)); ?>" class="bg-yellow-100 text-yellow-700 hover:bg-yellow-200">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <button type="button" class="bg-red-100 text-red-700 hover:bg-red-200 delete-btn"
                                data-tanggal="<?php echo e(\Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')); ?>"
                                data-kelas="<?php echo e($absensi->kelas->nama_kelas ?? '-'); ?>"
                                data-mapel="<?php echo e($absensi->mapel->nama ?? '-'); ?>"
                                data-absensi-id="<?php echo e($absensi->id); ?>">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                        <!-- Hidden form for delete -->
                        <form action="<?php echo e(route('guru.absensi.destroy', $absensi->id)); ?>" method="POST" class="hidden delete-form" data-id="<?php echo e($absensi->id); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-8 md:py-12">
                    <i class="fas fa-clipboard-list text-gray-400 text-3xl md:text-4xl mb-3 md:mb-4"></i>
                    <p class="text-base md:text-lg font-medium mb-2">Belum ada data absensi</p>
                    <p class="text-sm text-gray-400 mb-4">Silakan tambah data absensi atau ubah filter pencarian</p>
                    <a href="<?php echo e(route('guru.absensi.create')); ?>" class="action-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors inline-flex items-center">
                        <i class="fas fa-plus mr-2"></i> Tambah Absensi
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Desktop Table Container -->
        <div class="mobile-table-container overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Siswa</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hadir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tidak Hadir</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $absensi_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e(\Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($absensi->kelas->nama_kelas ?? '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php echo e($absensi->mapel->nama ?? '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            <?php echo e($absensi->total_siswa); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-center font-medium">
                            <?php echo e($absensi->hadir); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-center font-medium">
                            <?php echo e($absensi->tidak_hadir); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('guru.absensi.show', $absensi->id)); ?>" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                <a href="<?php echo e(route('guru.absensi.edit', $absensi->id)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="<?php echo e(route('guru.absensi.destroy', $absensi->id)); ?>" method="POST" class="inline delete-form">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="button" class="text-red-600 hover:text-red-900 delete-btn"
                                            data-tanggal="<?php echo e(\Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')); ?>"
                                            data-kelas="<?php echo e($absensi->kelas->nama_kelas ?? '-'); ?>"
                                            data-mapel="<?php echo e($absensi->mapel->nama ?? '-'); ?>">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center py-8">
                                <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-4"></i>
                                <p class="text-lg font-medium mb-2">Belum ada data absensi</p>
                                <p class="text-sm text-gray-400 mb-4">Silakan tambah data absensi atau ubah filter pencarian</p>
                                <a href="<?php echo e(route('guru.absensi.create')); ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i> Tambah Absensi
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Function to filter kelas berdasarkan mapel yang dipilih
    function filterKelasByMapel() {
        const mapelSelect = document.getElementById('mapel_filter');
        const kelasSelect = document.getElementById('kelas_filter');
        const mapelId = mapelSelect.value;
        
        // Save current selected kelas
        const currentKelasId = kelasSelect.value;
        
        if (mapelId) {
            // Fetch kelas berdasarkan mapel
            fetch(`<?php echo e(route('guru.absensi.kelas-by-mapel')); ?>?mapel_id=${mapelId}`)
                .then(response => response.json())
                .then(data => {
                    // Clear current options
                    kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                    
                    // Add new options
                    data.forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas.id;
                        option.textContent = kelas.nama_kelas;
                        if (kelas.id == currentKelasId) {
                            option.selected = true;
                        }
                        kelasSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching kelas:', error);
                });
        } else {
            // Reset to all kelas
            fetch(`<?php echo e(route('guru.absensi.kelas-by-mapel')); ?>`)
                .then(response => response.json())
                .then(data => {
                    kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                    data.forEach(kelas => {
                        const option = document.createElement('option');
                        option.value = kelas.id;
                        option.textContent = kelas.nama_kelas;
                        if (kelas.id == currentKelasId) {
                            option.selected = true;
                        }
                        kelasSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching kelas:', error);
                });
        }
    }

    // Initialize filters and delete handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Setup mapel filter
        const mapelSelect = document.getElementById('mapel_filter');
        if (mapelSelect) {
            mapelSelect.addEventListener('change', filterKelasByMapel);
            
            // Initial filter on page load if mapel is already selected
            if (mapelSelect.value) {
                filterKelasByMapel();
            }
        }
        
        // Single delete handler using event delegation
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-btn')) {
                e.preventDefault();
                
                const button = e.target.closest('.delete-btn');
                let form = button.closest('.delete-form') || button.closest('form');
                
                // If form not found in parent/closest, try to find by data-id attribute for mobile
                if (!form && button.dataset.absensiId) {
                    form = document.querySelector('.delete-form[data-id="' + button.dataset.absensiId + '"]');
                }
                
                if (!form) {
                    console.error('Form not found for delete button', button);
                    return;
                }
                
                const tanggal = button.dataset.tanggal || 'N/A';
                const kelas = button.dataset.kelas || 'N/A';
                const mapel = button.dataset.mapel || 'N/A';
                
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        html: 'Apakah Anda yakin ingin menghapus data absensi?<br><br>' +
                              '<strong>Tanggal:</strong> ' + tanggal + '<br>' +
                              '<strong>Kelas:</strong> ' + kelas + '<br>' +
                              '<strong>Mata Pelajaran:</strong> ' + mapel + '<br><br>' +
                              '<span class="text-red-600">Data yang dihapus tidak dapat dikembalikan!</span>',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                } else {
                    var confirmMessage = 'Hapus data absensi?\n\n' +
                                       'Tanggal: ' + tanggal + '\n' +
                                       'Kelas: ' + kelas + '\n' +
                                       'Mata Pelajaran: ' + mapel;
                    
                    if (confirm(confirmMessage)) {
                        form.submit();
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\index.blade.php ENDPATH**/ ?>