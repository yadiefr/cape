

<?php $__env->startSection('title', 'Jadwal Ujian - Mode Tabel'); ?>

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
                    <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" class="text-gray-600 hover:text-gray-900">Jadwal Ujian</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 w-3 h-3 mx-1"></i>
                    <span class="text-gray-500">Mode Tabel</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jadwal Ujian - Mode Tabel</h1>
            <p class="text-gray-600 mt-1">Kelola multiple jadwal ujian dengan mudah menggunakan mode tabel</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo e(route('admin.ujian.jadwal.index')); ?>" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-arrow-left w-4 h-4 mr-2"></i>
                Kembali
            </a>
            <button onclick="toggleView()" id="toggleViewBtn"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center">
                <i class="fas fa-list w-4 h-4 mr-2"></i>
                Mode Form
            </button>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select id="filter_kelas" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Kelas</option>
                    <?php if(isset($kelas)): ?>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelasItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kelasItem->id); ?>"><?php echo e($kelasItem->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="filter_periode" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Pilih Periode</option>
                    <option value="uts">UTS Ganjil 2024/2025</option>
                    <option value="uas">UAS Ganjil 2024/2025</option>
                    <option value="uts_genap">UTS Genap 2024/2025</option>
                    <option value="uas_genap">UAS Genap 2024/2025</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" id="start_date" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(date('Y-m-d', strtotime('+1 week'))); ?>">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                <input type="time" id="start_time" value="08:00"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="flex items-end">
                <button type="button" onclick="generateScheduleTable()" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                    <i class="fas fa-magic w-4 h-4 mr-2"></i>
                    Generate
                </button>
            </div>
        </div>
    </div>

    <!-- Table Mode -->
    <div id="tableMode" class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">Tabel Jadwal Ujian</h3>            <div class="flex space-x-2">
                <button type="button" onclick="addAllSubjects()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors duration-200 font-medium">
                    <i class="fas fa-plus w-4 h-4 mr-1"></i>
                    Tambah Semua Mapel
                </button>
                <button type="button" onclick="clearTable()" 
                        class="inline-flex items-center px-3 py-1.5 text-sm bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-200 font-medium">
                    <i class="fas fa-trash w-4 h-4 mr-1"></i>
                    Kosongkan
                </button>
            </div>
            </div>
        </div>
        
        <form id="bulkScheduleForm" action="<?php echo e(route('admin.ujian.jadwal.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="bulk_mode" value="1">
            
            <div class="overflow-x-auto">
                <table class="w-full" id="scheduleTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Mulai</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Dynamic content will be inserted here -->
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <span id="selectedCount">0</span> jadwal dipilih
                    </div>
                    <div class="flex space-x-3">
                        <button type="button" onclick="validateSchedules()" 
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-check-circle w-4 h-4 mr-2"></i>
                            Validasi
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200">
                            <i class="fas fa-save w-4 h-4 mr-2"></i>
                            Simpan Semua
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Templates -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Template Cepat</h4>
            <div class="space-y-3">
                <button type="button" onclick="applyTemplate('uts')" 
                        class="w-full text-left px-4 py-3 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition-colors duration-200">
                    <div class="font-medium text-blue-800">UTS Template</div>
                    <div class="text-sm text-blue-600">Semua mapel, 90 menit, interval 1 hari</div>
                </button>
                <button type="button" onclick="applyTemplate('uas')" 
                        class="w-full text-left px-4 py-3 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition-colors duration-200">
                    <div class="font-medium text-green-800">UAS Template</div>
                    <div class="text-sm text-green-600">Semua mapel, 120 menit, interval 1 hari</div>
                </button>
                <button type="button" onclick="applyTemplate('quiz')" 
                        class="w-full text-left px-4 py-3 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition-colors duration-200">
                    <div class="font-medium text-purple-800">Quiz Harian</div>
                    <div class="text-sm text-purple-600">Pilihan mapel, 30 menit</div>
                </button>
            </div>
        </div>

        <!-- Statistics -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Statistik</h4>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total Jadwal:</span>
                    <span id="totalSchedules" class="font-semibold">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Aktif:</span>
                    <span id="activeSchedules" class="font-semibold text-green-600">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Draft:</span>
                    <span id="draftSchedules" class="font-semibold text-yellow-600">0</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Konflik:</span>
                    <span id="conflictSchedules" class="font-semibold text-red-600">0</span>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6 border border-blue-200">
            <div class="flex items-center mb-3">
                <div class="p-2 bg-blue-600 rounded-lg">
                    <i class="fas fa-lightbulb text-white w-4 h-4"></i>
                </div>
                <h4 class="text-sm font-semibold text-gray-800 ml-3">Tips Mode Tabel</h4>
            </div>
            <ul class="text-xs text-gray-600 space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                    Pilih kelas dan periode untuk generate otomatis
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                    Edit langsung di tabel untuk efisiensi
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                    Gunakan template untuk setup cepat
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-green-500 w-3 h-3 mt-0.5 mr-2"></i>
                    Validasi sebelum simpan untuk cek konflik
                </li>
            </ul>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
let rowCounter = 0;
const mataPelajaranData = <?php echo json_encode($mataPelajaran ?? [], 15, 512) ?>;
const kelasData = <?php echo json_encode($kelas ?? [], 15, 512) ?>;

// Generate schedule table based on filters
function generateScheduleTable() {
    const kelasId = document.getElementById('filter_kelas').value;
    const periode = document.getElementById('filter_periode').value;
    const startDate = document.getElementById('start_date').value;
    const startTime = document.getElementById('start_time').value;
    
    if (!kelasId) {
        alert('Pilih kelas terlebih dahulu');
        return;
    }
    
    if (!startDate) {
        alert('Pilih tanggal mulai terlebih dahulu');
        return;
    }
    
    // Clear existing table first
    clearTableSilent();
    
    // Get selected class name
    const selectedKelas = kelasData.find(k => k.id == kelasId);
    if (!selectedKelas) return;
    
    // Generate based on periode
    if (periode) {
        generateFromTemplate(periode, kelasId, selectedKelas.nama_kelas, startDate, startTime);
    } else {
        // Generate for all subjects
        mataPelajaranData.forEach((mapel, index) => {
            let currentDate = new Date(startDate);
            currentDate.setDate(currentDate.getDate() + index);
            
            // Skip weekends
            while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
                currentDate.setDate(currentDate.getDate() + 1);
            }
            
            addScheduleRow({
                mata_pelajaran_id: mapel.id,
                mata_pelajaran_nama: mapel.nama,
                kelas_id: kelasId,
                kelas_nama: selectedKelas.nama_kelas,
                nama_ujian: `Ujian ${mapel.nama}`,
                tanggal: formatDate(currentDate),
                waktu_mulai: startTime,
                durasi: 90,
                jenis_ujian: 'uts',
                status: 'scheduled'
            });
        });
    }
    
    updateStatistics();
}

// Generate from template
function generateFromTemplate(template, kelasId, kelasNama, startDate, startTime) {
    const templates = {
        'uts': { durasi: 90, jenis: 'uts', prefix: 'UTS' },
        'uas': { durasi: 120, jenis: 'uas', prefix: 'UAS' },
        'uts_genap': { durasi: 90, jenis: 'uts', prefix: 'UTS' },
        'uas_genap': { durasi: 120, jenis: 'uas', prefix: 'UAS' }
    };
    
    const templateData = templates[template];
    if (!templateData) return;
    
    mataPelajaranData.forEach((mapel, index) => {
        let currentDate = new Date(startDate);
        currentDate.setDate(currentDate.getDate() + index);
        
        // Skip weekends
        while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        addScheduleRow({
            mata_pelajaran_id: mapel.id,
            mata_pelajaran_nama: mapel.nama,
            kelas_id: kelasId,
            kelas_nama: kelasNama,
            nama_ujian: `${templateData.prefix} ${mapel.nama}`,
            tanggal: formatDate(currentDate),
            waktu_mulai: startTime,
            durasi: templateData.durasi,
            jenis_ujian: templateData.jenis,
            status: 'scheduled'
        });
    });
}

// Add single schedule row
function addScheduleRow(data = {}) {
    const tableBody = document.getElementById('scheduleTableBody');
    const row = document.createElement('tr');
    row.className = 'hover:bg-gray-50';
    row.setAttribute('data-row-id', rowCounter);
    
    const defaultData = {
        mata_pelajaran_id: '',
        mata_pelajaran_nama: '',
        kelas_id: '',
        kelas_nama: '',
        nama_ujian: '',
        tanggal: document.getElementById('start_date').value || '',
        waktu_mulai: document.getElementById('start_time').value || '08:00',
        durasi: 90,
        jenis_ujian: 'uts',
        status: 'scheduled'
    };
    
    data = { ...defaultData, ...data };
    
    row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="checkbox" name="schedules[${rowCounter}][selected]" value="1" checked
                   class="schedule-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="hidden" name="schedules[${rowCounter}][nama_ujian]" value="${data.nama_ujian}">
            <select name="schedules[${rowCounter}][mata_pelajaran_id]" required
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Pilih Mata Pelajaran</option>
                ${mataPelajaranData.map(mapel => 
                    `<option value="${mapel.id}" ${data.mata_pelajaran_id == mapel.id ? 'selected' : ''}>${mapel.nama}</option>`
                ).join('')}
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="schedules[${rowCounter}][kelas_id]" required
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="">Pilih Kelas</option>
                ${kelasData.map(kelas => 
                    `<option value="${kelas.id}" ${data.kelas_id == kelas.id ? 'selected' : ''}>${kelas.nama_kelas}</option>`
                ).join('')}
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="date" name="schedules[${rowCounter}][tanggal]" required
                   value="${data.tanggal}"
                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                   min="${new Date().toISOString().split('T')[0]}">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="time" name="schedules[${rowCounter}][waktu_mulai]" required
                   value="${data.waktu_mulai}"
                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="schedules[${rowCounter}][durasi]" required
                   value="${data.durasi}" min="15" max="480"
                   class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
            <div class="text-xs text-gray-500">menit</div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="schedules[${rowCounter}][jenis_ujian]" required
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="uts" ${data.jenis_ujian === 'uts' ? 'selected' : ''}>UTS</option>
                <option value="uas" ${data.jenis_ujian === 'uas' ? 'selected' : ''}>UAS</option>
                <option value="quiz" ${data.jenis_ujian === 'quiz' ? 'selected' : ''}>Quiz</option>
                <option value="praktek" ${data.jenis_ujian === 'praktek' ? 'selected' : ''}>Praktek</option>
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="schedules[${rowCounter}][status]" required
                    class="w-full border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option value="draft" ${data.status === 'draft' ? 'selected' : ''}>Draft</option>
                <option value="scheduled" ${data.status === 'scheduled' ? 'selected' : ''}>Terjadwal</option>
                <option value="active" ${data.status === 'active' ? 'selected' : ''}>Aktif</option>
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <button type="button" onclick="removeScheduleRow(${rowCounter})" 
                    class="text-red-600 hover:text-red-700 p-1 rounded">
                <i class="fas fa-trash w-4 h-4"></i>
            </button>
        </td>
    `;
    
    tableBody.appendChild(row);
    
    // Add event listeners
    const checkbox = row.querySelector('.schedule-checkbox');
    if (checkbox) {
        checkbox.addEventListener('change', updateStatistics);
    }
    
    rowCounter++;
    updateStatistics();
}

// Remove schedule row
function removeScheduleRow(rowId) {
    const row = document.querySelector(`tr[data-row-id="${rowId}"]`);
    if (row) {
        row.remove();
        updateStatistics();
    }
}

// Add all subjects
function addAllSubjects() {
    const kelasId = document.getElementById('filter_kelas').value;
    const startDate = document.getElementById('start_date').value || new Date().toISOString().split('T')[0];
    const startTime = document.getElementById('start_time').value || '08:00';
    
    if (!kelasId) {
        alert('Pilih kelas terlebih dahulu');
        return;
    }
    
    const selectedKelas = kelasData.find(k => k.id == kelasId);
    if (!selectedKelas) return;
    
    mataPelajaranData.forEach((mapel, index) => {
        let currentDate = new Date(startDate);
        currentDate.setDate(currentDate.getDate() + index);
        
        // Skip weekends
        while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        addScheduleRow({
            mata_pelajaran_id: mapel.id,
            mata_pelajaran_nama: mapel.nama,
            kelas_id: kelasId,
            kelas_nama: selectedKelas.nama_kelas,
            nama_ujian: `Ujian ${mapel.nama}`,
            tanggal: formatDate(currentDate),
            waktu_mulai: startTime,
            durasi: 90,
            jenis_ujian: 'uts',
            status: 'scheduled'
        });
    });
}

// Clear table with confirmation
function clearTable() {
    if (confirm('Yakin ingin menghapus semua jadwal dari tabel?')) {
        clearTableSilent();
    }
}

// Clear table silently
function clearTableSilent() {
    document.getElementById('scheduleTableBody').innerHTML = '';
    rowCounter = 0;
    updateStatistics();
}

// Apply template
function applyTemplate(templateType) {
    const kelasId = document.getElementById('filter_kelas').value;
    const startDate = document.getElementById('start_date').value || new Date().toISOString().split('T')[0];
    const startTime = document.getElementById('start_time').value || '08:00';
    
    if (!kelasId) {
        alert('Pilih kelas terlebih dahulu');
        return;
    }
    
    clearTableSilent();
    
    const templates = {
        'uts': { durasi: 90, jenis: 'uts', prefix: 'UTS' },
        'uas': { durasi: 120, jenis: 'uas', prefix: 'UAS' },
        'quiz': { durasi: 30, jenis: 'quiz', prefix: 'Quiz' }
    };
    
    const template = templates[templateType];
    if (!template) return;
    
    const selectedKelas = kelasData.find(k => k.id == kelasId);
    if (!selectedKelas) return;
    
    mataPelajaranData.forEach((mapel, index) => {
        let currentDate = new Date(startDate);
        currentDate.setDate(currentDate.getDate() + index);
        
        // Skip weekends
        while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        addScheduleRow({
            mata_pelajaran_id: mapel.id,
            mata_pelajaran_nama: mapel.nama,
            kelas_id: kelasId,
            kelas_nama: selectedKelas.nama_kelas,
            nama_ujian: `${template.prefix} ${mapel.nama}`,
            tanggal: formatDate(currentDate),
            waktu_mulai: startTime,
            durasi: template.durasi,
            jenis_ujian: template.jenis,
            status: 'scheduled'
        });
    });
}

// Toggle view
function toggleView() {
    window.location.href = '<?php echo e(route("admin.ujian.jadwal.create")); ?>';
}

// Validate schedules
function validateSchedules() {
    const rows = Array.from(document.querySelectorAll('#scheduleTableBody tr'));
    const schedules = [];
    const conflicts = [];
    
    // Collect all schedule data
    rows.forEach(row => {
        const checkbox = row.querySelector('.schedule-checkbox');
        if (!checkbox || !checkbox.checked) return;
        
        const tanggal = row.querySelector('input[name*="[tanggal]"]')?.value;
        const waktuMulai = row.querySelector('input[name*="[waktu_mulai]"]')?.value;
        const durasi = parseInt(row.querySelector('input[name*="[durasi]"]')?.value || 0);
        const kelasId = row.querySelector('select[name*="[kelas_id]"]')?.value;
        
        if (tanggal && waktuMulai && durasi && kelasId) {
            schedules.push({
                row,
                tanggal,
                waktu_mulai: waktuMulai,
                durasi,
                kelas_id: kelasId
            });
        }
    });
    
    // Check for conflicts
    for (let i = 0; i < schedules.length; i++) {
        for (let j = i + 1; j < schedules.length; j++) {
            const a = schedules[i];
            const b = schedules[j];
            
            // Only check conflicts for same class on same date
            if (a.kelas_id === b.kelas_id && a.tanggal === b.tanggal) {
                const aStart = new Date(`${a.tanggal} ${a.waktu_mulai}`);
                const aEnd = new Date(aStart.getTime() + a.durasi * 60000);
                const bStart = new Date(`${b.tanggal} ${b.waktu_mulai}`);
                const bEnd = new Date(bStart.getTime() + b.durasi * 60000);
                
                if ((aStart < bEnd && aEnd > bStart)) {
                    conflicts.push({ a, b });
                }
            }
        }
    }
    
    // Clear previous conflict highlighting
    document.querySelectorAll('.bg-red-100').forEach(el => {
        el.classList.remove('bg-red-100', 'border-red-300');
    });
    
    // Highlight conflicts
    conflicts.forEach(conflict => {
        if (conflict.a.row) {
            conflict.a.row.classList.add('bg-red-100', 'border-red-300');
        }
        if (conflict.b.row) {
            conflict.b.row.classList.add('bg-red-100', 'border-red-300');
        }
    });
    
    if (conflicts.length > 0) {
        alert(`Ditemukan ${conflicts.length} konflik jadwal! Baris yang konflik telah ditandai dengan warna merah.`);
    } else {
        alert('Tidak ada konflik jadwal ditemukan. Jadwal valid!');
    }
    
    updateStatistics();
}

// Update statistics
function updateStatistics() {
    const rows = document.querySelectorAll('#scheduleTableBody tr');
    
    let total = rows.length;
    let selected = document.querySelectorAll('.schedule-checkbox:checked').length;
    let active = 0, draft = 0, conflicts = 0;
    
    rows.forEach(row => {
        const status = row.querySelector('select[name*="[status]"]')?.value;
        if (status === 'scheduled' || status === 'active') active++;
        if (status === 'draft') draft++;
        if (row.classList.contains('bg-red-100')) conflicts++;
    });
    
    // Update UI
    document.getElementById('selectedCount').textContent = selected;
    document.getElementById('totalSchedules').textContent = total;
    document.getElementById('activeSchedules').textContent = active;
    document.getElementById('draftSchedules').textContent = draft;
    document.getElementById('conflictSchedules').textContent = conflicts;
}

// Format date helper
function formatDate(date) {
    return date.toISOString().split('T')[0];
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.schedule-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateStatistics();
        });
    }
    
    // Handle form submission
    const form = document.getElementById('bulkScheduleForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const selectedRows = document.querySelectorAll('.schedule-checkbox:checked').length;
            
            if (selectedRows === 0) {
                alert('Pilih minimal satu jadwal untuk disimpan');
                return;
            }
            
            if (document.querySelectorAll('.bg-red-100').length > 0) {
                if (!confirm('Masih ada konflik jadwal. Yakin ingin melanjutkan?')) {
                    return;
                }
            }
            
            // Show loading
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            // Submit form normally (Laravel will handle redirection)
            const formData = new FormData(form);
            
            // Use normal form submission instead of fetch
            form.removeEventListener('submit', arguments.callee);
            form.submit();
        });
    }
    
    // Initialize statistics
    updateStatistics();
});
</script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
button[type="button"], button[onclick] {
    cursor: pointer;
}

button:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

.schedule-checkbox {
    cursor: pointer;
}

.bg-red-100 {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.ujian', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\ujian\jadwal\create-table.blade.php ENDPATH**/ ?>