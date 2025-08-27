

<?php $__env->startSection('title', 'Rapor Siswa - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="w-full px-3 py-4">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-3">Rapor Siswa</h1>
        <div class="text-sm breadcrumbs">
            <ul class="flex items-center space-x-2 text-gray-500">
                <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-blue-600">Dashboard</a></li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <a href="<?php echo e(route('admin.nilai.index')); ?>" class="hover:text-blue-600">Manajemen Nilai</a>
                </li>
                <li class="flex items-center space-x-2">
                    <span class="text-gray-400">/</span>
                    <span>Rapor Siswa</span>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6">
        <div class="p-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Filter Siswa</h3>
        </div>
        <div class="p-4">
            <form action="<?php echo e(route('admin.nilai.rapor')); ?>" method="GET" class="flex flex-wrap gap-4">
                <div class="w-full md:w-auto">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Filter Kelas</label>
                    <select id="kelas_id" class="w-full md:w-60 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">-- Pilih Kelas --</option>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($k->id); ?>" <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>><?php echo e($k->nama_kelas); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="w-full md:w-auto">
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
                    <select name="siswa_id" id="siswa_id" class="w-full md:w-80 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($s->id); ?>" data-kelas="<?php echo e($s->kelas_id); ?>" <?php echo e(request('siswa_id') == $s->id ? 'selected' : ''); ?>>
                            <?php echo e($s->nama_lengkap); ?> (<?php echo e($s->nis); ?>) - <?php echo e($s->kelas->nama_kelas ?? 'Belum ada kelas'); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-1"></i> Lihat Rapor
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <?php if($siswa): ?>
    <div class="mb-4 flex justify-end">
        <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 print:hidden">
            <i class="fas fa-print mr-1"></i> Cetak Rapor
        </button>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 print:shadow-none print:border-none">
        <div class="p-6">
            <div class="text-center mb-6 print:mb-4">
                <h2 class="text-xl font-bold uppercase">LAPORAN HASIL BELAJAR (RAPOR)</h2>
                <h3 class="text-lg font-semibold">SMK PGRI CIKAMPEK</h3>
                <p class="text-sm text-gray-600">Tahun Ajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?></p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <table class="w-full">
                        <tr>
                            <td class="py-1 font-medium">Nama</td>
                            <td class="py-1">: <?php echo e($siswa->nama_lengkap); ?></td>
                        </tr>
                        <tr>
                            <td class="py-1 font-medium">NIS/NISN</td>
                            <td class="py-1">: <?php echo e($siswa->nis); ?> / <?php echo e($siswa->nisn); ?></td>
                        </tr>
                        <tr>
                            <td class="py-1 font-medium">Kelas</td>
                            <td class="py-1">: <?php echo e($siswa->kelas->nama_kelas ?? '-'); ?></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <table class="w-full">
                        <tr>
                            <td class="py-1 font-medium">Semester</td>
                            <td class="py-1">: <?php echo e(date('n') >= 7 ? 'Ganjil' : 'Genap'); ?></td>
                        </tr>
                        <tr>
                            <td class="py-1 font-medium">Tahun Pelajaran</td>
                            <td class="py-1">: <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <?php if($nilaiList->isNotEmpty()): ?>
                <?php $__currentLoopData = $nilaiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kategori => $nilai_kategori): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-8">
                    <h3 class="text-lg font-bold mb-3 text-blue-800 border-b-2 border-blue-200 pb-1"><?php echo e($kategori ?? 'Umum'); ?></h3>
                    
                    <table class="w-full border-collapse">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">No</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Mata Pelajaran</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">KKM</th>
                                <th class="border border-gray-300 px-4 py-2 text-center">Nilai</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $nilai_kategori; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="<?php echo e($loop->even ? 'bg-gray-50' : 'bg-white'); ?>">
                                <td class="border border-gray-300 px-4 py-3 text-center"><?php echo e($index + 1); ?></td>
                                <td class="border border-gray-300 px-4 py-3"><?php echo e($nilai->mapel ? $nilai->mapel->nama : '-'); ?></td>
                                <td class="border border-gray-300 px-4 py-3 text-center"><?php echo e($nilai->mapel->kkm ?? 75); ?></td>
                                <td class="border border-gray-300 px-4 py-3 text-center font-bold
                                    <?php echo e($nilai->nilai >= ($nilai->mapel->kkm ?? 75) ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e($nilai->nilai); ?>

                                </td>
                                <td class="border border-gray-300 px-4 py-3"><?php echo e($nilai->keterangan); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="3" class="border border-gray-300 px-4 py-3 text-right">Rata-rata</td>
                                <td class="border border-gray-300 px-4 py-3 text-center">
                                    <?php echo e($nilai_kategori->avg('nilai') ? number_format($nilai_kategori->avg('nilai'), 2) : '-'); ?>

                                </td>
                                <td class="border border-gray-300 px-4 py-3"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                <div class="mt-10 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="mb-16">Orang Tua/Wali</p>
                        <p>__________________</p>
                    </div>
                    
                    <div></div>
                    
                    <div class="text-center">
                        <p>Cikampek, <?php echo e(date('d F Y')); ?></p>
                        <p class="mb-2">Wali Kelas</p>
                        <p class="mb-14"></p>
                        <p>__________________</p>
                        <p>NIP.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Belum ada data nilai untuk siswa ini.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php elseif(request('siswa_id')): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
            <p>Data siswa tidak ditemukan.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kelasFilter = document.getElementById('kelas_id');
        const siswaSelect = document.getElementById('siswa_id');
        
        kelasFilter.addEventListener('change', function() {
            const selectedKelasId = this.value;
            const siswaOptions = siswaSelect.querySelectorAll('option');
            
            siswaSelect.value = '';
            
            for (let i = 0; i < siswaOptions.length; i++) {
                const option = siswaOptions[i];
                if (option.value === '') continue;
                
                const kelasId = option.getAttribute('data-kelas');
                
                if (selectedKelasId === '' || kelasId === selectedKelasId) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            }
        });
    });
</script>

<style media="print">
    @page {
        size: A4;
        margin: 1.5cm;
    }
    body {
        background-color: white;
        font-size: 12pt;
    }
    .print\:hidden {
        display: none !important;
    }
    .print\:shadow-none {
        box-shadow: none !important;
    }
    .print\:border-none {
        border: none !important;
    }
    .breadcrumbs,
    .navbar,
    .sidebar {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .px-3 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    .print\:mb-4 {
        margin-bottom: 1rem !important;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\admin\nilai\rapor.blade.php ENDPATH**/ ?>