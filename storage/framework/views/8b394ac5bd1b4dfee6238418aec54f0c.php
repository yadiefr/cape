

<?php $__env->startSection('title', 'Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="w-full">
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-3">Absensi</h1>
                <div class="text-sm breadcrumbs">
                    <ul class="flex items-center space-x-2 text-gray-500">
                        <li><a href="<?php echo e(route('siswa.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                        <li class="flex items-center space-x-2">
                            <span class="text-gray-400">/</span>
                            <span>Kehadiran</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <!-- Statistik Kehadiran -->
        <div class="p-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold mb-4 text-gray-800">Ringkasan Kehadiran</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <div class="bg-green-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-2">
                            <i class="fas fa-check text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-600">Hadir</p>
                            <p class="text-xl font-bold text-green-800"><?php echo e($absensi->where('status', 'hadir')->count()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-2">
                            <i class="fas fa-clock text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-600">Izin</p>
                            <p class="text-xl font-bold text-blue-800"><?php echo e($absensi->where('status', 'izin')->count()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-yellow-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-2">
                            <i class="fas fa-procedures text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-600">Sakit</p>
                            <p class="text-xl font-bold text-yellow-800"><?php echo e($absensi->where('status', 'sakit')->count()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-red-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-2">
                            <i class="fas fa-times text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-600">Alpha</p>
                            <p class="text-xl font-bold text-red-800"><?php echo e($absensi->where('status', 'alpha')->count()); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-gray-500 rounded-lg p-2">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-600">Hari Ini</p>
                            <p class="text-xl font-bold text-gray-800"><?php echo e($statistik['total_hari_ini'] ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-indigo-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-2">
                            <i class="fas fa-calendar-week text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-indigo-600">Minggu Ini</p>
                            <p class="text-xl font-bold text-indigo-800"><?php echo e($statistik['total_minggu_ini'] ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="bg-purple-50 rounded-lg p-3">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-2">
                            <i class="fas fa-calendar-alt text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-purple-600">Bulan Ini</p>
                            <p class="text-xl font-bold text-purple-800"><?php echo e($statistik['total_bulan_ini'] ?? 0); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Kehadiran -->
        <div class="p-4">
            <!-- Filter dan Pencarian -->
            <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select class="form-select w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="hadir">Hadir</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Bulan</label>
                    <select class="form-select w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" id="filterBulan">
                        <option value="">Semua Bulan</option>
                        <?php for($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo e($i); ?>" <?php echo e($i == date('n') ? 'selected' : ''); ?>>
                                <?php echo e(\Carbon\Carbon::create()->month($i)->locale('id')->translatedFormat('F')); ?>

                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mata Pelajaran</label>
                    <input type="text" 
                           class="form-input w-full border-gray-300 rounded-lg focus:border-blue-500 focus:ring-blue-500" 
                           id="searchMapel" 
                           placeholder="Masukkan nama mata pelajaran...">
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="absensiTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($data->tanggal)->format('d F Y')); ?>

                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($data->mapel->nama ?? 'Mata Pelajaran Tidak Ditemukan'); ?>

                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($data->guru->nama ?? 'Guru Tidak Ditemukan'); ?>

                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?php if($data->status == 'hadir'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Hadir
                                    </span>
                                <?php elseif($data->status == 'izin'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Izin
                                    </span>
                                <?php elseif($data->status == 'sakit'): ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Sakit
                                    </span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Alpha
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                <a href="<?php echo e(route('siswa.absensi.show', $data->id)); ?>" 
                                   class="text-blue-600 hover:text-blue-900">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada data kehadiran
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($absensi->hasPages()): ?>
            <div class="px-4 py-3 border-t border-gray-200">
                <?php echo e($absensi->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterStatus = document.getElementById('filterStatus');
    const filterBulan = document.getElementById('filterBulan');
    const searchMapel = document.getElementById('searchMapel');
    const table = document.getElementById('absensiTable');
    const rows = table.querySelectorAll('tbody tr');

    function filterTable() {
        const statusValue = filterStatus.value.toLowerCase();
        const bulanValue = filterBulan.value;
        const searchValue = searchMapel.value.toLowerCase();

        rows.forEach(row => {
            if (row.children.length === 1) return; // Skip empty row

            const tanggalCell = row.children[0].textContent;
            const mapelCell = row.children[1].textContent.toLowerCase();
            const statusCell = row.children[3].querySelector('span').textContent.toLowerCase();

            // Extract month from date (assuming format: "dd month yyyy")
            const bulanFromDate = new Date(tanggalCell).getMonth() + 1;

            const statusMatch = !statusValue || statusCell.includes(statusValue);
            const bulanMatch = !bulanValue || bulanFromDate == bulanValue;
            const mapelMatch = !searchValue || mapelCell.includes(searchValue);

            if (statusMatch && bulanMatch && mapelMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });

        // Check if no rows are visible
        const visibleRows = Array.from(rows).filter(row => row.style.display !== 'none' && row.children.length > 1);
        const emptyRow = table.querySelector('tbody tr td[colspan="5"]');
        
        if (visibleRows.length === 0 && !emptyRow) {
            const tbody = table.querySelector('tbody');
            const noDataRow = document.createElement('tr');
            noDataRow.innerHTML = '<td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data yang sesuai dengan filter</td>';
            noDataRow.id = 'no-data-filter';
            tbody.appendChild(noDataRow);
        } else if (visibleRows.length > 0) {
            const noDataFilterRow = document.getElementById('no-data-filter');
            if (noDataFilterRow) {
                noDataFilterRow.remove();
            }
        }
    }

    filterStatus.addEventListener('change', filterTable);
    filterBulan.addEventListener('change', filterTable);
    searchMapel.addEventListener('input', filterTable);
});

// Fungsi Export Excel
function exportAbsensi() {
    // Ambil data filter
    const filterStatus = document.getElementById('filterStatus').value;
    const filterBulan = document.getElementById('filterBulan').value;
    const searchMapel = document.getElementById('searchMapel').value;
    
    // Buat URL dengan parameter
    let url = '<?php echo e(route("siswa.absensi")); ?>?export=excel';
    if (filterStatus) url += '&status=' + filterStatus;
    if (filterBulan) url += '&bulan=' + filterBulan;
    if (searchMapel) url += '&search=' + searchMapel;
    
    // Redirect ke URL export
    window.location.href = url;
}

// Fungsi Print
function printAbsensi() {
    // Create a new window with only the table data
    const printWindow = window.open('', '_blank');
    const table = document.getElementById('absensiTable').outerHTML;
    const styles = `
        <style>
            body { font-family: Arial, sans-serif; margin: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; font-weight: bold; }
            .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
            .badge-green { background: #dcfce7; color: #166534; }
            .badge-blue { background: #dbeafe; color: #1e40af; }
            .badge-yellow { background: #fef3c7; color: #92400e; }
            .badge-red { background: #fee2e2; color: #dc2626; }
            @media print {
                body { margin: 0; }
                .no-print { display: none; }
            }
        </style>
    `;
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Absensi - <?php echo e(Auth::guard('siswa')->user()->nama_lengkap); ?></title>
                ${styles}
            </head>
            <body>
                <h2>Absensi</h2>
                <p><strong>Nama:</strong> <?php echo e(Auth::guard('siswa')->user()->nama_lengkap); ?></p>
                <p><strong>Kelas:</strong> <?php echo e(Auth::guard('siswa')->user()->kelas->nama_kelas ?? 'Tidak ada kelas'); ?></p>
                <p><strong>Tanggal Cetak:</strong> <?php echo e(date('d/m/Y H:i')); ?></p>
                ${table}
            </body>
        </html>
    `);
    
    printWindow.document.close();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.siswa', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\siswa\absensi\index.blade.php ENDPATH**/ ?>