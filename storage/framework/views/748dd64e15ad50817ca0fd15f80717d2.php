

<?php $__env->startSection('title', 'Edit Absensi'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Edit Data Absensi</h1>
        </div>

        <?php if(session('success')): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            <p><?php echo e(session('success')); ?></p>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <p><?php echo e(session('error')); ?></p>
        </div>
        <?php endif; ?>
        
        <?php if($errors->any()): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
            <h4 class="font-bold">Validation Errors:</h4>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form id="absensi-form" action="<?php echo e(route('guru.absensi.update', $absensi->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm" required>
                        <?php $__empty_1 = true; $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($k->id); ?>" <?php echo e($absensi->kelas_id == $k->id ? 'selected' : ''); ?>>
                                <?php echo e($k->nama_kelas); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value="">Tidak ada kelas yang diampu</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm" required>
                        <?php $__empty_1 = true; $__currentLoopData = $mapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($m->id); ?>" <?php echo e($absensi->mapel_id == $m->id ? 'selected' : ''); ?>>
                                <?php echo e($m->nama); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <option value="">Tidak ada mata pelajaran yang diampu</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="sm:col-span-2 lg:col-span-1">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 text-sm"
                        value="<?php echo e(old('tanggal', $absensi->tanggal->format('Y-m-d'))); ?>" required readonly>
                    <p class="text-xs text-gray-500 mt-1">Tanggal tidak dapat diubah saat edit</p>
                </div>
            </div>

            <!-- Info Alert -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Mode Edit:</strong> Anda sedang mengedit absensi untuk tanggal <?php echo e(\Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y')); ?>, 
                            kelas <?php echo e($absensi->kelas->nama_kelas ?? 'N/A'); ?>, mata pelajaran <?php echo e($absensi->mapel->nama ?? 'N/A'); ?>.
                            Silakan ubah status kehadiran dan keterangan siswa sesuai kebutuhan.
                        </p>
                    </div>
                </div>
            </div>

            <div id="loading" class="text-center py-6 hidden">
                <div class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memuat data siswa...</span>
                </div>
            </div>

            <div id="siswa-container" class="mt-8">
                <h2 class="text-lg font-semibold mb-4">Data Kehadiran Siswa</h2>

                <?php if(isset($allAbsensi)): ?>
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Ditemukan <?php echo e($allAbsensi->count()); ?> siswa untuk sesi ini
                        </p>
                    </div>
                <?php else: ?>
                    <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-700">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Data absensi tidak ditemukan
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Desktop Table View -->
                <div id="desktop-view" class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="siswa-list-desktop" class="bg-white divide-y divide-gray-200">
                            <?php if(isset($allAbsensi) && $allAbsensi->count() > 0): ?>
                                <?php $__currentLoopData = $allAbsensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $absensiItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo e($index + 1); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($absensiItem->siswa->nis ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo e($absensiItem->siswa->nama_lengkap ?? $absensiItem->siswa->nama ?? 'Nama Tidak Ditemukan'); ?></div>
                                        <input type="hidden" name="siswa_id[]" value="<?php echo e($absensiItem->siswa_id); ?>">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-3">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status[<?php echo e($index); ?>]" value="hadir" <?php echo e($absensiItem->status == 'hadir' ? 'checked' : ''); ?> class="form-radio text-green-600 mr-1">
                                                <span class="text-sm">H</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status[<?php echo e($index); ?>]" value="izin" <?php echo e($absensiItem->status == 'izin' ? 'checked' : ''); ?> class="form-radio text-blue-600 mr-1">
                                                <span class="text-sm">I</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status[<?php echo e($index); ?>]" value="sakit" <?php echo e($absensiItem->status == 'sakit' ? 'checked' : ''); ?> class="form-radio text-yellow-600 mr-1">
                                                <span class="text-sm">S</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status[<?php echo e($index); ?>]" value="alpha" <?php echo e($absensiItem->status == 'alpha' ? 'checked' : ''); ?> class="form-radio text-red-600 mr-1">
                                                <span class="text-sm">A</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="text" name="keterangan[]" value="<?php echo e($absensiItem->keterangan); ?>" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Keterangan">
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data absensi</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div id="mobile-view" class="hidden">
                    <div class="space-y-4" id="siswa-list-mobile">
                        <?php if(isset($allAbsensi) && $allAbsensi->count() > 0): ?>
                            <?php $__currentLoopData = $allAbsensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $absensiItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <!-- Header dengan nomor dan nama -->
                                <div class="flex items-start mb-3">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3 flex-shrink-0">
                                        <?php echo e($index + 1); ?>

                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 break-words">
                                            <?php echo e($absensiItem->siswa->nama_lengkap ?? $absensiItem->siswa->nama ?? 'Nama Tidak Ditemukan'); ?>

                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-id-badge mr-1"></i>
                                            NIS: <?php echo e($absensiItem->siswa->nis ?? 'N/A'); ?>

                                        </p>
                                        <input type="hidden" name="siswa_id[]" value="<?php echo e($absensiItem->siswa_id); ?>">
                                    </div>
                                </div>

                                <!-- Status Radio Buttons -->
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Status:</label>
                                    <div class="flex gap-2">
                                        <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($absensiItem->status == 'hadir' ? 'bg-green-50 border-green-200' : ''); ?> flex-1">
                                            <input type="radio" name="status[<?php echo e($index); ?>]" value="hadir" <?php echo e($absensiItem->status == 'hadir' ? 'checked' : ''); ?> class="mr-1">
                                            <span class="text-sm font-medium">H</span>
                                        </label>
                                        <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($absensiItem->status == 'izin' ? 'bg-blue-50 border-blue-200' : ''); ?> flex-1">
                                            <input type="radio" name="status[<?php echo e($index); ?>]" value="izin" <?php echo e($absensiItem->status == 'izin' ? 'checked' : ''); ?> class="mr-1">
                                            <span class="text-sm font-medium">I</span>
                                        </label>
                                        <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($absensiItem->status == 'sakit' ? 'bg-yellow-50 border-yellow-200' : ''); ?> flex-1">
                                            <input type="radio" name="status[<?php echo e($index); ?>]" value="sakit" <?php echo e($absensiItem->status == 'sakit' ? 'checked' : ''); ?> class="mr-1">
                                            <span class="text-sm font-medium">S</span>
                                        </label>
                                        <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 <?php echo e($absensiItem->status == 'alpha' ? 'bg-red-50 border-red-200' : ''); ?> flex-1">
                                            <input type="radio" name="status[<?php echo e($index); ?>]" value="alpha" <?php echo e($absensiItem->status == 'alpha' ? 'checked' : ''); ?> class="mr-1">
                                            <span class="text-sm font-medium">A</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Keterangan -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Keterangan:</label>
                                    <input type="text" name="keterangan[]" value="<?php echo e($absensiItem->keterangan); ?>" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Tambahkan keterangan...">
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tombol Submit -->
            <div class="mt-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <a href="<?php echo e(route('guru.absensi.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg transition-colors text-center sm:text-left order-2 sm:order-1">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali
                </a>
                <div class="flex flex-col sm:flex-row gap-3 order-1 sm:order-2">
                    <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors text-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Responsive control for desktop/mobile views */
@media (min-width: 768px) {
    #desktop-view {
        display: block !important;
    }
    #mobile-view {
        display: none !important;
    }
}

@media (max-width: 767px) {
    #desktop-view {
        display: none !important;
    }
    #mobile-view {
        display: block !important;
    }
}

/* Ensure table is visible */
#desktop-view table {
    display: table !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const form = document.getElementById('absensi-form');
    const kelasSelect = document.getElementById('kelas_id');
    const mapelSelect = document.getElementById('mapel_id');
    const tanggalInput = document.getElementById('tanggal');
    const siswaContainer = document.getElementById('siswa-container');
    const siswaList = document.getElementById('siswa-list');
    const loadingElement = document.getElementById('loading');
    const submitBtn = document.getElementById('submit-btn');

    // Responsive view control
    function handleResponsiveView() {
        const desktopView = document.getElementById('desktop-view');
        const mobileView = document.getElementById('mobile-view');

        if (window.innerWidth >= 768) {
            if (desktopView) desktopView.style.display = 'block';
            if (mobileView) mobileView.style.display = 'none';
        } else {
            if (desktopView) desktopView.style.display = 'none';
            if (mobileView) mobileView.style.display = 'block';
        }
    }

    // Initial call and resize listener
    handleResponsiveView();
    window.addEventListener('resize', handleResponsiveView);

    // Initial setup - check if we need to reload students when class changes
    let originalKelasId = kelasSelect.value;
    let originalMapelId = mapelSelect.value;
    
    // Make kelas and mapel readonly in edit mode instead of disabled
    // This ensures values are still submitted with the form
    kelasSelect.setAttribute('readonly', true);
    mapelSelect.setAttribute('readonly', true);
    
    // Prevent changing values
    kelasSelect.addEventListener('mousedown', function(e) { e.preventDefault(); });
    kelasSelect.addEventListener('keydown', function(e) { e.preventDefault(); });
    mapelSelect.addEventListener('mousedown', function(e) { e.preventDefault(); });
    mapelSelect.addEventListener('keydown', function(e) { e.preventDefault(); });
    
    // Add hidden inputs as backup to ensure values are still submitted
    const kelasHidden = document.createElement('input');
    kelasHidden.type = 'hidden';
    kelasHidden.name = 'kelas_id';
    kelasHidden.value = kelasSelect.value;
    form.appendChild(kelasHidden);
    
    const mapelHidden = document.createElement('input');
    mapelHidden.type = 'hidden';
    mapelHidden.name = 'mapel_id';
    mapelHidden.value = mapelSelect.value;
    form.appendChild(mapelHidden);
    
    // Add visual indication that fields are disabled
    kelasSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
    mapelSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
    
    // Add notes
    const kelasNote = document.createElement('p');
    kelasNote.className = 'text-xs text-gray-500 mt-1';
    kelasNote.textContent = 'Kelas tidak dapat diubah saat edit';
    kelasSelect.parentNode.appendChild(kelasNote);
    
    const mapelNote = document.createElement('p');
    mapelNote.className = 'text-xs text-gray-500 mt-1';
    mapelNote.textContent = 'Mata pelajaran tidak dapat diubah saat edit';
    mapelSelect.parentNode.appendChild(mapelNote);
    
    // Load siswa when kelas changes (disabled in edit mode, kept for reference)
    if (kelasSelect && false) { // Disabled for edit mode
        kelasSelect.addEventListener('change', function() {
            const kelasId = this.value;
            
            if (kelasId && kelasId !== originalKelasId) {
                loadingElement.classList.remove('hidden');
                siswaContainer.classList.add('hidden');
                
                fetch('/api/kelas/' + kelasId + '/siswa')
                    .then(response => {
                        return response.json();
                    })
                    .then(data => {
                        loadingElement.classList.add('hidden');
                        renderSiswaList(data);
                        siswaContainer.classList.remove('hidden');
                    })
                    .catch(error => {
                        console.error('Error loading siswa:', error);
                        loadingElement.classList.add('hidden');
                        alert('Error loading siswa: ' + error.message);
                    });
            } else if (!kelasId) {
                siswaContainer.classList.add('hidden');
            }
        });
    }
    
    // Render siswa list (for when class changes)
    function renderSiswaList(siswaData) {
        const siswaListDesktop = document.getElementById('siswa-list-desktop');
        const siswaListMobile = document.getElementById('siswa-list-mobile');

        // Clear both desktop and mobile lists
        if (siswaListDesktop) siswaListDesktop.innerHTML = '';
        if (siswaListMobile) siswaListMobile.innerHTML = '';

        if (siswaData.length === 0) {
            if (siswaListDesktop) {
                siswaListDesktop.innerHTML = '<tr><td colspan="5" class="text-center p-4">Tidak ada siswa</td></tr>';
            }
            if (siswaListMobile) {
                siswaListMobile.innerHTML = '<div class="text-center p-4 text-gray-500">Tidak ada siswa</div>';
            }
            return;
        }

        siswaData.forEach((siswa, index) => {
            // Desktop table row
            if (siswaListDesktop) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${index + 1}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${siswa.nis}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${siswa.nama}</div>
                        <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex space-x-3">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status[${index}]" value="hadir" checked class="form-radio text-green-600 mr-1">
                                <span class="text-sm">H</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status[${index}]" value="izin" class="form-radio text-blue-600 mr-1">
                                <span class="text-sm">I</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status[${index}]" value="sakit" class="form-radio text-yellow-600 mr-1">
                                <span class="text-sm">S</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status[${index}]" value="alpha" class="form-radio text-red-600 mr-1">
                                <span class="text-sm">A</span>
                            </label>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="text" name="keterangan[]" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Keterangan">
                    </td>
                `;
                siswaListDesktop.appendChild(row);
            }

            // Mobile card
            if (siswaListMobile) {
                const card = document.createElement('div');
                card.className = 'bg-white border border-gray-200 rounded-lg p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex items-start mb-3">
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full mr-3 flex-shrink-0">
                            ${index + 1}
                        </span>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-gray-900 break-words">
                                ${siswa.nama}
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-id-badge mr-1"></i>
                                NIS: ${siswa.nis}
                            </p>
                            <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Status:</label>
                        <div class="flex gap-2">
                            <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 bg-green-50 border-green-200 flex-1">
                                <input type="radio" name="status[${index}]" value="hadir" checked class="mr-1">
                                <span class="text-sm font-medium">H</span>
                            </label>
                            <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="status[${index}]" value="izin" class="mr-1">
                                <span class="text-sm font-medium">I</span>
                            </label>
                            <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="status[${index}]" value="sakit" class="mr-1">
                                <span class="text-sm font-medium">S</span>
                            </label>
                            <label class="flex items-center justify-center p-2 border rounded-lg cursor-pointer hover:bg-gray-50 flex-1">
                                <input type="radio" name="status[${index}]" value="alpha" class="mr-1">
                                <span class="text-sm font-medium">A</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Keterangan:</label>
                        <input type="text" name="keterangan[]" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" placeholder="Tambahkan keterangan...">
                    </div>
                `;
                siswaListMobile.appendChild(card);
            }
        });

        console.log('Finished rendering, total rows:', siswaData.length);
    }
    
    // Form submit handler
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit triggered');
            
            const siswaIds = document.querySelectorAll('input[name="siswa_id[]"]');
            console.log('Siswa IDs found:', siswaIds.length);
            
            if (siswaIds.length === 0) {
                e.preventDefault();
                alert('Data siswa tidak ditemukan. Pastikan Anda telah memilih kelas dan data siswa telah dimuat.');
                return false;
            }
            
            console.log('Form validation passed');
        });
    }
    
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\edit.blade.php ENDPATH**/ ?>