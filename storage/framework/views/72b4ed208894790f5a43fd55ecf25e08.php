

<?php $__env->startSection('title', 'Buat Absensi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Mobile-first responsive design */
    .container-responsive {
        padding: 1rem;
    }

    @media (min-width: 768px) {
        .container-responsive {
            padding: 1.5rem;
        }
    }

    /* Mobile form styling */
    @media (max-width: 767px) {
        .mobile-form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .mobile-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .mobile-header h1 {
            font-size: 1.5rem;
            line-height: 1.3;
        }

        .mobile-back-btn {
            align-self: flex-end;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            background-color: #f3f4f6;
            border-radius: 0.5rem;
            text-decoration: none;
        }

        .mobile-form-field {
            margin-bottom: 1rem;
        }

        .mobile-form-field label {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }

        .mobile-form-field select,
        .mobile-form-field input {
            font-size: 1rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            width: 100%;
        }

        .mobile-form-field select:focus,
        .mobile-form-field input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    }

    /* Checkbox-style status options */
    .status-option {
        position: relative;
        transition: all 0.2s ease;
    }

    .status-option input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
    }

    .status-checkbox {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 0.375rem 0.25rem;
        border: 1px solid #d1d5db;
        border-radius: 0.375rem;
        background-color: #ffffff;
        transition: all 0.2s ease;
        cursor: pointer;
        min-height: 3rem;
        position: relative;
        width: 100%;
        box-sizing: border-box;
        text-align: center;
    }

    .status-option:hover .status-checkbox {
        border-color: #d1d5db;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* Checkbox indicator */
    .checkbox-indicator {
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #d1d5db;
        border-radius: 0.25rem;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.25rem;
        transition: all 0.2s ease;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .status-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #374151;
        text-align: center;
        line-height: 1.2;
    }

    /* Status-specific styling when checked */
    .status-hadir input[type="radio"]:checked + .status-checkbox {
        background-color: #f0fdf4;
        border-color: #16a34a;
    }

    .status-hadir input[type="radio"]:checked + .status-checkbox .checkbox-indicator {
        background-color: #16a34a;
        border-color: #16a34a;
        color: #ffffff;
    }

    .status-izin input[type="radio"]:checked + .status-checkbox {
        background-color: #fffbeb;
        border-color: #d97706;
    }

    .status-izin input[type="radio"]:checked + .status-checkbox .checkbox-indicator {
        background-color: #d97706;
        border-color: #d97706;
        color: #ffffff;
    }

    .status-sakit input[type="radio"]:checked + .status-checkbox {
        background-color: #eff6ff;
        border-color: #2563eb;
    }

    .status-sakit input[type="radio"]:checked + .status-checkbox .checkbox-indicator {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
    }

    .status-alpha input[type="radio"]:checked + .status-checkbox {
        background-color: #fef2f2;
        border-color: #dc2626;
    }

    .status-alpha input[type="radio"]:checked + .status-checkbox .checkbox-indicator {
        background-color: #dc2626;
        border-color: #dc2626;
        color: #ffffff;
    }

    /* Desktop table hide on mobile */
    @media (max-width: 767px) {
        .desktop-table {
            display: none;
        }

        .mobile-list {
            display: block;
        }
    }

    @media (min-width: 768px) {
        .desktop-table {
            display: block;
        }

        .mobile-list {
            display: none;
        }
    }

    /* Mobile student card styling */
    .mobile-student-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .mobile-student-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .student-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .student-details h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 0.25rem;
    }

    .student-details p {
        font-size: 0.875rem;
        color: #6b7280;
    }

    .student-number {
        background-color: #f3f4f6;
        color: #374151;
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.25rem;
        margin-bottom: 1rem;
        padding: 0.5rem;
        background-color: #f9fafb;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    @media (min-width: 640px) {
        .status-grid {
            gap: 0.5rem;
        }
    }

    .keterangan-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .keterangan-input:focus {
        outline: none;
        border-color: #3b82f6;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Mobile action buttons */
    @media (max-width: 767px) {
        .mobile-submit-container .flex {
            flex-direction: column;
            gap: 0.75rem;
        }

        .mobile-submit-container button,
        .mobile-submit-container a {
            width: 100%;
            justify-content: center;
            padding: 1rem;
            font-size: 1rem;
            border-radius: 0.75rem;
        }
    }

    /* Loading animation */
    .loading-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Smooth transitions */
    * {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Touch-friendly improvements */
    @media (max-width: 767px) {
        /* Increase touch targets */
        button, select, input[type="date"], .mobile-radio-group {
            min-height: 44px;
        }

        /* Improve form spacing */
        .form-section {
            margin-bottom: 1.5rem;
        }

        /* Better visual hierarchy */
        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }

        /* Loading state improvements */
        .loading-container {
            padding: 2rem 1rem;
            text-align: center;
        }

        .loading-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.5rem;
        }

        /* Empty state styling */
        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            border-radius: 0.75rem;
            border: 2px dashed #d1d5db;
        }

        .empty-state-icon {
            font-size: 2rem;
            color: #9ca3af;
            margin-bottom: 0.5rem;
        }

        .empty-state-text {
            color: #6b7280;
            font-size: 0.875rem;
        }

        /* Success/Error message improvements */
        .alert {
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Submit button container */
        .mobile-submit-container {
            padding: 1.5rem 0 1rem 0;
            margin-top: 2rem;
            background-color: transparent;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main-content'); ?>
    <div class="bg-white rounded-lg shadow-sm p-4 md:p-6">
        <div class="flex justify-between items-center mb-6 md:mb-8 mobile-header">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">Tambah Data Absensi</h1>
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

        <form id="absensi-form" action="<?php echo e(route('guru.absensi.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="mobile-form-grid md:grid md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
                <div class="mobile-form-field">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="w-full text-base md:text-lg py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Pilih Kelas</option>
                        <?php if(isset($kelas) && $kelas->count() > 0): ?>
                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($k->id); ?>" <?php echo e((isset($selectedKelas) && $selectedKelas == $k->id) ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada kelas yang diampu</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mobile-form-field">
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">Mata Pelajaran</label>
                    <select name="mapel_id" id="mapel_id" class="w-full text-base md:text-lg py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        <?php if(isset($mapel) && $mapel->count() > 0): ?>
                            <?php $__currentLoopData = $mapel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>" <?php echo e((isset($selectedMapel) && $selectedMapel == $m->id) ? 'selected' : ''); ?>><?php echo e($m->nama); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada mata pelajaran yang diampu</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="mobile-form-field">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="w-full text-base md:text-lg py-3 px-4 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
                </div>
            </div>

            <div id="loading" class="loading-container hidden">
                <div class="inline-flex flex-col items-center">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-medium text-gray-700">Memuat data siswa...</span>
                    <span class="loading-text">Mohon tunggu sebentar</span>
                </div>
            </div>

            <div id="siswa-container" class="mt-6 md:mt-8 hidden form-section">
                <h2 class="section-title">Data Kehadiran Siswa</h2>

                <!-- Desktop Table -->
                <div class="desktop-table overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="siswa-list-desktop" class="bg-white divide-y divide-gray-200">
                            <!-- Data siswa akan di-render oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List -->
                <div class="mobile-list">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-3 mb-4 border border-blue-200">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-blue-800">Daftar Siswa</span>
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Pilih Status</span>
                        </div>
                    </div>
                    <div id="siswa-cards-mobile" class="space-y-3">
                        <!-- List siswa mobile akan di-render oleh JavaScript -->
                    </div>
                </div>
            </div>
            
            <div class="mt-8 mobile-submit-container">
                <div class="flex flex-col md:flex-row gap-3 md:gap-0 md:justify-between">
                    <a href="<?php echo e(route('guru.absensi.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 md:px-8 py-3 text-base md:text-lg rounded-lg transition-colors flex items-center justify-center order-2 md:order-1">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 md:px-8 py-3 text-base md:text-lg rounded-lg transition-colors flex items-center justify-center order-1 md:order-2">
                        <i class="fas fa-save mr-2"></i>Simpan Data Absensi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get form elements
    const form = document.getElementById('absensi-form');
    const kelasSelect = document.getElementById('kelas_id');
    const mapelSelect = document.getElementById('mapel_id');
    const tanggalInput = document.getElementById('tanggal');
    const siswaContainer = document.getElementById('siswa-container');
    const siswaListDesktop = document.getElementById('siswa-list-desktop');
    const siswaCardsMobile = document.getElementById('siswa-cards-mobile');
    const loadingElement = document.getElementById('loading');
    const submitBtn = document.getElementById('submit-btn');
    
    // Debug: Check if all elements are found
    console.log('Elements check:');
    console.log('form:', form);
    console.log('kelasSelect:', kelasSelect);
    console.log('siswaContainer:', siswaContainer);
    console.log('siswaListDesktop:', siswaListDesktop);
    console.log('siswaCardsMobile:', siswaCardsMobile);
    console.log('loadingElement:', loadingElement);
    
    // Load siswa when kelas changes
    if (kelasSelect) {
        kelasSelect.addEventListener('change', function() {
            const kelasId = this.value;
            console.log('Kelas changed to:', kelasId);
            
            if (kelasId) {
                loadingElement.classList.remove('hidden');
                siswaContainer.classList.add('hidden');
                
                fetch('/api/kelas/' + kelasId + '/siswa')
                    .then(response => {
                        console.log('API Response:', response);
                        return response.json();
                    })
                    .then(data => {
                        console.log('API Data received:', data);
                        loadingElement.classList.add('hidden');
                        renderSiswaList(data);
                        siswaContainer.classList.remove('hidden');
                        console.log('Siswa container shown');
                    })
                    .catch(error => {
                        console.error('Error loading siswa:', error);
                        loadingElement.classList.add('hidden');
                        alert('Error loading siswa: ' + error.message);
                    });
            } else {
                siswaContainer.classList.add('hidden');
            }
        });
    }
    
    // Render siswa list
    function renderSiswaList(siswaData) {
        console.log('Rendering siswa data:', siswaData);
        console.log('Desktop element:', siswaListDesktop);
        console.log('Mobile element:', siswaCardsMobile);
        
        siswaListDesktop.innerHTML = '';
        siswaCardsMobile.innerHTML = '';
        
        if (siswaData.length === 0) {
            siswaListDesktop.innerHTML = '<tr><td colspan="5" class="text-center p-8"><div class="empty-state"><div class="empty-state-icon">👥</div><div class="empty-state-text">Tidak ada siswa di kelas ini</div></div></td></tr>';
            siswaCardsMobile.innerHTML = '<div class="empty-state"><div class="empty-state-icon">👥</div><div class="empty-state-text">Tidak ada siswa di kelas ini</div></div>';
            return;
        }
        
        siswaData.forEach((siswa, index) => {
            // Desktop Table Row
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4">${index + 1}</td>
                <td class="px-6 py-4">${siswa.nis}</td>
                <td class="px-6 py-4">
                    ${siswa.nama}
                    <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                </td>
                <td class="px-6 py-4">
                    <div class="space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="status[${index}]" value="hadir" checked class="mr-1"> Hadir
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status[${index}]" value="izin" class="mr-1"> Izin
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status[${index}]" value="sakit" class="mr-1"> Sakit
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status[${index}]" value="alpha" class="mr-1"> Alpha
                        </label>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <input type="text" name="keterangan[]" class="w-full border rounded px-2 py-1" placeholder="Keterangan">
                </td>
            `;
            siswaListDesktop.appendChild(row);
            console.log('Added desktop row for:', siswa.nama);
            
            // Mobile Student Card
            const mobileItem = document.createElement('div');
            mobileItem.className = 'mobile-student-card';
            mobileItem.innerHTML = `
                <div class="student-info">
                    <div class="student-details">
                        <h4>${siswa.nama}</h4>
                        <p>NIS: ${siswa.nis}</p>
                        <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                    </div>
                    <div class="student-number">#${index + 1}</div>
                </div>

                <div class="status-grid">
                    <label class="status-option status-hadir">
                        <input type="radio" name="status[${index}]" value="hadir" checked>
                        <div class="status-checkbox">
                            <div class="checkbox-indicator">H</div>
                            <div class="status-label">Hadir</div>
                        </div>
                    </label>
                    <label class="status-option status-izin">
                        <input type="radio" name="status[${index}]" value="izin">
                        <div class="status-checkbox">
                            <div class="checkbox-indicator">I</div>
                            <div class="status-label">Izin</div>
                        </div>
                    </label>
                    <label class="status-option status-sakit">
                        <input type="radio" name="status[${index}]" value="sakit">
                        <div class="status-checkbox">
                            <div class="checkbox-indicator">S</div>
                            <div class="status-label">Sakit</div>
                        </div>
                    </label>
                    <label class="status-option status-alpha">
                        <input type="radio" name="status[${index}]" value="alpha">
                        <div class="status-checkbox">
                            <div class="checkbox-indicator">A</div>
                            <div class="status-label">Alpha</div>
                        </div>
                    </label>
                </div>

                <input type="text" name="keterangan[]" class="keterangan-input" placeholder="Keterangan (opsional)">
            `;
            siswaCardsMobile.appendChild(mobileItem);
        });
        
        console.log('Finished rendering, total rows:', siswaListDesktop.children.length, 'total cards:', siswaCardsMobile.children.length);
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
    
    // Auto-load siswa if kelas is pre-selected
    if (kelasSelect && kelasSelect.value) {
        console.log('Kelas pre-selected, loading siswa automatically...');
        kelasSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\absensi\create.blade.php ENDPATH**/ ?>