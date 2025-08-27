

<?php $__env->startSection('title', 'Detail Nilai - SMK PGRI CIKAMPEK'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .detail-header {
        opacity: 0;
        transform: translateY(-20px);
        animation: slideInFromTop 0.6s ease-out forwards;
    }
    
    .mapel-card {
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.6s ease-out;
    }
    
    .siswa-card {
        opacity: 0;
        transform: translateX(-20px);
        transition: all 0.4s ease-out;
    }
    
    .empty-state {
        opacity: 0;
        transform: scale(0.9);
        animation: scaleIn 0.8s ease-out 0.3s forwards;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="px-3 py-4">
    <div class="flex items-center justify-between mb-6 detail-header">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">
                Detail Nilai Kelas <?php echo e($kelas->nama_kelas ?? 'Tidak Diketahui'); ?>

            </h1>
            <p class="text-gray-600">
                <?php echo e($kelas->jurusan->nama_jurusan ?? $kelas->jurusan->nama ?? 'Belum ada jurusan'); ?>

            </p>
        </div>
        <a href="<?php echo e(route('guru.nilai.index')); ?>" class="bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="space-y-6">
        <?php $__empty_1 = true; $__currentLoopData = $nilaiDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mapel-card">
            <div class="p-5 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg"><?php echo e($detail['mapel']->nama ?? 'Mata Pelajaran Tidak Diketahui'); ?></h2>
                        <p class="text-sm text-gray-500"><?php echo e($detail['mapel']->kode_mapel ?? $detail['mapel']->kode ?? 'Tidak ada kode'); ?></p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600"><?php echo e($detail['sudah_dinilai']); ?></p>
                            <p class="text-xs text-gray-500">Sudah Dinilai</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-red-600"><?php echo e($detail['belum_dinilai']); ?></p>
                            <p class="text-xs text-gray-500">Belum Dinilai</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600"><?php echo e($detail['total_siswa']); ?></p>
                            <p class="text-xs text-gray-500">Total Siswa</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: <?php echo e($detail['total_siswa'] > 0 ? ($detail['sudah_dinilai'] / $detail['total_siswa']) * 100 : 0); ?>%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Progress: <?php echo e($detail['total_siswa'] > 0 ? round(($detail['sudah_dinilai'] / $detail['total_siswa']) * 100, 1) : 0); ?>%
                    </p>
                </div>
            </div>

            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-800">Daftar Siswa & Nilai</h3>
                    <a href="<?php echo e(route('guru.nilai.create', ['kelas_id' => $kelas->id, 'mapel_id' => $detail['mapel']->id])); ?>" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                        <i class="fas fa-edit mr-1"></i> Input Nilai Baru
                    </a>
                </div>
                
                <div class="space-y-4">
                    <?php $__currentLoopData = $detail['siswa_data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="border border-gray-200 rounded-lg p-4 siswa-card">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h4 class="font-medium text-gray-800">
                                    <?php echo e($data['siswa']->nama_lengkap ?? ($data['siswa']->nama ?? 'Nama tidak tersedia')); ?>

                                </h4>
                                <p class="text-sm text-gray-500">NIS: <?php echo e($data['siswa']->nis ?? '-'); ?></p>
                            </div>
                            <div class="text-right">
                                <?php if($data['has_nilai']): ?>
                                    <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">
                                        <i class="fas fa-check mr-1"></i><?php echo e($data['nilai_records']->count()); ?> Nilai
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                        <i class="fas fa-times mr-1"></i>Belum Ada Nilai
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($data['has_nilai']): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50 border-b">
                                        <th class="text-left py-2 px-3">Jenis Nilai</th>
                                        <th class="text-center py-2 px-3">Nilai</th>
                                        <th class="text-left py-2 px-3">Deskripsi</th>
                                        <th class="text-left py-2 px-3">Keterangan</th>
                                        <th class="text-center py-2 px-3">Tanggal Input</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $data['nilai_records']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nilai): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-b border-gray-100 hover:bg-gray-50">
                                        <td class="py-2 px-3">
                                            <?php
                                                $jenisNilaiLabels = [
                                                    'tugas' => 'Tugas Harian',
                                                    'ulangan' => 'Ulangan Harian',
                                                    'uts' => 'UTS',
                                                    'uas' => 'UAS',
                                                    'praktik' => 'Praktik'
                                                ];
                                            ?>
                                            <span class="inline-block px-2 py-1 text-xs rounded
                                                <?php echo e($nilai->jenis_nilai === 'tugas' || $nilai->jenis_nilai === 'ulangan' ? 'bg-blue-100 text-blue-800' : 
                                                   ($nilai->jenis_nilai === 'uts' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($nilai->jenis_nilai === 'uas' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'))); ?>">
                                                <?php echo e($jenisNilaiLabels[$nilai->jenis_nilai] ?? ucfirst($nilai->jenis_nilai)); ?>

                                            </span>
                                        </td>
                                        <td class="py-2 px-3 text-center">
                                            <span class="font-medium <?php echo e($nilai->nilai >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                                <?php echo e($nilai->nilai); ?>

                                            </span>
                                        </td>
                                        <td class="py-2 px-3 text-xs text-gray-600">
                                            <?php echo e($nilai->deskripsi ?? '-'); ?>

                                        </td>
                                        <td class="py-2 px-3 text-xs text-gray-600">
                                            <?php echo e($nilai->catatan ?? '-'); ?>

                                        </td>
                                        <td class="py-2 px-3 text-center text-xs text-gray-500">
                                            <?php echo e($nilai->created_at ? $nilai->created_at->format('d/m/Y H:i') : '-'); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            
                            
                            <?php
                                $tugasNilai = $data['nilai_records']->where('jenis_nilai', 'tugas')->avg('nilai');
                                $ulanganNilai = $data['nilai_records']->where('jenis_nilai', 'ulangan')->avg('nilai');
                                $utsNilai = $data['nilai_records']->where('jenis_nilai', 'uts')->avg('nilai');
                                $uasNilai = $data['nilai_records']->where('jenis_nilai', 'uas')->avg('nilai');
                                $praktikNilai = $data['nilai_records']->where('jenis_nilai', 'praktik')->avg('nilai');
                                
                                $avgTugas = $tugasNilai ?: $ulanganNilai;
                                $nilaiAkhir = 0;
                                $komponen = 0;
                                
                                if ($avgTugas) {
                                    $nilaiAkhir += $avgTugas * 0.3;
                                    $komponen++;
                                }
                                if ($utsNilai) {
                                    $nilaiAkhir += $utsNilai * 0.3;
                                    $komponen++;
                                }
                                if ($uasNilai) {
                                    $nilaiAkhir += $uasNilai * 0.4;
                                    $komponen++;
                                }
                                
                                if ($komponen >= 2) {
                                    $grade = $nilaiAkhir >= 90 ? 'A' : ($nilaiAkhir >= 80 ? 'B' : ($nilaiAkhir >= 70 ? 'C' : ($nilaiAkhir >= 60 ? 'D' : 'E')));
                                }
                            ?>
                            
                            <div class="mt-3 p-3 bg-gray-50 rounded">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500">Rata-rata Tugas</p>
                                        <p class="font-medium <?php echo e($avgTugas >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                            <?php echo e($avgTugas ? number_format($avgTugas, 1) : '-'); ?>

                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">UTS</p>
                                        <p class="font-medium <?php echo e($utsNilai >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                            <?php echo e($utsNilai ? number_format($utsNilai, 1) : '-'); ?>

                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">UAS</p>
                                        <p class="font-medium <?php echo e($uasNilai >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                            <?php echo e($uasNilai ? number_format($uasNilai, 1) : '-'); ?>

                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Nilai Akhir</p>
                                        <div class="flex items-center justify-center space-x-2">
                                            <?php if(isset($nilaiAkhir) && $komponen >= 2): ?>
                                                <span class="font-bold <?php echo e($nilaiAkhir >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                                    <?php echo e(number_format($nilaiAkhir, 1)); ?>

                                                </span>
                                                <span class="inline-block px-2 py-1 text-xs rounded font-bold
                                                    <?php echo e($grade === 'A' ? 'bg-green-100 text-green-800' : 
                                                       ($grade === 'B' ? 'bg-blue-100 text-blue-800' : 
                                                       ($grade === 'C' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($grade === 'D' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800')))); ?>">
                                                    <?php echo e($grade); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-gray-400">-</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="text-center py-4 text-gray-500">
                            <i class="fas fa-clipboard-list text-2xl mb-2"></i>
                            <p>Belum ada nilai yang diinput</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-center empty-state">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-4">
                <i class="fas fa-book text-blue-400 text-xl"></i>
            </div>
            <p class="text-gray-600">Anda tidak mengajar mata pelajaran di kelas ini</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate subject cards with staggered timing
    const mapelCards = document.querySelectorAll('.mapel-card');
    mapelCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 200 + (index * 150));
        
        // Animate student cards inside each subject card
        const siswaCards = card.querySelectorAll('.siswa-card');
        siswaCards.forEach((siswaCard, siswaIndex) => {
            setTimeout(() => {
                siswaCard.style.opacity = '1';
                siswaCard.style.transform = 'translateX(0)';
            }, 400 + (index * 150) + (siswaIndex * 100));
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guru', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\website-smk3\resources\views\guru\nilai\detail.blade.php ENDPATH**/ ?>